<?php
if(!defined('WP_CLI'))exit;
add_filter('upload_mimes',function($mimes){$mimes['svg']='image/svg+xml';return $mimes;});
$source=get_template_directory().'/assets/logo-option-16-dark.svg';$hash=hash_file('sha256',$source);$found=get_posts(['post_type'=>'attachment','post_status'=>'inherit','meta_key'=>'_gj_dark_logo_sha256','meta_value'=>$hash,'fields'=>'ids','posts_per_page'=>1]);
if($found)$id=$found[0];else{$upload=wp_upload_bits('ghumna-jaun-dark.svg',null,file_get_contents($source));if($upload['error'])WP_CLI::error($upload['error']);$id=wp_insert_attachment(['post_title'=>'Ghumna Jaun dark mode logo','post_mime_type'=>'image/svg+xml','post_status'=>'inherit'],$upload['file']);update_post_meta($id,'_gj_dark_logo_sha256',$hash);update_post_meta($id,'_wp_attachment_image_alt','Ghumna Jaun');wp_update_attachment_metadata($id,['width'=>321,'height'=>64,'file'=>get_post_meta($id,'_wp_attached_file',true),'sizes'=>[]]);}
if(!get_field('site_logo_dark','option'))update_field('field_gj_site_logo_dark',$id,'option');
WP_CLI::success('Separate light and dark logo options are configured.');
