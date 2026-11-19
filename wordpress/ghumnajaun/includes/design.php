<?php
defined('ABSPATH') || exit;
function gj_design_view(){if(is_front_page())return 'home';if(is_singular('destination'))return 'detail';if(is_post_type_archive('destination')||is_tax(['outing','place_city','place_type']))return 'results';if(is_home())return 'blog';if(is_singular('post'))return 'post';if(is_page('about'))return 'about';if(is_page('contact'))return 'contact';return 'page';}
function gj_design_data(){
 static $out;if($out)return $out;
 $seed=json_decode(file_get_contents(get_template_directory().'/data/content.json'),true);
 $out=['photos'=>[],'places'=>[],'destinationGuides'=>[],'destinationGalleries'=>[],'tripDetails'=>[],'placePoints'=>[],'dayPlans'=>[],'citySightseeing'=>[],'blogPosts'=>[],'urls'=>[],'postUrls'=>[],'pages'=>[],'featured'=>[],'bannerPlaces'=>[]];
 foreach($seed['photos']as$name=>$photo){if(str_starts_with($photo['src'],'photos/'))$photo['src']=get_template_directory_uri().'/assets/'.$photo['src'];$out['photos'][$name]=$photo;}
 foreach(get_posts(['post_type'=>'attachment','post_status'=>'inherit','posts_per_page'=>-1])as$image){$photo=['src'=>wp_get_attachment_image_url($image->ID,'large'),'alt'=>get_post_meta($image->ID,'_wp_attachment_image_alt',true),'author'=>gj_value('photo_author',$image->ID),'source'=>gj_value('photo_source',$image->ID),'license'=>gj_value('photo_license',$image->ID)];$out['photos']['media-'.$image->ID]=$photo;$key=get_post_meta($image->ID,'_gj_photo_key',true);if($key)$out['photos'][$key]=$photo;}
 $names=[];
 foreach(get_posts(['post_type'=>'destination','post_status'=>'publish','posts_per_page'=>-1,'orderby'=>'menu_order title','order'=>'ASC'])as$p){
  $id=$p->ID;$name=$p->post_title;$names[$id]=$name;$f=function_exists('get_fields')?(get_fields($id)?:[]):[];foreach(['gallery','stops','facts','sources','day_plan','dining_stays','related']as$key)if(!is_array($f[$key]??null))$f[$key]=[];$tags=wp_get_post_terms($id,'outing',['fields'=>'names']);$types=wp_get_post_terms($id,'place_type',['fields'=>'names']);$address=$f['address']??'';$type=gj_value('card_label',$id,implode(' · ',$types));
  $out['places'][]=[$name,$type,implode('|',$tags)];$out['urls'][$name]=get_permalink($id);
  $cities=wp_get_post_terms($id,'place_city',['fields'=>'names']);foreach($cities as$city)$out['citySightseeing'][$city][]=$name;
  if(is_numeric($f['latitude']??null)&&is_numeric($f['longitude']??null))$out['placePoints'][$name]=[(float)$f['latitude'],(float)$f['longitude']];
  $cover=get_post_thumbnail_id($id);if(isset($out['photos']['media-'.$cover]))$out['photos'][$name]=$out['photos']['media-'.$cover];
  $gallery=array_values(array_filter(array_map(fn($image)=>'media-'.$image,$f['gallery']??[]),fn($key)=>isset($out['photos'][$key])));if(!$gallery&&$cover)$gallery=['media-'.$cover];$out['destinationGalleries'][$name]=$gallery;
  $summary=wp_strip_all_tags(apply_filters('the_content',$p->post_content));$stops=[];$activities=[];foreach($f['stops']??[]as$s){$photo=!empty($s['stop_image'])?'media-'.$s['stop_image']:null;$stops[]=[$s['stop_title'],$s['stop_description'],$photo];$activities[]=[esc_html($s['stop_title']),esc_html($s['stop_description']),'https://www.google.com/maps/search/?api=1&query='.rawurlencode($s['stop_map']?:$s['stop_title']),$photo];}
  $guide=['summary'=>$summary,'stops'=>$stops,'bus'=>$f['bus']??'','transport'=>$f['car']??''];if(!empty($f['history'])){$history=wp_strip_all_tags($f['history']);if(preg_match('~<p[^>]*>(.*?)</p>~s',$f['history'],$historyMatch))$history=wp_strip_all_tags($historyMatch[1]);$sources=$f['sources']??[];$guide['history']=[$history,$sources[0]['source_title']??'Source',$sources[0]['source_url']??home_url('/photo-credits/')];}$out['destinationGuides'][$name]=$guide;
  $facts=[];foreach($f['facts']??[]as$fact)$facts[]=[esc_html($fact['fact_label']),esc_html($fact['fact_value']),esc_html($fact['fact_note'])];if(!$facts)$facts=[['Outing',esc_html($type),''],['Visiting','Check local access',''],['Visit plan','See places to visit','']];
  $sources=[];foreach($f['sources']??[]as$s)$sources[]=[esc_html($s['source_title']),esc_url_raw($s['source_url'])];
  $plan=[];foreach($f['day_plan']??[]as$s)$plan[]=[esc_html($s['stage_title']),esc_html($s['stage_description'])];
  $meals=[];foreach($f['dining_stays']??[]as$v)$meals[]=['Nearby',esc_html($v['venue_title']),esc_html($v['venue_description']),esc_url_raw($v['venue_url']),'Visit website'];
  $nearby=[];foreach($f['related']??[]as$related)if(get_post_status($related)==='publish')$nearby[]=[null,esc_html(get_the_title($related)),get_permalink($related)];
  $out['tripDetails'][$name]=['location'=>$address?:$type,'photos'=>$gallery,'facts'=>$facts,'activities'=>$activities,'sources'=>$sources,'visit'=>[['Before you go',[wp_kses_post($f['visit_notes']??'')]]],'transport'=>[['By bus',esc_html($f['bus']??'')],['By car or taxi',esc_html($f['car']??'')]],'plan'=>$plan,'nearby'=>$nearby,'meals'=>$meals];
  if(!empty($f['facilities']))$out['tripDetails'][$name]['visit'][]=['Facilities and accessibility',[esc_html($f['facilities'])]];
 }
 // The original city cards group nearby sights; honor stored taxonomy first.
 foreach(['Kathmandu','Lalitpur','Bhaktapur','Banepa','Panauti','Dhulikhel']as$city){$original=$seed['citySightseeing'][$city]??[];foreach($original as$name)if(isset($out['urls'][$name])&&!in_array($name,$out['citySightseeing'][$city]??[],true))$out['citySightseeing'][$city][]=$name;}
 $cityOrder=gj_option('home_cities',[]);$cityNames=[];foreach($cityOrder as$termId){$term=get_term($termId,'place_city');if($term&&!is_wp_error($term))$cityNames[]=$term->name;}if(!$cityNames)$cityNames=['Kathmandu','Lalitpur','Bhaktapur','Banepa','Panauti','Dhulikhel'];$out['citySightseeing']=array_intersect_key(array_replace(array_fill_keys($cityNames,[]),$out['citySightseeing']),array_fill_keys($cityNames,true));
 foreach(get_posts(['post_type'=>'post','post_status'=>'publish','posts_per_page'=>-1])as$p){$image=get_post_thumbnail_id($p->ID);if(!$image)continue;$out['postUrls'][$p->post_name]=get_permalink($p->ID);$out['blogPosts'][]=['slug'=>$p->post_name,'title'=>esc_html($p->post_title),'category'=>esc_html(implode(', ',wp_get_post_categories($p->ID,['fields'=>'names']))),'photo'=>'media-'.$image,'intro'=>esc_html($p->post_excerpt),'sections'=>[],'places'=>[],'sources'=>[],'html'=>apply_filters('the_content',$p->post_content)];}
 foreach(['index'=>'','explore'=>'explore','blog'=>'blog','post'=>'blog','about'=>'about','contact'=>'contact','photo-credits'=>'photo-credits','privacy-policy'=>'privacy-policy','terms-of-use'=>'terms-of-use','disclaimer'=>'disclaimer','destination'=>'explore']as$file=>$slug)$out['pages'][$file.'.html']=home_url('/'.($slug?$slug.'/':''));
 $featured=gj_option('featured_destinations',[]);foreach($featured as$id)if(isset($names[$id]))$out['featured'][]=$names[$id];if(!$out['featured'])$out['featured']=array_slice(array_values($names),0,6);
 $banners=gj_option('home_banners',[]);foreach($banners as$id)if(isset($names[$id]))$out['bannerPlaces'][]=$names[$id];if(!$out['bannerPlaces'])$out['bannerPlaces']=array_values(array_filter(['Nagarkot','Chandragiri Hills','Panauti','Kulekhani','Bheda Farm'],fn($n)=>isset($out['urls'][$n])));
 if(!$out['bannerPlaces'])$out['bannerPlaces']=array_slice(array_values($names),0,1);
 if(is_singular('destination'))$out['currentPlace']=get_the_title(get_queried_object_id());if(is_singular('post'))$out['currentPost']=get_post_field('post_name',get_queried_object_id());
 if(is_tax('outing'))$out['category']=get_queried_object()->name;if(is_tax('place_city'))$out['city']=get_queried_object()->name;
 return $out;
}
function gj_design_cards_html($names,$data=null){$data=$data?:gj_design_data();$html='';foreach($names as$name){$place=null;foreach($data['places']as$p)if($p[0]===$name){$place=$p;break;}if(!$place)continue;$photo=$data['photos'][$name]??null;$html.='<div class="col" data-reveal="fade-up"><a class="place page-link" href="'.esc_url($data['urls'][$name]).'" data-place="'.esc_attr($name).'"><span class="art">'.($photo?'<img src="'.esc_url($photo['src']).'" alt="'.esc_attr($photo['alt']).'" loading="lazy">':'').'</span><span class="info"><span class="meta">'.esc_html($place[1]).'</span><strong>'.esc_html($name).'</strong><span class="card-foot">'.esc_html(implode(' · ',array_slice(explode('|',$place[2]),0,2))).' <span aria-hidden="true">↗</span></span></span></a></div>'; }return $html;}
function gj_design_chips_html(){ $out='';foreach(get_terms(['taxonomy'=>'outing','hide_empty'=>true])as$t)$out.='<a class="page-link" href="'.esc_url(get_term_link($t)).'" data-filter="'.esc_attr($t->name).'">'.esc_html($t->name).'</a>';return $out;}
function gj_design_blog_html($limit=0){$data=gj_design_data();$out='';$posts=$limit?array_slice($data['blogPosts'],0,$limit):$data['blogPosts'];foreach($posts as$p){$photo=$data['photos'][$p['photo']];$out.='<div class="col"><a class="place page-link blog-card" href="'.esc_url($data['postUrls'][$p['slug']]).'"><span class="art"><img src="'.esc_url($photo['src']).'" alt="'.esc_attr($photo['alt']).'" loading="lazy"></span><span class="info"><span class="meta">'.$p['category'].'</span><strong>'.$p['title'].'</strong><span>'.$p['intro'].'</span><span class="meta">Read guide ↗</span></span></a></div>';}return $out;}
function gj_design_dom($html){$doc=new DOMDocument();$previous=libxml_use_internal_errors(true);$doc->loadHTML('<?xml encoding="UTF-8"><div id="gj-fragment">'.$html.'</div>',LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);libxml_clear_errors();libxml_use_internal_errors($previous);return $doc;}
function gj_dom_fill($doc,$xpath,$html){$xp=new DOMXPath($doc);foreach($xp->query($xpath)as$node){while($node->firstChild)$node->removeChild($node->firstChild);$fragment=gj_design_dom($html);$root=$fragment->getElementById('gj-fragment');foreach(iterator_to_array($root->childNodes)as$child)$node->appendChild($doc->importNode($child,true));}}
function gj_dom_html($doc){$out='';foreach($doc->getElementById('gj-fragment')->childNodes as$child)$out.=$doc->saveHTML($child);return $out;}
function gj_design_template($name){
 $data=gj_design_data();$doc=gj_design_dom(file_get_contents(get_template_directory().'/views/'.$name.'.html'));$xp=new DOMXPath($doc);
 if($name==='index'){
  $slides='';foreach($data['bannerPlaces']as$i=>$place){$photo=$data['photos'][$place];$slides.='<div class="swiper-slide"><img src="'.esc_url($photo['src']).'" alt="'.esc_attr($photo['alt']).'" '.($i?'loading="lazy"':'fetchpriority="high"').'></div>';}
  gj_dom_fill($doc,'//*[@id="gj-banner-slides"]',$slides);gj_dom_fill($doc,'//*[@id="gj-home-chips"]',gj_design_chips_html());gj_dom_fill($doc,'//*[@id="gj-featured"]',gj_design_cards_html($data['featured'],$data));gj_dom_fill($doc,'//*[@id="gj-family-trips"]',gj_design_cards_html(['Kathmandu Fun Park','Central Zoo','Natural History Museum'],$data));gj_dom_fill($doc,'//*[@id="gj-blog-posts"]',gj_design_blog_html(3));
  $cities='';foreach($data['citySightseeing']as$city=>$names){$photo=$data['photos'][$names[0]]??null;if(!$photo)continue;$cities.='<div class="col"><a class="place city-card page-link" data-city="'.esc_attr($city).'" href="'.esc_url(add_query_arg('city',$city,home_url('/explore/'))).'"><span class="art"><img src="'.esc_url($photo['src']).'" alt="" loading="lazy"></span><span class="info"><strong>'.esc_html($city).'</strong><span class="card-foot"><span>'.count($names).' places to see</span><span aria-hidden="true">↗</span></span></span></a></div>';}
  gj_dom_fill($doc,'//*[@id="gj-cities"]',$cities);foreach($xp->query('//*[@id="gj-dining-image"]')as$n){$n->setAttribute('src',$data['photos']['Dining']['src']);}
  if(gj_option('home_title')&&gj_option('home_title')!=='Where shall we go today?')gj_dom_fill($doc,'//*[contains(@class,"hero-copy")]/h1',esc_html(gj_option('home_title')));
  if(gj_option('home_eyebrow'))gj_dom_fill($doc,'//*[contains(@class,"hero-copy")]/p',esc_html(gj_option('home_eyebrow')));
 }
 if($name==='blog')gj_dom_fill($doc,'//*[@id="gj-blog-posts"]',gj_design_blog_html());
 if($name==='explore'){
  $names=array_column($data['places'],0);$term=get_queried_object();if(is_tax('outing'))$names=array_map(fn($p)=>$p->post_title,get_posts(['post_type'=>'destination','posts_per_page'=>-1,'tax_query'=>[['taxonomy'=>'outing','terms'=>$term->term_id]]]));
  $q=sanitize_text_field(wp_unslash($_GET['q']??''));if($q)$names=array_values(array_filter($names,fn($n)=>stripos($n,$q)!==false));
  gj_dom_fill($doc,'//*[@id="gj-list"]',gj_design_cards_html($names,$data));gj_dom_fill($doc,'//*[@id="gj-filters"]',gj_design_chips_html());gj_dom_fill($doc,'//*[@id="gj-count"]',count($names).' places');
 }
 if($name==='post'){$id=get_queried_object_id();gj_dom_fill($doc,'//*[@id="gj-post-title"]',esc_html(get_the_title($id)));gj_dom_fill($doc,'//*[@id="gj-post-intro"]',esc_html(get_the_excerpt($id)));gj_dom_fill($doc,'//*[@id="gj-post-body"]',apply_filters('the_content',get_post_field('post_content',$id)));foreach($xp->query('//*[@id="gj-post-image"]')as$n){$n->setAttribute('src',get_the_post_thumbnail_url($id,'large'));$n->removeAttribute('hidden');}}
 return gj_legacy_links(gj_dom_html($doc));
}
function gj_design_trip_html(){
 $id=get_queried_object_id();$name=get_the_title($id);$data=gj_design_data();$details=$data['tripDetails'][$name];$guide=$data['destinationGuides'][$name];
 $raw=file_get_contents(get_template_directory().'/views/destination.html');preg_match('~<template[^>]*>(.*)</template>~s',$raw,$match);$doc=gj_design_dom($match[1]);$xp=new DOMXPath($doc);
 $fill=function($slot,$html)use($doc){gj_dom_fill($doc,'//*[@data-trip-'.$slot.']',$html);};
 $fill('title',esc_html($name));$fill('location',esc_html(strtoupper($details['location'])));$fill('intro',esc_html($guide['summary']));
 $slides='';foreach($details['photos']as$i=>$key){$photo=$data['photos'][$key];$slides.='<div class="swiper-slide"><img src="'.esc_url($photo['src']).'" data-trip-photo="'.esc_attr($key).'" alt="'.esc_attr($photo['alt']).'" '.($i?'loading="lazy"':'fetchpriority="high"').'></div>';}$fill('slides',$slides);
 $facts='';foreach($details['facts']as$f)$facts.='<div><dt>'.$f[0].'</dt><dd>'.$f[1].'</dd><dd>'.$f[2].'</dd></div>';$fill('facts',$facts);
 $activities='';foreach($details['activities']as$a){$photo=$data['photos'][$a[3]]??null;$activities.='<li>'.($photo?'<img class="trip-stop-photo" src="'.esc_url($photo['src']).'" alt="'.esc_attr($photo['alt']).'" loading="lazy">':'').'<div><h3>'.$a[0].'</h3><p>'.$a[1].'</p><a class="trip-inline-link" href="'.esc_url($a[2]).'" rel="noopener">View on map ↗</a></div></li>';}$fill('activities',$activities);
 $sources='';foreach($details['sources']as$s)$sources.='<a class="trip-inline-link" href="'.esc_url($s[1]).'" rel="noopener">'.$s[0].' ↗</a>';$fill('feature-link',$sources);
 foreach($xp->query('//*[@data-trip-feature]')as$n){if(isset($details['photos'][1])){$photo=$data['photos'][$details['photos'][1]];gj_dom_fill($doc,'//*[@data-trip-feature]','<img src="'.esc_url($photo['src']).'" alt="'.esc_attr($photo['alt']).'" loading="lazy"><figcaption>'.esc_html($photo['alt']).'</figcaption>');}else$n->parentNode->removeChild($n);}
 if(!empty($guide['history'])){$fill('history',esc_html($guide['history'][0]));$fill('history-source','<a href="'.esc_url($guide['history'][2]).'">'.esc_html($guide['history'][1]).'</a>');foreach($xp->query('//*[@id="gj-history" or @data-trip-history-jump]')as$n)$n->removeAttribute('hidden');}
 foreach($xp->query('//*[@id="gj-vlog-title"]')as$n)$n->parentNode->removeChild($n);
 $visit='';foreach($details['visit']as$v)$visit.='<div class="trip-card"><h3>'.esc_html($v[0]).'</h3>'.implode('',$v[1]).'</div>';$fill('visit',$visit);
 $transport='';foreach($details['transport']as$t)$transport.='<div><h3>'.$t[0].'</h3><p>'.$t[1].'</p></div>';$fill('transport',$transport.'<div class="trip-text-links" data-trip-travel-links></div>');
 $map='https://www.google.com/maps/search/?api=1&query='.rawurlencode(gj_value('address',$id,$name));foreach($xp->query('//*[@data-trip-directions or @data-trip-map-link]')as$n)$n->setAttribute('href',$map);
 $fill('map','<a class="action trip-map-link" href="'.esc_url($map).'" target="_blank" rel="noopener">Open location in Google Maps ↗</a>');
 $meals='';foreach($details['meals']as$m)$meals.='<article class="trip-card"><p class="eyebrow">'.$m[0].'</p><h3>'.$m[1].'</h3><p>'.$m[2].'</p>'.($m[3]?'<a href="'.esc_url($m[3]).'">Visit website ↗</a>':'').'</article>';$fill('meals',$meals);$fill('meal-note','');$fill('stays','');$fill('food-links','<a class="page-link" href="'.esc_url('https://www.google.com/maps/search/?api=1&query='.rawurlencode('Restaurants near '.$name)).'">Find nearby restaurants ↗</a>');
 $plan='';foreach($details['plan']as$p)$plan.='<li><strong>'.$p[0].'</strong><span>'.$p[1].'</span></li>';$fill('plan',$plan);$fill('nearby-note','');$fill('nearby-links','');
 $related=array_slice(array_values(array_diff(array_column($data['places'],0),[$name])),0,3);$fill('related',gj_design_cards_html($related,$data));
 $html='<section data-screen="detail" class="destination-trip">'.gj_dom_html($doc).'</section>';
 $html.=$raw; // Retain the inert template for the original interactive renderer.
 // Remove its empty screen; only the SSR screen above is visible before enhancement.
 $html=preg_replace('~<section data-screen="detail"></section>~','',$html);
 return gj_legacy_links($html);
}
function gj_footer_navigation(){
 $locations=get_nav_menu_locations();$items=wp_get_nav_menu_items($locations['footer']??0)?:[];
 echo '<div class="footer-navigation"><div class="footer-links">';foreach($items as$i=>$item){if($i===3)echo '</div><div class="footer-links footer-legal" aria-label="Policies">';echo '<a class="footer-link" href="'.esc_url($item->url).'">'.esc_html($item->title).'</a>';}echo '<button class="footer-cookie-preferences" type="button" data-cookie-preferences hidden>Cookie preferences</button></div></div>';
}
