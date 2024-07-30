<?php
/*
Plugin Name: WpAdmin Dynamic Options
Description: Dynamic Options for WordPress
This plugin empowers users to create custom options and settings within their WordPress admin panel. By providing an easy-to-use interface, users can add and manage custom fields, enabling them to tailor their WordPress experience to meet specific needs without the need for coding knowledge. Perfect for developers and site administrators looking to extend the functionality of their WordPress sites with unique options and settings. 
Version: 1.0
Author: Aamir
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'inc/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-wp-form-builder.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-custom-admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/admin_page.php';

