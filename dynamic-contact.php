<?php


 /*
 * Plugin Name: Dynamic Form Fields
 * Description: A plugin to create form fields dynamically.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Author: Shams Khan
 * Author URI: https://shamskhan.com
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:
 * Text Domain: custom-table
 * Domain Path: /languages/asset/
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

//plugin Versions
defined( 'PLUGIN_NAME_VERSION', '1.0.0' );


// Include other plugin components
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

include_once( MY_PLUGIN_PATH . 'includes/admin/admin.php');