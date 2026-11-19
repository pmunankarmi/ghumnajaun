<?php
/** wp eval-file tools/export-nepali.php — private working snapshot, never a theme hook. */
if(!defined('WP_CLI'))exit;
$out=['posts'=>[],'terms'=>[],'options'=>[],'menus'=>[]];
foreach(get_posts(['post_type'=>['page','post','destination'],'post_status'=>['publish','draft','private','pending'],'posts_per_page'=>-1,'lang'=>'','orderby'=>'ID','order'=>'ASC'])as$p){
 if(pll_get_post_language($p->ID)==='ne')continue;
 $out['posts'][]=['id'=>$p->ID,'type'=>$p->post_type,'status'=>$p->post_status,'title'=>$p->post_title,'content'=>$p->post_content,'excerpt'=>$p->post_excerpt,'slug'=>$p->post_name,'parent'=>$p->post_parent,'fields'=>get_fields($p->ID,false)?:[],'meta'=>get_post_meta($p->ID),'taxonomies'=>wp_get_object_terms($p->ID,['place_city','place_type','outing','category','post_tag'],['fields'=>'all'])];
}
foreach(['place_city','place_type','outing','category','post_tag']as$tax)foreach(get_terms(['taxonomy'=>$tax,'hide_empty'=>false,'lang'=>''])as$t){
 if(pll_get_term_language($t->term_id)==='ne')continue;
 $out['terms'][]=['id'=>$t->term_id,'taxonomy'=>$tax,'name'=>$t->name,'description'=>$t->description,'slug'=>$t->slug,'parent'=>$t->parent,'fields'=>get_fields('term_'.$t->term_id,false)?:[]];
}
$out['options']=get_fields('option',false)?:[];
$out['media']=[];
foreach(get_posts(['post_type'=>'attachment','post_status'=>'inherit','posts_per_page'=>-1])as$p)$out['media'][]=['id'=>$p->ID,'alt'=>get_post_meta($p->ID,'_wp_attachment_image_alt',true),'caption'=>$p->post_excerpt,'description'=>$p->post_content];
foreach(wp_get_nav_menus(['lang'=>''])as$m)if(!str_ends_with($m->name,' नेपाली'))$out['menus'][]=['id'=>$m->term_id,'name'=>$m->name,'items'=>wp_get_nav_menu_items($m->term_id)];
file_put_contents('/tmp/gj-ne-source.json',wp_json_encode($out,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));chmod('/tmp/gj-ne-source.json',0600);
$schema=[];foreach(acf_get_local_store('fields')->get()as$f)$schema[$f['key']]=['name'=>$f['name'],'type'=>$f['type']];
file_put_contents('/tmp/gj-ne-schema.json',wp_json_encode($schema));
WP_CLI::success('Exported '.count($out['posts']).' English records and '.count($out['terms']).' terms.');
