<?php
/** Published, language-matched relationships between articles and destination guides. */
defined('ABSPATH') || exit;
function gj_links_language($id){return function_exists('pll_get_post_language')?(pll_get_post_language($id,'slug')?:'en'):'en';}
function gj_links_local_id($id,$language){
 $id=absint($id);if(function_exists('pll_get_post'))$id=(int)pll_get_post($id,$language);
 return $id&&get_post_status($id)==='publish'?$id:0;
}
function gj_links_destination_index($language){
 static $cache=[];if(isset($cache[$language]))return $cache[$language];
 $index=['names'=>[],'paths'=>[]];
 foreach(get_posts(['post_type'=>'destination','post_status'=>'publish','posts_per_page'=>-1,'lang'=>$language])as$post){
  if(gj_links_language($post->ID)!==$language)continue;
  $index['names'][gj_canonical_title($post->ID)]=$post->ID;
  $index['paths'][untrailingslashit(wp_parse_url(get_permalink($post->ID),PHP_URL_PATH))]=$post->ID;
 }
 return $cache[$language]=$index;
}
function gj_article_destinations($post_id){
 static $cache=[];if(isset($cache[$post_id]))return $cache[$post_id];
 $language=gj_links_language($post_id);$ids=[];
 foreach((array)get_post_meta($post_id,'linked_destinations',true)as$id){$local=gj_links_local_id($id,$language);if($local&&get_post_type($local)==='destination')$ids[]=$local;}
 $index=gj_links_destination_index($language);$html=get_post_field('post_content',$post_id);
 $doc=gj_design_dom($html);$xp=new DOMXPath($doc);
 foreach($xp->query('//a[@href]')as$link){
  $href=$link->getAttribute('href');$host=wp_parse_url($href,PHP_URL_HOST);
  if($host&&strtolower($host)!==strtolower(wp_parse_url(home_url(),PHP_URL_HOST)))continue;
  $path=untrailingslashit(wp_parse_url($href,PHP_URL_PATH)?:'');$id=$index['paths'][$path]??0;
  $name=$link->getAttribute('data-place');
  if(!$name){parse_str(wp_parse_url($href,PHP_URL_QUERY)?:'',$query);$name=$query['place']??'';}
  if(!$id&&$name)$id=$index['names'][$name]??0;
  if($id)$ids[]=$id;
 }
 return $cache[$post_id]=array_values(array_unique($ids));
}
function gj_links_articles($language){
 static $cache=[];if(isset($cache[$language]))return $cache[$language];
 $rows=[];foreach(get_posts(['post_type'=>'post','post_status'=>'publish','posts_per_page'=>-1,'lang'=>$language,'orderby'=>'date','order'=>'DESC'])as$post){
  if(gj_links_language($post->ID)===$language)$rows[$post->ID]=gj_article_destinations($post->ID);
 }return $cache[$language]=$rows;
}
function gj_reading_links_html($ids,$heading,$intro=''){
 if(!$ids)return '';$html='<section class="gj-related-reading" data-gj-reading><h2>'.esc_html($heading).'</h2>';
 if($intro)$html.='<p>'.esc_html($intro).'</p>';
 $html.='<ul>';foreach($ids as$id)$html.='<li><a href="'.esc_url(get_permalink($id)).'">'.esc_html(get_the_title($id)).'</a></li>';
 return $html.'</ul></section>';
}
function gj_destination_reading_html($destination_id){
 $language=gj_links_language($destination_id);$ids=[];
 foreach(gj_links_articles($language)as$post_id=>$destinations)if(in_array($destination_id,$destinations,true))$ids[]=$post_id;
 $ne=$language==='ne';$html=gj_reading_links_html($ids,$ne?'यात्रा योजनाका लागि उपयोगी लेखहरू':'Travel articles for your visit',$ne?'यो गन्तव्य समेटिएका यात्रा योजनाहरू पढ्नुहोस्।':'Read itineraries that include this destination.');
 return $html?'<div class="body trip-related">'.$html.'</div>':'';
}
function gj_article_reading_html($html,$post_id){
 if(str_contains($html,'data-gj-reading'))return $html;
 $language=gj_links_language($post_id);$destinations=gj_article_destinations($post_id);$related=[];
 foreach(gj_links_articles($language)as$id=>$other)if($id!==$post_id&&array_intersect($destinations,$other))$related[]=$id;
 // Curated ACF choices are discoverable even if an editor has not added prose links yet.
 $missing=array_values(array_filter($destinations,fn($id)=>!str_contains($html,get_permalink($id))));
 if($missing)$html.=gj_reading_links_html($missing,$language==='ne'?'सम्बन्धित गन्तव्यहरू':'Related destination guides');
 return $html;
}
