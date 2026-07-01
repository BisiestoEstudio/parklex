<?php
/**
 * Plugin Name:       Parklex Blocks
 * Plugin URI:
 * Description:       Gutenberg blocks plugin.
 * Version:           1.0.0
 * Author:
 * Author URI:
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       parklex-blocks
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'BIS_BLOCKS_VERSION',    '1.0.0' );
define( 'BIS_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'BIS_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

include_once 'includes/utils.php';
include_once 'includes/blocks.php';
include_once 'includes/editor-plugins.php';
