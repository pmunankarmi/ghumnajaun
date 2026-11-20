<?php

/** Blog post template. @package Ghumnajaun */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/post' );
	echo gj_blog_article_footer( get_the_ID() );
endwhile;
get_footer();
