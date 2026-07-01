<?php
defined( 'ABSPATH' ) || exit;

/*********************************
 * DEFINES
***********************************/
define( 'BIS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'BIS_THEME_DIR', get_template_directory() );
define( 'BIS_THEME_URI', get_template_directory_uri() );


/**********************************
 * INCLUDES
***********************************/
include_once get_template_directory() . '/includes/utils.php';
include_once get_template_directory() . '/includes/class-config.php';
include_once get_template_directory() . '/includes/class-assets.php';


