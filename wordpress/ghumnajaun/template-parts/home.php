<?php
/** Home. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
$data = gj_site_data();
$banner_place = $data['bannerPlaces'][0] ?? '';
?>
<section data-screen="home">
	<div class="hero home-hero">
		<div
			aria-label="Featured destinations"
			class="swiper banner-slider"
			id="gj-banner"
			role="region"
		>
			<div class="swiper-wrapper" id="gj-banner-slides">
				<?php get_template_part( 'template-parts/home/slides', null, array( 'data' => $data ) ); ?>
			</div>
			<div
				aria-hidden="true"
				class="banner-timer"
				hidden=""
				id="gj-banner-timer"
			></div>
			<div class="banner-controls">
				<div
					aria-label="Choose a featured destination"
					class="banner-pagination"
					data-reveal="fade-up"
					role="group"
				></div>
			</div>
		</div>
		<div class="hero-copy">
			<p class="eyebrow" data-reveal="fade-up"><?php echo esc_html( gj_option( 'home_eyebrow', __( 'DAY TRIPS FROM KATHMANDU VALLEY', 'ghumnajaun' ) ) ); ?></p>
			<h1 data-reveal="fade-up"><?php echo gj_home_heading(); ?></h1>
		</div>
		<form
			action="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>"
			class="search"
			data-reveal="fade-up"
			method="get"
		>
			<label>
				<?php esc_html_e( 'Starting from', 'ghumnajaun' ); ?>
				<select id="gj-start" name="from">
					<option value="Kathmandu Valley"><?php esc_html_e( 'Kathmandu Valley', 'ghumnajaun' ); ?></option>
					<option value="Kathmandu"><?php esc_html_e( 'Kathmandu', 'ghumnajaun' ); ?></option>
					<option value="Lalitpur"><?php esc_html_e( 'Lalitpur', 'ghumnajaun' ); ?></option>
					<option value="Bhaktapur"><?php esc_html_e( 'Bhaktapur', 'ghumnajaun' ); ?></option>
				</select>
			</label>
			<button class="primary" type="submit"><?php esc_html_e( 'Explore nearby', 'ghumnajaun' ); ?></button>
		</form>
		<a
			class="hero-location page-link"
			data-place="<?php echo esc_attr( $banner_place ); ?>"
			data-reveal="fade-up"
			href="<?php echo esc_url( $data['urls'][$banner_place] ?? gj_home_url( '/explore/' ) ); ?>"
			id="gj-banner-place"
		>
			<?php esc_html_e( 'Nagarkot', 'ghumnajaun' ); ?>
			<span aria-hidden="true" class="link-arrow">↗</span>
		</a>
	</div>
	<div class="body home-content container-xl">
		<?php if ( ! gj_is_nepali() && gj_option( 'home_intro' ) ) : ?>
		<section aria-label="Plan a trip near Kathmandu">
			<p><?php echo esc_html( gj_option( 'home_intro' ) ); ?></p>
		</section>
		<?php endif; ?>

		<div class="browse">
			<h2 data-reveal="fade-up"><?php esc_html_e( 'Find your kind of outing', 'ghumnajaun' ); ?></h2>
			<div class="chips" id="gj-home-chips"><?php echo gj_home_categories_html(); ?></div>
		</div>
		<section aria-labelledby="gj-city-heading">
			<h2 data-reveal="fade-up" id="gj-city-heading"><?php esc_html_e( 'Browse by city', 'ghumnajaun' ); ?></h2>
			<div class="row row-cols-2 row-cols-md-3 g-3 place-row" id="gj-cities">
				<?php get_template_part( 'template-parts/home/cities', null, array( 'data' => $data ) ); ?>
			</div>
		</section>
		<section class="featured">
			<div class="flex-heading" data-reveal="fade-up">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'EXPLORE NEARBY', 'ghumnajaun' ); ?></p>
					<h2><?php esc_html_e( 'Day trips from Kathmandu Valley', 'ghumnajaun' ); ?></h2>
				</div>
				<a class="page-link" data-filter="All" href="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>">
					<?php esc_html_e( 'View all places', 'ghumnajaun' ); ?>
					<span aria-hidden="true" class="link-arrow">↗</span>
				</a>
			</div>
			<div
				class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
				id="gj-featured"
			>
				<?php echo gj_destination_cards_html( $data['featured'], $data ); ?>
			</div>
		</section>
		<?php
		// Keep the English trip shortlist editable in the native homepage editor.
		if ( ! gj_is_nepali() && trim( get_post_field( 'post_content', get_queried_object_id() ) ) ) : ?>
		<div class="home-trip-shortlist">
			<?php echo apply_filters( 'the_content', get_post_field( 'post_content', get_queried_object_id() ) ); ?>
		</div>
		<?php endif; ?>
		<section aria-labelledby="gj-family-heading">
			<div class="flex-heading" data-reveal="fade-up">
				<h2 id="gj-family-heading"><?php esc_html_e( 'Family trips with kids', 'ghumnajaun' ); ?></h2>
				<a class="page-link" data-filter="Kids" href="<?php echo esc_url( gj_home_url( '/explore/?category=Kids' ) ); ?>">
					<?php esc_html_e( 'View all kids’ outings', 'ghumnajaun' ); ?>
					<span aria-hidden="true" class="link-arrow">↗</span>
				</a>
			</div>
			<div
				class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
				id="gj-family-trips"
			>
				<?php echo gj_destination_cards_html( gj_family_destinations(), $data ); ?>
			</div>
		</section>
		<?php if ( gj_has_scenic_dining() ) : ?>
		<aside class="dining-callout">
			<div class="row g-0 align-items-center">
				<div class="col-12 col-md-6" data-reveal="fade-up">
					<?php gj_photo_image( $data['photos']['Dining'] ?? null, array( 'id' => 'gj-dining-image', 'loading' => 'lazy' ) ); ?>
				</div>
				<div class="col-12 col-md-6 dining-copy" data-reveal="fade-up">
					<p class="eyebrow"><?php esc_html_e( 'SCENIC DINING', 'ghumnajaun' ); ?></p>
					<h2><?php esc_html_e( 'Food with a view', 'ghumnajaun' ); ?></h2>
					<p><?php esc_html_e( 'Head out of the city for lunch with a view of the hills.', 'ghumnajaun' ); ?></p>
					<a
						class="page-link"
						data-filter="Scenic dining"
						href="<?php echo esc_url( gj_home_url( '/explore/?category=Scenic%20dining' ) ); ?>"
					>
						<?php esc_html_e( 'Explore scenic dining', 'ghumnajaun' ); ?>
						<span aria-hidden="true" class="link-arrow">↗</span>
					</a>
				</div>
			</div>
		</aside>
		<?php endif; ?>
		<section aria-labelledby="gj-blog-heading">
			<div class="flex-heading" data-reveal="fade-up">
				<h2 id="gj-blog-heading"><?php esc_html_e( 'From the blog', 'ghumnajaun' ); ?></h2>
				<a class="page-link" data-page="blog" href="<?php echo esc_url( gj_home_url( '/blog/' ) ); ?>">
					<?php esc_html_e( 'All travel guides', 'ghumnajaun' ); ?>
					<span aria-hidden="true" class="link-arrow">↗</span>
				</a>
			</div>
			<div
				class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
				id="gj-blog-posts"
			>
				<?php echo gj_article_cards_html( 3 ); ?>
			</div>
		</section>
	</div>
</section>
