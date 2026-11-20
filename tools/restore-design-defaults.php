<?php
if(!defined('WP_CLI'))exit;
if(!get_option('gj_original_design_defaults')){
 $menu=wp_get_nav_menu_object('Ghumna Jaun primary');$items=wp_get_nav_menu_items($menu->term_id);$labels=['Explore','Scenic dining','Dharmik','Blog'];$urls=['/explore/','/explore/?category=Scenic%20dining','/explore/?category=Dharmik','/blog/'];foreach($items as$i=>$item)if(isset($labels[$i]))wp_update_nav_menu_item($menu->term_id,$item->ID,['menu-item-title'=>$labels[$i],'menu-item-url'=>home_url($urls[$i]),'menu-item-status'=>'publish']);
 $featured=[];foreach(['Nagarkot','Panauti','Chitlang','Chandragiri Hills','Godawari Botanical Garden','Bhaktapur Durbar Square']as$name){$p=get_posts(['post_type'=>'destination','title'=>$name,'posts_per_page'=>1]);if($p)$featured[]=$p[0]->ID;}update_field('field_gj_featured_destinations',$featured,'option');
 update_option('gj_original_design_defaults',1);
}
// Assign the native location term required by the original Panauti city card.
$p=get_posts(['post_type'=>'destination','title'=>'Panauti','posts_per_page'=>1]);if($p){wp_set_object_terms($p[0]->ID,'Panauti','place_city',true);$term=get_term_by('name','Panauti','place_city');if($term&&function_exists('pll_set_term_language'))pll_set_term_language($term->term_id,'en');}
$data=json_decode(file_get_contents(__DIR__.'/data/legacy-theme/content.json'),true);
foreach($data['places']as$place){$posts=get_posts(['post_type'=>'destination','post_status'=>'any','meta_key'=>'_gj_import_key','meta_value'=>$place[0],'posts_per_page'=>1]);if($posts&&!get_field('card_label',$posts[0]->ID))update_field('field_gj_card_label',$place[1],$posts[0]->ID);}
foreach($data['blogPosts']as$article){$p=get_page_by_path($article['slug'],OBJECT,'post');if($p){$cats=wp_get_post_categories($p->ID,['fields'=>'names']);if($cats===['Uncategorized'])wp_set_object_terms($p->ID,$article['category'],'category');}}
flush_rewrite_rules();WP_CLI::success('Original design defaults restored; future admin edits remain editable.');
