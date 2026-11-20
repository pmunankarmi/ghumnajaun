<?php
/** Photo dialog. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

?>
<dialog
	aria-labelledby="gj-stop-photo-title"
	class="search-dialog stop-photo-dialog"
	id="gj-stop-photo-dialog"
>
	<div class="search-dialog-heading">
		<h2 id="gj-stop-photo-title"><?php esc_html_e( 'Stop photo', 'ghumnajaun' ); ?></h2>
		<button
			aria-label="Close photo"
			autofocus=""
			class="search-close"
			id="gj-stop-photo-close"
			type="button"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path d="m6 6 12 12M18 6 6 18"></path>
			</svg>
		</button>
	</div>
	<figure>
		<div class="stop-photo-stage">
			<img alt="" hidden="" id="gj-stop-photo-large" />
			<p id="gj-stop-photo-status" role="status"></p>
		</div>
		<figcaption id="gj-stop-photo-caption"></figcaption>
	</figure>
	<div
		aria-label="Choose a stop photo"
		class="stop-photo-thumbnails"
		hidden=""
		id="gj-stop-photo-thumbnails"
		role="group"
	></div>
	<div class="stop-photo-controls" hidden="" id="gj-stop-photo-controls">
		<button
			aria-label="Previous stop photo"
			id="gj-stop-photo-previous"
			type="button"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path d="m14 6-6 6 6 6"></path>
			</svg>
		</button>
		<span id="gj-stop-photo-position" role="status"></span>
		<button aria-label="Next stop photo" id="gj-stop-photo-next" type="button">
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path d="m10 6 6 6-6 6"></path>
			</svg>
		</button>
	</div>
</dialog>
