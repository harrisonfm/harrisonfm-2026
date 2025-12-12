<?php
namespace hfm\Core;

function setup() {
  $n = function( $function ) {
    return __NAMESPACE__ . "\\$function";
  };

  // Remove all default WP template redirects/lookups
  remove_action( 'template_redirect', 'redirect_canonical' );

  // Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
  function remove_redirects() {
    add_rewrite_rule( '^/(.+)/?', 'index.php', 'top' );
  }
  add_action( 'init', $n('remove_redirects'));
  register_nav_menu('header-menu', 'hfm');

  // Load scripts
  function load_vue_scripts() {
    wp_enqueue_script(
      'hfm-js',
      HFM_URL.'/dist/scripts/index.js',
      array( 'jquery' ),
      filemtime( get_stylesheet_directory() . '/dist/scripts/index.js' ),
      true
    );

    wp_enqueue_style(
      'hfm-css',
      HFM_URL.'/dist/styles.css',
      null,
      filemtime( get_stylesheet_directory() . '/dist/styles.css' )
    );
  }

  if (file_exists( get_stylesheet_directory() . '/dist/scripts/index.js' )) {
    add_action('wp_enqueue_scripts', $n('load_vue_scripts'), 100);
  } else {
    echo 'core JS scripts missing';
  }

  add_theme_support('post-thumbnails');
  add_theme_support('custom-logo');
  add_theme_support('custom-header');
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page();
  }

  add_image_size('square_gallery', 800, 800, true);
  add_filter('sort_media_custom_order_and_title_asc', '__return_true');
}