<?php
defined('ABSPATH') || exit;
function gj_external_seo(){return defined('WPSEO_VERSION')||defined('RANK_MATH_VERSION')||defined('AIOSEO_VERSION');}
add_filter('pre_get_document_title',function($title){return !gj_external_seo()&&is_singular()?(gj_value('seo_title')?:$title):$title;});
add_filter('wp_robots',function($robots){if(is_singular()&&gj_value('seo_noindex')){$robots['noindex']=true;unset($robots['index']);}if(is_search()||isset($_GET['q']))$robots['noindex']=true;return $robots;});
add_action('wp_head',function(){
 if(gj_external_seo())return;
 $id=get_queried_object_id();$singular=is_singular();$url=$singular?get_permalink($id):(is_front_page()?home_url('/'):get_pagenum_link());
 $description=$singular?gj_value('seo_description',$id,wp_trim_words(wp_strip_all_tags(get_post_field('post_excerpt',$id)?:get_post_field('post_content',$id)),30)):get_bloginfo('description');
 $title=wp_get_document_title();$image=$singular?get_the_post_thumbnail_url($id,'large'):wp_get_attachment_image_url(gj_option('home_cover'),'large');
 echo '<meta name="description" content="'.esc_attr($description).'">' . "\n";
 // WordPress emits the singular canonical itself.
 if(!$singular&&!is_404()&&!is_search())echo '<link rel="canonical" href="'.esc_url($url).'">' . "\n";
 foreach(['og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'og:site_name'=>get_bloginfo('name'),'og:type'=>is_singular('post')?'article':'website']as$key=>$value)echo '<meta property="'.esc_attr($key).'" content="'.esc_attr($value).'">' . "\n";
 echo '<meta name="twitter:card" content="summary_large_image">';if($image)echo '<meta property="og:image" content="'.esc_url($image).'">';
 $schema=['@context'=>'https://schema.org','@type'=>'WebSite','@id'=>home_url('/#website'),'name'=>get_bloginfo('name'),'url'=>home_url('/')];
 if(is_singular('destination')){$schema=['@context'=>'https://schema.org','@type'=>'TouristAttraction','name'=>get_the_title($id),'url'=>$url,'description'=>$description];if($image)$schema['image']=$image;$lat=gj_value('latitude',$id);$lng=gj_value('longitude',$id);if(is_numeric($lat)&&is_numeric($lng))$schema['geo']=['@type'=>'GeoCoordinates','latitude'=>(float)$lat,'longitude'=>(float)$lng];}
 elseif(is_singular('post'))$schema=['@context'=>'https://schema.org','@type'=>'BlogPosting','headline'=>get_the_title($id),'url'=>$url,'datePublished'=>get_post_time('c',true,$id),'dateModified'=>get_post_modified_time('c',true,$id),'author'=>['@type'=>'Person','name'=>get_the_author_meta('display_name',get_post_field('post_author',$id))]];
 echo '<script type="application/ld+json">'.wp_json_encode($schema,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).'</script>';
},5);
// Redirect legacy prototype URLs to the permanent server-rendered destination.
add_action('template_redirect',function(){
 $path=wp_parse_url($_SERVER['REQUEST_URI']??'',PHP_URL_PATH);
 if(preg_match('~/destination(?:\.html)?/?$~',$path)&&isset($_GET['place'])&&is_string($_GET['place'])){$name=sanitize_text_field(wp_unslash($_GET['place']));$posts=get_posts(['post_type'=>'destination','post_status'=>'publish','title'=>$name,'posts_per_page'=>1]);if($posts){wp_safe_redirect(get_permalink($posts[0]),301);exit;}}
});
