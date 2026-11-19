<?php get_header();while(have_posts()):the_post(); ?>
<article class="body wp-guide wp-content"><p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p><h1><?php the_title(); ?></h1><?php the_post_thumbnail('large');the_content(); ?></article>
<?php endwhile;get_footer(); ?>
