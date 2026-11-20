<?php
/** Blog. @package Ghumnajaun */
defined( 'ABSPATH' ) || exit;

?>
<section class="body blog-index">
	<h1 data-reveal="fade-up"><?php echo esc_html( is_category() ? single_cat_title( '', false ) : __( 'Travel guides', 'ghumnajaun' ) ); ?></h1>
	<div id="gj-blog-categories"><?php echo gj_blog_category_navigation(); ?></div>
	<div
		class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 place-row"
		id="gj-blog-posts"
	>
		<?php echo gj_article_cards_html(); ?>
	</div>
</section>
