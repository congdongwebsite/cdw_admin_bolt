<?php
defined('ABSPATH') || exit;

define('CDW_MAIN_VERSION', "1.1.1");

add_action('init', 'theme_global_setup');
add_action('wp_enqueue_scripts', 'congdongtheme_styles_scripts');
add_action('admin_enqueue_scripts', 'congdongtheme_admin_styles_scripts');


//Register Post Type vs Taxonomy
add_action('init', 'create_siteorder_posttype', 1);
//add_action('init', 'create_siteorderitem_posttype', 1);

//Show ACF Admin Column
add_filter('manage_edit-site-managers_sortable_columns', 'congdongtheme_edit_site_managers_columns_sortable');
add_action('pre_get_posts', 'congdongtheme_edit_site_managers_columns_sortable_num');
add_filter('manage_site-managers_posts_columns', 'congdongtheme_add_site_managers_custom_posts_columns');
add_action('manage_site-managers_posts_custom_column', 'congdongtheme_site_managers_columns_content', 10, 2);

add_filter('manage_edit-site-orders_sortable_columns', 'congdongtheme_edit_site_orders_columns_sortable');
add_action('pre_get_posts', 'congdongtheme_edit_site_orders_columns_sortable_num');
add_filter('manage_site-orders_posts_columns', 'congdongtheme_add_site_orders_custom_posts_columns');
add_action('manage_site-orders_posts_custom_column', 'congdongtheme_site_orders_columns_content', 10, 2);


add_filter('manage_edit-domain_sortable_columns', 'congdongtheme_edit_domain_columns_sortable');
add_action('pre_get_posts', 'congdongtheme_edit_domain_columns_sortable_num');
add_filter('manage_domain_posts_columns', 'congdongtheme_add_domain_custom_posts_columns');
add_action('manage_domain_posts_custom_column', 'congdongtheme_domain_columns_content', 10, 2);


//Display a custom taxonomy dropdown in admin
//Filter posts by taxonomy in admin
add_action('restrict_manage_posts', 'congdongtheme_filter_post_type_by_taxonomy');
add_filter('parse_query', 'congdongtheme_convert_id_to_term_in_query');
// switch widget, blog back to the old version
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
add_filter('use_widgets_block_editor', '__return_false');
add_filter('use_block_editor_for_post', '__return_false');

//Ajax
add_action('wp_ajax_nopriv_ajax_arc_site_managers_pagination_data', 'set_ajax_arc_site_managers_pagination_data');
add_action('wp_ajax_ajax_arc_site_managers_pagination_data', 'set_ajax_arc_site_managers_pagination_data');
add_action('wp_ajax_nopriv_ajax_arc_site_order_post', 'set_ajax_arc_site_order_post');
add_action('wp_ajax_ajax_arc_site_order_post', 'set_ajax_arc_site_order_post');
add_action('wp_ajax_ajax_congdongcontact', 'set_ajax_congdongcontact');
add_action('wp_ajax_nopriv_ajax_congdongcontact', 'set_ajax_congdongcontact');

//add_action('phpmailer_init', 'congdongtheme_smtp');
//căn giữ và thêm alt
add_action('after_setup_theme', 'custom_image_size');
add_action('add_attachment', 'congdongblog_set_image_meta_image_upload');
//shortcode
add_shortcode('slick_post', 'create_slickpost_shortcode'); //[slick_post title="" show_arrows="" show_dots="" infinite="" show_date="" show_cat=""  show_desktop="" show_tab="" show_mobile="" id_element="id_**" category_name="" cat="" post_type="post" post_status="publish" posts_per_page="5" offset="0" order="ASC" orderby="date" ignore_sticky_posts="true"]
add_shortcode('slick_image', 'create_slickimage_shortcode'); //[slick_image title="" url="" listid="" show_arrows="" show_dots="" infinite="" show_desktop="" show_tab="" show_mobile="" id_element="id_**"]
add_shortcode('lightslider_lightgallery', 'create_lightslider_lightgallery_shortcode'); //[lightslider_lightgallery id_element="id_**" show_desktop="" show_tab="" show_mobile="" ]
add_shortcode('lightgallery', 'create_lightgallery_shortcode'); //[lightgallery id_element="id_**" show_desktop="" show_tab="" show_mobile="" ]

//Schema

add_action('wp_head', 'shw_schema_site_manager');


