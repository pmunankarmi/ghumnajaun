<?php
defined('ABSPATH') || exit;
add_action('after_setup_theme', function () {
    load_theme_textdomain('ghumnajaun', get_template_directory().'/languages');
    foreach (['title-tag','post-thumbnails','custom-logo','automatic-feed-links','responsive-embeds'] as $feature) add_theme_support($feature);
    add_theme_support('html5',['search-form','gallery','caption','style','script']);
    register_nav_menus(['primary'=>'Main navigation','footer'=>'Footer navigation']);
});
add_filter('use_block_editor_for_post', '__return_false', 100);
add_filter('use_block_editor_for_post_type', '__return_false', 100);
add_filter('use_widgets_block_editor', '__return_false');
add_action('init', function () {
    register_post_type('destination', ['labels'=>['name'=>'Destinations','singular_name'=>'Destination'],'public'=>true,'has_archive'=>'explore','rewrite'=>['slug'=>'destination'],'show_in_rest'=>true,'menu_icon'=>'dashicons-location-alt','supports'=>['title','editor','excerpt','thumbnail','revisions','page-attributes']]);
    foreach (['place_city'=>'Cities','place_type'=>'Destination types','outing'=>'Outing categories'] as $key=>$label) register_taxonomy($key,['destination'],['label'=>$label,'public'=>true,'hierarchical'=>true,'show_in_rest'=>true]);
    if (defined('POLYLANG_VERSION') && !function_exists('polylang_slug_unique_slug_in_language')) require_once __DIR__.'/polylang-slug.php';
});
add_filter('pll_get_post_types', function($types){ $types['destination']='destination'; return $types; });
add_filter('pll_get_taxonomies', function($types){ foreach(['place_city','place_type','outing'] as $t)$types[$t]=$t; return $types; });
add_action('after_switch_theme',function(){ do_action('gj_register_ready'); flush_rewrite_rules(); });
add_action('wp_enqueue_scripts', function () {
    $base=get_template_directory_uri();
    wp_enqueue_style('gj-grid','https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap-grid.min.css',[], '5.3.8');
    wp_enqueue_style('gj-swiper','https://cdn.jsdelivr.net/npm/swiper@11.2.10/swiper-bundle.min.css',[],'11.2.10');
    wp_enqueue_style('gj-aos','https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',[],'2.3.4');
    foreach(['design'=>'styles.css','wordpress'=>'wordpress.css']as$key=>$file)wp_enqueue_style('gj-'.$key,$base.'/assets/'.$file,['gj-grid','gj-swiper','gj-aos'],hash_file('sha256',get_template_directory().'/assets/'.$file));
    wp_enqueue_script('gj-swiper','https://cdn.jsdelivr.net/npm/swiper@11.2.10/swiper-bundle.min.js',[],'11.2.10',true);
    wp_enqueue_script('gj-aos','https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js',[],'2.3.4',true);
    wp_enqueue_script('gj-runtime',$base.'/assets/design.js',['gj-swiper','gj-aos'],hash_file('sha256',get_template_directory().'/assets/design.js'),true);
    wp_add_inline_script('gj-runtime','window.GJ='.wp_json_encode(gj_design_data(),JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).';','before');
});
function gj_value($name,$id=null,$default='') { $id=$id?:get_the_ID(); $v=function_exists('get_field')?get_field($name,$id):get_post_meta($id,$name,true); return ($v===false||$v===null||$v==='')?$default:$v; }
function gj_option($name,$default=''){ return function_exists('get_field')?(get_field($name,'option')?:$default):$default; }
function gj_logo(){ return wp_get_attachment_image_url(get_theme_mod('custom_logo'),'full') ?: get_template_directory_uri().'/assets/logo-option-16.svg'; }
// ACF stores an attachment ID; Customizer uses the same attachment, never a second copy.
add_filter('acf/update_value/name=site_logo',function($value){set_theme_mod('custom_logo',absint($value));return absint($value);});
add_action('updated_option',function($name,$old,$new){if($name==='theme_mods_'.get_stylesheet() && ($old['custom_logo']??0)!==($new['custom_logo']??0) && function_exists('update_field'))update_field('field_gj_site_logo',absint($new['custom_logo']??0),'option');},10,3);
add_filter('acf/load_value/name=site_logo',function($value){return absint(get_theme_mod('custom_logo'));});
function gj_legacy_links($html){
    return preg_replace_callback('/(href|action)="([a-z-]+)\.html([^"\n]*)"/',function($m){return $m[1].'="'.esc_url(gj_home_url('/'.($m[2]==='index'?'':$m[2].'/').$m[3])).'"';},$html);
}
add_filter('the_content','gj_legacy_links');
add_action('admin_notices',function(){if(!function_exists('acf_add_local_field_group'))echo '<div class="notice notice-warning"><p>Ghumna Jaun requires ACF Pro for guide fields and theme options.</p></div>';});
class GJ_Flat_Nav extends Walker_Nav_Menu {
 public function start_lvl(&$output,$depth=0,$args=null){}
 public function end_lvl(&$output,$depth=0,$args=null){}
 public function start_el(&$output,$item,$depth=0,$args=null,$id=0){$data='';$parts=wp_parse_url($item->url);if(($parts['host']??'')===wp_parse_url(home_url(),PHP_URL_HOST)){if(untrailingslashit($parts['path']??'')===untrailingslashit(wp_parse_url(home_url('/explore/'),PHP_URL_PATH))){parse_str($parts['query']??'',$query);$data=' data-filter="'.esc_attr($query['category']??'All').'"';}elseif(untrailingslashit($parts['path']??'')===untrailingslashit(wp_parse_url(home_url('/blog/'),PHP_URL_PATH)))$data=' data-page="blog"';}$output.='<a class="page-link" href="'.esc_url($item->url).'"'.$data.(in_array('current-menu-item',$item->classes??[],true)?' aria-current="page"':'').'>'.esc_html($item->title).'</a>';}
 public function end_el(&$output,$item,$depth=0,$args=null){}
}
// Local's macOS PHP-FPM can abort in Apple's asynchronous IPv6 resolver.
// IPv4 transport retains HTTPS verification and allows WordPress/GitHub updates.
if (PHP_OS_FAMILY==='Darwin' && wp_get_environment_type()==='local') {
 add_action('http_api_curl',function($handle){curl_setopt($handle,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);});
}

function gj_uses_reference_logo(){ $id=(int)get_theme_mod('custom_logo');return !$id || get_post_meta($id,'_gj_svg_logo_sha256',true)===hash_file('sha256',get_template_directory().'/assets/site-logo.svg'); }

function gj_dark_logo(){ $id=(int)gj_option('site_logo_dark');return ($id?wp_get_attachment_image_url($id,'full'):false) ?: (gj_uses_reference_logo()?get_template_directory_uri().'/assets/logo-option-16-dark.svg':gj_logo()); }
function gj_brand_logo(){ echo '<img class="brand-logo brand-logo--light" src="'.esc_url(gj_logo()).'" width="321" height="64" alt=""><img class="brand-logo brand-logo--dark" src="'.esc_url(gj_dark_logo()).'" width="321" height="64" alt="">'; }
