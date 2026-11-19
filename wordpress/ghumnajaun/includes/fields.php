<?php
defined('ABSPATH') || exit;
add_action('acf/init',function(){
    acf_add_options_page(['page_title'=>'Ghumna Jaun settings','menu_title'=>'Site settings','menu_slug'=>'gj-settings','capability'=>'edit_theme_options','redirect'=>false]);
    $field=function($name,$label,$type='text',$extra=[]){return array_merge(['key'=>'field_gj_'.$name,'name'=>$name,'label'=>$label,'type'=>$type],$extra);};
    $row=function($name,$label,$subs)use($field){return $field($name,$label,'repeater',['layout'=>'block','button_label'=>'Add item','sub_fields'=>$subs]);};
    $fields=[
        $field('address','Address'),$field('latitude','Latitude','number',['step'=>'any']),$field('longitude','Longitude','number',['step'=>'any']),
        $field('history','History','wysiwyg'),
        $field('gallery','Photo gallery','gallery',['return_format'=>'id']),
        $row('facts','Visit facts',[$field('fact_label','Label'),$field('fact_value','Value (include units)'),$field('fact_note','Note')]),
        $row('stops','Things to do',[$field('stop_title','Title'),$field('stop_description','Description','textarea'),$field('stop_image','Photo','image',['return_format'=>'id']),$field('stop_map','Map query'),$field('stop_destination','Linked destination','post_object',['post_type'=>['destination'],'return_format'=>'id'])]),
        $field('visit_notes','Plan your visit','wysiwyg'),$field('bus','By bus','textarea'),$field('car','By car or taxi','textarea'),
        $field('facilities','Facilities and accessibility','textarea'),
        $row('day_plan','Day plan',[$field('stage_title','Stage'),$field('stage_description','Description','textarea')]),
        $row('dining_stays','Eat and stay',[$field('venue_title','Name'),$field('venue_description','Details','textarea'),$field('venue_url','Website','url')]),
        $row('sources','Sources',[$field('source_title','Source name'),$field('source_url','URL','url')]),
        $field('verification_date','Last verified','date_picker',['return_format'=>'Y-m-d']),$field('video_url','Video URL','url'),
        $field('related','Related destinations','relationship',['post_type'=>['destination'],'return_format'=>'id'])
    ];
    acf_add_local_field_group(['key'=>'group_gj_guide','title'=>'Destination guide','fields'=>$fields,'location'=>[[['param'=>'post_type','operator'=>'==','value'=>'destination']]],'show_in_rest'=>true]);
    acf_add_local_field_group(['key'=>'group_gj_options','title'=>'Branding and homepage','fields'=>[
        $field('site_logo','Site logo','image',['return_format'=>'id']),$field('home_eyebrow','Homepage eyebrow'),$field('home_title','Homepage headline'),$field('home_intro','Homepage introduction','textarea'),
        $field('home_cover','Homepage cover','image',['return_format'=>'id']),$field('featured_destinations','Featured destinations','relationship',['post_type'=>['destination'],'return_format'=>'id']),$field('contact_recipient','Contact recipient','email'),
    ],'location'=>[[['param'=>'options_page','operator'=>'==','value'=>'gj-settings']]]]);
    acf_add_local_field_group(['key'=>'group_gj_credits','title'=>'Photo attribution','fields'=>[$field('photo_author','Photographer / author'),$field('photo_source','Source URL','url'),$field('photo_license','License')],'location'=>[[['param'=>'post_type','operator'=>'==','value'=>'attachment']]]]);
    acf_add_local_field_group(['key'=>'group_gj_seo','title'=>'Search appearance','fields'=>[$field('seo_title','SEO title'),$field('seo_description','Meta description','textarea',['rows'=>2]),$field('seo_noindex','Hide from search engines','true_false')],'location'=>[[['param'=>'post_type','operator'=>'==','value'=>'destination']],[['param'=>'post_type','operator'=>'==','value'=>'page']],[['param'=>'post_type','operator'=>'==','value'=>'post']]]]);
});
