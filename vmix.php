<?php

/*
Plugin Name: VMIX
Plugin URI: http://vmix.com/
Description: Easy embedding of videos from VMIX <a href="options-general.php?page=vmix_options_page">Configure...</a>
Version: 1.0.2
License: GPL
Author: Ian D. Miller
Author URI: http://vmix.com/

Contact mail: ian@vmix.com
*/


function vmix_addbuttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_vmix_tinymce_plugin");
		add_filter('mce_buttons', 'register_vmix_button');
	}
}

function register_vmix_button($buttons) {
	array_push($buttons, "separator", "vmix");
	return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_vmix_tinymce_plugin($plugin_array) {
	$plugin_array['vmix'] = get_option('siteurl').'/wp-content/plugins/vmix/vmix_plugin.js';
	return $plugin_array;
}
 
// init process for button control
add_action('init', 'vmix_addbuttons');

function vmix_scripts() {
        echo '<script src="'.get_option('siteurl').'/wp-content/plugins/vmix/vmix.js"></script>';
}

function vmix_init() {
        add_options_page('VMIX Video', 'VMIX Video', 'manage_options', 'vmix_options_page', 'vmix_option_page');
        add_action('admin_print_scripts', 'vmix_scripts');
}

function vmix_option_page() {
	include 'vmix-options.php';
}

if ( function_exists('add_action') ) {
	add_action('admin_menu', 'vmix_init');
}

?>
