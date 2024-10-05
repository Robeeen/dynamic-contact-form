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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );


// Include other plugin components
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

include_once( MY_PLUGIN_PATH . 'includes/admin/admin.php');

//For admin panel and front-end bootstrap
wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
wp_enqueue_script('prefix_bootstrap');

wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
wp_enqueue_style('prefix_bootstrap');

//add js 

add_action( 'admin_enqueue_scripts', 'add_js_scripts' );
function add_js_scripts(){
    wp_enqueue_script( 'ajaxcalls', plugins_url( 'includes/admin/main.js', __FILE__ ));
}