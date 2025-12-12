<?php
namespace hfm\Api;

function setup() {
  global $n;
  $n = function( $function ) {
    return __NAMESPACE__ . "\\$function";
  };

  add_action('rest_api_init', $n('register_rest_routes'));
  add_filter('wp_rest_cache/allowed_endpoints', $n('wprc_add_hfm_endpoints'), 10, 1);

  function wprc_add_hfm_endpoints( $allowed_endpoints ) {
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('posts', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'posts';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('post', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'post';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('page', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'page';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('media', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'media';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('sitemeta', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'sitemeta';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('home', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'home';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('stories', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'stories';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('story', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'story';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('storymedia', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'storymedia';
    }
    if (!isset($allowed_endpoints['menus/v1']) || !in_array('menus', $allowed_endpoints['menus/v1'])) {
      $allowed_endpoints['menus/v1'][] = 'menus';
    }
    if (!isset($allowed_endpoints['hfm/v1']) || !in_array('harrigrams', $allowed_endpoints['hfm/v1'])) {
      $allowed_endpoints['hfm/v1'][] = 'harrigrams';
    }
    return $allowed_endpoints;
  }

  add_filter('acf/rest_api/post/get_fields', function ($data) {
    if (!isset($data) || !isset($data["acf"])) {
      return $data;
    }

    // Here add the possible field names that might be a type of an attachment, needs to be tested
    $fieldNames = ["background", "image"];
    // Loop through them and add key acf with the custom fields
    foreach ($fieldNames as $fieldName) {
      if (isset($data->acf) && isset($data->acf[$fieldName]) && is_array($data->acf[$fieldName])) {
        $data->acf[$fieldName]['acf'] = get_fields($data->acf[$fieldName]["id"]);
        $data->acf[$fieldName]['acf']['likes'] = $data->acf[$fieldName]['acf']['likes'] ? $data->acf[$fieldName]['acf']['likes'] : 0;
      }
    };

    return $data;
  }, 10, 3);

  function likeMedia($request) {
    if($request['likes'] < 0) {
      $request['likes'] = 0;
    }
    if(update_field('likes', $request['likes'], $request['id'])) {
      return true;
    }
    return false;
  }

  function getPostsByType($request) {
    $args = array(
      'posts_per_page' => $request['per_page'] ? $request['per_page'] : 8,
      'post_type' => 'post',
      'paged' => $request['page'] ? $request['page'] : 1,
      'ignore_sticky_posts' => 1
    );

    if($request['category']) {
      $args['category_name'] = $request['category'];
    }
    elseif($request['tag']) {
      $args['tag'] = $request['tag'];
    }
    elseif($request['search']) {
      $args['s'] = $request['search'];
    }

    $query = new \WP_Query($args);
    formatPostsForApi($query->posts);

    return new \WP_REST_Response($query);
  }

  function getPost($request) {
    $args = array(
      'name' => $request['slug'],
      'post_type' => 'post',
      'post_status' => 'publish'
    );

    $query = new \WP_Query($args);
    if($query->posts) {
      $post = $query->posts[0];
      formatPostForApi($post);
    }
    else {
      $post = false;
    }
    
    return new \WP_REST_Response($post);
  }

  function getPage($request) {
    $args = array(
      'pagename' => $request['slug'],
      'post_type' => 'page'
    );

    $query = new \WP_Query($args);

    if(!empty($query->posts)) {
      $page = $query->posts[0];
      formatPostForApi($page);
      if($page->post_name === 'photos') {
        $page->stories = getStories(false, true);
      }
    }
    else {
      $page = false;
    }
    
    return new \WP_REST_Response($page);
  }

  function getHome() {
    $data = get_object_vars(get_theme_mod('header_image_data'));

    return new \WP_REST_Response(array(
      'hero' => getAttachment($data['attachment_id'])
    ));
  }

  function formatPostsForApi(&$posts) {
    foreach($posts as $post) {
      formatPostForApi($post);
    }
  }

  function formatPostForApi(&$post, $ignoreStories = false) {
    $post->acf = get_fields($post->ID) ?? array();

    if(is_array($post->acf['gallery'])) {
      $gallery = new \WP_Query(array(
        'post_type' => 'attachment',
        'post_status' => 'any',
        'order' => 'ASC',
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'wpmf_order',
        'posts_per_page' => -1,
        'tax_query' => array(
          array(
            'taxonomy' => 'wpmf-category',
            'terms'    => $post->acf['gallery'][0],
            'include_children' => false
          ),
        ),
      ));
      formatGalleryImages($gallery->posts);
      $post->gallery = $gallery->posts;
      unset($post->acf['gallery']);
    }

    if(isset($post->acf['genres'])) {
      foreach($post->acf['genres'] as &$genre) {
        $genre['featured'] = getAttachment($genre['featured']);
        formatGalleryImages($genre['gallery'], true);
        $genre['slug'] = strtolower(str_replace(' ', '-', $genre['title']));
      }

      $post->genres = $post->acf['genres'];
    }

    if(isset($post->acf['projects'])) {
      foreach($post->acf['projects'] as &$project) {
        $project['image'] = getAttachment($project['image']);
      }

      $post->projects = $post->acf['projects'];
    }

    $post->post_excerpt = get_the_excerpt($post);

    $post->link = str_replace(network_site_url(), '', get_permalink($post->ID));

    $post->categories = array();
    $cats = wp_get_post_categories($post->ID);
    foreach($cats as $c){
      $post->categories[] = get_category($c);
    }

    $post->post_date = date_format(date_create($post->post_date), 'M jS, Y');

    $post->post_content = apply_filters('the_content', $post->post_content);

    $post->tags = wp_get_post_terms($post->ID);

    if($featuredMediaID = get_post_thumbnail_id($post->ID)) {
      $featuredMedia = new \WP_Query(array(
        'p' => $featuredMediaID,
        'post_type' => 'attachment',
        'post_status' => 'any'
      ));

      if($featuredMedia->posts) {
        formatGalleryImages($featuredMedia->posts);
        $post->featured = $featuredMedia->posts[0];
      }
      else {
        $post->featured = false;
      }
    }
    else {
      $post->featured = false;
    }

    if(!$ignoreStories) { //recursive to get next/prev navigation on story posts
      $postStories = wp_get_object_terms($post->ID, 'story');
      if($postStories) {
        $post->story = $postStories[0];

        $args = array(
          'post_type' => 'post',
          'order' => 'DESC',
          'posts_per_page' => -1,

          'tax_query' => array(
            array(
              'taxonomy' => 'story',
              'field'    => 'slug',
              'terms'    => $post->story->slug,
            ),
          ),
        );
        $query = new \WP_Query($args);
        $postsInStory = $query->posts;

        for($i = 0; $i < count($postsInStory); $i++) {
          if($postsInStory[$i]->ID === $post->ID) {
            if($i !== 0) {
              $post->story->next = true;
              $post->next = $postsInStory[$i - 1];
              formatPostForApi($post->next, true);
            }
            else {
              getAdjacentPosts($post, false, true);
            }
            if($i !== count($postsInStory) - 1) {
              $post->story->prev = true;
              $post->prev = $postsInStory[$i + 1];
              formatPostForApi($post->prev, true);
            }
            else {
              getAdjacentPosts($post, true, false);
            }
            break;
          }
        }
      }
      else {
        getAdjacentPosts($post);
      }
    }
  }

  function getAdjacentPosts(&$postObject, $getPrev = true, $getNext = true) {
    global $post; // adjacent posts require $post global
    $post = get_post($postObject->ID);
    if($getPrev) {
      $postObject->prev = get_previous_post();
      if(!empty($postObject->prev)) {
        formatPostForApi($postObject->prev, true);
      }
    }
    if($getNext) {
      $postObject->next = get_next_post();
      if(!empty($postObject->next)) {
        formatPostForApi($postObject->next, true);
      }
    }
  }

  function formatGalleryImages(&$galleryItems, $getPost = false) {
    $imageSizes = wp_get_registered_image_subsizes();
    $imageSizes['full'] = 'full';

    foreach($galleryItems as &$galleryItem) {
      if($getPost) {
        $galleryItem = get_post($galleryItem);
      }
      $likes = get_field('likes', $galleryItem->ID);
      $galleryItem->likes = $likes ? $likes : 0;

      $galleryItem->location = get_field('location', $galleryItem->ID);
      $galleryItem->date = get_field('date', $galleryItem->ID);

      $galleryItem->post_name = strtolower(str_replace(' ', '-', $galleryItem->post_title));

      $galleryItem->images = array();
      foreach($imageSizes as $size => $dimensions) {
        $imageSrc = wp_get_attachment_image_src($galleryItem->ID, $size);

        $galleryItem->images["$size"] = array(
          'src' => $imageSrc[0],
          'width' => $imageSrc[1],
          'height' => $imageSrc[2]
        );
      }
    }
  }

  function getAttachment($id) {
    $query = new \WP_Query(array(
      'p' => $id,
      'post_type' => 'attachment',
      'post_status' => 'any'
    ));

    $attachment = array();

    if($query->posts) {
      formatGalleryImages($query->posts);
      $attachment = $query->posts[0];
    }

    return $attachment;

  }

  function getSiteMeta() {
    return new \WP_REST_Response(array(
      'title' => get_bloginfo('name'),
      'tagline' => get_bloginfo('description'),
      'url' => get_bloginfo('url'),
      'img' => get_header_image(),
    ));
  }

  function getStories($request, $photosPage = false) {
    $terms = getStoryTerms();

    if($photosPage) {
      return $terms;
    }
    else{
      return new \WP_REST_Response(array(
        'stories' => $terms,
        'hero' => getAttachment(get_field('stories_hero', 'option')),
        'description' => getAttachment(get_field('stories_description', 'option'))
      ));
    }
  }

  function getStory($request) {
    $args = array(
      'post_type' => 'post',
      'order' => 'ASC',
      'posts_per_page' => -1,

      'tax_query' => array(
        array(
          'taxonomy' => 'story',
          'field'    => 'slug',
          'terms'    => $request['slug'],
        ),
      ),
    );

    $query = new \WP_Query($args);
    formatPostsForApi($query->posts);

    if($request['queryForTerm']) {
       $args = array(
        'taxonomy' => 'story',
        'slug' => $request['slug']
      );
      $termQuery = new \WP_Term_Query($args);
      $terms = $termQuery->get_terms();
      
      foreach($terms as &$term) {
        $term->featured = getAttachment(get_field('featured', 'story_'.$term->term_id));
      }
      $query->term = $terms[0];
    }

    return new \WP_REST_Response($query);
  }

  function getStoryMedia($request) {
    global $n;

    $terms = getStoryTerms();
    $currentTerm = false;
    foreach ($terms as $term) {
      if($term->slug === $request['slug']) {
        $currentTerm = $term;
        break;
      }
    }
    if(!$currentTerm) {
      return new \WP_REST_Response(array(
        'media' => false
      ));
    }

    $args = array(
      'taxonomy' => 'wpmf-category',
      'slug' => $request['slug'],
    );
    $parent_term_query = new \WP_Term_Query($args);
    
    $args = array(
      'taxonomy' => 'wpmf-category',
      'parent' => $parent_term_query->terms[0]->term_id,
      'orderby' => 'term_order'
    );
    $child_terms_query = new \WP_Term_Query($args);

    $wpmf_terms = $child_terms_query->terms;

    $posts = array();
    foreach($wpmf_terms as $term) {
      $args = array(
        'post_type' => 'attachment',
        'post_status' => 'any',
        'order' => 'ASC',
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'wpmf_order',
        'posts_per_page' => -1,
        'tax_query' => array(
          array(
            'taxonomy' => 'wpmf-category',
            'terms' => $term->term_id
          ),
        ),
      );
      $query = new \WP_Query($args);
      foreach($query->posts as $post) {
        $posts[] = $post;
      }
    }

    formatGalleryImages($posts);

    return new \WP_REST_Response(array(
      'media' => $posts,
      'term' => $currentTerm
    ));
  }

  function getStoryTerms() {
    $args = array(
      'taxonomy' => 'story',
      'orderby' => 'term_order',
      'order' => 'ASC'
    );
    $query = new \WP_Term_Query($args);
    $terms = $query->get_terms();

    foreach($terms as &$term) {
      $term->featured = getAttachment(get_field('featured', 'story_'.$term->term_id));
      $term->title = $term->name;
    }
    return $terms;
  }

  function getHarrigrams($request) {
    $args = array(
      'post_type' => 'attachment',
      'post_status' => 'any',
      'posts_per_page' => $request['fetchAll'] ? -1 : 8,
      'meta_query' => array(
        array(
          'key'     => 'harrigram',
          'value'   => 1
        ),
      ),
    );

    $query = new \WP_Query($args);
    $harrigrams = $query->posts;
    formatGalleryImages($harrigrams);

    $response = array(
      'images' => $harrigrams
    );

    return new \WP_REST_Response($response);
  }

  function register_rest_routes() {
    global $n;
    register_rest_route('hfm/v1', '/media/(?P<id>\d+)/like', array(
      'methods' => 'PUT',
      'callback' => $n('likeMedia'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/posts', array(
      'methods' => 'GET',
      'callback' => $n('getPostsByType'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/post', array(
      'methods' => 'GET',
      'callback' => $n('getPost'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/page', array(
      'methods' => 'GET',
      'callback' => $n('getPage'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/media', array(
      'methods' => 'GET',
      'callback' => $n('getMedia'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/sitemeta', array(
      'methods' => 'GET',
      'callback' => $n('getSiteMeta'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/home', array(
      'methods' => 'GET',
      'callback' => $n('getHome'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/stories', array(
      'methods' => 'GET',
      'callback' => $n('getStories'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/story', array(
      'methods' => 'GET',
      'callback' => $n('getStory'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/storymedia', array(
      'methods' => 'GET',
      'callback' => $n('getStoryMedia'),
      'permission_callback' => '__return_true'
    ));

    register_rest_route('hfm/v1', '/harrigrams', array(
      'methods' => 'GET',
      'callback' => $n('getHarrigrams'),
      'permission_callback' => '__return_true'
    ));
  }
}