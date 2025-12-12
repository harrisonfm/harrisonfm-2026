<?php
namespace hfm\Taxonomies;

function setup() {
  $n = function($function) {
    return __NAMESPACE__ . "\\$function";
  };

  add_action('init', $n('register_stories'));
  add_action('restrict_manage_posts', $n('filter_backend_by_taxonomies'), 99, 2);
}

function register_stories() {
  $singular = 'Story';
  $plural = 'Stories';

  $labels = array (
    "name"              => __("$plural"),
    "singular_name"     => __("$singular"),
    "search_items"      => __("Search $plural"),
    "all_items"         => __("All $plural"),
    "parent_item"       => __("Parent $singular"),
    "edit_item"         => __("Edit $singular"),
    "update_item"       => __("Update $singular"),
    "add_new_item"      => __("Add New $singular"),
    "new_item_name"     => __("New $singular Name"),
    "menu_name"         => __("$plural"),
  );

  $args = array (
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_admin_column' => true,
    'query_var'         => true,
    'show_in_rest'      => true,
    'rewrite'           => array( 'slug' => 'stories' ),
    'public'            => true
  );

  register_taxonomy('story', array('post'), $args);
}

/* Filter CPT via Custom Taxonomy */
/* https://generatewp.com/filtering-posts-by-taxonomies-in-the-dashboard/ */

function filter_backend_by_taxonomies( $post_type, $which ) {
  // Apply this to a specific CPT
  if ($post_type !== 'post') {
    return;
  }

  // A list of custom taxonomy slugs to filter by
  $taxonomies = array('story');
  foreach ( $taxonomies as $taxonomy_slug ) {
    // Retrieve taxonomy data
    $taxonomy_obj = get_taxonomy( $taxonomy_slug );
    $taxonomy_name = $taxonomy_obj->labels->name;

    // Retrieve taxonomy terms
    $terms = get_terms( $taxonomy_slug );

    // Display filter HTML
    echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
    echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
    foreach ( $terms as $term ) {
      printf(
        '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
        $term->slug,
        ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
        $term->name,
        $term->count
      );
    }
    echo '</select>';
  }
}