<?php
defined('ABSPATH') || exit;
//ACF Page Option
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> __( 'Theme General Settings', 'congdongtheme' ),
		'menu_title'	=> __( 'Theme Settings', 'congdongtheme' ),
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'icon_url' => 'dashicons-info', 
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> __( 'Site Manager Settings', 'congdongtheme' ),
		'menu_title'	=> __( 'Site Manager Settings', 'congdongtheme' ),
		'parent_slug'	=> 'theme-general-settings',
	));
	
}