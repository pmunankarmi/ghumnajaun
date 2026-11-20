<?php
/** Archive and fallback template. @package Ghumnajaun */
get_header();

if ( is_post_type_archive( 'destination' ) || is_tax( array( 'outing', 'place_city', 'place_type' ) ) ) :
	get_template_part( 'template-parts/explore' );
elseif ( is_home() ) :
	get_template_part( 'template-parts/blog' );
else :
	?>
	<div class="body">
		<h1><?php echo esc_html( get_the_archive_title() ); ?></h1>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	</div>
	<?php
endif;
get_footer();
