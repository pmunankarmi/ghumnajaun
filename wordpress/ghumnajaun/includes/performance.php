<?php
defined('ABSPATH') || exit;
/** Responsive information stays tied to the selected WordPress attachment. */
function gj_photo_sizes($id){
 $image=wp_get_attachment_image_src($id,'large');
 return $image?['width'=>$image[1],'height'=>$image[2],'srcset'=>wp_get_attachment_image_srcset($id,'large')?:'']:[];
}
function gj_responsive_attributes($photo,$sizes='100vw'){
 $html='';
 foreach(['width','height']as$key)if(!empty($photo[$key]))$html.=' '.$key.'="'.absint($photo[$key]).'"';
 if(!empty($photo['srcset']))$html.=' srcset="'.esc_attr($photo['srcset']).'" sizes="'.esc_attr($sizes).'"';
 return $html;
}
function gj_responsive_fragment($doc,$photos){
 $by_url=[];foreach($photos as$photo)if(!empty($photo['src']))$by_url[$photo['src']]=$photo;
 $xp=new DOMXPath($doc);
 foreach($xp->query('//img[@src]')as$image){
  $photo=$by_url[$image->getAttribute('src')]??null;if(!$photo)continue;
  $sizes=$xp->query('ancestor::*[contains(concat(" ",normalize-space(@class)," ")," art ")]',$image)->length?'(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px':'100vw';
  if($image->hasAttribute('width')&&!$image->hasAttribute('height'))continue;
  foreach(['width','height']as$key)if(!$image->hasAttribute($key)&&!empty($photo[$key]))$image->setAttribute($key,(string)$photo[$key]);
  if(!empty($photo['srcset'])){$image->setAttribute('srcset',$photo['srcset']);$image->setAttribute('sizes',$sizes);}
 }
}
// Fetch the real first hero early, with the same responsive candidates as its markup.
add_action('wp_head',function(){
 $data=gj_design_data();$photo=null;
 if(is_front_page()){$name=$data['bannerPlaces'][0]??'';$photo=$data['bannerPhotos'][$name]??$data['photos'][$name]??null;}
 elseif(is_singular('destination')){$name=$data['currentPlace']??'';$key=$data['tripDetails'][$name]['photos'][0]??'';$photo=$data['photos'][$key]??null;}
 if($photo){echo '<link rel="preload" as="image" fetchpriority="high" href="'.esc_url($photo['src']).'"';if(!empty($photo['srcset']))echo ' imagesrcset="'.esc_attr($photo['srcset']).'" imagesizes="100vw"';echo ">\n";}
 $font=gj_is_nepali()?'noto-sans-devanagari-devanagari.woff2':'instrument-serif-latin.woff2';
 echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="'.esc_url(get_template_directory_uri().'/assets/fonts/'.$font.'?v='.substr(hash_file('sha256',get_template_directory().'/assets/fonts/'.$font),0,12)).'">'."\n";
},2);
