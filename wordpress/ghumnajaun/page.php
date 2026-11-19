<?php get_header();while(have_posts()):the_post();
$raw=get_the_content();$structured=preg_match('~<section\b[^>]*class=[\"\'][^\"\']*body~',$raw);
$autop=has_filter('the_content','wpautop');if($structured&&$autop!==false)remove_filter('the_content','wpautop',$autop);
$content=apply_filters('the_content',$raw);if($structured&&$autop!==false)add_filter('the_content','wpautop',$autop);
if(is_page('contact')){ob_start();gj_contact_form();$form=ob_get_clean();$content=str_replace('<div class="col-12 col-md-7">','<div class="col-12 col-md-7">'.$form,$content);if(!str_contains($content,'id="gj-contact-form"'))$content=preg_replace('~(<div class="col-12 col-md-7"[^>]*>)~','$1'.$form,$content,1);}
if(is_page(['about','contact','privacy-policy','terms-of-use','disclaimer']))echo gj_restore_page_reveals($content,get_post_field('post_name',get_the_ID()));
elseif(is_page('photo-credits')){ ?><section class="body photo-credits"><h1 data-reveal="fade-up"><?php the_title(); ?></h1><p>Follow each photo’s source link to view the original. Photos may be cropped to fit the layout.</p><ul id="gj-credits"><?php echo gj_credits_html(); ?></ul></section><?php }
else{ ?><section class="body wp-content"><h1><?php the_title(); ?></h1><?php echo $content;if(is_page('photo-credits')){ ?><ul class="photo-credit-list" id="gj-credits"></ul><?php } ?></section><?php }
endwhile;get_footer(); ?>