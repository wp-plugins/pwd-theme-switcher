<?php
/*
Plugin Name: PWD Theme Switcher
Description: Change theme to view your front office changes without saving just for your session
Version: 1.0
Plugin URI: http://www.plateformewpdigital.fr/plugins/pwd-theme-switcher
Author: Plateforme WP Digital, Kulka Nicolas
Author URI: http://www.plateformewpdigital.fr
Network: false
Text Domain: pwd-theme-switcher
Domain Path: languages
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'PWD_THEME_SWITCHER_VERSION', '1.0' );
define( 'PWDTHEME_SWITCHER_FOLDER', 'pwd-theme-switcher' );

define( 'PWD_THEME_SWITCHER_URL', plugin_dir_url( __FILE__ ) );
define( 'PWD_THEME_SWITCHER_DIR', plugin_dir_path( __FILE__ ) );

// Function for easy load files
function _pwd_theme_switcher_load_files( $dir, $files, $prefix = '' ) {
	foreach ( $files as $file ) {
		if ( is_file( $dir . $prefix . $file . '.php' ) ) {
			require_once($dir . $prefix . $file . '.php');
		}
	}
}

// Plugin client classes
_pwd_theme_switcher_load_files( PWD_THEME_SWITCHER_DIR . 'classes/', array( 'plugin' ) );

add_action( 'plugins_loaded', 'init_pwd_theme_switcher_plugin' );
function init_pwd_theme_switcher_plugin() {
	// Load client
	new PWD_THEME_SWITCHER_Plugin();
}