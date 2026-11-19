<?php
/** Apply reviewed dictionary changes to recognized generated copy; preserve separate edits.
 * wp eval-file tools/review-nepali.php /tmp/before-dictionary.json /tmp/content-snapshot.json apply
 */
if(!defined('WP_CLI'))exit;
if(empty($args[0])||empty($args[1]))WP_CLI::error('Provide the before dictionary and pre-edit content snapshot.');
$old_file=$args[0];$snapshot_file=$args[1];$apply=($args[2]??'')==='apply';
define('GJ_NE_FUNCTIONS_ONLY',true);require __DIR__.'/populate-nepali.php';
$before=json_decode(file_get_contents($old_file),true,512,JSON_THROW_ON_ERROR);
$snapshot=json_decode(file_get_contents($snapshot_file),true,512,JSON_THROW_ON_ERROR);
$GLOBALS['gj_review_before']=$before;$GLOBALS['gj_review_after']=$dictionary;$GLOBALS['gj_review_reverse']=[];
foreach($before as$english=>$nepali)if(isset($dictionary[$english])&&$dictionary[$english]!==$nepali)$GLOBALS['gj_review_reverse'][$nepali]=$dictionary[$english];
$GLOBALS['gj_review_counts']=['native_values'=>0,'acf_values'=>0,'terms'=>0,'menu_labels'=>0,'media_values'=>0,'preserved_separate_edits'=>0];
function gj_review_render($english,$old=false){
 $GLOBALS['gj_ne_dictionary']=$GLOBALS[$old?'gj_review_before':'gj_review_after'];$GLOBALS['gj_ne_skip_editorial_paragraphs']=$old;
 return gj_ne_content($english);
}
function gj_review_patch_text($text){
 if(!is_string($text))return $text;
 $key=trim(html_entity_decode($text,ENT_QUOTES|ENT_HTML5,'UTF-8'));
 if(!isset($GLOBALS['gj_review_reverse'][$key]))return $text;
 preg_match('/^\s*/u',$text,$leading);preg_match('/\s*$/u',$text,$trailing);
 return $leading[0].$GLOBALS['gj_review_reverse'][$key].$trailing[0];
}
function gj_review_patch_html($html){
 $parts=preg_split('~(<[^>]+>)~s',$html,-1,PREG_SPLIT_DELIM_CAPTURE);$skip=false;
 foreach($parts as$i=>$part){if(str_starts_with($part,'<')){if(preg_match('~^<(script|style)\b~i',$part))$skip=true;if(preg_match('~^</(script|style)\b~i',$part))$skip=false;if(!$skip)$parts[$i]=preg_replace_callback('~\b(alt|title|placeholder|aria-label)=("|\x27)(.*?)\2~s',fn($m)=>$m[1].'='.$m[2].esc_attr(gj_review_patch_text($m[3])).$m[2],$part);}elseif(!$skip){$value=gj_review_patch_text($part);if($value!==$part)$parts[$i]=esc_html($value);}}
 return implode('',$parts);
}
function gj_review_value($english,$current){
 if(!is_string($english)||$english==='')return $current;
 $old=gj_review_render($english,true);$new=gj_review_render($english);
 if($current===$old||$current===$english)return $new;
 if($current===$new)return $current;
 $patched=preg_match('~<[a-zA-Z][^>]*>~',(string)$current)?gj_review_patch_html($current):gj_review_patch_text($current);
 if($patched===$current&&$new!==$current)$GLOBALS['gj_review_counts']['preserved_separate_edits']++;
 return $patched;
}
function gj_review_fields($values,$schema){
 if(!is_array($values))return $values;
 foreach($values as$key=>$value){
  if(is_array($value)){$values[$key]=gj_review_fields($value,$schema);continue;}
  if(!is_string($key)||str_ends_with($key,'_ne'))continue;
  $info=$schema[$key]??$schema['field_gj_'.$key]??null;
  if(!$info||!in_array($info['type'],['text','textarea','wysiwyg'],true)||in_array($info['name'],['photo_author','photo_license','stage_link_url','stop_map','transport_pin_query'],true))continue;
  if(empty($values[$key.'_ne']))continue;
  $new=gj_review_value($value,$values[$key.'_ne']);
  if($new!==$values[$key.'_ne']){$values[$key.'_ne']=$new;$GLOBALS['gj_review_counts']['acf_values']++;}
 }
 return $values;
}
foreach($snapshot['posts']as$id=>$record){
 if($record['language']!=='en')continue;
 $target=pll_get_post((int)$id,'ne');if(!$target)continue;
 $source_post=get_post($id);$translated=get_post($target);$update=['ID'=>$target];
 foreach(['post_title','post_content','post_excerpt']as$property){$new=gj_review_value($source_post->$property,$translated->$property);if($new!==$translated->$property){$update[$property]=$new;$GLOBALS['gj_review_counts']['native_values']++;}}
 if($apply&&count($update)>1){$result=wp_update_post(wp_slash($update),true);if(is_wp_error($result))WP_CLI::error($result->get_error_message());}
 $current=get_fields($id,false)?:[];$updated=gj_review_fields($current,$schema);
 if($apply&&$updated!==$current){foreach($updated as$name=>$value)if($value!==($current[$name]??null))update_field('field_gj_'.$name,$value,$id);do_action('acf/save_post',$id);}
}
$options=get_fields('option',false)?:[];$updated=gj_review_fields($options,$schema);
if($apply)foreach($updated as$name=>$value)if($value!==($options[$name]??null))update_field('field_gj_'.$name,$value,'option');
foreach($snapshot['terms']as$id=>$record){
 if($record['language']!=='en')continue;
 $target=pll_get_term((int)$id,'ne');if(!$target)continue;
 $original=get_term($id);$translated=get_term($target);$update=[];
 foreach(['name','description']as$property){$new=gj_review_value($original->$property,$translated->$property);if($new!==$translated->$property)$update[$property]=$new;}
 if($update){$GLOBALS['gj_review_counts']['terms']++;if($apply){$result=wp_update_term($target,$original->taxonomy,$update);if(is_wp_error($result))WP_CLI::error($result->get_error_message());}}
}
foreach($snapshot['menus']as$menu=>$items){$object=wp_get_nav_menu_object($menu);if(!$object||!str_ends_with($object->name,' नेपाली'))continue;foreach($items?:[]as$item){$current=get_post_field('post_title',$item['ID']);$new=gj_review_patch_text($current);if($new!==$current){$GLOBALS['gj_review_counts']['menu_labels']++;if($apply)wp_update_post(wp_slash(['ID'=>$item['ID'],'post_title'=>$new]));}}}
foreach($snapshot['media']as$id=>$metadata){
 $post=get_post($id);
 foreach(['alt'=>get_post_meta($id,'_wp_attachment_image_alt',true),'caption'=>$post->post_excerpt,'description'=>$post->post_content]as$key=>$english){$meta='_gj_'.$key.'_ne';$current=get_post_meta($id,$meta,true);if($current==='')continue;$new=gj_review_value($english,$current);if($new!==$current){$GLOBALS['gj_review_counts']['media_values']++;if($apply)update_post_meta($id,$meta,$new);}}
}
$GLOBALS['gj_ne_dictionary']=$dictionary;$GLOBALS['gj_ne_skip_editorial_paragraphs']=false;
WP_CLI::log(wp_json_encode(['mode'=>$apply?'applied':'dry-run','changes'=>$GLOBALS['gj_review_counts']],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
