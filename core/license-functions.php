<?php
if (!defined('ABSPATH')) exit;

function create_license_post_type()
{
    register_post_type(
        'license',
        array(
            'labels'      => array(
                'name'          => __('Licenses'),
                'singular_name' => __('License'),
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'licenses'),
            'show_in_rest' => true,
            'supports'    => array('title', 'editor', 'custom-fields'),
            'menu_icon'   => 'dashicons-admin-network',
        )
    );
}
add_action('init', 'create_license_post_type');

function license_api_init()
{
    register_rest_route('cdw/v1', '/license/verify', array(
        'methods' => 'POST',
        'callback' => 'verify_license_key',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('cdw/v1', '/plugin/connect', array(
        'methods' => 'POST',
        'callback' => 'cdw_plugin_connect_callback',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('cdw/v1', '/plugin/update-check', array(
        'methods' => 'POST',
        'callback' => 'cdw_plugin_update_check_callback',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('cdw/v1', '/plugin/info', array(
        'methods' => 'POST',
        'callback' => 'cdw_plugin_info_callback',
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'license_api_init');

function verify_license_key($request)
{
    $license_key = $request->get_param('license_key');
    $plugin_id = $request->get_param('plugin_id');
    $current_version = $request->get_param('version'); 

    if (empty($license_key) || empty($plugin_id)) {
        return new WP_Error('missing_params', 'Missing parameters', array('status' => 400));
    }

    $args = array(
        'post_type' => 'license',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_license_key',
                'value' => $license_key,
                'compare' => '=',
            ),
            array(
                'key' => '_plugin_id',
                'value' => $plugin_id,
                'compare' => '=',
            ),
        ),
    );

    $licenses = get_posts($args);

    if (empty($licenses)) {
        return new WP_Error('invalid_license', 'Invalid license key', array('status' => 403));
    }

    $license = $licenses[0];
    $expires_at = get_post_meta($license->ID, '_expires_at', true);
    $status = get_post_meta($license->ID, '_status', true);
    $license_type = get_post_meta($license->ID, '_license_type', true);
    $stored_version = get_post_meta($license->ID, '_version', true);

    if ($status !== 'active') {
        return new WP_Error('license_not_active', 'License is not active', array('status' => 403));
    }

    if (!empty($current_version) && !empty($stored_version) && version_compare($current_version, $stored_version, '<')) {
        return new WP_Error('version_mismatch', 'Plugin version is older than licensed version.', array('status' => 403));
    }

    if ($license_type === 'lifetime' || $license_type === 'free') {
        return new WP_REST_Response(array('status' => 'valid'), 200);
    }

    if (!empty($expires_at) && strtotime($expires_at) < time()) {
        return new WP_Error('license_expired', 'License has expired', array('status' => 403));
    }

    return new WP_REST_Response(array('status' => 'valid'), 200);
}

function cdw_plugin_connect_callback($request)
{
    $license_key = $request->get_param('license_key');
    $plugin_id = $request->get_param('plugin_id');

    if (empty($license_key) || empty($plugin_id)) {
        return new WP_Error('missing_params', 'Missing license key or plugin ID', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_id);

    if (empty($license_info)) {
        return new WP_Error('invalid_license', 'Invalid license key or plugin ID', array('status' => 403));
    }

    return new WP_REST_Response(array(
        'status' => 'connected',
        'license_info' => $license_info
    ), 200);
}

function cdw_plugin_update_check_callback($request)
{
    $plugin_id = $request->get_param('plugin_id');
    $license_key = $request->get_param('license_key');
    $current_version = $request->get_param('version');

    if (empty($plugin_id) || empty($license_key) || empty($current_version)) {
        return new WP_Error('missing_params', 'Missing plugin ID, license key, or current version', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_id);

    if (empty($license_info) || $license_info['status'] !== 'active') {
        return new WP_Error('invalid_license', 'Invalid or inactive license', array('status' => 403));
    }

    $plugin_post = get_post($plugin_id);
    if (!$plugin_post) {
        return new WP_Error('plugin_not_found', 'Plugin not found', array('status' => 404));
    }

    $latest_version = get_post_meta($license_info['id'], '_version', true);
    if (version_compare($latest_version, $current_version, '>')) {
        $args = array(
            'post_type' => 'version-detail',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => 'version',
                    'value' => $latest_version,
                    'compare' => '=',
                ),
            ),
        );
        $version_details_posts = get_posts($args);

        $download_url = '';
        if (!empty($version_details_posts)) {
            $download_url =  get_post_meta($version_details_posts[0]->ID, 'url', true);
        }

        return new WP_REST_Response(array(
            'status' => 'update_available',
            'new_version' => $latest_version,
            'download_url' => $download_url,
        ), 200);
    } else {
        return new WP_REST_Response(array(
            'status' => 'no_update',
            'message' => 'No update available.'
        ), 200);
    }
}

function cdw_plugin_info_callback($request)
{
    $license_key = $request->get_param('license_key');
    $plugin_id = $request->get_param('plugin_id');
    $slug = $request->get_param('slug');

    if (empty($license_key) || empty($plugin_id) || empty($slug)) {
        return new WP_Error('missing_params', 'Missing license key, plugin ID, or slug', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_id);

    if (empty($license_info) || $license_info['status'] !== 'active') {
        return new WP_Error('invalid_license', 'Invalid or inactive license', array('status' => 403));
    }

    $plugin_post = get_post($plugin_id);
    if (!$plugin_post || $plugin_post->post_type !== 'plugin') {
        return new WP_Error('plugin_not_found', 'Plugin not found or invalid post type', array('status' => 404));
    }

    $plugin_name = $plugin_post->post_title;

    $args = array(
        'post_type' => 'version',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'type',
                'value' => 'plugin',
                'compare' => '=',
            ),
            array(
                'key' => 'name',
                'value' => $plugin_name,
                'compare' => '=',
            ),
        ),
    );
    $version_posts = get_posts($args);

    $latest_version = '';
    $download_url = '';
    $changelog_content = '';

    if (!empty($version_posts)) {
        $version_post_id = $version_posts[0]->ID;
        $latest_version = get_post_meta($version_post_id, 'last-version', true);

        $detail_args = array(
            'post_type' => 'version-detail',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'version-id',
                    'value' => $version_post_id,
                    'compare' => '=',
                ),
                array(
                    'key' => 'version',
                    'value' => $latest_version,
                    'compare' => '=',
                ),
            ),
        );
        $detail_posts = get_posts($detail_args);

        if (!empty($detail_posts)) {
            $download_url = get_post_meta($detail_posts[0]->ID, 'url', true);
            $changelog_content = get_post_meta($detail_posts[0]->ID, 'note', true); 
        }
    }

    $response_data = array(
        'name'          => $plugin_post->post_title,
        'slug'          => $slug,
        'version'       => $latest_version,
        'author'        => get_post_meta($plugin_post->ID, 'author', true) ? get_post_meta($plugin_post->ID, 'author', true) : 'CongDongWeb',
        'homepage'      => get_post_meta($plugin_post->ID, 'homepage', true) ? get_post_meta($plugin_post->ID, 'homepage', true) : '',
        'download_link' => $download_url,
        'requires'      => get_post_meta($plugin_post->ID, 'requires_wp', true) ? get_post_meta($plugin_post->ID, 'requires_wp', true) : '',
        'tested'        => get_post_meta($plugin_post->ID, 'tested_up_to', true) ? get_post_meta($plugin_post->ID, 'tested_up_to', true) : '',
        'last_updated'  => $plugin_post->post_modified_gmt,
        'sections'      => array(
            'description' => $plugin_post->post_content, 
            'changelog'   => !empty($changelog_content) ? $changelog_content : 'No changelog available.',
        ),
        'banners'       => array(
            // 'low' => 'URL_TO_LOW_RES_BANNER',
            // 'high' => 'URL_TO_HIGH_RES_BANNER',
        ),
    );

    return new WP_REST_Response($response_data, 200);
}

function generate_unique_license_key()
{
    do {
        $license_key = 'CDW-' . strtoupper(wp_generate_password(12, false));
    } while (get_posts(array('post_type' => 'license', 'meta_key' => '_license_key', 'meta_value' => $license_key)));

    return $license_key;
}

function cdw_get_license_info($license_key, $plugin_id)
{
    $args = array(
        'post_type'  => 'license',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => '_license_key',
                'value'   => $license_key,
                'compare' => '=',
            ),
            array(
                'key'     => '_plugin_id',
                'value'   => $plugin_id,
                'compare' => '=',
            ),
        ),
    );

    $licenses = get_posts($args);

    if (empty($licenses)) {
        return null;
    }

    $license = $licenses[0];
    $plugin = get_post($plugin_id);

    $license_info = array(
        'id'          => $license->ID,
        'title'       => $license->post_title,
        'key'         => get_post_meta($license->ID, '_license_key', true),
        'plugin_id'   => get_post_meta($license->ID, '_plugin_id', true),
        'plugin_name' => $plugin ? $plugin->post_title : 'N/A',
        'type'        => get_post_meta($license->ID, '_license_type', true),
        'starts_at'   => get_post_meta($license->ID, '_starts_at', true),
        'expires_at'  => get_post_meta($license->ID, '_expires_at', true),
        'status'      => get_post_meta($license->ID, '_status', true),
        'version'     => get_post_meta($license->ID, '_version', true),
    );

    return $license_info;
}

/**
 * Creates a new license entry in the WordPress database.
 *
 * @param int    $plugin_id    The ID of the plugin this license is for.
 * @param string $license_type The type of license. Accepts 'free' or 'premium'.
 * @param string $duration     The duration of the license. Accepts values like '1 year', '6 months', 'lifetime', or 'free'.
 * @param string $status       Optional. The status of the license. Accepts 'active' or 'inactive'. Default is 'active'.
 * @param string $title        Optional. The title for the license post. If empty, it will be generated from the plugin title.
 * @param string $starts_at    Optional. The start date of the license in 'YYYY-MM-DD' format. If empty, defaults to the current date.
 * @param string $version      Optional. The version associated with the license.
 * @return int|WP_Error The ID of the created license post on success, or a WP_Error object on failure.
 */
function cdw_create_license($plugin_id, $license_type, $duration, $status = 'active', $title = '', $starts_at = '', $version = '')
{
    if (empty($starts_at)) {
        $starts_at = date('Y-m-d');
    }

    $expires_at = '';
    if ($duration !== 'lifetime' && $duration !== 'free') {
        $expires_at = date('Y-m-d', strtotime("+$duration", strtotime($starts_at)));
    }

    if (empty($title)) {
        $plugin = get_post($plugin_id);
        $title = $plugin ? $plugin->post_title . ' License' : 'License';
    }

    $post_data = array(
        'post_title' => $title,
        'post_type' => 'license',
        'post_status' => 'publish',
    );

    $license_id = wp_insert_post($post_data);

    if (is_wp_error($license_id)) {
        return $license_id;
    }

    if ($license_id) {
        update_post_meta($license_id, '_license_key', generate_unique_license_key());
        update_post_meta($license_id, '_plugin_id', $plugin_id);
        update_post_meta($license_id, '_license_type', $license_type);
        update_post_meta($license_id, '_starts_at', $starts_at);
        update_post_meta($license_id, '_expires_at', $expires_at);
        update_post_meta($license_id, '_status', $status);
        update_post_meta($license_id, '_version', $version);
    }

    return $license_id;
}

function cdw_handle_license_action()
{
    check_ajax_referer('cdw_license_ajax_nonce', 'nonce');

    $action = $_POST['license_action'];
    $license_id = isset($_POST['license_id']) ? intval($_POST['license_id']) : 0;

    switch ($action) {
        case 'get_licenses':
            $licenses_query = get_posts(array('post_type' => 'license', 'posts_per_page' => -1));
            $licenses = array();
            foreach ($licenses_query as $license) {
                $plugin_id = get_post_meta($license->ID, '_plugin_id', true);
                $module_id = get_post_meta($license->ID, '_module_id', true);
                $plugin = get_post($plugin_id);
                $module = get_post($module_id);
                $licenses[] = array(
                    'id' => $license->ID,
                    'title' => $license->post_title,
                    'key' => get_post_meta($license->ID, '_license_key', true),
                    'plugin_id' => $plugin ? $plugin->ID : 'N/A',
                    'plugin_name' => $plugin ? $plugin->post_title : 'N/A',
                    'module_id' => $module ? $module->ID : 'N/A',
                    'module_name' => $module ? $module->post_title : 'N/A',
                    'type' => get_post_meta($license->ID, '_license_type', true),
                    'starts_at' => get_post_meta($license->ID, '_starts_at', true),
                    'expires_at' => get_post_meta($license->ID, '_expires_at', true),
                    'status' => get_post_meta($license->ID, '_status', true),
                    'version' => get_post_meta($license->ID, '_version', true),
                );
            }
            wp_send_json_success($licenses);
            break;
        case 'get_version_details_by_module':
            $module_id = isset($_POST['module_id']) ? intval($_POST['module_id']) : 0;
            if ($module_id === 0) {
                wp_send_json_error(array('message' => 'Module ID is missing.'));
            }

            $version_details_query = get_posts(array(
                'post_type' => 'version-detail',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'version-id',
                        'value' => $module_id,
                        'compare' => '=',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            $version_details = array();
            foreach ($version_details_query as $vd_post) {
                $version_details[] = array(
                    'id' => $vd_post->ID,
                    'version' => get_post_meta($vd_post->ID, 'version', true),
                    'url' => get_post_meta($vd_post->ID, 'url', true),
                    'date' => get_post_meta($vd_post->ID, 'date', true),
                    'note' => get_post_meta($vd_post->ID, 'note', true),
                );
            }
            wp_send_json_success($version_details);
            break;
        case 'get_details':
            $license = get_post($license_id);
            $stored_version_string = get_post_meta($license->ID, '_version', true);
            $version_detail_id_for_form = '';
            $version_module_id_for_form = '';

            if (!empty($stored_version_string)) {
                $args = array(
                    'post_type' => 'version-detail',
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'meta_query' => array(
                        array(
                            'key' => 'version',
                            'value' => $stored_version_string,
                            'compare' => '=',
                        ),
                    ),
                );
                $version_details_posts = get_posts($args);

                if (!empty($version_details_posts)) {
                    $found_vd_post = $version_details_posts[0];
                    $version_detail_id_for_form = $found_vd_post->ID;
                    $version_module_id_for_form = get_post_meta($found_vd_post->ID, 'version-id', true);
                }
            }

            $details = array(
                'id' => $license->ID,
                'title' => $license->post_title,
                'plugin_id' => get_post_meta($license->ID, '_plugin_id', true),
                'module_id' => get_post_meta($license->ID, '_module_id', true),
                'type' => get_post_meta($license->ID, '_license_type', true),
                'starts_at' => get_post_meta($license->ID, '_starts_at', true),
                'status' => get_post_meta($license->ID, '_status', true),
                'version' => $version_detail_id_for_form
            );
            wp_send_json_success($details);
            break;
        case 'delete':
            $deleted = wp_delete_post($license_id, true);
            if ($deleted) {
                wp_send_json_success(array('message' => 'License deleted successfully.'));
            } else {
                wp_send_json_error(array('message' => 'Failed to delete license.'));
            }
            break;
        case 'activate':
            update_post_meta($license_id, '_status', 'active');
            wp_send_json_success();
            break;
        case 'deactivate':
            update_post_meta($license_id, '_status', 'inactive');
            wp_send_json_success();
            break;
        case 'renew':
            $current_expires_at = get_post_meta($license_id, '_expires_at', true);
            $duration = isset($_POST['duration']) ? sanitize_text_field($_POST['duration']) : '1 year'; // Default to 1 year
            $new_expires_at = '';

            if ($duration === 'lifetime' || $duration === 'free') {
                $new_expires_at = ''; // No expiration date
            } else {
                $renewal_start_date = time(); // Default to current time

                if (!empty($current_expires_at) && strtotime($current_expires_at) > $renewal_start_date) {
                    // If not expired, renew from current expiration date
                    $renewal_start_date = strtotime($current_expires_at);
                }
                $new_expires_at = date('Y-m-d', strtotime("+$duration", $renewal_start_date));
            }

            update_post_meta($license_id, '_expires_at', $new_expires_at);
            update_post_meta($license_id, '_status', 'active'); // Set status to active on renewal
            wp_send_json_success(array('message' => 'License renewed successfully.', 'new_expires_at' => $new_expires_at));
            break;
        case 'save':
            $license_title = sanitize_text_field($_POST['license_title']);
            $plugin_id = intval($_POST['plugin_id']);
            $license_type = sanitize_text_field($_POST['license_type']);
            $duration = sanitize_text_field($_POST['duration']);
            $starts_at = sanitize_text_field($_POST['starts_at']);
            $status = sanitize_text_field($_POST['status']);
            $module_id = sanitize_text_field($_POST['module_id']);
            $version_detail_id = intval($_POST['version_detail_id']);

            $version_string = '';
            if ($version_detail_id) {
                $version_string = get_post_meta($version_detail_id, 'version', true);
            }

            $expires_at = '';
            if ($duration !== 'lifetime' && $duration !== 'free') {
                $expires_at = date('Y-m-d', strtotime("+$duration", strtotime($starts_at)));
            }

            $post_data = array(
                'post_title' => $license_title,
                'post_type' => 'license',
                'post_status' => 'publish',
            );

            if ($license_id > 0) {
                $post_data['ID'] = $license_id;
                wp_update_post($post_data);
            } else {
                $license_id = wp_insert_post($post_data);
                if ($license_id) {
                    update_post_meta($license_id, '_license_key', generate_unique_license_key());
                }
            }

            if ($license_id) {
                update_post_meta($license_id, '_plugin_id', $plugin_id);
                update_post_meta($license_id, '_module_id', $module_id);
                update_post_meta($license_id, '_license_type', $license_type);
                update_post_meta($license_id, '_starts_at', $starts_at);
                update_post_meta($license_id, '_expires_at', $expires_at);
                update_post_meta($license_id, '_status', $status);
                update_post_meta($license_id, '_version', $version_string);
            }
            wp_send_json_success(array('license_id' => $license_id));
            break;
    }

    wp_send_json_error();
}
add_action('wp_ajax_cdw_handle_license_action', 'cdw_handle_license_action');
