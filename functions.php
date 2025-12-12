<?php
// Useful global constants
define('HFM_VERSION',      '2.0');
define('HFM_URL',          get_stylesheet_directory_uri());
define('HFM_TEMPLATE_URL', get_template_directory_uri());
define('HFM_PATH',         get_template_directory().'/');
define('HFM_INC',          HFM_PATH.'includes/');

// Include compartmentalized functions
require_once HFM_INC.'core.php';
require_once HFM_INC.'api.php';
require_once HFM_INC.'taxonomies.php';
require_once HFM_INC.'rss.php';

hfm\Core\setup();
hfm\Api\setup();
hfm\Taxonomies\setup();
hfm_rss_setup();