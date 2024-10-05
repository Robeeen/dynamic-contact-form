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
define( 'DYNAMIC_PLUGIN_VERSION', '1.0.2' );


// Include other plugin components
define( 'DYNAMIC_PLUGIN', plugin_dir_path( __FILE__ ) );

include_once( DYNAMIC_PLUGIN . 'includes/admin/admin.php');
include_once( DYNAMIC_PLUGIN . 'shortcoes/shortcode.php');

//For admin panel 
function bootstrap_js(){
    wp_enqueue_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js');
    wp_enqueue_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
}
add_action( 'admin_enqueue_scripts', 'bootstrap_js');

//Front-end bootstrap
function bootstrap_js_front(){
    wp_enqueue_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js');
    wp_enqueue_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
}
add_action( 'wp_enqueue_scripts', 'bootstrap_js_front');

//add js 
function add_js_scripts(){
    wp_enqueue_script( 'jscall', plugins_url( 'includes/admin/main.js', __FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'add_js_scripts' );