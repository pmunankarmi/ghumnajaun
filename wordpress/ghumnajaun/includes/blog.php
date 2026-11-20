<?php
/** Native categories and language-matched article discovery. */
defined('ABSPATH') || exit;
function gj_blog_category_links($id){
 $links=[];foreach(get_the_category($id)as$term){$url=get_term_link($term);if(!is_wp_error($url))$links[]='<a href="'.esc_url($url).'">'.esc_html($term->name).'</a>';}
 return implode(' · ',$links);
}
function gj_blog_category_navigation(){
 $ne=gj_is_nepali();$html='<nav class="blog-category-nav" aria-label="'.esc_attr($ne?'लेखका वर्गहरू':'Article categories').'"><a class="page-link" href="'.esc_url(gj_home_url('/blog/')).'"'.(!is_category()?' aria-current="page"':'').'>'.esc_html($ne?'सबै लेखहरू':'All articles').'</a>';
 foreach(get_terms(['taxonomy'=>'category','hide_empty'=>true])as$term){$url=get_term_link($term);if(is_wp_error($url))continue;$html.='<a class="page-link" href="'.esc_url($url).'"'.(is_category($term->term_id)?' aria-current="page"':'').'>'.esc_html($term->name).'</a>';}
 return $html.'</nav>';
}
function gj_blog_article_footer($id){
 $ne=gj_links_language($id)==='ne';$language=$ne?'ne':'en';$categories=wp_get_post_categories($id);$destinations=gj_article_destinations($id);$scores=[];
 foreach(gj_links_articles($language)as$other=>$places){if($other===$id)continue;$scores[$other]=count(array_intersect($destinations,$places))*3+count(array_intersect($categories,wp_get_post_categories($other)));}
 arsort($scores,SORT_NUMERIC);$related=array_slice(array_keys($scores),0,3);
 $html='<div class="body blog-article article-discovery">';
 $all=array_keys(gj_links_articles($language));$position=array_search($id,$all,true);
 if($position!==false&&count($all)>1){$html.='<nav class="article-pagination" aria-label="'.esc_attr($ne?'अघिल्लो र पछिल्लो लेख':'Previous and next articles').'">';
  foreach([['previous',$all[$position+1]??0,$ne?'← अघिल्लो लेख':'← Previous article'],['next',$position>0?$all[$position-1]:0,$ne?'पछिल्लो लेख →':'Next article →']]as[$rel,$target,$label])if($target)$html.='<a class="article-'.$rel.'" rel="'.($rel==='previous'?'prev':'next').'" href="'.esc_url(get_permalink($target)).'"><span>'.esc_html($label).'</span><strong>'.esc_html(get_the_title($target)).'</strong></a>';
  $html.='</nav>';
 }
 if($related){$html.='<section class="related-articles"><h2>'.esc_html($ne?'सम्बन्धित लेखहरू':'Related articles').'</h2><div class="related-article-grid">';
  foreach($related as$other){$html.='<a class="related-article-card" href="'.esc_url(get_permalink($other)).'">';if(has_post_thumbnail($other))$html.=get_the_post_thumbnail($other,'medium',['loading'=>'lazy']);$html.='<span><span class="meta">'.esc_html(implode(', ',wp_get_post_categories($other,['fields'=>'names']))).'</span><h3>'.esc_html(get_the_title($other)).'</h3><p>'.esc_html(wp_trim_words(get_the_excerpt($other),23)).'</p><span class="related-read">'.esc_html($ne?'लेख पढ्नुहोस् ↗':'Read article ↗').'</span></span></a>';}
  $html.='</div></section>';
 }
 return $html.'</div>';
}
