<?php
/** Post. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

?>
<article class="body blog-article" id="gj-post">
	<a class="page-link" data-page="blog" href="<?php echo esc_url( gj_home_url( '/blog/' ) ); ?>">
		<?php esc_html_e( '← All travel guides', 'ghumnajaun' ); ?>
	</a>
	<header class="post-heading" data-reveal="fade-up">
		<p class="eyebrow" id="gj-post-category"><?php echo gj_blog_category_links( get_the_ID() ); ?></p>
		<h1 id="gj-post-title"><?php the_title(); ?></h1>
		<p class="post-intro" id="gj-post-intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
	</header>
	<?php the_post_thumbnail( 'large', array( 'class' => 'post-image', 'id' => 'gj-post-image' ) ); ?>
	<div class="post-body" id="gj-post-body"><?php echo gj_article_reading_html( apply_filters( 'the_content', get_the_content() ), get_the_ID() ); ?></div>
</article>
