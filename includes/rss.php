<?php
//add_feed cannot handle namespaces, so all RSS stuff is global namespce.
function hfm_rss_setup() {
  add_action('init', 'add_harrigrams_feed');
  add_filter('the_content', 'hfm_featured_image_in_feed');
}
function add_harrigrams_feed() {
  add_feed('feed/harrigrams', 'render_harrigrams_feed');
}
function hfm_featured_image_in_feed($content) {
  global $post;
  if(is_feed() && has_post_thumbnail($post->ID)) {
    $featImg = get_the_post_thumbnail($post->ID, 'large', array('style' => 'height: auto;margin-bottom:2em;max-width: 600px !important;padding-top: 0.75em;width: 100% !important;'));
    $content = $featImg.$content;
  }
  return $content;
}
function render_harrigrams_feed() {
    header('Content-Type: application/rss+xml');
    global $wp_query;
    $wp_query = new WP_Query(
      array(
        'post_type' => 'attachment',
        'post_status' => 'any',
        'meta_query' => array(
          array(
            'key'     => 'harrigram',
            'value'   => 1
          ),
        )
      )
    );
    get_template_part('rss', 'harrigrams');
  }