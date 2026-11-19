<?php
if(!defined('WP_CLI'))exit;
require __DIR__.'/import-wordpress.php';
if(!get_option('gj_original_guide_facts')){
 foreach($data['places']as$place){$posts=get_posts(['post_type'=>'destination','post_status'=>'publish','meta_key'=>'_gj_import_key','meta_value'=>$place[0],'posts_per_page'=>1]);if(!$posts)continue;$id=$posts[0]->ID;$facts=get_field('facts',$id)?:[];
 if(count($facts)===1&&$facts[0]['fact_label']==='Published entry fees'){
  $visit=get_field('visit_notes',$id);update_field('field_gj_visit_notes','<h3>Published entry fees</h3><p>'.esc_html($facts[0]['fact_value']).'</p><p>'.esc_html($facts[0]['fact_note']).'</p>'.$visit,$id);
  $rows=[['field_gj_fact_label'=>'Outing','field_gj_fact_value'=>explode(' · ',$place[1])[0],'field_gj_fact_note'=>''],['field_gj_fact_label'=>'Entry fees','field_gj_fact_value'=>'See ticket details','field_gj_fact_note'=>''],['field_gj_fact_label'=>'Visit plan','field_gj_fact_value'=>'See places to visit','field_gj_fact_note'=>'']];update_field('field_gj_facts',$rows,$id);
 }
 }
 update_option('gj_original_guide_facts',1);
}
$p=get_posts(['post_type'=>'destination','title'=>'Godawari Botanical Garden','posts_per_page'=>1]);
if($p&&!get_option('gj_original_garden_details')){
 $id=$p[0]->ID;$doc=gj_design_dom(file_get_contents(get_template_directory().'/views/destination.html'));$xp=new DOMXPath($doc);$node=fn($slot)=>$xp->query('//*[@data-trip-'.$slot.']')->item(0);
 $inner=function($n)use($doc){$html='';foreach($n->childNodes as$c)$html.=$doc->saveHTML($c);return $html;};
 $gallery=[];foreach(['Godawari Garden View','Godawari Hillside','Godawari Garden Stream']as$name){$image=gj_seed_photo($name);if($image)$gallery[]=$image;}if($gallery){update_field('field_gj_gallery',$gallery,$id);set_post_thumbnail($id,$gallery[0]);}
 $facts=[];foreach($node('facts')->childNodes as$row)if($row instanceof DOMElement){$children=[];foreach($row->childNodes as$c)if($c instanceof DOMElement)$children[]=trim($c->textContent);if(count($children)>=3&&!str_contains($children[0],'central Kathmandu'))$facts[]=['field_gj_fact_label'=>$children[0],'field_gj_fact_value'=>$children[1],'field_gj_fact_note'=>$children[2]];}update_field('field_gj_facts',$facts,$id);
 $stops=[];foreach($xp->query('.//li',$node('activities'))as$row){$title=$xp->query('.//h3',$row)->item(0);$copy=$xp->query('.//p',$row)->item(0);$photo=$xp->query('.//img',$row)->item(0);$stops[]=['field_gj_stop_title'=>$title?->textContent??'','field_gj_stop_description'=>$copy?->textContent??'','field_gj_stop_image'=>$photo?gj_seed_photo($photo->getAttribute('data-trip-photo')):0,'field_gj_stop_map'=>'National Botanical Garden, Godawari'];}update_field('field_gj_stops',$stops,$id);
 update_field('field_gj_visit_notes',$inner($node('visit')),$id);
 $transport=$node('transport');$paragraphs=$xp->query('.//p',$transport);update_field('field_gj_bus',$paragraphs->item(0)?->textContent??'',$id);update_field('field_gj_car',$paragraphs->item(1)?->textContent??'',$id);
 $rows=[];foreach($xp->query('.//li',$node('plan'))as$row)$rows[]=['field_gj_stage_title'=>$xp->query('.//strong',$row)->item(0)?->textContent??'','field_gj_stage_description'=>$xp->query('.//span',$row)->item(0)?->textContent??''];update_field('field_gj_day_plan',$rows,$id);
 $venues=[];foreach($xp->query('.//article',$node('meals'))as$row)$venues[]=['field_gj_venue_title'=>$xp->query('.//h3',$row)->item(0)?->textContent??'','field_gj_venue_description'=>$xp->query('.//p[not(@class)]',$row)->item(0)?->textContent??'','field_gj_venue_url'=>''];foreach($xp->query('.//li',$node('stays'))as$row)$venues[]=['field_gj_venue_title'=>$xp->query('.//strong',$row)->item(0)?->textContent??'','field_gj_venue_description'=>$xp->query('.//span',$row)->item(0)?->textContent??'','field_gj_venue_url'=>$xp->query('.//a',$row)->item(0)?->getAttribute('href')??''];update_field('field_gj_dining_stays',$venues,$id);
 update_option('gj_original_garden_details',1);
}
WP_CLI::success('Original guide facts and garden detail content preserved in editable ACF fields.');
