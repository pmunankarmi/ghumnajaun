<?php
/** Explore dialog. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

?>
<dialog
	aria-labelledby="gj-start-title"
	class="search-dialog start-dialog"
	id="gj-start-dialog"
>
	<div class="search-dialog-heading">
		<h2 id="gj-start-title"><?php esc_html_e( 'Change starting point', 'ghumnajaun' ); ?></h2>
		<button
			aria-label="Close starting point"
			class="search-close"
			id="gj-start-close"
			type="button"
		>
			<svg aria-hidden="true" viewbox="0 0 24 24">
				<path d="m6 6 12 12M18 6 6 18"></path>
			</svg>
		</button>
	</div>
	<form action="<?php echo esc_url( gj_home_url( '/explore/' ) ); ?>" id="gj-start-form" method="get">
		<label for="gj-start-origin"><?php esc_html_e( 'Starting from', 'ghumnajaun' ); ?></label>
		<select autofocus="" id="gj-start-origin" name="from">
			<option value="Kathmandu Valley"><?php esc_html_e( 'Kathmandu Valley', 'ghumnajaun' ); ?></option>
			<option value="Kathmandu"><?php esc_html_e( 'Kathmandu', 'ghumnajaun' ); ?></option>
			<option value="Lalitpur"><?php esc_html_e( 'Lalitpur', 'ghumnajaun' ); ?></option>
			<option value="Bhaktapur"><?php esc_html_e( 'Bhaktapur', 'ghumnajaun' ); ?></option>
		</select>
		<p><?php esc_html_e( 'Your current search and filters will stay selected.', 'ghumnajaun' ); ?></p>
		<button class="primary" type="submit"><?php esc_html_e( 'Explore nearby', 'ghumnajaun' ); ?></button>
	</form>
</dialog>
