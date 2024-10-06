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


//Create a table for storing data from front-end Contact Form.
register_activation_hook(__FILE__, 'dff_create_custom_table');

function dff_create_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dynamic_form_submissions'; // Table name with WordPress prefix
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        field_name varchar(255) NOT NULL,
        field_value text NOT NULL,
        submission_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);  // Creates or updates the table structure
}


