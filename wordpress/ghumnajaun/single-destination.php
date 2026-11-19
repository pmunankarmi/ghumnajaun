<?php get_header();while(have_posts()):the_post();echo gj_design_trip_html();echo gj_destination_reading_html(get_the_ID());endwhile;get_footer(); ?>
