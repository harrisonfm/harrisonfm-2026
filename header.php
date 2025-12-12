<?php status_header(200); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="twitter:card" content="summary_large_image">
    <?php if($_SERVER['SERVER_NAME'] === 'dev.harrisonfm.com') { ?><meta name="robots" content="noindex"><?php } ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/bolt.svg" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>