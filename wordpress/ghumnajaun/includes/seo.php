<?php
defined('ABSPATH') || exit;
function gj_external_seo(){return defined('WPSEO_VERSION')||defined('RANK_MATH_VERSION')||defined('AIOSEO_VERSION');}
// The configured Posts page is an archive, so WordPress does not mark it singular.
function gj_seo_page_id(){
 if(is_singular())return (int)get_queried_object_id();
 if(!is_home())return 0;
 $id=(int)get_option('page_for_posts');
 if($id&&function_exists('pll_get_post')&&function_exists('pll_current_language'))$id=(int)(pll_get_post($id,pll_current_language('slug'))?:$id);
 return $id;
}
function gj_archive_seo(){
 $ne=function_exists('gj_is_nepali')&&gj_is_nepali();
 $brand=get_bloginfo('name');
 if(is_post_type_archive('destination'))return [
  'title'=>gj_option('explore_seo_title',$ne?'काठमाडौं उपत्यकामा घुम्ने ठाउँ खोज्नुहोस् | घुम्न जाऔँ':'Explore Kathmandu Valley Places to Visit | Ghumna Jaun'),
  'description'=>gj_option('explore_seo_description',$ne?'काठमाडौं उपत्यकाका मन्दिर, सम्पदा, बगैंचा र पिकनिक स्थल खोज्नुहोस्। सहर र घुमघामको प्रकारअनुसार गन्तव्य छानेर आफ्नो यात्राको योजना बनाउनुहोस्।':'Explore Kathmandu Valley temples, heritage sites, gardens and picnic spots. Browse destinations by city and outing type to plan your next day out.')
 ];
 if(is_tax(['outing','place_city','place_type'])||is_category()||is_tag()){
  $term=get_queried_object();
  if(!($term instanceof WP_Term))return [];
  $name=wp_strip_all_tags($term->name);
  if($term->taxonomy==='place_city')return [
   'title'=>$ne?$name.'मा घुम्ने ठाउँ र भ्रमण जानकारी | '.$brand:'Places to Visit in '.$name.' | '.$brand,
   'description'=>$ne?$name.'का गन्तव्य, मुख्य आकर्षण र जाने बाटोबारे जानकारी लिनुहोस्। परिवार वा साथीसँग घुम्न मिल्ने ठाउँ छानेर आफ्नो यात्राको योजना बनाउनुहोस्।':'Discover places to visit in '.$name.'. Explore destination highlights, visiting information and transport guidance to plan an outing with family or friends.'
  ];
  $blog=in_array($term->taxonomy,['category','post_tag'],true);
  return [
   'title'=>$ne?$name.($blog?': यात्रा लेख र घुमघामका विचार | ':': घुम्ने ठाउँ र यात्रा जानकारी | ').$brand:$name.($blog?' Travel Articles & Trip Ideas | ':' Outings & Destination Guides | ').$brand,
   'description'=>$ne?$name.($blog?'सम्बन्धी यात्रा लेख पढ्नुहोस्। घुम्ने ठाउँ र दिनको योजना बनाउने विचार लिएर काठमाडौं उपत्यका आसपासको यात्राको तयारी गर्नुहोस्।':'का लागि घुम्ने ठाउँ खोज्नुहोस्। गन्तव्यका आकर्षण, भ्रमणसम्बन्धी जानकारी र जाने बाटो हेरेर काठमाडौं उपत्यका आसपासको यात्रा मिलाउनुहोस्।'):'Explore '.$name.($blog?' travel articles and outing ideas. Find inspiration for heritage walks, day trips and visits around Kathmandu Valley.':' outings around Kathmandu Valley. Browse destination highlights, visiting information and directions to help plan your day.')
  ];
 }
 return [];
}
add_filter('pre_get_document_title',function($title){
 if(gj_external_seo())return $title;
 $id=gj_seo_page_id();
 $custom=$id?gj_value('seo_title',$id):(gj_archive_seo()['title']??'');
 if(!$custom)return $title;
 $page=max((int)get_query_var('paged'),(int)get_query_var('page'));
 return $custom.($page>1?((function_exists('gj_is_nepali')&&gj_is_nepali())?' — पृष्ठ ':' — Page ').$page:'');
});
add_filter('wp_robots',function($robots){if(is_singular()&&gj_value('seo_noindex')){$robots['noindex']=true;unset($robots['index']);}if(is_search()||isset($_GET['q']))$robots['noindex']=true;return $robots;});
add_action('wp_head',function(){
 if(gj_external_seo())return;
 $id=gj_seo_page_id();$singular=is_singular();$url=$singular?get_permalink($id):(is_front_page()?home_url('/'):get_pagenum_link());
 $description=$id?gj_value('seo_description',$id,wp_trim_words(wp_strip_all_tags(get_post_field('post_excerpt',$id)?:get_post_field('post_content',$id)),30)):(gj_archive_seo()['description']??get_bloginfo('description'));
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
