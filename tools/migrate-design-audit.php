<?php
if(!defined('WP_CLI'))exit;
require __DIR__.'/import-wordpress.php';
foreach($data['bannerPhotos']??[]as$name=>$photo)$data['photos']['Banner: '.$name]=$photo;$GLOBALS['gj_seed_data']=$data;
foreach($data['bannerPhotos']??[]as$name=>$photo)gj_seed_photo('Banner: '.$name);
$rows=json_decode(file_get_contents(__DIR__.'/data/design-guides.json'),true);
function gj_audit_map_query($url){parse_str(wp_parse_url(html_entity_decode($url),PHP_URL_QUERY)??'',$query);return $query['query']??'';}
function gj_audit_local_url($url){if(!str_starts_with($url,'http://127.0.0.1:8765/'))return esc_url_raw($url);$parts=wp_parse_url(html_entity_decode($url));parse_str($parts['query']??'',$q);if(isset($q['place'])){$post=get_posts(['post_type'=>'destination','post_status'=>'publish','title'=>$q['place'],'posts_per_page'=>1]);if($post)return get_permalink($post[0]);}return home_url('/'.str_replace('.html','/',$parts['path']??''));}
$names=[];
foreach($rows as$row){
 $posts=get_posts(['post_type'=>'destination','post_status'=>'publish','meta_key'=>'_gj_import_key','meta_value'=>$row['name'],'posts_per_page'=>1]);if(!$posts)continue;$id=$posts[0]->ID;$names[$row['name']]=$id;
 if(get_post_meta($id,'_gj_design_audited',true))continue;
 update_post_meta($id,'_gj_before_design_audit',get_fields($id));
 gj_seed_field('guide_location',$row['location'],$id);
 $gallery=[];foreach($row['gallery']as$name){$image=gj_seed_photo($name);if($image)$gallery[]=$image;}if($gallery){gj_seed_field('gallery',$gallery,$id);set_post_thumbnail($id,$gallery[0]);}
 $facts=[];foreach($row['facts']as$f)$facts[]=['field_gj_fact_label'=>$f['label'],'field_gj_fact_value'=>$f['value'],'field_gj_fact_note'=>$f['note'],'field_gj_fact_note_url'=>$f['link']];gj_seed_field('facts',$facts,$id);
 $stops=[];foreach($row['stops']as$s)$stops[]=['field_gj_stop_title'=>$s['title'],'field_gj_stop_description'=>$s['description'],'field_gj_stop_image'=>$s['photo']?gj_seed_photo($s['photo']):0,'field_gj_stop_map'=>gj_audit_map_query($s['map'])];gj_seed_field('stops',$stops,$id);
 gj_seed_field('feature_image',$row['feature']['photo']?gj_seed_photo($row['feature']['photo']):0,$id);gj_seed_field('feature_caption',$row['feature']['caption'],$id);
 $links=[];foreach($row['guideLinks']as$link)$links[]=['field_gj_guide_link_label'=>$link['label'],'field_gj_guide_link_url'=>$link['url']];gj_seed_field('guide_links',$links,$id);
 gj_seed_field('visit_notes',$row['visitHtml'],$id);gj_seed_field('video_url',$row['video'],$id);gj_seed_field('bus',$row['bus'],$id);gj_seed_field('car',$row['car'],$id);
 gj_seed_field('bus_source_label',$row['transportLinks'][0][0]['label']??'',$id);gj_seed_field('bus_source_url',$row['transportLinks'][0][0]['url']??'',$id);
 if(count($row['travelLinks'])>2){gj_seed_field('transport_pin_label',$row['travelLinks'][0]['label'],$id);gj_seed_field('transport_pin_query',gj_audit_map_query($row['travelLinks'][0]['url']),$id);}
 gj_seed_field('meal_note',$row['mealNote'],$id);$venues=[];
 foreach(['meal'=>$row['meals'],'stay'=>$row['stays']]as$kind=>$items)foreach($items as$v){$links=[];foreach($v['links']as$link)$links[]=['field_gj_venue_link_label'=>$link['label'],'field_gj_venue_link_url'=>gj_audit_local_url($link['url'])];$venues[]=['field_gj_venue_kind'=>$kind,'field_gj_venue_stage'=>$v['stage']??'','field_gj_venue_title'=>$v['title'],'field_gj_venue_description'=>$v['description'],'field_gj_venue_url'=>$v['links'][0]['url']??'','field_gj_venue_links'=>$links];}gj_seed_field('dining_stays',$venues,$id);
 $stages=[];foreach($row['plan']as$p)$stages[]=['field_gj_stage_title'=>$p['title'],'field_gj_stage_description'=>$p['description']];gj_seed_field('day_plan',$stages,$id);
 $nearby=[];foreach($row['nearby']as$n)$nearby[]=['field_gj_nearby_label'=>$n['label'],'field_gj_nearby_url'=>gj_audit_local_url($n['url'])];gj_seed_field('nearby_stops',$nearby,$id);gj_seed_field('nearby_note',$row['nearbyNote'],$id);
 if($row['history']){gj_seed_field('history',wpautop(esc_html($row['history'])),$id);gj_seed_field('history_source_title',$row['historyLinks'][0]['label']??'',$id);gj_seed_field('history_source_url',$row['historyLinks'][0]['url']??'',$id);}
 update_post_meta($id,'_gj_design_audited',1);
}
foreach($names as$name=>$id){if(get_post_meta($id,'_gj_card_cover_audited',true))continue;$gallery=get_field('gallery',$id)?:[];$cover=gj_seed_photo($data['destinationGalleries'][$name][0]??$name);if($cover&&get_post_thumbnail_id($id)==($gallery[0]??0)&&get_post_thumbnail_id($id)!=$cover){update_post_meta($id,'_gj_before_audit_featured_image',get_post_thumbnail_id($id));set_post_thumbnail($id,$cover);}update_post_meta($id,'_gj_card_cover_audited',1);}
foreach($data['citySightseeing']as$city=>$sights){$term=get_term_by('name',$city,'place_city');if($term&&!get_field('city_sights','place_city_'.$term->term_id)){gj_seed_field('city_sights',array_values(array_filter(array_map(fn($name)=>$names[$name]??0,$sights))),'place_city_'.$term->term_id);}}
foreach(json_decode(file_get_contents(__DIR__.'/data/design-articles.json'),true)as$a){$post=get_page_by_path($a['slug'],OBJECT,'post');if(!$post||get_post_meta($post->ID,'_gj_article_design_audited',true))continue;update_post_meta($post->ID,'_gj_before_design_audit_content',$post->post_content);$body=preg_replace_callback('~href="(http://127\.0\.0\.1:8765/[^"]+)"~',fn($m)=>'href="'.esc_url(gj_audit_local_url($m[1])).'"',$a['body']);wp_update_post(['ID'=>$post->ID,'post_content'=>$body]);update_post_meta($post->ID,'_gj_article_design_audited',1);}
update_field('field_gj_social_instagram','https://www.instagram.com/ghumnajaun','option');
WP_CLI::success('Audited guide sections, articles, city cards and Instagram saved. Pre-audit fields preserved as private metadata.');
