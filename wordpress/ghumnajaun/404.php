<?php
/** Missing page template. @package Ghumnajaun */
get_header();
?>
<div class="body wp-guide">
	<h1><?php esc_html_e( 'That place wasn’t found', 'ghumnajaun' ); ?></h1>
	<p><?php esc_html_e( 'Explore the guides to find your next day out.', 'ghumnajaun' ); ?></p>
	<a class="action" href="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>">
		<?php esc_html_e( 'Explore places', 'ghumnajaun' ); ?>
	</a>
</div>
<?php get_footer(); ?>
