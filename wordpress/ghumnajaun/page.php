<?php get_header();while(have_posts()):the_post(); ?>
<div class="body wp-content"><?php if(!is_page(['about','contact','privacy-policy','terms-of-use','disclaimer'])): ?><h1><?php the_title(); ?></h1><?php endif;the_content();if(is_page('contact'))gj_contact_form();if(is_page('photo-credits')){foreach(get_posts(['post_type'=>'attachment','posts_per_page'=>-1,'meta_key'=>'photo_author'])as$photo){echo '<figure>';echo wp_get_attachment_image($photo->ID,'thumbnail');gj_photo_credit($photo->ID);echo '</figure>';}} ?></div>
<?php endwhile;get_footer(); ?>
