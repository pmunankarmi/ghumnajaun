<?php
/** Explicit content-only export for the Nepali live-site sync. Never runs in the theme. */
if(!defined('WP_CLI'))exit;
$out=['format'=>'ghumnajaun-nepali-v1','origin'=>home_url('/'),'posts'=>[],'terms'=>[],'media'=>[],'options'=>[],'menus'=>[],'schema'=>[]];
foreach(acf_get_local_store('fields')->get()as$f)$out['schema'][$f['key']]=['name'=>$f['name'],'type'=>$f['type']];
foreach(get_posts(['post_type'=>['page','post','destination'],'post_status'=>['publish','draft','private','pending'],'posts_per_page'=>-1,'lang'=>'en','orderby'=>'ID','order'=>'ASC'])as$p){
 $ne=pll_get_post($p->ID,'ne');if(!$ne)continue;$translated=get_post($ne);
 $out['posts'][]=['source_id'=>$p->ID,'source_slug'=>$p->post_name,'type'=>$p->post_type,'import_key'=>get_post_meta($p->ID,'_gj_import_key',true),'source_title'=>$p->post_title,'source_content'=>$p->post_content,'source_excerpt'=>$p->post_excerpt,'fields'=>get_fields($p->ID,false)?:[],'ne'=>['id'=>$ne,'title'=>$translated->post_title,'content'=>$translated->post_content,'excerpt'=>$translated->post_excerpt,'status'=>$translated->post_status,'slug'=>$translated->post_name]];
}
foreach(['place_city','place_type','outing','category','post_tag']as$tax)foreach(get_terms(['taxonomy'=>$tax,'hide_empty'=>false,'lang'=>'en'])as$t){$ne=pll_get_term($t->term_id,'ne');if(!$ne)continue;$translated=get_term($ne,$tax);$out['terms'][]=['source_id'=>$t->term_id,'source_slug'=>$t->slug,'taxonomy'=>$tax,'name'=>$t->name,'fields'=>get_fields('term_'.$t->term_id,false)?:[],'ne'=>['id'=>$ne,'name'=>$translated->name,'description'=>$translated->description,'slug'=>$translated->slug]];}
foreach(get_posts(['post_type'=>'attachment','post_status'=>'inherit','posts_per_page'=>-1])as$p){$row=['id'=>$p->ID,'file'=>get_post_meta($p->ID,'_wp_attached_file',true),'ne'=>[]];foreach(['alt','caption','description']as$key){$value=get_post_meta($p->ID,'_gj_'.$key.'_ne',true);if($value!=='')$row['ne'][$key]=$value;}$out['media'][]=$row;}
$options=get_fields('option',false)?:[];foreach($options as$key=>$value)if(str_ends_with($key,'_ne')){$base=substr($key,0,-3);$out['options'][$base]=['en'=>$options[$base]??'','ne'=>$value];}
$menus=PLL()->options['nav_menus'][get_stylesheet()]??[];
foreach(['primary','footer']as$location){$id=$menus[$location]['ne']??0;if(!$id)continue;$items=[];foreach(wp_get_nav_menu_items($id)?:[]as$item)$items[]=['title'=>$item->title,'url'=>$item->url,'object'=>$item->object,'object_id'=>(int)$item->object_id,'type'=>$item->type,'order'=>$item->menu_order];$out['menus'][$location]=$items;}
$path='/tmp/ghumnajaun-nepali-sync.json';file_put_contents($path,wp_json_encode($out,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));chmod($path,0600);WP_CLI::success('Exported '.count($out['posts']).' translated records and '.count($out['terms']).' translated terms.');
