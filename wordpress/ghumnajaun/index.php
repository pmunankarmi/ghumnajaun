<?php get_header(); ?>
<div class="body"><h1><?php echo esc_html(is_home()?'Travel guides':(is_search()?'Search results':get_the_archive_title())); ?></h1>
<?php if(is_post_type_archive('destination')||is_tax(['outing','place_city','place_type'])): ?>
<form class="wp-filters" method="get" action="<?php echo esc_url(home_url('/explore/')); ?>"><input type="search" name="q" aria-label="Search destinations" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET['q']??''))); ?>" placeholder="Place, area or activity"><button class="primary">Search</button></form>
<?php $args=['paged'=>max(1,get_query_var('paged')),'s'=>sanitize_text_field(wp_unslash($_GET['q']??''))]; if(is_tax()){$term=get_queried_object();$args['tax_query']=[['taxonomy'=>$term->taxonomy,'terms'=>$term->term_id]];} $q=gj_cards($args);echo paginate_links(['total'=>$q->max_num_pages]);if(!$q->found_posts)echo '<p class="wp-empty">No destinations found.</p>'; ?>
<?php else: ?><div class="row row-cols-1 row-cols-md-3 g-4 wp-cards"><?php while(have_posts()):the_post(); ?><div class="col"><a class="place" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?><div class="info"><h2><?php the_title(); ?></h2><?php the_excerpt(); ?></div></a></div><?php endwhile; ?></div><?php the_posts_pagination(); endif; ?></div>
<?php get_footer(); ?>
