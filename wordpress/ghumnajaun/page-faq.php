<?php
/** Native page content, kept as semantic disclosure markup in the classic editor. */
get_header();
while(have_posts()):the_post(); ?>
<section class="body wp-content faq-page"><h1><?php the_title(); ?></h1><?php echo gj_legacy_links(wp_kses_post(get_the_content())); ?></section>
<?php endwhile;get_footer();
