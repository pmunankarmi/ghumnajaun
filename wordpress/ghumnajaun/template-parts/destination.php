<?php
/** Destination. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
$context = gj_destination_context( get_the_ID() );
$data = $context['data'];
$name = $context['name'];
$details = $context['details'];
$guide = $context['guide'];
$description = gj_value( 'destination_description', get_the_ID() );
?>
<section class="destination-trip" data-screen="detail">
	<div class="trip-hero">
		<div
			aria-label="<?php echo esc_attr( get_the_title() . ' photos' ); ?>"
			class="swiper trip-photos"
			data-trip-slider=""
			role="region"
		>
			<div class="swiper-wrapper" data-trip-slides="">
				<?php get_template_part( 'template-parts/destination/gallery', null, $context ); ?>
			</div>
			<div
				aria-hidden="true"
				class="banner-timer"
				data-trip-timer=""
				hidden=""
			></div>
			<div
				aria-label="<?php esc_attr_e( 'Choose a destination photo', 'ghumnajaun' ); ?>"
				class="trip-photo-dots"
				role="group"
			></div>
		</div>
		<div class="body trip-hero-inner container-xl">
			<a class="trip-back" data-page="results" href="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>">
				<?php esc_html_e( '← Back to places', 'ghumnajaun' ); ?>
			</a>
			<div>
				<p class="eyebrow" data-trip-location=""><?php echo esc_html( gj_display_label( $details['location'] ) ); ?></p>
				<h1 data-trip-title=""><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
	<div class="body trip-heading container-xl">
		<div class="trip-summary">
			<p class="trip-intro" data-trip-intro=""><?php echo esc_html( $guide['summary'] ); ?></p>
			<a
				class="action trip-action"
				data-trip-directions=""
				href="<?php echo esc_url( $context['map_url'] ); ?>"
				rel="noopener"
				target="_blank"
			>
				<?php esc_html_e( 'Get directions', 'ghumnajaun' ); ?>
				<span aria-hidden="true" class="link-arrow">↗</span>
			</a>
		</div>
		<dl class="trip-facts" data-trip-facts=""><?php get_template_part( 'template-parts/destination/facts', null, $context ); ?></dl>
	</div>
	<nav aria-label="On this page" class="body trip-jumps container-xl">
		<?php if ( $description ) : ?>
		<a class="page-link" href="#gj-about"><?php esc_html_e( 'About this destination', 'ghumnajaun' ); ?></a>
		<?php endif; ?>
		<a class="page-link" href="#gj-things"><?php esc_html_e( 'Things to do', 'ghumnajaun' ); ?></a>
		<?php if ( ! empty( $guide['history'] ) ) : ?>
		<a class="page-link" data-trip-history-jump="" href="#gj-history">
			<?php esc_html_e( 'History', 'ghumnajaun' ); ?>
		</a>
		<?php endif; ?>
		<?php if ( ! empty( $details['videoUrl'] ) ) : ?>
		<a class="page-link" href="#gj-vlog-title"><?php esc_html_e( 'Watch the vlog', 'ghumnajaun' ); ?></a>
		<?php endif; ?>
		<?php if ( ! empty( $details['photos'] ) ) : ?>
		<a class="page-link" href="#gj-gallery"><?php esc_html_e( 'Photo gallery', 'ghumnajaun' ); ?></a>
		<?php endif; ?>
		<a class="page-link" href="#gj-visit"><?php esc_html_e( 'Plan your visit', 'ghumnajaun' ); ?></a>
		<a class="page-link" href="#gj-getting"><?php esc_html_e( 'Getting there', 'ghumnajaun' ); ?></a>
		<a class="page-link" href="#gj-eat"><?php esc_html_e( 'Eat & stay', 'ghumnajaun' ); ?></a>
		<a class="page-link" href="#gj-day"><?php esc_html_e( 'Make a day of it', 'ghumnajaun' ); ?></a>
	</nav>
	<div class="body trip-main container-xl">
		<?php if ( $description ) : ?>
		<section aria-labelledby="gj-about-title" id="gj-about">
			<h2 id="gj-about-title"><?php esc_html_e( 'About this destination', 'ghumnajaun' ); ?></h2>
			<div class="trip-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		</section>
		<?php endif; ?>
		<section aria-labelledby="gj-things-title" id="gj-things">
			<div class="trip-section-grid">
				<div>
					<h2 id="gj-things-title"><?php esc_html_e( 'Things to do', 'ghumnajaun' ); ?></h2>
					<ul class="trip-activities" data-trip-activities="">
						<?php get_template_part( 'template-parts/destination/activities', null, $context ); ?>
					</ul>
					<div class="guide-links" data-trip-feature-link="">
						<?php get_template_part( 'template-parts/destination/sources', null, $context ); ?>
					</div>
				</div>
				<?php if ( isset( $data['photos'][$details['featurePhoto'] ?? $details['photos'][1] ?? ''] ) ) : ?>
				<figure class="trip-feature-photo" data-trip-feature="">
					<?php get_template_part( 'template-parts/destination/feature', null, $context ); ?>
				</figure>
				<?php endif; ?>
			</div>
		</section>
		<?php if ( ! empty( $guide['history'] ) ) : ?>
		<section aria-labelledby="gj-history-title" id="gj-history">
			<h2 id="gj-history-title"><?php esc_html_e( 'History', 'ghumnajaun' ); ?></h2>
			<p class="trip-history-copy" data-trip-history="">
				<?php echo esc_html( $guide['history'][0] ?? '' ); ?>
			</p>
			<div class="guide-links" data-trip-history-source="">
				<?php get_template_part( 'template-parts/destination/history-source', null, $context ); ?>
			</div>
		</section>
		<?php endif;

if ( ! empty( $details['videoUrl'] ) ) : ?>
		<section aria-labelledby="gj-vlog-heading" id="gj-vlog-title">
			<h2 id="gj-vlog-heading"><?php echo esc_html( gj_value( 'video_title', get_the_ID(), __( 'Watch the vlog', 'ghumnajaun' ) ) ); ?></h2>
			<?php if ( gj_value( 'video_intro', get_the_ID() ) ) : ?>
			<p><?php echo esc_html( gj_value( 'video_intro', get_the_ID() ) ); ?></p>
			<?php endif; ?>
			<div class="trip-vlog-player consent-embed consent-video">
				<div class="consent-placeholder">
					<p><?php esc_html_e( 'This video loads after you accept optional embedded content.', 'ghumnajaun' ); ?></p>
					<button data-cookie-preferences="" type="button">
						<?php esc_html_e( 'Cookie preferences', 'ghumnajaun' ); ?>
					</button>
				</div>
				<iframe
					allow="encrypted-media; picture-in-picture; fullscreen"
					allowfullscreen=""
					data-consent-src="<?php echo esc_url( $details['videoUrl'] ?? '' ); ?>"
					hidden=""
					loading="lazy"
					referrerpolicy="strict-origin-when-cross-origin"
					title="<?php echo esc_attr( get_the_title() . ' video on YouTube' ); ?>"
				></iframe>
			</div>
		</section>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/destination/photo-gallery', null, $context ); ?>
		<section aria-labelledby="gj-visit-title" id="gj-visit">
			<h2 id="gj-visit-title"><?php esc_html_e( 'Plan your visit', 'ghumnajaun' ); ?></h2>
			<div class="trip-section-grid" data-trip-visit="">
				<?php get_template_part( 'template-parts/destination/visit', null, $context ); ?>
			</div>
		</section>
		<section aria-labelledby="gj-getting-title" id="gj-getting">
			<h2 id="gj-getting-title"><?php esc_html_e( 'Getting there', 'ghumnajaun' ); ?></h2>
			<div class="trip-section-grid trip-getting-grid">
				<div class="trip-card trip-transport" data-trip-transport="">
					<?php get_template_part( 'template-parts/destination/transport', null, $context ); ?>
				</div>
				<div class="trip-map" data-trip-map=""><?php get_template_part( 'template-parts/destination/map', null, $context ); ?></div>
			</div>
		</section>
		<section aria-labelledby="gj-eat-title" id="gj-eat">
			<h2 id="gj-eat-title"><?php esc_html_e( 'Eat & stay', 'ghumnajaun' ); ?></h2>
			<p class="trip-section-note" data-trip-meal-note="">
				<?php echo esc_html( $details['mealNote'] ?? '' ); ?>
			</p>
			<div class="trip-meals" data-trip-meals=""><?php get_template_part( 'template-parts/destination/meals', null, $context ); ?></div>
			<div class="trip-overnight">
				<?php if ( ! empty( $details['stays'] ) ) : ?>
<h3><?php esc_html_e( 'Stay nearby', 'ghumnajaun' ); ?></h3>
				<ul class="trip-stays" data-trip-stays="">
					<?php get_template_part( 'template-parts/destination/stays', null, $context ); ?>
				</ul>
<?php endif; ?>
				<div class="guide-links" data-trip-food-links="">
					<?php get_template_part( 'template-parts/destination/food-links', null, $context ); ?>
				</div>
			</div>
		</section>
		<section aria-labelledby="gj-day-title" id="gj-day">
			<h2 id="gj-day-title"><?php esc_html_e( 'Make a day of it', 'ghumnajaun' ); ?></h2>
			<div class="trip-section-grid">
				<ol class="trip-day-plan" data-trip-plan="">
					<?php get_template_part( 'template-parts/destination/plan', null, $context ); ?>
				</ol>
				<div class="trip-card">
					<h3><?php esc_html_e( 'Nearby stops', 'ghumnajaun' ); ?></h3>
					<p data-trip-nearby-note=""><?php echo esc_html( $details['nearbyNote'] ?? '' ); ?></p>
					<div class="guide-links" data-trip-nearby-links="">
						<?php get_template_part( 'template-parts/destination/nearby-links', null, $context ); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
	<section aria-labelledby="gj-related-title" class="body trip-related container-xl">
		<h2 id="gj-related-title"><?php esc_html_e( 'Related destinations', 'ghumnajaun' ); ?></h2>
		<div
			class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
			data-trip-related=""
		>
			<?php get_template_part( 'template-parts/destination/related', null, $context ); ?>
		</div>
	</section>
	<div class="trip-mobile-bar">
		<a
			class="action trip-action"
			data-trip-directions=""
			href="<?php echo esc_url( $context['map_url'] ); ?>"
			rel="noopener"
			target="_blank"
		>
			<?php esc_html_e( 'Get directions', 'ghumnajaun' ); ?>
			<span aria-hidden="true" class="link-arrow">↗</span>
		</a>
	</div>
</section>
