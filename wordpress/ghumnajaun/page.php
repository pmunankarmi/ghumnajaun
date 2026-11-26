<?php
/** Standard pages, editorial layouts and photo credits. @package Ghumnajaun */
get_header();

while ( have_posts() ) :
	the_post();

	// Imported editorial layouts already contain paragraphs and section markup.
	$raw_content = get_the_content();
	$has_editorial_layout = preg_match( '~<section\b[^>]*class=[\"\'][^\"\']*body~', $raw_content );
	$paragraph_filter_priority = has_filter( 'the_content', 'wpautop' );

	if ( $has_editorial_layout && false !== $paragraph_filter_priority ) {
		remove_filter( 'the_content', 'wpautop', $paragraph_filter_priority );
	}

	$page_content = apply_filters( 'the_content', $raw_content );

	if ( $has_editorial_layout && false !== $paragraph_filter_priority ) {
		add_filter( 'the_content', 'wpautop', $paragraph_filter_priority );
	}

	// Place the working WordPress form in the editorial contact layout.
	if ( is_page( 'contact' ) ) {
		ob_start();
		gj_contact_form();
		$contact_form = ob_get_clean();
		$page_content = preg_replace(
			'~(<div\b[^>]*class="col-12 col-md-7"[^>]*>)~',
			'$1' . $contact_form,
			$page_content,
			1
		);
	}

	if ( is_page( array( 'about', 'contact', 'privacy-policy', 'terms-of-use', 'disclaimer' ) ) ) :
		echo $page_content;
	elseif ( is_page( 'photo-credits' ) ) :
		?>
		<section class="body photo-credits container-xl">
			<h1 data-reveal="fade-up"><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'Follow each photo’s source link to view the original. Photos may be cropped to fit the layout.', 'ghumnajaun' ); ?></p>
			<ul id="gj-credits"><?php echo gj_credits_html(); ?></ul>
		</section>
		<?php else :
			?>
		<section class="body wp-content container-xl">
			<h1><?php the_title(); ?></h1>
			<?php echo $page_content; ?>
		</section>
		<?php
		endif;
endwhile;

get_footer();
