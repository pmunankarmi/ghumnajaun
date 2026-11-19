<?php
if(!defined('WP_CLI'))exit;
$expected=['youtube'=>'https://www.youtube.com/@ghumna-jaun','tiktok'=>'https://www.tiktok.com/@ghumnajaun','instagram'=>'https://www.instagram.com/ghumnajaun','facebook'=>'https://www.facebook.com/profile.php?id=61596894200200'];
foreach($expected as$platform=>$url){if(gj_option('social_'.$platform)!==$url)WP_CLI::error('Incorrect '.$platform.' link');if(!acf_get_field('field_gj_social_'.$platform))WP_CLI::error('Missing social option');}
$logo=get_theme_mod('custom_logo');if((int)get_field('site_logo','option')!==(int)$logo)WP_CLI::error('Logo settings disagree');if(hash_file('sha256',get_attached_file($logo))!==hash_file('sha256',get_template_directory().'/assets/site-logo.svg'))WP_CLI::error('Logo differs from requested SVG');
if(count(gj_credit_items())!==75)WP_CLI::error('Expected 75 unique photo credits');
$privacy=get_page_by_path('privacy-policy');if(!$privacy||$privacy->post_status!=='publish')WP_CLI::error('Privacy Policy not published');
$guides=get_posts(['post_type'=>'destination','post_status'=>'publish','posts_per_page'=>-1]);foreach($guides as$p)if(!get_post_meta($p->ID,'_gj_design_audited',true))WP_CLI::error('Unaudited guide: '.$p->post_title);
foreach(['/photo-credits/','/privacy-policy/','/contact/','/destination/sankhu-bajrayogini/']as$path){$r=wp_remote_get(home_url($path));$body=wp_remote_retrieve_body($r);if(wp_remote_retrieve_response_code($r)!==200||str_contains($body,'Warning:')||str_contains($body,'Fatal error:'))WP_CLI::error('Page check failed: '.$path);}
WP_CLI::success('75 credits, four social settings, the exact shared SVG, published privacy, and all 57 audited guides verified.');
