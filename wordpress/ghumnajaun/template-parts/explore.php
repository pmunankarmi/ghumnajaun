<?php
/** Explore. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;
$data = gj_site_data();
$listing = gj_destination_listing( $data );
?>
<section class="body container-xl" data-screen="results">
	<div class="flex-heading" data-reveal="fade-up">
		<div>
			<p class="meta" id="gj-origin"><?php echo esc_html( $listing['origin'] ); ?></p>
			<h1 id="gj-results-title"><?php echo esc_html( $listing['title'] ); ?></h1>
		</div>
		<button
			aria-controls="gj-start-dialog"
			aria-haspopup="dialog"
			class="page-link"
			id="gj-change-start"
			type="button"
		>
			<?php esc_html_e( 'Change starting point', 'ghumnajaun' ); ?>
		</button>
	</div>
	<div aria-label="Filter outings" class="chips" id="gj-filters">
		<?php echo gj_outing_filters_html(); ?>
	</div>
	<p aria-live="polite" data-reveal="fade-up" id="gj-count">
		<?php printf( esc_html__( '%d places', 'ghumnajaun' ), count( $listing['names'] ) ); ?>
	</p>
	<p class="distance-note" id="gj-distance-note"><?php esc_html_e( 'Nearest first by approximate straight-line distance. Kathmandu Valley uses central Kathmandu.', 'ghumnajaun' ); ?></p>
	<div
		class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
		id="gj-list"
	>
		<?php echo gj_destination_cards_html( $listing['names'], $data, $listing['distances'] ); ?>
	</div>
</section>
