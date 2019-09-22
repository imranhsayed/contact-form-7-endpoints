<?php
/**
 * Plugin Name: CONTACT FORM 7 ENDPOINTS
 * Plugin URI: https://github.com/imranhsayed/contact-form-7-endpoints
 * Description: This plugin provides you different endpoints for Contact form 7 using WordPress REST API
 * Version: 1.0.0
 * Author: Imran Sayed, Smit patadiya
 * Author URI: https://codeytek.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: contact-form-7-endpoints
 * Domain Path: /languages
 *
 * @package WordPress Contributors
 */

// Define Constants.
define( 'CF7E_URI', plugins_url( 'contact-form-7-endpoints' ) );
define( 'CF7E_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'CF7E_PLUGIN_PATH', __FILE__ );

// File Includes
include_once 'apis/class-cf7e-register-posts-api.php';
