<?php
if(!defined('WP_CLI'))exit;
// Language creation uses the installed Polylang model; linked translations stay native.
if(function_exists('PLL')){
 foreach([['locale'=>'en_US','name'=>'English','slug'=>'en','flag'=>'us'],['locale'=>'ne_NP','name'=>'नेपाली','slug'=>'ne','flag'=>'np']]as$lang){if(!PLL()->model->languages->get($lang['slug'])){$result=PLL()->model->languages->add($lang);if(is_wp_error($result))WP_CLI::warning($result->get_error_message());}}
 PLL()->model->clean_languages_cache();
}
require __DIR__.'/import-wordpress.php';
foreach(get_posts(['post_type'=>'destination','post_status'=>'any','posts_per_page'=>-1])as$p){
 $name=get_post_meta($p->ID,'_gj_import_key',true);if(!$name)continue;
 $guide=$data['destinationGuides'][$name]??[];$trip=$data['tripDetails'][$name]??[];
 if(!get_post_thumbnail_id($p->ID)){$gallery=[];foreach(($trip['photos']??$data['destinationGalleries'][$name]??[$name])as$photo){$image=gj_seed_photo($photo);if($image)$gallery[]=$image;}if($gallery){gj_seed_field('gallery',$gallery,$p->ID);set_post_thumbnail($p->ID,$gallery[0]);}}
 $stops=get_field('stops',$p->ID)?:[];foreach($stops as$i=>$stop){if(empty($stop['stop_image'])){$source=$trip['activities'][$i][3]??$guide['stops'][$i][2]??'';if($source)$stops[$i]['stop_image']=gj_seed_photo($source);}}if($stops)gj_seed_field('stops',$stops,$p->ID);
 if(function_exists('pll_set_post_language'))pll_set_post_language($p->ID,'en');
 // Populate a useful location while retaining custom editor changes.
 if(get_field('address',$p->ID)===($trip['location']??explode(' · ',get_field('address',$p->ID))[0]) && isset($guide['stops'][0][0]))gj_seed_field('address',$guide['stops'][0][0],$p->ID);
}
foreach($data['blogPosts']as$a){$post=get_page_by_path($a['slug'],OBJECT,'post');if($post&&!get_post_thumbnail_id($post->ID)){ $photo=gj_seed_photo($a['photo']);if($photo)set_post_thumbnail($post->ID,$photo);}}
if(!get_field('home_cover','option'))gj_seed_field('home_cover',gj_seed_photo('Nagarkot'),'option');
$logo=get_posts(['post_type'=>'attachment','post_status'=>'inherit','meta_key'=>'_gj_logo','meta_value'=>'1','fields'=>'ids','posts_per_page'=>1]);
if(!$logo){$file=get_template_directory().'/assets/logo-option-16.png';if(file_exists($file)){$tmp=wp_tempnam($file);copy($file,$tmp);$id=media_handle_sideload(['name'=>'ghumna-jaun-logo.png','tmp_name'=>$tmp],0,'Ghumna Jaun');if(!is_wp_error($id)){update_post_meta($id,'_gj_logo',1);gj_seed_field('site_logo',$id,'option');}}}
foreach(['primary'=>['Explore'=>'/explore/','Blog'=>'/blog/','About us'=>'/about/','Contact'=>'/contact/'],'footer'=>['About us'=>'/about/','Contact us'=>'/contact/','Photo credits'=>'/photo-credits/','Privacy Policy'=>'/privacy-policy/','Terms of Use'=>'/terms-of-use/','Disclaimer'=>'/disclaimer/']]as$location=>$links){$menu=wp_get_nav_menu_object('Ghumna Jaun '.$location);if(!$menu){$menu_id=wp_create_nav_menu('Ghumna Jaun '.$location);foreach($links as$title=>$path)wp_update_nav_menu_item($menu_id,0,['menu-item-title'=>$title,'menu-item-url'=>home_url($path),'menu-item-status'=>'publish']);$locations=get_theme_mod('nav_menu_locations',[]);$locations[$location]=$menu_id;set_theme_mod('nav_menu_locations',$locations);}}
if(function_exists('PLL')){$menus=PLL()->options['nav_menus'];foreach(['primary','footer']as$location){$menu=wp_get_nav_menu_object('Ghumna Jaun '.$location);if($menu)$menus[get_stylesheet()][$location]['en']=$menu->term_id;}PLL()->options['nav_menus']=$menus;}
foreach(['place_city','place_type','outing']as$taxonomy)foreach(get_terms(['taxonomy'=>$taxonomy,'hide_empty'=>false,'lang'=>''])as$term)if(function_exists('pll_set_term_language')&&!pll_get_term_language($term->term_id))pll_set_term_language($term->term_id,'en');
$bhaktapur=get_posts(['post_type'=>'destination','title'=>'Bhaktapur Durbar Square','posts_per_page'=>1]);if($bhaktapur){$id=$bhaktapur[0]->ID;$gallery=get_field('gallery',$id)?:[];if(count($gallery)===3){$image=gj_seed_photo('Bhaktapur Durbar Square Panorama');if($image){$gallery[]=$image;gj_seed_field('gallery',$gallery,$id);}}}
flush_rewrite_rules();WP_CLI::success('Media, menus, logo and English/Nepali editing configured.');
