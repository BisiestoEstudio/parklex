<?php
/**
 * Plugin Name:       Parklex Core
 * Plugin URI:
 * Description:       Core plugin. Manages CPTs and core functionalities.
 * Version:           1.0.0
 * Author:
 * Author URI:
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       parklex-core
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'BIS_CORE_VERSION', '1.0.0' );
define( 'BIS_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BIS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once BIS_CORE_DIR . 'includes/config.php';
require_once BIS_CORE_DIR . 'includes/class-loader.php';

register_activation_hook( __FILE__, array( 'Bis_Core_Loader', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bis_Core_Loader', 'deactivate' ) );

Bis_Core_Loader::init();
