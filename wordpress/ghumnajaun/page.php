<?php get_header();while(have_posts()):the_post();
$content=apply_filters('the_content',get_the_content());
if(is_page('contact')){ob_start();gj_contact_form();$form=ob_get_clean();$content=str_replace('<div class="col-12 col-md-7">','<div class="col-12 col-md-7">'.$form,$content);if(!str_contains($content,'id="gj-contact-form"'))$content=preg_replace('~(<div class="col-12 col-md-7"[^>]*>)~','$1'.$form,$content,1);}
if(is_page(['about','contact','privacy-policy','terms-of-use','disclaimer']))echo $content;
else{ ?><section class="body wp-content"><h1><?php the_title(); ?></h1><?php echo $content;if(is_page('photo-credits')){ ?><ul class="photo-credit-list" id="gj-credits"></ul><?php } ?></section><?php }
endwhile;get_footer(); ?>