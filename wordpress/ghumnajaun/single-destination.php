<?php

/** Destination guide and related reading. @package Ghumnajaun */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/destination' );
	echo gj_destination_reading_html( get_the_ID() );
endwhile;
get_footer();
