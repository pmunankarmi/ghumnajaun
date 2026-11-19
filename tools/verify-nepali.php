<?php
if(!defined('WP_CLI'))exit;
$source=json_decode(file_get_contents('/tmp/gj-ne-source.json'),true);$schema=json_decode(file_get_contents('/tmp/gj-ne-schema.json'),true);
$GLOBALS['issues']=[];$GLOBALS['pairs']=0;$issues=&$GLOBALS['issues'];$pairs=&$GLOBALS['pairs'];$count=0;
function gj_ne_strip($value){if(!is_array($value))return $value;foreach($value as$k=>$v){if(is_string($k)&&str_ends_with($k,'_ne'))unset($value[$k]);else$value[$k]=gj_ne_strip($v);}return $value;}
function gj_ne_check_pairs($values,$schema,$context){global $issues,$pairs;if(!is_array($values))return;foreach($values as$key=>$value){if(is_array($value)){gj_ne_check_pairs($value,$schema,$context);continue;}$info=$schema[$key]??$schema['field_gj_'.$key]??null;if(!$info||str_ends_with($key,'_ne')||!in_array($info['type'],['text','textarea','wysiwyg'],true)||in_array($info['name'],['photo_author','photo_license'],true)||!is_string($value)||$value==='')continue;$pairs++;if(empty($values[$key.'_ne']))$issues[]=$context.': missing '.$key.'_ne';}}
foreach($source['posts']as$p){
 $id=pll_get_post($p['id'],'ne');if(!$id){$issues[]='Missing translation '.$p['id'];continue;}$count++;
 $english=get_post($p['id']);foreach(['title'=>'post_title','content'=>'post_content','excerpt'=>'post_excerpt','status'=>'post_status','slug'=>'post_name']as$key=>$property)if($english->$property!==$p[$key])$issues[]='English '.$key.' changed: '.$p['id'];
 $translated=get_post($id);if(!preg_match('/\p{Devanagari}/u',$translated->post_title))$issues[]='Untranslated title '.$id;
 if($p['content']&&!preg_match('/\p{Devanagari}/u',$translated->post_content))$issues[]='Untranslated body '.$id;
 if($translated->post_status!==$p['status'])$issues[]='Status mismatch '.$id;
 $fields=get_fields($p['id'],false)?:[];$target=get_fields($id,false)?:[];
 if($fields!=$target)$issues[]='ACF bundle differs '.$id;
 foreach($p['fields']as$key=>$old){if(str_ends_with($key,'_ne'))continue;if(gj_ne_strip($old)!=gj_ne_strip($fields[$key]??null))$issues[]='English ACF changed '.$p['id'].' '.$key;}
 gj_ne_check_pairs($fields,$schema,'Post '.$p['id']);
 if(get_post_thumbnail_id($p['id'])!==get_post_thumbnail_id($id))$issues[]='Featured image mismatch '.$id;
}
gj_ne_check_pairs(get_fields('option',false),$schema,'Options');
foreach($source['terms']as$t)if(!pll_get_term($t['id'],'ne'))$issues[]='Missing term '.$t['id'];
if($issues){foreach($issues as$issue)WP_CLI::warning($issue);WP_CLI::error(count($issues).' validation failures');}
WP_CLI::success($count.' linked records; '.$pairs.' filled Nepali ACF values; 51 linked terms; English content and shared images preserved.');
