<?php
/** Content-only migration. Run with WP-CLI from a private directory, never through HTTP.
 * wp eval-file /private/path/sync-nepali-live.php /private/path/payload.json [apply|verify]
 */
if(!defined('WP_CLI'))exit;
function gj_sync_normalize($value,$origin){return is_string($value)?str_replace(rtrim($origin,'/'),rtrim(home_url('/'),'/'),$value):$value;}
function gj_sync_without_ne($value){if(!is_array($value))return $value;foreach($value as$key=>$item){if(is_string($key)&&str_ends_with($key,'_ne'))unset($value[$key]);else$value[$key]=gj_sync_without_ne($item);}return $value;}
function gj_sync_map_fields($values,$schema,$maps,$origin){
 if(!is_array($values))return gj_sync_normalize($values,$origin);
 foreach($values as$key=>$value){
  $info=is_string($key)?($schema[$key]??$schema['field_gj_'.$key]??null):null;$type=$info['type']??'';
  $kind=in_array($type,['image','gallery'],true)?'media':(in_array($type,['relationship','post_object'],true)?'posts':($type==='taxonomy'?'terms':''));
  if($kind&&$value){$convert=function($id)use($maps,$kind){if(!$id)return $id;if(!isset($maps[$kind][(int)$id]))throw new RuntimeException('Unmatched '.$kind.' reference '.$id);return is_string($id)?(string)$maps[$kind][(int)$id]:$maps[$kind][(int)$id];};$values[$key]=is_array($value)?array_map($convert,$value):$convert($value);}
  elseif(is_array($value))$values[$key]=gj_sync_map_fields($value,$schema,$maps,$origin);
  else$values[$key]=gj_sync_normalize($value,$origin);
 }
 return $values;
}
function gj_sync_merge_ne($live,$incoming){foreach($incoming as$key=>$value){if(is_string($key)&&str_ends_with($key,'_ne'))$live[$key]=$value;elseif(is_array($value)&&isset($live[$key])&&is_array($live[$key]))$live[$key]=gj_sync_merge_ne($live[$key],$value);}return $live;}
function gj_sync_ne_count($values){$count=0;foreach((array)$values as$key=>$value){if(is_array($value))$count+=gj_sync_ne_count($value);elseif(is_string($key)&&str_ends_with($key,'_ne')&&$value!==''&&$value!==false&&$value!==null)$count++;}return $count;}
function gj_sync_post_signature($id){
 $p=get_post($id);$meta=get_post_meta($id);$stored=[];
 foreach($meta as$key=>$values)if(str_starts_with($key,'_')&&!str_ends_with($key,'_ne')&&str_starts_with((string)($values[0]??''),'field_gj_')){$name=substr($key,1);$stored[$name]=$meta[$name]??[];}
 ksort($stored);
 return hash('sha256',wp_json_encode([$p->post_title,$p->post_content,$p->post_excerpt,$p->post_name,$p->post_status,$p->post_parent,get_post_thumbnail_id($id),$stored]));
}
function gj_sync_check_result($result){if(is_wp_error($result))throw new RuntimeException($result->get_error_message());return $result;}
function gj_sync_run($arguments){
 $file=$arguments[0]??'';$mode=$arguments[1]??'preview';$apply=$mode==='apply';
 if(!is_readable($file))throw new RuntimeException('Payload file is required.');
 $data=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 if(($data['format']??'')!=='ghumnajaun-nepali-v1')throw new RuntimeException('Unknown payload format.');
 if(!function_exists('pll_get_post')||!function_exists('get_fields')||!acf_get_field('field_gj_home_title_ne'))throw new RuntimeException('Activate the bilingual theme, ACF and Polylang first.');
 if(!in_array('ne',pll_languages_list(['fields'=>'slug']),true))throw new RuntimeException('Nepali is not configured in Polylang.');
 $maps=['posts'=>[],'terms'=>[],'media'=>[]];$issues=[];$sources=[];$mapped_fields=[];$signatures=[];$existing=0;
 $candidates=get_posts(['post_type'=>['page','post','destination'],'post_status'=>['publish','draft','private','pending'],'posts_per_page'=>-1,'lang'=>'']);
 foreach($data['posts']as$row){$matches=array_values(array_filter($candidates,fn($p)=>$p->post_type===$row['type']&&pll_get_post_language($p->ID)==='en'&&($row['source_slug']!==''?$p->post_name===$row['source_slug']:$p->post_title===$row['source_title'])));if(count($matches)!==1){$issues[]='English source not unique: '.$row['type'].'/'.$row['source_slug'];continue;}$id=$matches[0]->ID;$maps['posts'][$row['source_id']]=$id;$sources[$row['source_id']]=$id;$signatures[$id]=gj_sync_post_signature($id);if(pll_get_post($id,'ne'))$existing++;foreach(['source_title'=>'post_title','source_content'=>'post_content','source_excerpt'=>'post_excerpt']as$key=>$property)if(trim(gj_sync_normalize($row[$key],$data['origin']))!==trim($matches[0]->$property))$issues[]='English '.$property.' differs: '.$row['source_slug'];}
 foreach($data['terms']as$row){$terms=get_terms(['taxonomy'=>$row['taxonomy'],'slug'=>$row['source_slug'],'lang'=>'en','hide_empty'=>false]);if(is_wp_error($terms)||count($terms)!==1)$issues[]='English term not unique: '.$row['taxonomy'].'/'.$row['source_slug'];else$maps['terms'][$row['source_id']]=$terms[0]->term_id;}
 foreach($data['media']as$row){$ids=get_posts(['post_type'=>'attachment','post_status'=>'inherit','posts_per_page'=>2,'fields'=>'ids','meta_key'=>'_wp_attached_file','meta_value'=>$row['file'],'lang'=>'']);if(count($ids)===1)$maps['media'][$row['id']]=(int)$ids[0];}
 if(!$issues)foreach($data['posts']as$row){$id=$sources[$row['source_id']];$incoming=gj_sync_map_fields($row['fields'],$data['schema'],$maps,$data['origin']);$live=get_fields($id,false)?:[];$mapped_fields[$id]=$incoming;foreach(gj_sync_without_ne($incoming)as$key=>$value)if($value!=gj_sync_without_ne($live[$key]??null))$issues[]='English ACF differs: '.$row['source_slug'].' / '.$key;}
 foreach($data['options']as$name=>$values)if(gj_sync_normalize($values['en'],$data['origin'])!=get_field($name,'option',false))$issues[]='English homepage setting differs: '.$name;
 if($issues){foreach($issues as$issue)WP_CLI::warning($issue);throw new RuntimeException('Preflight stopped: '.count($issues).' differences. No content was changed.');}
 $plan=['mode'=>$mode,'records'=>count($data['posts']),'existing_nepali_records'=>$existing,'terms'=>count($data['terms']),'matched_media'=>count($maps['media']),'translated_acf_values'=>array_sum(array_map('gj_sync_ne_count',$mapped_fields))+count(array_filter($data['options'],fn($v)=>$v['ne']!=='')),'menus'=>count($data['menus'])];
 WP_CLI::log('SYNC_PLAN '.wp_json_encode($plan,JSON_UNESCAPED_UNICODE));
 if(!$apply&&$mode!=='verify'){WP_CLI::success('Preflight passed. No content changed.');return;}
 if($apply){
  $backup=['time'=>gmdate('c'),'posts'=>[],'options'=>get_fields('option',false)?:[],'terms'=>[],'menus'=>PLL()->options['nav_menus']];
  foreach($sources as$id){$target=pll_get_post($id,'ne');$backup['posts'][$id]=['post'=>get_post($id),'meta'=>get_post_meta($id),'ne'=>$target?['post'=>get_post($target),'meta'=>get_post_meta($target)]:null];}
  foreach($maps['terms']as$id)$backup['terms'][$id]=['term'=>get_term($id),'translations'=>pll_get_term_translations($id)];
  $backup_file=dirname($file).'/before-nepali-content.json';if(!file_exists($backup_file)){file_put_contents($backup_file,wp_json_encode($backup,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));chmod($backup_file,0600);}
  foreach($data['terms']as$row){$id=$maps['terms'][$row['source_id']];$ne=pll_get_term($id,'ne');$values=['name'=>$row['ne']['name'],'description'=>gj_sync_normalize($row['ne']['description'],$data['origin'])];if(!$ne){$result=gj_sync_check_result(wp_insert_term($values['name'],$row['taxonomy'],['slug'=>$row['ne']['slug'],'description'=>$values['description']]));$ne=(int)$result['term_id'];pll_set_term_language($ne,'ne');$translations=pll_get_term_translations($id);$translations['ne']=$ne;pll_save_term_translations($translations);}else gj_sync_check_result(wp_update_term($ne,$row['taxonomy'],$values));$maps['terms'][$row['ne']['id']]=$ne;}
  foreach($data['posts']as$row){$id=$sources[$row['source_id']];$ne=pll_get_post($id,'ne');$values=['post_title'=>$row['ne']['title'],'post_content'=>gj_sync_normalize($row['ne']['content'],$data['origin']),'post_excerpt'=>gj_sync_normalize($row['ne']['excerpt'],$data['origin'])];if(!$ne){$ne=gj_sync_check_result(wp_insert_post(wp_slash(array_merge($values,['post_type'=>$row['type'],'post_status'=>'draft','post_name'=>$row['ne']['slug']])),true));pll_set_post_language($ne,'ne');$translations=pll_get_post_translations($id);$translations['ne']=$ne;pll_save_post_translations($translations);}else gj_sync_check_result(wp_update_post(wp_slash(array_merge($values,['ID'=>$ne])),true));$maps['posts'][$row['ne']['id']]=(int)$ne;
   $live=get_fields($id,false)?:[];$merged=gj_sync_merge_ne($live,$mapped_fields[$id]);foreach($merged as$key=>$value)if($value!==($live[$key]??null))update_field('field_gj_'.$key,$value,$id);do_action('acf/save_post',$id);
   foreach(['_thumbnail_id','_wp_page_template','_gj_import_key']as$key)if(metadata_exists('post',$id,$key))update_post_meta($ne,$key,get_post_meta($id,$key,true));
   foreach(['place_city','place_type','outing','category','post_tag']as$tax)if(is_object_in_taxonomy($row['type'],$tax)){$terms=wp_get_object_terms($id,$tax,['fields'=>'ids']);$translated=[];foreach($terms as$term){$target=pll_get_term($term,'ne');if($target)$translated[]=(int)$target;}gj_sync_check_result(wp_set_object_terms($ne,$translated,$tax));}
   gj_sync_check_result(wp_update_post(wp_slash(['ID'=>$ne,'post_name'=>$row['ne']['slug'],'post_status'=>$row['ne']['status']]),true));
  }
  foreach($data['posts']as$row){$id=$sources[$row['source_id']];$parent=(int)get_post_field('post_parent',$id);if($parent){$translated=pll_get_post($parent,'ne');if($translated)wp_update_post(['ID'=>pll_get_post($id,'ne'),'post_parent'=>$translated]);}}
  foreach($data['options']as$name=>$values)update_field('field_gj_'.$name.'_ne',gj_sync_normalize($values['ne'],$data['origin']),'option');
  foreach($data['media']as$row)if(isset($maps['media'][$row['id']]))foreach($row['ne']as$key=>$value)update_post_meta($maps['media'][$row['id']],'_gj_'.$key.'_ne',gj_sync_normalize($value,$data['origin']));
  foreach($data['menus']as$location=>$items){$name='Ghumna Jaun '.$location.' नेपाली';$menu=wp_get_nav_menu_object($name);$menu_id=$menu?$menu->term_id:gj_sync_check_result(wp_create_nav_menu($name));$existing_items=wp_get_nav_menu_items($menu_id)?:[];foreach($items as$i=>$item){$target=$item['type']==='post_type'?($maps['posts'][$item['object_id']]??0):0;$url=$target?get_permalink($target):gj_sync_normalize($item['url'],$data['origin']);gj_sync_check_result(wp_update_nav_menu_item($menu_id,$existing_items[$i]->ID??0,['menu-item-title'=>$item['title'],'menu-item-url'=>$url,'menu-item-type'=>$target?'post_type':'custom','menu-item-object'=>$target?get_post_type($target):'custom','menu-item-object-id'=>$target?:0,'menu-item-position'=>$item['order'],'menu-item-status'=>'publish']));}$menus=PLL()->options['nav_menus'];$menus[get_stylesheet()][$location]['ne']=$menu_id;PLL()->options['nav_menus']=$menus;}
  if(method_exists(PLL()->model,'clean_languages_cache'))PLL()->model->clean_languages_cache();flush_rewrite_rules();
 }
 $errors=[];$pairs=count(array_filter($data['options'],fn($v)=>$v['ne']!==''));$urls=[];
 foreach($data['posts']as$row){$id=$sources[$row['source_id']];$ne=pll_get_post($id,'ne');if(!$ne){$errors[]='Missing linked translation: '.$row['source_slug'];continue;}$post=get_post($ne);foreach(['title'=>'post_title','content'=>'post_content','excerpt'=>'post_excerpt','status'=>'post_status','slug'=>'post_name']as$key=>$property)if(gj_sync_normalize($row['ne'][$key],$data['origin'])!==$post->$property)$errors[]='Nepali '.$property.' mismatch: '.$row['source_slug'];$live=get_fields($id,false)?:[];$expected=gj_sync_merge_ne($live,$mapped_fields[$id]);if($live!=$expected)$errors[]='Nepali fields missing: '.$row['source_slug'];if($live!=(get_fields($ne,false)?:[]))$errors[]='Linked ACF bundle differs: '.$row['source_slug'];if(gj_sync_post_signature($id)!==$signatures[$id])$errors[]='English content changed: '.$row['source_slug'];$pairs+=gj_sync_ne_count($live);if($post->post_status==='publish')$urls[]=get_permalink($ne);}
 foreach($data['terms']as$row)if(!pll_get_term($maps['terms'][$row['source_id']],'ne'))$errors[]='Missing translated term: '.$row['source_slug'];
 foreach($data['options']as$name=>$value)if(get_field($name.'_ne','option',false)!==gj_sync_normalize($value['ne'],$data['origin']))$errors[]='Nepali homepage setting mismatch: '.$name;
 if($errors){foreach($errors as$error)WP_CLI::warning($error);throw new RuntimeException(count($errors).' post-sync validation failures.');}
 $report=['mode'=>$mode,'records'=>count($data['posts']),'translated_acf_values'=>$pairs,'terms'=>count($data['terms']),'published_urls'=>$urls,'english_preserved'=>true];file_put_contents(dirname($file).'/nepali-sync-report.json',wp_json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));chmod(dirname($file).'/nepali-sync-report.json',0600);
 WP_CLI::success('Verified '.count($data['posts']).' linked Nepali records, '.$pairs.' translated ACF values and '.count($data['terms']).' terms. English content preserved.');
}
try{gj_sync_run($args??[]);}catch(Throwable $error){WP_CLI::error($error->getMessage());}
