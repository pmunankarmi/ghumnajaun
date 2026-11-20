<?php
/** Contact form. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

?>
<form
	action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
	class="contact-form"
	id="gj-contact-form"
	method="post"
>
	<h2><?php esc_html_e( 'Write to us', 'ghumnajaun' ); ?></h2>
	<p id="gj-contact-note">
		<?php esc_html_e( 'Send us a message and we’ll reply by email.', 'ghumnajaun' ); ?>
		<a class="trip-inline-link" href="<?php echo esc_url( gj_home_url( '/privacy-policy/' ) ); ?>">
			<?php esc_html_e( 'Privacy Policy', 'ghumnajaun' ); ?>
		</a>
	</p>
	<div aria-hidden="true" class="contact-honeypot">
		<label for="gj-contact-website"><?php esc_html_e( 'Leave this field empty', 'ghumnajaun' ); ?></label>
		<input
			autocomplete="off"
			id="gj-contact-website"
			name="website"
			tabindex="-1"
			type="text"
		/>
	</div>
	<div class="row g-3">
		<div class="col-12 col-sm-6">
			<label for="gj-contact-name"><?php esc_html_e( 'Your name', 'ghumnajaun' ); ?></label>
			<input
				autocomplete="name"
				id="gj-contact-name"
				maxlength="100"
				name="name"
				required=""
				type="text"
			/>
		</div>
		<div class="col-12 col-sm-6">
			<label for="gj-contact-email"><?php esc_html_e( 'Your email', 'ghumnajaun' ); ?></label>
			<input
				autocomplete="email"
				id="gj-contact-email"
				maxlength="254"
				name="email"
				required=""
				type="email"
			/>
		</div>
		<div class="col-12">
			<label for="gj-contact-subject"><?php esc_html_e( 'What’s it about?', 'ghumnajaun' ); ?></label>
			<select id="gj-contact-subject" name="subject" required="">
				<option value=""><?php esc_html_e( 'Choose a topic', 'ghumnajaun' ); ?></option>
				<option><?php esc_html_e( 'Suggest a place', 'ghumnajaun' ); ?></option>
				<option><?php esc_html_e( 'Update a guide', 'ghumnajaun' ); ?></option>
				<option><?php esc_html_e( 'Listings and collaborations', 'ghumnajaun' ); ?></option>
				<option><?php esc_html_e( 'General enquiry', 'ghumnajaun' ); ?></option>
			</select>
		</div>
		<div class="col-12">
			<label for="gj-contact-message"><?php esc_html_e( 'Your message', 'ghumnajaun' ); ?></label>
			<textarea
				id="gj-contact-message"
				maxlength="3000"
				name="body"
				required=""
				rows="6"
			></textarea>
		</div>
	</div>
	<p
		class="contact-validation-summary"
		hidden=""
		id="gj-contact-errors"
		role="alert"
	></p>
	<p
		class="contact-send-status"
		hidden=""
		id="gj-contact-status"
		role="status"
	></p>
	<button aria-describedby="gj-contact-note" class="primary" type="submit">
		<?php esc_html_e( 'Send message', 'ghumnajaun' ); ?>
	</button>
	<noscript>
		<p>
			<?php esc_html_e( 'Please enable JavaScript to send this form, or', 'ghumnajaun' ); ?>
			<a class="trip-inline-link" href="mailto:hello@ghumnajaun.com">
				<?php esc_html_e( 'email hello@ghumnajaun.com', 'ghumnajaun' ); ?>
			</a>
			.
		</p>
	</noscript>
	<input type="hidden" name="action" value="gj_contact" />
	<?php wp_nonce_field( 'gj_contact', 'gj_nonce' ); ?>
</form>
