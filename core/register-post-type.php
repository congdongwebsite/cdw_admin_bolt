<?php
defined('ABSPATH') || exit;
// Register Custom Post Type Manage VPS
// Post Type Key: Manage VPS
function create_manage_vps_cpt()
{

    $labels = array(
        'name' => _x('Manage VPS', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Manage VPS', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Manage VPS', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Manage VPS', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Manage VPS', 'congdongweb'),
        'attributes' => __('Manage VPS', 'congdongweb'),
        'parent_item_colon' => __('Manage VPS', 'congdongweb'),
        'all_items' => __('All Manage VPS', 'congdongweb'),
        'add_new_item' => __('Add New Manage VPS', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Manage VPS', 'congdongweb'),
        'edit_item' => __('Edit Manage VPS', 'congdongweb'),
        'update_item' => __('Update Manage VPS', 'congdongweb'),
        'view_item' => __('View Manage VPS', 'congdongweb'),
        'view_items' => __('View Manage VPS', 'congdongweb'),
        'search_items' => __('Search Manage VPS', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Manage VPS', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Manage VPS', 'congdongweb'),
        'items_list' => __('Manage VPS list', 'congdongweb'),
        'items_list_navigation' => __('Manage VPS list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Manage VPS list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Manage VPS', 'congdongweb'),
        'description' => __('Quản lý VPS', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('manage-vps', $args);
}
add_action('init', 'create_manage_vps_cpt', 0);

// Register Custom Post Type Customer
// Post Type Key: Customer
function create_customer_cpt()
{

    $labels = array(
        'name' => _x('Customer', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer', 'congdongweb'),
        'attributes' => __('Customer', 'congdongweb'),
        'parent_item_colon' => __('Customer', 'congdongweb'),
        'all_items' => __('All Customer', 'congdongweb'),
        'add_new_item' => __('Add New Customer', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer', 'congdongweb'),
        'edit_item' => __('Edit Customer', 'congdongweb'),
        'update_item' => __('Update Customer', 'congdongweb'),
        'view_item' => __('View Customer', 'congdongweb'),
        'view_items' => __('View Customer', 'congdongweb'),
        'search_items' => __('Search Customer', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer', 'congdongweb'),
        'items_list' => __('Customer list', 'congdongweb'),
        'items_list_navigation' => __('Customer list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer', $args);
}
add_action('init', 'create_customer_cpt', 0);

// Register Custom Post Type Customer Hosting
// Post Type Key: Customer Hosting
function create_customer_hosting_cpt()
{

    $labels = array(
        'name' => _x('Customer Hosting', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Hosting', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Hosting', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Hosting', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Hosting', 'congdongweb'),
        'attributes' => __('Customer Hosting', 'congdongweb'),
        'parent_item_colon' => __('Customer Hosting', 'congdongweb'),
        'all_items' => __('All Customer Hosting', 'congdongweb'),
        'add_new_item' => __('Add New Customer Hosting', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Hosting', 'congdongweb'),
        'edit_item' => __('Edit Customer Hosting', 'congdongweb'),
        'update_item' => __('Update Customer Hosting', 'congdongweb'),
        'view_item' => __('View Customer Hosting', 'congdongweb'),
        'view_items' => __('View Customer Hosting', 'congdongweb'),
        'search_items' => __('Search Customer Hosting', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Hosting', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Hosting', 'congdongweb'),
        'items_list' => __('Customer Hosting list', 'congdongweb'),
        'items_list_navigation' => __('Customer Hosting list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Hosting list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Hosting', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-hosting', $args);
}
add_action('init', 'create_customer_hosting_cpt', 0);

// Register Custom Post Type Customer Domain
// Post Type Key: Customer Domain
function create_customer_domain_cpt()
{

    $labels = array(
        'name' => _x('Customer Domain', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Domain', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Domain', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Domain', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Domain', 'congdongweb'),
        'attributes' => __('Customer Domain', 'congdongweb'),
        'parent_item_colon' => __('Customer Domain', 'congdongweb'),
        'all_items' => __('All Customer Domain', 'congdongweb'),
        'add_new_item' => __('Add New Customer Domain', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Domain', 'congdongweb'),
        'edit_item' => __('Edit Customer Domain', 'congdongweb'),
        'update_item' => __('Update Customer Domain', 'congdongweb'),
        'view_item' => __('View Customer Domain', 'congdongweb'),
        'view_items' => __('View Customer Domain', 'congdongweb'),
        'search_items' => __('Search Customer Domain', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Domain', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Domain', 'congdongweb'),
        'items_list' => __('Customer Domain list', 'congdongweb'),
        'items_list_navigation' => __('Customer Domain list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Domain list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Domain', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-domain', $args);
}
add_action('init', 'create_customer_domain_cpt', 0);

// Register Custom Post Type Customer Email
// Post Type Key: Customer Email
function create_customer_email_cpt()
{

    $labels = array(
        'name' => _x('Customer Email', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Email', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Email', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Email', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Email', 'congdongweb'),
        'attributes' => __('Customer Email', 'congdongweb'),
        'parent_item_colon' => __('Customer Email', 'congdongweb'),
        'all_items' => __('All Customer Email', 'congdongweb'),
        'add_new_item' => __('Add New Customer Email', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Email', 'congdongweb'),
        'edit_item' => __('Edit Customer Email', 'congdongweb'),
        'update_item' => __('Update Customer Email', 'congdongweb'),
        'view_item' => __('View Customer Email', 'congdongweb'),
        'view_items' => __('View Customer Email', 'congdongweb'),
        'search_items' => __('Search Customer Email', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Email', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Email', 'congdongweb'),
        'items_list' => __('Customer Email list', 'congdongweb'),
        'items_list_navigation' => __('Customer Email list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Email list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Email', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-email', $args);
}
add_action('init', 'create_customer_email_cpt', 0);
// Register Custom Post Type Customer Theme
// Post Type Key: Customer Theme
function create_customer_theme_cpt()
{

    $labels = array(
        'name' => _x('Customer Theme', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Theme', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Theme', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Theme', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Theme', 'congdongweb'),
        'attributes' => __('Customer Theme', 'congdongweb'),
        'parent_item_colon' => __('Customer Theme', 'congdongweb'),
        'all_items' => __('All Customer Theme', 'congdongweb'),
        'add_new_item' => __('Add New Customer Theme', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Theme', 'congdongweb'),
        'edit_item' => __('Edit Customer Theme', 'congdongweb'),
        'update_item' => __('Update Customer Theme', 'congdongweb'),
        'view_item' => __('View Customer Theme', 'congdongweb'),
        'view_items' => __('View Customer Theme', 'congdongweb'),
        'search_items' => __('Search Customer Theme', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Theme', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Theme', 'congdongweb'),
        'items_list' => __('Customer Theme list', 'congdongweb'),
        'items_list_navigation' => __('Customer Theme list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Theme list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Theme', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-theme', $args);
}
add_action('init', 'create_customer_theme_cpt', 0);

// Register Custom Post Type Customer Plugin
// Post Type Key: Customer Plugin
function create_customer_plugin_cpt()
{

    $labels = array(
        'name' => _x('Customer Plugin', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Plugin', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Plugin', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Plugin', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Plugin', 'congdongweb'),
        'attributes' => __('Customer Plugin', 'congdongweb'),
        'parent_item_colon' => __('Customer Plugin', 'congdongweb'),
        'all_items' => __('All Customer Plugin', 'congdongweb'),
        'add_new_item' => __('Add New Customer Plugin', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Plugin', 'congdongweb'),
        'edit_item' => __('Edit Customer Plugin', 'congdongweb'),
        'update_item' => __('Update Customer Plugin', 'congdongweb'),
        'view_item' => __('View Customer Plugin', 'congdongweb'),
        'view_items' => __('View Customer Plugin', 'congdongweb'),
        'search_items' => __('Search Customer Plugin', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Plugin', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Plugin', 'congdongweb'),
        'items_list' => __('Customer Plugin list', 'congdongweb'),
        'items_list_navigation' => __('Customer Plugin list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Plugin list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Plugin', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-plugin', $args);
}
add_action('init', 'create_customer_plugin_cpt', 0);
// Register Custom Post Type Customer Billing
// Post Type Key: Customer Billing
function create_customer_billing_cpt()
{

    $labels = array(
        'name' => _x('Customer Billing', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Customer Billing', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Customer Billing', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Customer Billing', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Customer Billing', 'congdongweb'),
        'attributes' => __('Customer Billing', 'congdongweb'),
        'parent_item_colon' => __('Customer Billing', 'congdongweb'),
        'all_items' => __('All Customer Billing', 'congdongweb'),
        'add_new_item' => __('Add New Customer Billing', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Customer Billing', 'congdongweb'),
        'edit_item' => __('Edit Customer Billing', 'congdongweb'),
        'update_item' => __('Update Customer Billing', 'congdongweb'),
        'view_item' => __('View Customer Billing', 'congdongweb'),
        'view_items' => __('View Customer Billing', 'congdongweb'),
        'search_items' => __('Search Customer Billing', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Customer Billing', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Customer Billing', 'congdongweb'),
        'items_list' => __('Customer Billing list', 'congdongweb'),
        'items_list_navigation' => __('Customer Billing list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Customer Billing list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Customer Billing', 'congdongweb'),
        'description' => __('Quản lý khách hàng', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('customer-billing', $args);
}
add_action('init', 'create_customer_billing_cpt', 0);

// Register Custom Post Type Finance Type
// Post Type Key: Finance Type
function create_finance_type_cpt()
{

    $labels = array(
        'name' => _x('Finance Type', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Finance Type', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Finance Type', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Finance Type', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Finance Type', 'congdongweb'),
        'attributes' => __('Finance Type', 'congdongweb'),
        'parent_item_colon' => __('Finance Type', 'congdongweb'),
        'all_items' => __('All Finance Type', 'congdongweb'),
        'add_new_item' => __('Add New Finance Type', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Finance Type', 'congdongweb'),
        'edit_item' => __('Edit Finance Type', 'congdongweb'),
        'update_item' => __('Update Finance Type', 'congdongweb'),
        'view_item' => __('View Finance Type', 'congdongweb'),
        'view_items' => __('View Finance Type', 'congdongweb'),
        'search_items' => __('Search Finance Type', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Finance Type', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Finance Type', 'congdongweb'),
        'items_list' => __('Finance Type list', 'congdongweb'),
        'items_list_navigation' => __('Finance Type list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Finance Type list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Finance Type', 'congdongweb'),
        'description' => __('Loại thu chi', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('finance-type', $args);
}
add_action('init', 'create_finance_type_cpt', 0);


// Register Custom Post Type Receipt
// Post Type Key: Receipt
function create_receipt_cpt()
{

    $labels = array(
        'name' => _x('Receipt', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Receipt', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Receipt', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Receipt', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Receipt', 'congdongweb'),
        'attributes' => __('Receipt', 'congdongweb'),
        'parent_item_colon' => __('Receipt', 'congdongweb'),
        'all_items' => __('All Receipt', 'congdongweb'),
        'add_new_item' => __('Add New Receipt', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Receipt', 'congdongweb'),
        'edit_item' => __('Edit Receipt', 'congdongweb'),
        'update_item' => __('Update Receipt', 'congdongweb'),
        'view_item' => __('View Receipt', 'congdongweb'),
        'view_items' => __('View Receipt', 'congdongweb'),
        'search_items' => __('Search Receipt', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Receipt', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Receipt', 'congdongweb'),
        'items_list' => __('Receipt list', 'congdongweb'),
        'items_list_navigation' => __('Receipt list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Receipt list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Receipt', 'congdongweb'),
        'description' => __('Phiếu thu', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('receipt', $args);
}
add_action('init', 'create_receipt_cpt', 0);

// Register Custom Post Type Receipt Detail
// Post Type Key: Receipt Detail
function create_receipt_detail_cpt()
{

    $labels = array(
        'name' => _x('Receipt Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Receipt Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Receipt Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Receipt Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Receipt Detail', 'congdongweb'),
        'attributes' => __('Receipt Detail', 'congdongweb'),
        'parent_item_colon' => __('Receipt Detail', 'congdongweb'),
        'all_items' => __('All Receipt Detail', 'congdongweb'),
        'add_new_item' => __('Add New Receipt Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Receipt Detail', 'congdongweb'),
        'edit_item' => __('Edit Receipt Detail', 'congdongweb'),
        'update_item' => __('Update Receipt Detail', 'congdongweb'),
        'view_item' => __('View Receipt Detail', 'congdongweb'),
        'view_items' => __('View Receipt Detail', 'congdongweb'),
        'search_items' => __('Search Receipt Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Receipt Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Receipt Detail', 'congdongweb'),
        'items_list' => __('Receipt Detail list', 'congdongweb'),
        'items_list_navigation' => __('Receipt Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Receipt Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Receipt Detail', 'congdongweb'),
        'description' => __('Phiếu thu chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('receipt-detail', $args);
}
add_action('init', 'create_receipt_detail_cpt', 0);


// Register Custom Post Type Payment
// Post Type Key: Payment
function create_payment_cpt()
{

    $labels = array(
        'name' => _x('Payment', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Payment', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Payment', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Payment', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Payment', 'congdongweb'),
        'attributes' => __('Payment', 'congdongweb'),
        'parent_item_colon' => __('Payment', 'congdongweb'),
        'all_items' => __('All Payment', 'congdongweb'),
        'add_new_item' => __('Add New Payment', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Payment', 'congdongweb'),
        'edit_item' => __('Edit Payment', 'congdongweb'),
        'update_item' => __('Update Payment', 'congdongweb'),
        'view_item' => __('View Payment', 'congdongweb'),
        'view_items' => __('View Payment', 'congdongweb'),
        'search_items' => __('Search Payment', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Payment', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Payment', 'congdongweb'),
        'items_list' => __('Payment list', 'congdongweb'),
        'items_list_navigation' => __('Payment list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Payment list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Payment', 'congdongweb'),
        'description' => __('Phiếu chi', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('payment', $args);
}
add_action('init', 'create_payment_cpt', 0);

// Register Custom Post Type Payment Detail
// Post Type Key: Payment Detail
function create_payment_detail_cpt()
{

    $labels = array(
        'name' => _x('Payment Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Payment Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Payment Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Payment Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Payment Detail', 'congdongweb'),
        'attributes' => __('Payment Detail', 'congdongweb'),
        'parent_item_colon' => __('Payment Detail', 'congdongweb'),
        'all_items' => __('All Payment Detail', 'congdongweb'),
        'add_new_item' => __('Add New Payment Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Payment Detail', 'congdongweb'),
        'edit_item' => __('Edit Payment Detail', 'congdongweb'),
        'update_item' => __('Update Payment Detail', 'congdongweb'),
        'view_item' => __('View Payment Detail', 'congdongweb'),
        'view_items' => __('View Payment Detail', 'congdongweb'),
        'search_items' => __('Search Payment Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Payment Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Payment Detail', 'congdongweb'),
        'items_list' => __('Payment Detail list', 'congdongweb'),
        'items_list_navigation' => __('Payment Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Payment Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Payment Detail', 'congdongweb'),
        'description' => __('Phiếu thu chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('payment-detail', $args);
}
add_action('init', 'create_payment_detail_cpt', 0);

//Register Post Type SiteManager
function create_sitemanager_posttype()
{
    $labels = array(
        'name'                  => __('Kho Giao Diện',  'congdongtheme'),
        'singular_name'         => __('Kho Giao Diện',  'congdongtheme'),
        'menu_name'             => __('Kho Giao Diện', 'congdongtheme'),
        'name_admin_bar'        => __('Kho Giao Diện', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Kho Giao Diện', 'congdongtheme'),
        'description'           => __('Kho Giao Diện', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'has_archive' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite' => array(
            'slug' => 'kho-giao-dien', // use this slug instead of post type name
            'with_front' => false // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('site-managers', $args);
}
add_action('init', 'create_sitemanager_posttype', 0);

// Register Taxonomy SiteType
// Taxonomy Key: sitetype
function create_sitetype_tax()
{
    $labels = array(
        'name'              => _x('Loại sites', 'taxonomy general name', 'congdongtheme'),
        'singular_name'     => _x('Loại site', 'taxonomy singular name', 'congdongtheme'),
        'search_items'      => __('Search loại sites', 'congdongtheme'),
        'all_items'         => __('All loại sites', 'congdongtheme'),
        'parent_item'       => __('Parent loại site', 'congdongtheme'),
        'parent_item_colon' => __('Parent loại site:', 'congdongtheme'),
        'edit_item'         => __('Edit loại site', 'congdongtheme'),
        'update_item'       => __('Update loại site', 'congdongtheme'),
        'add_new_item'      => __('Add New loại site', 'congdongtheme'),
        'new_item_name'     => __('New loại site Name', 'congdongtheme'),
        'menu_name'         => __('Loại site', 'congdongtheme'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Loại sites', 'congdongtheme'),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => false,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'rewrite' => array(
            'slug' => 'mau-giao-dien', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_taxonomy('site-types', 'site-managers', $args);
}
add_action('init', 'create_sitetype_tax');
//Register Post Type Plugin
function create_plugin_posttype()
{
    $labels = array(
        'name' => __('Kho Plugin', 'congdongtheme'),
        'singular_name' => __('Kho Plugin', 'congdongtheme'),
        'menu_name' => __('Kho Plugin', 'congdongtheme'),
        'name_admin_bar' => __('Kho Plugin', 'congdongtheme'),
        'archives' => __('Item Archives', 'congdongtheme'),
        'attributes' => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon' => __('Parent Item:', 'congdongtheme'),
        'all_items' => __('Tất cả', 'congdongtheme'),
        'add_new_item' => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new' => __('Tạo mới', 'congdongtheme'),
        'new_item' => __('Thêm', 'congdongtheme'),
        'edit_item' => __('Sửa', 'congdongtheme'),
        'update_item' => __('Cập nhật', 'congdongtheme'),
        'view_item' => __('Xem', 'congdongtheme'),
        'view_items' => __('Xem', 'congdongtheme'),
        'search_items' => __('Tìm kiếm', 'congdongtheme'),
        'not_found' => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash' => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image' => __('Ảnh', 'congdongtheme'),
        'set_featured_image' => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image' => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item' => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list' => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list' => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label' => __('Kho Plugin', 'congdongtheme'),
        'description' => __('Kho Plugin', 'congdongtheme'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => True,
        'exclude_from_search' => True,
        'publicly_queryable' => True,
        'has_archive' => false,
        'public' => True,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false, 'rewrite' => array(
            'slug' => 'kho-plugin', // use this slug instead of post type name
            'with_front' => True // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('plugin', $args);
}
add_action('init', 'create_plugin_posttype', 0);

// Register Taxonomy Plugin Type
// Taxonomy Key: Plugin Type
function create_plugin_type_tax()
{
    $labels = array(
        'name' => _x('Loại plugin', 'taxonomy general name', 'congdongtheme'),
        'singular_name' => _x('Loại site', 'taxonomy singular name', 'congdongtheme'),
        'search_items' => __('Search loại plugin', 'congdongtheme'),
        'all_items' => __('All loại plugin', 'congdongtheme'),
        'parent_item' => __('Parent loại site', 'congdongtheme'),
        'parent_item_colon' => __('Parent loại site:', 'congdongtheme'),
        'edit_item' => __('Edit loại site', 'congdongtheme'),
        'update_item' => __('Update loại site', 'congdongtheme'),
        'add_new_item' => __('Add New loại site', 'congdongtheme'),
        'new_item_name' => __('New loại site Name', 'congdongtheme'),
        'menu_name' => __('Loại site', 'congdongtheme'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('Loại plugin', 'congdongtheme'),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => false,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => true,
        'rewrite' => array(
            'slug' => 'mau-plugin', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_taxonomy('plugin-type', 'plugin', $args);
}
add_action('init', 'create_plugin_type_tax');
//Register Post Type Domain
function create_domain_posttype()
{
    $labels = array(
        'name'                  => __('Domain',  'congdongtheme'),
        'singular_name'         => __('Domain',  'congdongtheme'),
        'menu_name'             => __('Domain', 'congdongtheme'),
        'name_admin_bar'        => __('Domain', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Domain', 'congdongtheme'),
        'description'           => __('Domain', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite' => array(
            'slug' => 'domain', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('domain', $args);
}
add_action('init', 'create_domain_posttype', 0);
// Register Custom Post Type Domain Detail
// Post Type Key: Domain Detail
function create_domain_detail_cpt()
{

    $labels = array(
        'name' => _x('Domain Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Domain Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Domain Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Domain Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Domain Detail', 'congdongweb'),
        'attributes' => __('Domain Detail', 'congdongweb'),
        'parent_item_colon' => __('Domain Detail', 'congdongweb'),
        'all_items' => __('All Domain Detail', 'congdongweb'),
        'add_new_item' => __('Add New Domain Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Domain Detail', 'congdongweb'),
        'edit_item' => __('Edit Domain Detail', 'congdongweb'),
        'update_item' => __('Update Domain Detail', 'congdongweb'),
        'view_item' => __('View Domain Detail', 'congdongweb'),
        'view_items' => __('View Domain Detail', 'congdongweb'),
        'search_items' => __('Search Domain Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Domain Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Domain Detail', 'congdongweb'),
        'items_list' => __('Domain Detail list', 'congdongweb'),
        'items_list_navigation' => __('Domain Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Domain Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Domain Detail', 'congdongweb'),
        'description' => __('Domain chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('domain-detail', $args);
}
add_action('init', 'create_domain_detail_cpt', 0);
// Register Custom Post Type Ticket
// Post Type Key: Ticket
function create_ticket_cpt()
{

    $labels = array(
        'name' => _x('Ticket', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Ticket', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Ticket', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Ticket', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Ticket', 'congdongweb'),
        'attributes' => __('Ticket', 'congdongweb'),
        'parent_item_colon' => __('Ticket', 'congdongweb'),
        'all_items' => __('All Ticket', 'congdongweb'),
        'add_new_item' => __('Add New Ticket', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Ticket', 'congdongweb'),
        'edit_item' => __('Edit Ticket', 'congdongweb'),
        'update_item' => __('Update Ticket', 'congdongweb'),
        'view_item' => __('View Ticket', 'congdongweb'),
        'view_items' => __('View Ticket', 'congdongweb'),
        'search_items' => __('Search Ticket', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Ticket', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Ticket', 'congdongweb'),
        'items_list' => __('Ticket list', 'congdongweb'),
        'items_list_navigation' => __('Ticket list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Ticket list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Ticket', 'congdongweb'),
        'description' => __('Ticket', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('ticket', $args);
}
add_action('init', 'create_ticket_cpt', 0);
// Register Custom Post Type Ticket Detail
// Post Type Key: Ticket Detail
function create_ticket_detail_cpt()
{

    $labels = array(
        'name' => _x('Ticket Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Ticket Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Ticket Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Ticket Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Ticket Detail', 'congdongweb'),
        'attributes' => __('Ticket Detail', 'congdongweb'),
        'parent_item_colon' => __('Ticket Detail', 'congdongweb'),
        'all_items' => __('All Ticket Detail', 'congdongweb'),
        'add_new_item' => __('Add New Ticket Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Ticket Detail', 'congdongweb'),
        'edit_item' => __('Edit Ticket Detail', 'congdongweb'),
        'update_item' => __('Update Ticket Detail', 'congdongweb'),
        'view_item' => __('View Ticket Detail', 'congdongweb'),
        'view_items' => __('View Ticket Detail', 'congdongweb'),
        'search_items' => __('Search Ticket Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Ticket Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Ticket Detail', 'congdongweb'),
        'items_list' => __('Ticket Detail list', 'congdongweb'),
        'items_list_navigation' => __('Ticket Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Ticket Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Ticket Detail', 'congdongweb'),
        'description' => __('Ticket Detail', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('ticket-detail', $args);
}
add_action('init', 'create_ticket_detail_cpt', 0);

// Register Custom Post Type Notification
// Post Type Key: Notification
function create_notification_cpt()
{

    $labels = array(
        'name' => _x('Notification', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Notification', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Notification', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Notification', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Notification', 'congdongweb'),
        'attributes' => __('Notification', 'congdongweb'),
        'parent_item_colon' => __('Notification', 'congdongweb'),
        'all_items' => __('All Notification', 'congdongweb'),
        'add_new_item' => __('Add New Notification', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Notification', 'congdongweb'),
        'edit_item' => __('Edit Notification', 'congdongweb'),
        'update_item' => __('Update Notification', 'congdongweb'),
        'view_item' => __('View Notification', 'congdongweb'),
        'view_items' => __('View Notification', 'congdongweb'),
        'search_items' => __('Search Notification', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Notification', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Notification', 'congdongweb'),
        'items_list' => __('Notification list', 'congdongweb'),
        'items_list_navigation' => __('Notification list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Notification list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Notification', 'congdongweb'),
        'description' => __('Notification', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('notification', $args);
}
add_action('init', 'create_notification_cpt', 0);

//Register Post Type Hosting
function create_hosting_posttype()
{
    $labels = array(
        'name'                  => __('Hosting',  'congdongtheme'),
        'singular_name'         => __('Hosting',  'congdongtheme'),
        'menu_name'             => __('Hosting', 'congdongtheme'),
        'name_admin_bar'        => __('Hosting', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Hosting', 'congdongtheme'),
        'description'           => __('Hosting', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite' => array(
            'slug' => 'hosting', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('hosting', $args);
}
add_action('init', 'create_hosting_posttype', 0);

// Register Custom Post Type Hosting Detail
// Post Type Key: Hosting Detail
function create_hosting_detail_cpt()
{

    $labels = array(
        'name' => _x('Hosting Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Hosting Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Hosting Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Hosting Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Hosting Detail', 'congdongweb'),
        'attributes' => __('Hosting Detail', 'congdongweb'),
        'parent_item_colon' => __('Hosting Detail', 'congdongweb'),
        'all_items' => __('All Hosting Detail', 'congdongweb'),
        'add_new_item' => __('Add New Hosting Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Hosting Detail', 'congdongweb'),
        'edit_item' => __('Edit Hosting Detail', 'congdongweb'),
        'update_item' => __('Update Hosting Detail', 'congdongweb'),
        'view_item' => __('View Hosting Detail', 'congdongweb'),
        'view_items' => __('View Hosting Detail', 'congdongweb'),
        'search_items' => __('Search Hosting Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Hosting Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Hosting Detail', 'congdongweb'),
        'items_list' => __('Hosting Detail list', 'congdongweb'),
        'items_list_navigation' => __('Hosting Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Hosting Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Hosting Detail', 'congdongweb'),
        'description' => __('Hosting chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('hosting-detail', $args);
}
add_action('init', 'create_hosting_detail_cpt', 0);

//Register Post Type Email
function create_email_posttype()
{
    $labels = array(
        'name'                  => __('Email',  'congdongtheme'),
        'singular_name'         => __('Email',  'congdongtheme'),
        'menu_name'             => __('Email', 'congdongtheme'),
        'name_admin_bar'        => __('Email', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Email', 'congdongtheme'),
        'description'           => __('Email', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite' => array(
            'slug' => 'email', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('email', $args);
}
add_action('init', 'create_email_posttype', 0);

// Register Custom Post Type Email Detail
// Post Type Key: Email Detail
function create_email_detail_cpt()
{

    $labels = array(
        'name' => _x('Email Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Email Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Email Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Email Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Email Detail', 'congdongweb'),
        'attributes' => __('Email Detail', 'congdongweb'),
        'parent_item_colon' => __('Email Detail', 'congdongweb'),
        'all_items' => __('All Email Detail', 'congdongweb'),
        'add_new_item' => __('Add New Email Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Email Detail', 'congdongweb'),
        'edit_item' => __('Edit Email Detail', 'congdongweb'),
        'update_item' => __('Update Email Detail', 'congdongweb'),
        'view_item' => __('View Email Detail', 'congdongweb'),
        'view_items' => __('View Email Detail', 'congdongweb'),
        'search_items' => __('Search Email Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Email Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Email Detail', 'congdongweb'),
        'items_list' => __('Email Detail list', 'congdongweb'),
        'items_list_navigation' => __('Email Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Email Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Email Detail', 'congdongweb'),
        'description' => __('Email chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('email-detail', $args);
}
add_action('init', 'create_email_detail_cpt', 0);

//Register Post Type Version
function create_version_posttype()
{
    $labels = array(
        'name'                  => __('Version',  'congdongtheme'),
        'singular_name'         => __('Version',  'congdongtheme'),
        'menu_name'             => __('Version', 'congdongtheme'),
        'name_admin_bar'        => __('Version', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Manager', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Manager', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Manager', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Version', 'congdongtheme'),
        'description'           => __('Version', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
        'rewrite' => array(
            'slug' => 'version', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('version', $args);
}
add_action('init', 'create_version_posttype', 0);
// Register Custom Post Type Version Detail
// Post Type Key: Version Detail
function create_version_detail_cpt()
{

    $labels = array(
        'name' => _x('Version Detail', 'Post Type General Name', 'congdongweb'),
        'singular_name' => _x('Version Detail', 'Post Type Singular Name', 'congdongweb'),
        'menu_name' => _x('Version Detail', 'Admin Menu text', 'congdongweb'),
        'name_admin_bar' => _x('Version Detail', 'Add New on Toolbar', 'congdongweb'),
        'archives' => __('Version Detail', 'congdongweb'),
        'attributes' => __('Version Detail', 'congdongweb'),
        'parent_item_colon' => __('Version Detail', 'congdongweb'),
        'all_items' => __('All Version Detail', 'congdongweb'),
        'add_new_item' => __('Add New Version Detail', 'congdongweb'),
        'add_new' => __('Add New', 'congdongweb'),
        'new_item' => __('New Version Detail', 'congdongweb'),
        'edit_item' => __('Edit Version Detail', 'congdongweb'),
        'update_item' => __('Update Version Detail', 'congdongweb'),
        'view_item' => __('View Version Detail', 'congdongweb'),
        'view_items' => __('View Version Detail', 'congdongweb'),
        'search_items' => __('Search Version Detail', 'congdongweb'),
        'not_found' => __('Not found', 'congdongweb'),
        'not_found_in_trash' => __('Not found in Trash', 'congdongweb'),
        'featured_image' => __('Featured Image', 'congdongweb'),
        'set_featured_image' => __('Set featured image', 'congdongweb'),
        'remove_featured_image' => __('Remove featured image', 'congdongweb'),
        'use_featured_image' => __('Use as featured image', 'congdongweb'),
        'insert_into_item' => __('Insert into Version Detail', 'congdongweb'),
        'uploaded_to_this_item' => __('Uploaded to this Version Detail', 'congdongweb'),
        'items_list' => __('Version Detail list', 'congdongweb'),
        'items_list_navigation' => __('Version Detail list navigation', 'congdongweb'),
        'filter_items_list' => __('Filter Version Detail list', 'congdongweb'),
    );

    $args = array(
        'label' => __('Version Detail', 'congdongweb'),
        'description' => __('Version chi tiết', 'congdongweb'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-admin-appearance',
        'supports' => array(),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'show_in_admin_bar' => false,
        'can_export' => false,
        'show_in_nav_menus' => false,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => false,
    );

    register_post_type('version-detail', $args);
}
add_action('init', 'create_version_detail_cpt', 0);



function create_license_post_type()
{
    register_post_type(
        'license',
        array(
            'labels'      => array(
                'name'          => __('Licenses'),
                'singular_name' => __('License'),
            ),
            'public'      => false,
            'has_archive' => false,
            'rewrite'     => array('slug' => 'licenses'),
            'show_in_rest' => true,
            'supports'    => array('title', 'editor', 'custom-fields'),
            'menu_icon'   => 'dashicons-admin-network',
        )
    );
}
add_action('init', 'create_license_post_type');
