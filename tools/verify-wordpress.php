<?php
if(!defined('WP_CLI'))exit;
function gj_check($ok,$message){if(!$ok)WP_CLI::error($message);WP_CLI::log('PASS '.$message);}
gj_check(!wp_is_block_theme(),'Theme is classic, not block');
$published=(int)wp_count_posts('destination')->publish;$drafts=(int)wp_count_posts('destination')->draft;
gj_check($published===57&&$drafts===2,'57 published guides and 2 draft samples');
$bhaktapur=get_posts(['post_type'=>'destination','title'=>'Bhaktapur Durbar Square','posts_per_page'=>1])[0];$id=$bhaktapur->ID;
gj_check(count(get_field('gallery',$id))===4,'Bhaktapur gallery contains all four images');
gj_check(count(get_field('stops',$id))===3,'Three editable Bhaktapur stops');
gj_check(get_post_thumbnail_id($id)===(int)get_field('gallery',$id)[0],'Featured image matches first gallery image');
$missing=get_posts(['post_type'=>'destination','posts_per_page'=>-1,'meta_query'=>[['key'=>'_thumbnail_id','compare'=>'NOT EXISTS']]]);gj_check(!$missing,'Every published guide has a featured image');
gj_check(in_array('ne',pll_languages_list(),true)&&in_array('en',pll_languages_list(),true),'English and Nepali configured');
gj_check(!use_block_editor_for_post($id),'Classic editor enabled');
$old_user=get_current_user_id();wp_set_current_user(0);
$request=new WP_REST_Request('GET','/ghumnajaun/v1/destinations');$request->set_param('per_page',1000);$list=rest_do_request($request);gj_check($list->get_status()===200&&count($list->get_data())<=50,'Anonymous list caps page size at 50');
$detail=rest_do_request(new WP_REST_Request('GET','/ghumnajaun/v1/destinations/'.$id));gj_check($detail->get_status()===200&&$detail->get_data()['image']['id']==get_post_thumbnail_id($id),'Detail API uses selected cover');
$draft=get_posts(['post_type'=>'destination','post_status'=>'draft','posts_per_page'=>1])[0];$response=rest_do_request(new WP_REST_Request('GET','/ghumnajaun/v1/destinations/'.$draft->ID));gj_check($response->get_status()===404,'Draft is excluded from public detail API');
$response=rest_do_request(new WP_REST_Request('GET','/ghumnajaun/v1/destinations/999999'));gj_check($response->get_status()===404,'Missing record returns 404');
$search=new WP_REST_Request('GET','/ghumnajaun/v1/search');$search->set_param('q','Bhaktapur');gj_check(count(rest_do_request($search)->get_data())>0,'Anonymous search finds Bhaktapur');
$language=new WP_REST_Request('GET','/ghumnajaun/v1/destinations');$language->set_param('lang','invalid');gj_check(rest_do_request($language)->get_status()===400,'Unknown language is rejected');
wp_set_current_user($old_user);
$old=get_field('address',$id);try{update_field('field_gj_address',$old.' [verification]',$id);$detail=rest_do_request(new WP_REST_Request('GET','/ghumnajaun/v1/destinations/'.$id));gj_check($detail->get_data()['guide']['address']===$old.' [verification]','Saved ACF edits appear in API');}finally{update_field('field_gj_address',$old,$id);}
$logo=get_theme_mod('custom_logo');$other=get_post_thumbnail_id($id);try{update_field('field_gj_site_logo',$other,'option');gj_check(get_theme_mod('custom_logo')===$other,'ACF logo updates Customizer');set_theme_mod('custom_logo',$logo);gj_check((int)get_field('site_logo','option')===(int)$logo,'Customizer logo updates ACF');}finally{update_field('field_gj_site_logo',$logo,'option');}
foreach(['/','/explore/','/destination/bhaktapur-durbar-square/','/about/','/contact/','/blog/','/outing/kids/']as$path){$r=wp_remote_get(home_url($path),['timeout'=>20]);gj_check(!is_wp_error($r)&&wp_remote_retrieve_response_code($r)===200,'HTTP 200 '.$path);gj_check(!str_contains(wp_remote_retrieve_body($r),'Warning:')&&!str_contains(wp_remote_retrieve_body($r),'Fatal error:'),'No PHP errors '.$path);if($path==='/destination/bhaktapur-durbar-square/'){$html=wp_remote_retrieve_body($r);gj_check(str_contains($html,'TouristAttraction')&&str_contains($html,'rel="canonical"')&&str_contains($html,'property="og:image"'),'Destination SEO metadata and schema');gj_check(str_contains($html,'55 Window Palace')&&str_contains($html,'Nyatapola Temple'),'Destination guide is server rendered');}}
gj_check((int)get_option('blog_public')===0,'Local site is noindex');
gj_check((bool)wp_next_scheduled('gj_github_update'),'Automatic GitHub check is scheduled');
WP_CLI::success('WordPress verification passed.');
