<?php
/** Site header and opening content wrapper. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<script>
			try {
				const theme = localStorage.getItem('ghumna-jaun-theme');
				if (theme === 'light' || theme === 'dark')
					document.documentElement.dataset.theme = theme;
			} catch {}
		</script>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
		<a class="screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'ghumnajaun' ); ?></a>
		<div id="gj" data-view="<?php echo esc_attr( gj_current_view() ); ?>">
			<header>
				<div class="header-inner container-xl">
					<a
						href="<?php echo esc_url( gj_home_url( '/' ) ); ?>"
						class="brand page-link"
						data-page="home"
						aria-label="Ghumna Jaun home"
					>
						<?php gj_brand_logo(); ?>
					</a>
					<?php get_template_part( 'template-parts/navigation' ); ?>
					<div class="header-actions">
						<?php gj_language_switcher(); ?>
						<button
							class="header-search-toggle"
							id="gj-search-toggle"
							type="button"
							aria-label="Search places"
							aria-haspopup="dialog"
							aria-controls="gj-search-dialog"
							hidden
						>
							<svg viewBox="0 0 24 24" aria-hidden="true">
								<circle cx="10.5" cy="10.5" r="6.5" />
								<path d="m16 16 4 4" />
							</svg>
						</button>
						<button
							class="theme-toggle"
							id="gj-theme-toggle"
							type="button"
							hidden
							aria-label="Switch to dark mode"
						>
							<svg class="theme-moon" viewBox="0 0 24 24" aria-hidden="true">
								<path d="M20.8 14.2A9 9 0 0 1 9.8 3.2 9 9 0 1 0 20.8 14.2Z" />
							</svg>
							<svg class="theme-sun" viewBox="0 0 24 24" aria-hidden="true">
								<circle cx="12" cy="12" r="4" />
								<path
									d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4m0-14.2-1.4 1.4M6.3 17.7l-1.4 1.4"
								/>
							</svg>
						</button>
					</div>
				</div>
			</header>
			<?php get_template_part( 'template-parts/search-dialog' ); ?> <?php
			// These dialogs are only needed on their corresponding pages.
			if ( gj_current_view() === 'results' ) {
				get_template_part( 'template-parts/explore-dialog' );
			}

if ( is_singular( 'destination' ) ) {
	get_template_part( 'template-parts/photo-dialog' );
}
?>
			<main id="main">
