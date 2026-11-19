<?php
/** Populate linked Nepali content from a reviewed dictionary; run explicitly with WP-CLI. */
if(!defined('WP_CLI'))exit;
$dictionary=json_decode(file_get_contents(__DIR__.'/data/ne-translations.json'),true,512,JSON_THROW_ON_ERROR);
$source=json_decode(file_get_contents('/tmp/gj-ne-source.json'),true,512,JSON_THROW_ON_ERROR);
$schema=json_decode(file_get_contents('/tmp/gj-ne-schema.json'),true,512,JSON_THROW_ON_ERROR);
$GLOBALS['gj_ne_dictionary']=$dictionary;
function gj_ne_text($text){
 if(!is_string($text)||$text==='')return $text;
 $key=trim(html_entity_decode($text,ENT_QUOTES|ENT_HTML5,'UTF-8'));
 $translated=$GLOBALS['gj_ne_dictionary'][$key]??null;
 if($translated===null)return $text;
 preg_match('/^\s*/u',$text,$leading);preg_match('/\s*$/u',$text,$trailing);
 return $leading[0].$translated.$trailing[0];
}
function gj_ne_html($html){
 if(!is_string($html)||$html==='')return $html;
 if(function_exists('gj_ne_paragraphs') && empty($GLOBALS['gj_ne_skip_editorial_paragraphs']))$html=gj_ne_paragraphs($html);
 $parts=preg_split('~(<[^>]+>)~s',$html,-1,PREG_SPLIT_DELIM_CAPTURE);$skip=false;
 foreach($parts as$i=>$part){
  if(str_starts_with($part,'<')){
   if(preg_match('~^<(script|style)\b~i',$part))$skip=true;
   if(preg_match('~^</(script|style)\b~i',$part))$skip=false;
   $parts[$i]=preg_replace_callback('~\b(alt|title|placeholder|aria-label)=("|\x27)(.*?)\2~s',function($m){return $m[1].'='.$m[2].esc_attr(gj_ne_text($m[3])).$m[2];},$part);
  }elseif(!$skip){$translated=gj_ne_text($part);if($translated!==$part)$parts[$i]=esc_html($translated);}
 }
 return implode('',$parts);
}
function gj_ne_content($text){return is_string($text)&&preg_match('~<[a-zA-Z][^>]*>~',$text)?gj_ne_html($text):gj_ne_text($text);}
function gj_ne_fields($values,$schema){
 if(!is_array($values))return $values;
 foreach($values as$key=>$value){
  if(is_array($value)){$values[$key]=gj_ne_fields($value,$schema);continue;}
  if(!is_string($key)||str_ends_with($key,'_ne'))continue;
  $info=$schema[$key]??$schema['field_gj_'.$key]??null;
  if(!$info || !in_array($info['type'],['text','textarea','wysiwyg'],true)||in_array($info['name'],['photo_author','photo_license'],true))continue;
  if(!empty($values[$key.'_ne']))continue;
  if(is_string($value)&&$value!=='')$values[$key.'_ne']=in_array($info['name'],['stage_link_url','stop_map','transport_pin_query'],true)?$value:gj_ne_content($value);
 }
 return $values;
}
function gj_ne_save_fields($fields,$schema,$id){$fields=array_replace($fields,get_fields($id,false)?:[]);foreach(gj_ne_fields($fields,$schema)as$name=>$value)update_field(str_starts_with($name,'field_gj_')?$name:'field_gj_'.$name,$value,$id);}

if(defined('GJ_NE_FUNCTIONS_ONLY'))return;

// A complete database snapshot is created before running this tool. The journal makes reruns inspectable.
$journal=['posts'=>[],'terms'=>[],'menus'=>[]];$term_ids=[];$post_ids=[];
foreach($source['terms']as$t){
 if(!pll_get_term_language($t['id']))pll_set_term_language($t['id'],'en');
 $id=pll_get_term($t['id'],'ne');
 if(!$id){$created=wp_insert_term(gj_ne_text($t['name']),$t['taxonomy'],['slug'=>$t['slug'].'-ne','description'=>gj_ne_content($t['description'])]);if(is_wp_error($created))WP_CLI::error($created->get_error_message());$id=$created['term_id'];pll_set_term_language($id,'ne');pll_save_term_translations(['en'=>$t['id'],'ne'=>$id]);}
 $term_ids[$t['id']]=$id;$journal['terms'][]=$id;
 gj_ne_save_fields($t['fields'],$schema,'term_'.$id);
}
foreach($source['terms']as$t)if($t['parent']&&isset($term_ids[$t['parent']]))wp_update_term($term_ids[$t['id']],$t['taxonomy'],['parent'=>$term_ids[$t['parent']]]);
foreach($source['posts']as$p){
 if(!pll_get_post_language($p['id']))pll_set_post_language($p['id'],'en');
 $id=pll_get_post($p['id'],'ne');
 if(!$id){
  $id=wp_insert_post(['post_type'=>$p['type'],'post_status'=>'draft','post_title'=>gj_ne_text($p['title']),'post_content'=>gj_ne_content($p['content']),'post_excerpt'=>gj_ne_content($p['excerpt']),'post_name'=>$p['slug']],true);
  if(is_wp_error($id))WP_CLI::error($id->get_error_message());
  pll_set_post_language($id,'ne');pll_save_post_translations(['en'=>$p['id'],'ne'=>$id]);
 }
 $post_ids[$p['id']]=$id;$journal['posts'][]=$id;
 // Preserve the original bundle and fill only its empty Nepali values.
 gj_ne_save_fields($p['fields'],$schema,$p['id']);
 do_action('acf/save_post',$p['id']);
 foreach(['_thumbnail_id','_wp_page_template','_gj_import_key']as$key)if(isset($p['meta'][$key]))update_post_meta($id,$key,maybe_unserialize($p['meta'][$key][0]));
 $terms=[];foreach($p['taxonomies']as$t)if(isset($term_ids[$t['term_id']]))$terms[$t['taxonomy']][]=(int)$term_ids[$t['term_id']];
 foreach($terms as$tax=>$ids)wp_set_object_terms($id,$ids,$tax);
 wp_update_post(['ID'=>$id,'post_name'=>$p['slug'],'post_status'=>$p['status']]);
 WP_CLI::log($p['id'].' → '.$id.' '.gj_ne_text($p['title']));
}
foreach($source['posts']as$p)if($p['parent']&&isset($post_ids[$p['parent']]))wp_update_post(['ID'=>$post_ids[$p['id']],'post_parent'=>$post_ids[$p['parent']]]);
gj_ne_save_fields($source['options'],$schema,'option');
foreach($source['media']??[]as$media)foreach(['alt','caption','description']as$key)if($media[$key]!=='')update_post_meta($media['id'],'_gj_'.$key.'_ne',gj_ne_content($media[$key]));
$locations=get_theme_mod('nav_menu_locations',[]);
foreach($source['menus']as$m){
 $name=$m['name'].' नेपाली';$existing=wp_get_nav_menu_object($name);$menu=$existing?$existing->term_id:wp_create_nav_menu($name);if(is_wp_error($menu))WP_CLI::error($menu->get_error_message());
 if(!wp_get_nav_menu_items($menu))foreach($m['items']?:[]as$item){
  $url=$item['url'];$target=$post_ids[$item['object_id']]??0;
  if(!$target){$english=url_to_postid($url);if($english)$target=$post_ids[$english]??0;}
  if($target)$url=get_permalink($target);
  elseif(str_starts_with($url,home_url('/')))$url=str_replace(home_url('/'),trailingslashit(PLL()->links_model->home_url('ne')),$url);
  wp_update_nav_menu_item($menu,0,['menu-item-title'=>gj_ne_text($item['title']),'menu-item-url'=>$url,'menu-item-type'=>$target?'post_type':'custom','menu-item-object'=>$target?get_post_type($target):'custom','menu-item-object-id'=>$target,'menu-item-status'=>'publish','menu-item-position'=>$item['menu_order']]);
 }
 foreach($locations as$location=>$english_menu)if((int)$english_menu===(int)$m['id']){ $menus=PLL()->options['nav_menus'];$menus[get_stylesheet()][$location]['ne']=$menu;PLL()->options['nav_menus']=$menus;}
 $journal['menus'][]=$menu;
}
file_put_contents('/tmp/gj-ne-import-journal.json',wp_json_encode($journal,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
flush_rewrite_rules();WP_CLI::success('Populated '.count($post_ids).' linked Nepali records, '.count($term_ids).' terms, paired ACF fields, options and menus.');
