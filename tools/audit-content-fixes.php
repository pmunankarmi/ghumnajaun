<?php
if(!defined('WP_CLI'))exit;
foreach(['youtube'=>'https://www.youtube.com/@ghumna-jaun','tiktok'=>'https://www.tiktok.com/@ghumnajaun','facebook'=>'https://www.facebook.com/profile.php?id=61596894200200']as$platform=>$url)update_field('field_gj_social_'.$platform,$url,'option');
$p=get_page_by_path('privacy-policy');if($p&&$p->post_status==='draft'&&(str_contains($p->post_content,'Suggested text')||str_contains($p->post_content,'suggested text')||str_contains($p->post_content,'<!-- wp:'))){
 update_post_meta($p->ID,'_gj_original_privacy_draft',$p->post_content);
 $source=file_get_contents(get_template_directory().'/data/privacy-policy.html');wp_update_post(['ID'=>$p->ID,'post_content'=>gj_legacy_links($source),'post_status'=>'publish']);if(function_exists('pll_set_post_language'))pll_set_post_language($p->ID,'en');update_option('wp_page_for_privacy_policy',$p->ID);WP_CLI::log('Published the supplied Privacy Policy in place of the unused WordPress draft.');
}
$sample=get_page_by_path('sample-page');if($sample&&str_contains($sample->post_content,'This is an example page')){wp_trash_post($sample->ID);WP_CLI::log('Moved the unused default Sample Page to Trash.');}
$hello=get_page_by_path('hello-world',OBJECT,'post');if($hello&&str_contains($hello->post_content,'Welcome to WordPress')){wp_trash_post($hello->ID);WP_CLI::log('Moved the unused default Hello World post to Trash.');}
flush_rewrite_rules();WP_CLI::success('Social links saved and privacy routing repaired.');
