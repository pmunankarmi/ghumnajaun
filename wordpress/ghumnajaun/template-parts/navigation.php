<?php
/** Primary navigation with a fallback for a new installation. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
?>
<nav aria-label="<?php esc_attr_e( 'Main', 'ghumnajaun' ); ?>">
	<?php if ( has_nav_menu( 'primary' ) ) :

		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'walker'         => new GJ_Flat_Nav(),
		) );
	else : ?>
	<a href="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>" data-filter="All" class="page-link">
		<?php esc_html_e( 'Explore', 'ghumnajaun' ); ?>
	</a>
	<a href="<?php echo esc_url( gj_home_url( '/explore/?category=Scenic%20dining' ) ); ?>" data-filter="Scenic dining" class="page-link">
		<?php esc_html_e( 'Scenic dining', 'ghumnajaun' ); ?>
	</a>
	<a href="<?php echo esc_url( gj_home_url( '/explore/?category=Dharmik' ) ); ?>" data-filter="Dharmik" class="page-link">
		<?php esc_html_e( 'Dharmik', 'ghumnajaun' ); ?>
	</a>
	<a href="<?php echo esc_url( gj_home_url( '/blog/' ) ); ?>" data-page="blog" class="page-link">
		<?php esc_html_e( 'Blog', 'ghumnajaun' ); ?>
	</a>
	<?php endif; ?>
</nav>
