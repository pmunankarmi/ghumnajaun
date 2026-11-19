<?php
/** Run once using wp eval-file. Reruns skip records already imported. */
if(!defined('WP_CLI'))exit;
if(!function_exists('update_field'))WP_CLI::error('Activate ACF Pro before importing.');
$data=json_decode(file_get_contents(get_template_directory().'/data/content.json'),true);
$GLOBALS['gj_seed_data']=$data;
require_once ABSPATH.'wp-admin/includes/media.php';require_once ABSPATH.'wp-admin/includes/file.php';require_once ABSPATH.'wp-admin/includes/image.php';
function gj_seed_field($key,$value,$id){update_field('field_gj_'.$key,$value,$id);}
function gj_seed_photo($name){
 $data=$GLOBALS['gj_seed_data'];$photo=$data['photos'][$name]??null;if(!$photo)return 0;
 $found=get_posts(['post_type'=>'attachment','post_status'=>'inherit','meta_key'=>'_gj_photo_key','meta_value'=>$name,'fields'=>'ids','posts_per_page'=>1]);if($found)return $found[0];
 $src=$photo['src'];$path=get_template_directory().'/assets/'.$src;
 if(str_starts_with($src,'photos/')&&file_exists($path)){$tmp=wp_tempnam($path);copy($path,$tmp);$id=media_handle_sideload(['name'=>basename($path),'tmp_name'=>$tmp],0,$name);}
 else{
   $cache=getenv('GJ_MEDIA_CACHE')?:'/tmp/ghumnajaun-media';$cached=$cache?($cache.'/'.hash('sha256',$src).'.'.pathinfo(wp_parse_url($src,PHP_URL_PATH),PATHINFO_EXTENSION)):'';
   if(!$cached||!file_exists($cached)){WP_CLI::warning('Image not cached: '.$name);return 0;}
   $tmp=wp_tempnam($cached);copy($cached,$tmp);$id=media_handle_sideload(['name'=>sanitize_file_name($name).'.'.pathinfo($cached,PATHINFO_EXTENSION),'tmp_name'=>$tmp],0,$name);
 }
 if(is_wp_error($id)){WP_CLI::warning($name.': '.$id->get_error_message());return 0;}
 update_post_meta($id,'_gj_photo_key',$name);update_post_meta($id,'_wp_attachment_image_alt',$photo['alt']??$name);
 foreach(['photo_author'=>'author','photo_source'=>isset($photo['commons'])?'commons':'source','photo_license'=>'license']as$key=>$source)gj_seed_field($key,$photo[$source]??'',$id);
 return $id;
}
function gj_seed_page($slug,$title,$content=''){
 $existing=get_page_by_path($slug);if($existing)return $existing->ID;
 return wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_name'=>$slug,'post_title'=>$title,'post_content'=>gj_legacy_links($content)],true);
}
$home=gj_seed_page('home','Ghumna Jaun');$blog=gj_seed_page('blog','Travel guides');
update_option('show_on_front','page');update_option('page_on_front',$home);update_option('page_for_posts',$blog);update_option('blogname','Ghumna Jaun');update_option('blogdescription','Day trips, heritage walks and family outings around Kathmandu Valley.');update_option('permalink_structure','/%postname%/');update_option('blog_public',0);
foreach(['about'=>'About us','contact'=>'Contact us','privacy-policy'=>'Privacy Policy','terms-of-use'=>'Terms of Use','disclaimer'=>'Disclaimer','photo-credits'=>'Photo credits']as$slug=>$title){
 $file=get_template_directory().'/data/'.$slug.'.html';$html=file_exists($file)?file_get_contents($file):'';
 if($slug==='contact')$html=preg_replace('~<form\b.*?</form>~s','',$html);
 gj_seed_page($slug,$title,$html);
}
$ids=[];
foreach($data['places']as$place){
 [$name,$type,$tags]=$place;$existing=get_posts(['post_type'=>'destination','post_status'=>'any','meta_key'=>'_gj_import_key','meta_value'=>$name,'posts_per_page'=>1,'fields'=>'ids']);
 if($existing){$ids[$name]=$existing[0];continue;}
 $guide=$data['destinationGuides'][$name]??[];$trip=$data['tripDetails'][$name]??[];$summary=$guide['summary']??'';
 // The two scenic dining listings are sample content, kept private as drafts.
 $draft=!empty($place[4])||!$summary;
 $id=wp_insert_post(['post_type'=>'destination','post_status'=>$draft?'draft':'publish','post_title'=>$name,'post_content'=>wpautop(esc_html($summary)),'post_excerpt'=>$summary],true);if(is_wp_error($id))WP_CLI::error($id->get_error_message());$ids[$name]=$id;update_post_meta($id,'_gj_import_key',$name);
 wp_set_object_terms($id,explode('|',$tags),'outing');wp_set_object_terms($id,trim(explode(' · ',$type)[0]),'place_type');
 $address=$trip['location']??$type;gj_seed_field('address',$address,$id);
 $city='';foreach(['Bhaktapur','Lalitpur','Kathmandu','Dhulikhel','Banepa','Kirtipur']as$c)if(stripos($type.' '.$name,$c)!==false){$city=$c;break;}if($city)wp_set_object_terms($id,$city,'place_city');
 if(isset($data['placePoints'][$name])){gj_seed_field('latitude',$data['placePoints'][$name][0],$id);gj_seed_field('longitude',$data['placePoints'][$name][1],$id);}
 $gallery=[];foreach(($trip['photos']??$data['destinationGalleries'][$name]??[$name])as$p){$image=gj_seed_photo($p);if($image)$gallery[]=$image;}if($gallery){gj_seed_field('gallery',$gallery,$id);set_post_thumbnail($id,$gallery[0]);}
 $stops=[];foreach(($trip['activities']??$guide['stops']??[])as$stop){if(!is_array($stop))$stop=[$stop];$photo=!empty($trip)?($stop[3]??null):($stop[2]??null);$stops[]=['field_gj_stop_title'=>$stop[0],'field_gj_stop_description'=>$stop[1]??'','field_gj_stop_image'=>$photo?gj_seed_photo($photo):0,'field_gj_stop_map'=>$stop[0].', Nepal'];}gj_seed_field('stops',$stops,$id);
 if(!empty($guide['history'])){$h=$guide['history'];gj_seed_field('history',wpautop(esc_html($h[0])).'<p><a href="'.esc_url($h[2]).'">'.esc_html($h[1]).'</a></p>',$id);}
 $facts=[];foreach($trip['facts']??[]as$f)$facts[]=['field_gj_fact_label'=>$f[0],'field_gj_fact_value'=>$f[1],'field_gj_fact_note'=>$f[2]??''];if(isset($data['entryFees'][$name]))$facts[]=['field_gj_fact_label'=>'Published entry fees','field_gj_fact_value'=>'SAARC: NPR '.$data['entryFees'][$name][0].'; other international visitors: NPR '.$data['entryFees'][$name][1],'field_gj_fact_note'=>'Confirm current fees with the venue.'];gj_seed_field('facts',$facts,$id);
 $visit='';foreach($trip['visit']??[]as$v){$visit.='<h3>'.esc_html($v[0]).'</h3>';foreach($v[1]as$line)$visit.='<p>'.esc_html($line).'</p>';}
 if(!$visit)$visit=wpautop(esc_html($guide['picnic']??$guide['visitNote']??'Check opening hours and access before leaving, especially on holidays.'));
 gj_seed_field('visit_notes',$visit,$id);gj_seed_field('bus',$trip['transport'][0][1]??$guide['bus']??'Confirm boarding, the nearest drop-off and the last return with a local operator.',$id);gj_seed_field('car',$trip['transport'][1][1]??$guide['transport']??'Use the destination map pin and confirm parking before setting out.',$id);
 $plan=[];foreach($trip['plan']??$data['dayPlans'][$name]??array_column($guide['stops']??[],0)as$i=>$stage){if(!is_array($stage))$stage=explode(' · ',$stage,2);$plan[]=['field_gj_stage_title'=>$stage[0]??('Stop '.($i+1)),'field_gj_stage_description'=>$stage[1]??''];}gj_seed_field('day_plan',$plan,$id);
 $sources=$guide['sources']??$trip['sources']??[];if(isset($guide['source']))$sources[]=['Visitor information',$guide['source']];if(isset($data['entryFees'][$name]))$sources[]=['Nepal Tourism Board fee table',$data['entryFeeSource']];$rows=[];foreach($sources as$s)$rows[]=['field_gj_source_title'=>$s[0],'field_gj_source_url'=>$s[1]];gj_seed_field('sources',$rows,$id);
 $venues=[];foreach($trip['meals']??[]as$v)$venues[]=['field_gj_venue_title'=>$v[1]??$v[0],'field_gj_venue_description'=>$v[2]??'','field_gj_venue_url'=>$v[3]??''];foreach($trip['stays']??[]as$v)$venues[]=['field_gj_venue_title'=>$v[0],'field_gj_venue_description'=>$v[1]??'','field_gj_venue_url'=>$v[2]??''];gj_seed_field('dining_stays',$venues,$id);
 if(function_exists('pll_set_post_language'))pll_set_post_language($id,'en');
 WP_CLI::log(($draft?'Draft: ':'Imported: ').$name);
}
foreach($data['blogPosts']as$article){if(get_page_by_path($article['slug'],OBJECT,'post'))continue;$content=wpautop(esc_html($article['intro']));foreach($article['sections']as$s)$content.='<h2>'.esc_html($s[0]).'</h2>'.wpautop(esc_html($s[1]));foreach($article['sources']??[]as$s)$content.='<p><a href="'.esc_url($s[1]).'">'.esc_html($s[0]).'</a></p>';$id=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_name'=>$article['slug'],'post_title'=>$article['title'],'post_excerpt'=>$article['intro'],'post_content'=>$content]);$photo=gj_seed_photo($article['photo']);if($photo)set_post_thumbnail($id,$photo);if(function_exists('pll_set_post_language'))pll_set_post_language($id,'en');}
if(!get_option('gj_seed_options')){
 gj_seed_field('home_title','Where shall we go today?','option');gj_seed_field('home_eyebrow','DAY TRIPS FROM KATHMANDU VALLEY','option');gj_seed_field('home_cover',gj_seed_photo('Nagarkot'),'option');gj_seed_field('contact_recipient','ghumnajaun@gmail.com','option');
 $featured=[];foreach(['Bhaktapur Durbar Square','Nagarkot','Kulekhani','Changu Narayan Temple','Godawari Botanical Garden','Kakani']as$n)if(isset($ids[$n]))$featured[]=$ids[$n];gj_seed_field('featured_destinations',$featured,'option');update_option('gj_seed_options',1);
}
foreach(get_posts(['post_type'=>['page','post'],'post_status'=>'publish','posts_per_page'=>-1])as$p)if(function_exists('pll_set_post_language')&&!pll_get_post_language($p->ID))pll_set_post_language($p->ID,'en');
flush_rewrite_rules();WP_CLI::success('Content imported. Search indexing is disabled for this local site.');
