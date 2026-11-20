<?php
/** Paired editorial fields share one bilingual bundle across linked translations. */
defined('ABSPATH') || exit;

function gj_is_nepali(){return function_exists('pll_current_language') && pll_current_language('slug')==='ne';}
function gj_language_switcher(){
    if(!function_exists('pll_the_languages'))return;
    $links='';
    foreach((array)pll_the_languages(['raw'=>1,'hide_if_empty'=>1])as$language){
        if(!empty($language['current_lang'])||empty($language['url']))continue;
        $links.='<a href="'.esc_url($language['url']).'" lang="'.esc_attr($language['slug']).'" hreflang="'.esc_attr($language['slug']).'">'.esc_html($language['name']).'</a>';
    }
    if($links)echo '<nav class="language-switcher" aria-label="'.esc_attr(gj_is_nepali()?'भाषा बदल्नुहोस्':'Change language').'">'.$links.'</nav>';
}
function gj_canonical_title($id){$english=function_exists('pll_get_post')?pll_get_post($id,'en'):0;return get_post_field('post_title',$english?:$id);}
function gj_canonical_term_name($term){$english=function_exists('pll_get_term')?pll_get_term($term->term_id,'en'):0;$source=$english?get_term($english,$term->taxonomy):$term;return $source&&!is_wp_error($source)?$source->name:$term->name;}
function gj_canonical_terms($id,$taxonomy){
    return array_map(function($term)use($taxonomy){$english=function_exists('pll_get_term')?pll_get_term($term->term_id,'en'):0;$source=$english?get_term($english,$taxonomy):$term;return $source&&!is_wp_error($source)?$source->name:$term->name;},wp_get_post_terms($id,$taxonomy));
}
function gj_home_url($path='/'){
    if(gj_is_nepali())return $path==='/'?pll_home_url('ne'):trailingslashit(PLL()->links_model->home_url('ne')).ltrim($path,'/');
    return home_url($path);
}
add_filter('theme_mod_nav_menu_locations',function($locations){
    if(is_admin()||!function_exists('pll_current_language'))return $locations;
    $language=pll_current_language('slug');$menus=PLL()->options['nav_menus'][get_stylesheet()]??[];
    foreach(['primary','footer']as$location)if(!empty($menus[$location][$language]))$locations[$location]=(int)$menus[$location][$language];
    return $locations;
},30);
add_filter('option_blogname',fn($value)=>gj_is_nepali()?'घुम्न जाऔँ':$value);
add_filter('option_blogdescription',fn($value)=>gj_is_nepali()?gj_translate_label($value):$value);
add_filter('the_content',function($content){
    if(!gj_is_nepali()||is_admin())return $content;
    return preg_replace_callback('~href=("|\x27)(https?://[^"\x27]+)\1~',function($m){
        $url=html_entity_decode($m[2],ENT_QUOTES|ENT_HTML5,'UTF-8');
        if(wp_parse_url($url,PHP_URL_HOST)!==wp_parse_url(home_url(),PHP_URL_HOST))return $m[0];
        $id=url_to_postid($url);$target=$id?pll_get_post($id,'ne'):0;
        $destination=$target?get_permalink($target):'';
        // Archives do not resolve to a post ID, including the configured blog page.
        if(!$destination)foreach(['explore','blog']as$archive)if(untrailingslashit(wp_parse_url($url,PHP_URL_PATH))===untrailingslashit(wp_parse_url(home_url('/'.$archive.'/'),PHP_URL_PATH)))$destination=gj_home_url('/'.$archive.'/');
        if(!$destination)return $m[0];
        $query=wp_parse_url($url,PHP_URL_QUERY);$fragment=wp_parse_url($url,PHP_URL_FRAGMENT);
        return 'href='.$m[1].esc_url($destination.($query?'?'.$query:'').($fragment?'#'.$fragment:'')).$m[1];
    },$content);
},15);
function gj_ne_dictionary(){
    static $dictionary;
    if($dictionary===null){$file=get_template_directory().'/data/ne-translations.json';$dictionary=is_readable($file)?json_decode(file_get_contents($file),true):[];}
    return $dictionary?:[];
}
function gj_translate_label($text){
    $dictionary=gj_ne_dictionary();$key=trim(html_entity_decode($text,ENT_QUOTES|ENT_HTML5,'UTF-8'));
    $translated=$dictionary[$key]??null;
    if($translated===null && str_contains($key,' · '))$translated=implode(' · ',array_map(fn($part)=>$dictionary[$part]??$part,explode(' · ',$key)));
    if($translated===null && preg_match('/^(\d+) (places to see|place to see|places|place)$/',$key,$m))$translated=str_contains($m[2],'to see')?'हेर्न मिल्ने '.$m[1].' ठाउँ':$m[1].' ठाउँ';
    if($translated===null)return $text;
    preg_match('/^\s*/u',$text,$leading);preg_match('/\s*$/u',$text,$trailing);
    return $leading[0].$translated.$trailing[0];
}
function gj_ne_paragraphs($html){
    static $templates;
    if($templates===null){$path=get_template_directory().'/data/ne-editorial-paragraphs.json';$templates=is_readable($path)?json_decode(file_get_contents($path),true):[];}
    return preg_replace_callback('~(<p\b[^>]*>)(.*?)(</p>)~s',function($m)use($templates){
        $key=preg_replace('/\s+/u',' ',trim(html_entity_decode(wp_strip_all_tags($m[2]),ENT_QUOTES|ENT_HTML5,'UTF-8')));
        if(!isset($templates[$key]))return $m[0];
        preg_match_all('~<a\b[^>]*>.*?</a>~s',$m[2],$matches);$replacements=[];
        foreach($matches[0]as$i=>$anchor)$replacements['{{'.$i.'}}']=preg_replace_callback('~>([^<]+)<~',fn($text)=>'>'.esc_html(gj_translate_label($text[1])).'<',$anchor);
        return $m[1].strtr($templates[$key],$replacements).$m[3];
    },$html);
}
function gj_translate_html($html){
    $html=gj_ne_paragraphs($html);
    // An option without value submits its label; keep the English protocol value.
    $html=preg_replace_callback('~<option\b([^>]*)>([^<]*)</option>~s',fn($m)=>'<option'.$m[1].(preg_match('/\bvalue\s*=/i',$m[1])?'':' value="'.esc_attr(html_entity_decode($m[2],ENT_QUOTES|ENT_HTML5,'UTF-8')).'"').'>'.$m[2].'</option>',$html);
    $parts=preg_split('~(<!--.*?-->|<[^>]+>)~s',$html,-1,PREG_SPLIT_DELIM_CAPTURE);$skip=false;
    foreach($parts as$i=>$part){
        if(str_starts_with($part,'<')){
            if(preg_match('~^<(script|style)\b~i',$part))$skip=true;
            if(preg_match('~^</(script|style)\b~i',$part))$skip=false;
            if(!$skip)$parts[$i]=preg_replace_callback('~\b(alt|title|placeholder|aria-label)=("|\x27)(.*?)\2~s',fn($m)=>$m[1].'='.$m[2].esc_attr(gj_translate_label($m[3])).$m[2],$part);
        }elseif(!$skip){$translated=gj_translate_label($part);if($translated!==$part)$parts[$i]=esc_html($translated);}
    }
    return implode('',$parts);
}
// Translate static theme copy in the server response as well as enhanced UI text.
add_action('template_redirect',function(){if(gj_is_nepali()&&!is_feed()&&!is_embed())ob_start('gj_translate_html');},1);
add_action('wp_enqueue_scripts',function(){
    if(!gj_is_nepali())return;
    $file=get_template_directory().'/assets/nepali.js';
    wp_enqueue_script('gj-nepali',get_template_directory_uri().'/assets/nepali.js',['gj-runtime'],hash_file('sha256',$file),['in_footer'=>true,'strategy'=>'defer']);
},20);

function gj_bilingual_fields($fields) {
    $out=[];
    foreach($fields as $field) {
        if(isset($field['sub_fields'])) $field['sub_fields']=gj_bilingual_fields($field['sub_fields']);
        if(in_array($field['type'],['text','textarea','wysiwyg'],true) && !in_array($field['name'],['photo_author','photo_license'],true)) {
            $ne=$field;
            $field['label'].=' — English';
            $field['wrapper']['width']='50';
            $ne['key'].='_ne';
            $ne['name'].='_ne';
            $ne['label'].=' — नेपाली';
            $ne['wrapper']['width']='50';
            $ne['required']=0;
            $ne['instructions']='Nepali translation. Leave empty to use English.';
            $out[]=$field;
            $out[]=$ne;
        } else $out[]=$field;
    }
    return $out;
}

// Expand before ACF builds its local field store, including nested repeater rows.
function gj_add_field_group($group) {
    $group['fields']=gj_bilingual_fields($group['fields']??[]);
    acf_add_local_field_group($group);
}

function gj_localized_values($values) {
    if(!is_array($values)) return $values;
    foreach($values as $key=>$value) {
        if(is_array($value)) $values[$key]=gj_localized_values($value);
        if(is_string($key) && !str_ends_with($key,'_ne') && isset($values[$key.'_ne']) && $values[$key.'_ne']!=='') $values[$key]=$values[$key.'_ne'];
    }
    return $values;
}

// Format for display only: admin editing and raw reads always retain both languages.
add_filter('acf/format_value',function($value,$post_id,$field){
    if(is_admin() || !function_exists('pll_current_language') || pll_current_language('slug')!=='ne' || !str_starts_with($field['key']??'','field_gj_')) return $value;
    if(is_array($value)) return gj_localized_values($value);
    if(in_array($field['type'],['text','textarea','wysiwyg'],true) && !str_ends_with($field['name'],'_ne')) {
        $translation=get_field($field['name'].'_ne',$post_id);
        if($translation!==false && $translation!==null && $translation!=='') return $translation;
    }
    return $value;
},20,3);

/** Include only this theme's ACF metadata, including reference keys and repeater children. */
add_filter('pll_copy_post_metas',function($metas,$sync,$from){
    foreach(get_post_meta($from) as $key=>$values) {
        if(str_starts_with($key,'_') && str_starts_with((string)($values[0]??''),'field_gj_')) {
            $metas[]=$key;
            $metas[]=substr($key,1);
        }
    }
    return array_values(array_unique($metas));
},10,3);

// Run after ACF has saved, independent of Polylang's global custom-fields setting.
add_action('acf/save_post',function($post_id){
    static $syncing=false;
    if($syncing || !is_numeric($post_id) || wp_is_post_revision($post_id) || !function_exists('pll_get_post_translations')) return;
    $syncing=true;
    $source=[];
    foreach(get_post_meta($post_id) as $key=>$values) {
        if(str_starts_with($key,'_') && str_starts_with((string)($values[0]??''),'field_gj_')) {
            $source[$key]=true;
            $source[substr($key,1)]=true;
        }
    }
    foreach(pll_get_post_translations((int)$post_id) as $target) {
        if((int)$target===(int)$post_id) continue;
        // Remove obsolete rows and cleared fields before copying the saved bundle.
        foreach(get_post_meta($target) as $key=>$values) {
            if(str_starts_with($key,'_') && str_starts_with((string)($values[0]??''),'field_gj_') && !isset($source[$key])) {
                delete_post_meta($target,$key);
                delete_post_meta($target,substr($key,1));
            }
        }
        foreach(array_keys($source) as $key) update_post_meta($target,$key,get_post_meta($post_id,$key,true));
        if(function_exists('acf_get_store')) acf_get_store('values')->reset();
    }
    $syncing=false;
},30);

// Keep editable policy copy accurate for the language-specific map providers.
add_filter('the_content',function($content){
 if(is_page('privacy-policy')&&!str_contains($content,'Google Maps'))$content=str_replace(['OpenStreetMap maps','OpenStreetMap का नक्सा'],['Google Maps or OpenStreetMap maps','Google Maps वा OpenStreetMap का नक्सा'],$content);
 return $content;
},40);
