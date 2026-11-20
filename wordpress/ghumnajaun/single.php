<?php get_header();while(have_posts()):the_post();echo gj_design_template('post');echo gj_blog_article_footer(get_the_ID());endwhile;get_footer(); ?>
