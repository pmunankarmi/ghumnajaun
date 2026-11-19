<?php defined('ABSPATH') || exit; ?><!doctype html>
<html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><script>try{const theme=localStorage.getItem('ghumna-jaun-theme');if(theme==='light'||theme==='dark')document.documentElement.dataset.theme=theme;}catch{}</script><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?><a class="screen-reader-text" href="#main">Skip to content</a><div id="gj" data-view="<?php echo esc_attr(gj_design_view()); ?>">
<header><div class="header-inner"><a href="<?php echo esc_url(home_url('/')); ?>" class="brand page-link" data-page="home" aria-label="Ghumna Jaun home" ><?php gj_brand_logo(); ?></a><?php if(has_nav_menu('primary')): ?><nav aria-label="Main"><?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s','walker'=>new GJ_Flat_Nav()]); ?></nav><?php else: ?><nav aria-label="Main"><a href="<?php echo esc_url(home_url('/explore/')); ?>" data-filter="All"  class="page-link">Explore</a><a href="<?php echo esc_url(home_url('/explore/?category=Scenic%20dining')); ?>" data-filter="Scenic dining"  class="page-link">Scenic dining</a><a href="<?php echo esc_url(home_url('/explore/?category=Dharmik')); ?>" data-filter="Dharmik"  class="page-link">Dharmik</a><a href="<?php echo esc_url(home_url('/blog/')); ?>" data-page="blog"  class="page-link">Blog</a></nav><?php endif; ?><?php if(function_exists('pll_the_languages')&&count((array)pll_the_languages(['raw'=>1,'hide_if_empty'=>1]))>1): ?><nav aria-label="Languages"><?php pll_the_languages(['show_flags'=>0,'show_names'=>1,'hide_if_empty'=>1]); ?></nav><?php endif; ?><div class="header-actions">
<button class="header-search-toggle" id="gj-search-toggle" type="button" aria-label="Search places" aria-haspopup="dialog" aria-controls="gj-search-dialog"  hidden>
  <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4 4"/></svg>
</button><button class="theme-toggle" id="gj-theme-toggle" type="button"  hidden aria-label="Switch to dark mode">
  <svg class="theme-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 14.2A9 9 0 0 1 9.8 3.2 9 9 0 1 0 20.8 14.2Z"/></svg>
  <svg class="theme-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4m0-14.2-1.4 1.4M6.3 17.7l-1.4 1.4"/></svg>
</button></div>
</div></header>
<dialog class="search-dialog" id="gj-search-dialog" aria-labelledby="gj-search-title">
<div class="search-dialog-heading">
  <h2 id="gj-search-title">Search places</h2>
  <button class="search-close" id="gj-search-close" type="button" aria-label="Close search">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
  </button>
</div>
<form class="header-search" id="gj-header-search" action="<?php echo esc_url(home_url('/explore/')); ?>" method="get" role="search" aria-label="Find places">
  <input type="hidden" name="from" disabled>
  <input id="gj-search-input" type="search" name="q" aria-label="Search places" placeholder="Place, area or activity" maxlength="100" autocomplete="off" autofocus required>
  <button class="primary" type="submit">Search</button>
  <div class="header-suggestions" id="gj-search-suggestions" hidden>
    <p id="gj-search-status" role="status"></p>
    <ul id="gj-search-matches" aria-label="Matching destinations"></ul>
    <a class="page-link search-all" id="gj-search-all" href="<?php echo esc_url(home_url('/explore/')); ?>" hidden>View all results <span aria-hidden="true">↗</span></a>
  </div>
</form>
</dialog>
<?php if(gj_design_view()==='results')readfile(get_template_directory().'/views/explore-dialogs.html'); if(is_singular('destination'))readfile(get_template_directory().'/views/dialogs.html'); ?><main id="main">