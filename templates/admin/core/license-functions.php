<?php
if (!defined('ABSPATH')) exit;

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
    $plugin_code = $request->get_param('plugin_code');
    $current_version = $request->get_param('version');

    if (empty($license_key) || empty($plugin_code)) {
        return new WP_Error('missing_params', 'Missing parameters', array('status' => 400));
    }

    $license = get_license_by_code($license_key);

    if (empty($license)) {
        return new WP_Error('invalid_license', 'Invalid license key', array('status' => 403));
    }

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
function get_license_by_code($code)
{
    $args = array(
        'post_type' => 'license',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_license_key',
                'value' => $code,
                'compare' => '=',
            ),
        ),
    );
    $licenses = get_posts($args);
    if (empty($licenses)) {
        return null;
    }
    return $licenses[0];
}

function cdw_get_plugin_id_by_code($plugin_code)
{
    if (empty($plugin_code)) {
        return null;
    }
    $args = array(
        'post_type'  => 'plugin',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key'     => 'code',
                'value'   => $plugin_code,
                'compare' => '=',
            ),
        ),
        'fields' => 'ids'
    );
    $plugins = get_posts($args);
    if (empty($plugins)) {
        return null;
    }
    return $plugins[0];
}

function cdw_plugin_connect_callback($request)
{
    $license_key = $request->get_param('license_key');
    $plugin_code = $request->get_param('plugin_code');

    if (empty($license_key) || empty($plugin_code)) {
        return new WP_Error('missing_params', 'Missing license key or plugin code', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_code);

    if (empty($license_info) || $license_info['status'] !== 'active') {
        return new WP_Error('invalid_license', 'Invalid license key or plugin code', array('status' => 403));
    }

    return new WP_REST_Response(array(
        'status' => 'connected',
        'license_info' => $license_info
    ), 200);
}

function cdw_plugin_update_check_callback($request)
{
    $plugin_code = $request->get_param('plugin_code');
    $license_key = $request->get_param('license_key');
    $current_version = $request->get_param('version');

    if (empty($plugin_code) || empty($license_key) || empty($current_version)) {
        return new WP_Error('missing_params', 'Missing plugin code, license key, or current version', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_code);

    if (empty($license_info) || $license_info['status'] !== 'active') {
        return new WP_Error('invalid_license', 'Invalid or inactive license', array('status' => 403));
    }

    if (version_compare($license_info['version'], $current_version, '>')) {
        return new WP_REST_Response(array(
            'status' => 'update_available',
            'new_version' => $license_info['version'],
            'download_url' => $license_info['version_url'],
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
    $plugin_code = $request->get_param('plugin_code');
    $slug = $request->get_param('slug');

    if (empty($license_key) || empty($plugin_code) || empty($slug)) {
        return new WP_Error('missing_params', 'Missing license key, plugin code, or slug', array('status' => 400));
    }

    $license_info = cdw_get_license_info($license_key, $plugin_code);

    if (empty($license_info) || $license_info['status'] !== 'active') {
        return new WP_Error('invalid_license', 'Invalid or inactive license', array('status' => 403));
    }

    $plugin_id = cdw_get_plugin_id_by_code($plugin_code);
    if (!$plugin_id) {
        return new WP_Error('plugin_not_found', 'Plugin not found for the given code', array('status' => 404));
    }

    $plugin_post = get_post($plugin_id);
    if (!$plugin_post || $plugin_post->post_type !== 'plugin') {
        return new WP_Error('plugin_not_found', 'Plugin not found or invalid post type', array('status' => 404));
    }

    $response_data = array(
        'name'          => $plugin_post->post_title,
        'slug'          => $slug,
        'version'       => $license_info['version'],
        'author'        => get_post_meta($plugin_post->ID, 'author', true) ? get_post_meta($plugin_post->ID, 'author', true) : 'CongDongWeb',
        'homepage'      => get_post_meta($plugin_post->ID, 'homepage', true) ? get_post_meta($plugin_post->ID, 'homepage', true) : '',
        'download_link' => $license_info['version_url'],
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

function cdw_get_license_info($license_key, $plugin_code)
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
                'key'     => '_plugin_code',
                'value'   => $plugin_code,
                'compare' => '=',
            ),
        ),
    );

    $licenses = get_posts($args);

    if (empty($licenses)) {
        return null;
    }

    $license = $licenses[0];

    $license_info = array(
        'id'          => $license->ID,
        'title'       => $license->post_title,
        'key'         => get_post_meta($license->ID, '_license_key', true),
        'plugin_code'   => get_post_meta($license->ID, '_plugin_code', true),
        'plugin_name'   => get_post_meta($license->ID, '_plugin_name', true),
        'type'        => get_post_meta($license->ID, '_license_type', true),
        'starts_at'   => get_post_meta($license->ID, '_starts_at', true),
        'expires_at'  => get_post_meta($license->ID, '_expires_at', true),
        'status'      => get_post_meta($license->ID, '_status', true),
        'version'     => get_post_meta($license->ID, '_version', true),
        'version_url' => get_post_meta($license->ID, '_version_url', true),
    );

    return $license_info;
}
function cdw_get_license_code($license_id)
{
    return get_post_meta($license_id, '_license_key', true);
}
/** 
 * Khởi tạo license mới cho plugin.
 * Nếu truyền customer_id, sẽ lưu license_id vào custom field 'license_ids' của customer và lưu customer_id vào license.
 * 
 * @param int    $plugin_id    ID plugin
 * @param string $license_type Loại license ('free', 'premium')
 * @param string $duration     Thời hạn ('1 year', '2 years', '3 years', 'lifetime', 'free')
 * @param string $status       Trạng thái ('active', 'inactive', ...)
 * @param string $title        Tiêu đề license (tùy chọn)
 * @param string $starts_at    Ngày bắt đầu (tùy chọn, định dạng 'Y-m-d')
 * @param string $version      Phiên bản plugin (tùy chọn)
 * @param int    $customer_id  ID customer (tùy chọn)
 * @return int|WP_Error        ID license vừa tạo hoặc WP_Error nếu lỗi
 */
function cdw_create_license($plugin_id, $license_type = 'free', $duration = 'free', $status = 'active', $title = '', $starts_at = '', $version = '', $customer_id = 0)
{
    if (empty($starts_at)) {
        $starts_at = date('Y-m-d');
    }
    switch ($duration) {
        case 'free':
            $duration = 'free';
            break;
        case '1':
            $duration = '1 year';
            break;
        case '2':
            $duration = '2 years';
            break;
        case '3':
            $duration = '3 years';
            break;
        case 'lifetime':
            $duration = 'lifetime';
            break;
        default:
            $duration = 'free';
            break;
    }
    switch ($license_type) {
        case 'free':
            $license_type = 'free';
            break;
        case 'premium':
            $license_type = 'premium';
            break;
        default:
            $license_type = 'free';
            break;
    }
    $expires_at = '';
    if ($duration !== 'lifetime' && $duration !== 'free') {
        $expires_at = date('Y-m-d', strtotime("+$duration", strtotime($starts_at)));
    }

    $plugin = get_post($plugin_id);
    if (!$plugin) return false;
    
    $plugin_code = get_post_meta($plugin_id, 'code', true);
    if (empty($plugin_code)) {
        return new WP_Error('plugin_missing_code', "Vui lòng cập nhật code cho plugin");
    }

    $plugin_name = $plugin ? $plugin->post_title . ' License' : 'License';
    if (empty($title)) {
        $title = $plugin_name;
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
    if (empty($version)) {
        if (cdw_get_last_version($plugin_id)) {
            $version = cdw_get_last_version($plugin_id);
        }
    }
    if ($license_id) {

        update_post_meta($license_id, '_license_key', generate_unique_license_key());
        update_post_meta($license_id, '_plugin_code', $plugin_code);
        update_post_meta($license_id, '_plugin_name', $plugin_name);
        update_post_meta($license_id, '_license_type', $license_type);
        update_post_meta($license_id, '_starts_at', $starts_at);
        update_post_meta($license_id, '_expires_at', $expires_at);
        update_post_meta($license_id, '_duration', $duration);
        update_post_meta($license_id, '_status', $status);
        update_post_meta($license_id, '_version', $version['version'] ?? '');
        update_post_meta($license_id, '_version_id', $version['id'] ?? '');
        update_post_meta($license_id, '_version_url', $version['url'] ?? '');

        if ($customer_id) {
            update_post_meta($license_id, '_customer_id', $customer_id);
        }
    }

    return $license_id;
}
function cdw_get_last_version($plugin_id)
{
    $id = get_post_meta($plugin_id, 'module_id', true);
    $args = array(
        'post_type' => 'version-detail',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'and',
            array(
                'key' =>  'version-id',
                'value' => $id,
                'compare' => '=',
            )
        )
    );
    $posts = get_posts($args);
    return isset($posts[0]) ? ['id' => $posts[0], 'version' => get_post_meta($posts[0], 'version', true), 'url' => get_post_meta($posts[0], 'url', true)] : false;
}
function cdw_handle_license_action()
{
    global $CDWFunc;
    check_ajax_referer('cdw_license_ajax_nonce', 'nonce');

    $action = $_POST['license_action'];
    $license_id = isset($_POST['license_id']) ? intval($_POST['license_id']) : 0;

    switch ($action) {
        case 'get_licenses':
            $licenses_query = get_posts(array('post_type' => 'license', 'posts_per_page' => -1));
            $licenses = array();
            foreach ($licenses_query as $license) {
                $plugin_code = get_post_meta($license->ID, '_plugin_code', true);
                $plugin_name = get_post_meta($license->ID, '_plugin_name', true);
                $plugin_id = cdw_get_plugin_id_by_code($plugin_code);
                $customer_id = get_post_meta($license->ID, '_customer_id', true);
                $customer_name = get_post_meta($customer_id, 'name', true);
                $starts_at = get_post_meta($license->ID, '_starts_at', true);
                $expires_at = get_post_meta($license->ID, '_expires_at', true);
                $starts_at = $CDWFunc->date->convertDateTimeDisplay($starts_at);
                $expires_at = $CDWFunc->date->convertDateTimeDisplay($expires_at);

                $licenses[] = array(
                    'id' => $license->ID,
                    'title' => $license->post_title,
                    'key' => get_post_meta($license->ID, '_license_key', true),
                    'customer_name' => $customer_name ? $customer_name : 'N/A',
                    'plugin_id' => $plugin_id ? $plugin_id : 'N/A',
                    'plugin_code' => $plugin_code,
                    'plugin_name' => $plugin_name ? $plugin_name : 'N/A',
                    'type' => get_post_meta($license->ID, '_license_type', true),
                    'starts_at' => $starts_at,
                    'expires_at' => $expires_at,
                    'duration' => get_post_meta($license->ID, '_duration', true),
                    'status' => get_post_meta($license->ID, '_status', true),
                    'version' => get_post_meta($license->ID, '_version', true),
                );
            }
            wp_send_json_success($licenses);
            break;
        case 'get_version_details_by_plugin':
            $plugin_id = isset($_POST['plugin_id']) ? intval($_POST['plugin_id']) : 0;
            if ($plugin_id === 0) {
                wp_send_json_error(array('message' => 'Module ID is missing.'));
            }

            $plugin_code = get_post_meta($plugin_id, 'code', true);
            if (empty($plugin_code)) {
                wp_send_json_error(array('msg' => "Vui lòng cập nhật code cho plugin"));
            }

            $module_id = get_post_meta($plugin_id, 'module_id', true);

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

            $starts_at = get_post_meta($license->ID, '_starts_at', true);
            $expires_at = get_post_meta($license->ID, '_expires_at', true);
            $starts_at = $CDWFunc->date->convertDateTime($starts_at, $CDWFunc->date->formatDB, 'Y-m-d');
            $plugin_code = get_post_meta($license->ID, '_plugin_code', true);
            $plugin_id = cdw_get_plugin_id_by_code($plugin_code);
            $details = array(
                'id' => $license->ID,
                'title' => $license->post_title,
                'plugin_id' => $plugin_id,
                'plugin_code' => $plugin_code,
                'customer_id' => get_post_meta($license->ID, '_customer_id', true),
                'type' => get_post_meta($license->ID, '_license_type', true),
                'starts_at' => $starts_at,
                'duration' => get_post_meta($license->ID, '_duration', true),
                'status' => get_post_meta($license->ID, '_status', true),
                'version_id' => get_post_meta($license->ID, '_version_id', true),
                'version_url' => get_post_meta($license->ID, '_version_url', true),
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
            $version_detail_id = intval($_POST['version_detail_id']);
            $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

            if ($plugin_id) {
                $plugin_code_check = get_post_meta($plugin_id, 'code', true);
                if (empty($plugin_code_check)) {
                    wp_send_json_error(array('msg' => "Vui lòng cập nhật code cho plugin"));
                }
            }

            $version_string = '';
            if ($version_detail_id) {
                $version_string = get_post_meta($version_detail_id, 'version', true);
                $version_detail_url = get_post_meta($version_detail_id, 'url', true);
            }
            if ($starts_at == '') {
                $starts_at = date('Y-m-d');
            }
            switch ($duration) {
                case 'free':
                    $quantity = 1;
                    break;
                case '1 year':
                    $quantity = '1';
                    break;
                case '2 years':
                    $quantity = '2';
                    break;
                case '3 years':
                    $quantity = '3';
                    break;
                case 'lifetime':
                    $quantity = 999;
                    break;
                default:
                    $quantity = 1;
                    break;
            }
            $starts_at = $CDWFunc->date->convertDateTime($starts_at, 'Y-m-d', $CDWFunc->date->formatDB);

            $expires_at = '';
            if ($duration !== 'lifetime' && $duration !== 'free') {
                $expires_at = $CDWFunc->date->addYears($starts_at, $quantity, $CDWFunc->date->formatDB, $CDWFunc->date->formatDB);
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
                    $code = generate_unique_license_key();
                    update_post_meta($license_id, '_license_key', $code);
                    if ($customer_id > 0) {

                        $plugin = get_post($plugin_id);

                        $price = (float) get_post_meta($plugin_id, 'price', true);
                        if (!$price) {
                            $price = 0;
                        }
                        $plugins = [
                            [
                                'name' => ($plugin ? $plugin->post_title : 'N/A'),
                                'plugin-type' => $plugin_id,
                                'price' => $price,
                                'date' => $starts_at,
                                'expiry_date' => $expires_at,
                                'license' => $code
                            ]
                        ];

                        if (is_array($plugins) && count($plugins) > 0) {
                            $pluginColumns = ['name', 'price', 'plugin-type', 'license'];
                            $pluginColumnDates = ['date', 'expiry_date'];
                            $plugins =  $CDWFunc->wpdb->func_new_detail_post('customer-plugin', 'customer-id', $customer_id, $plugins, $pluginColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-plugin', 'customer-id', $customer_id, $plugins, $pluginColumnDates);
                        }
                    } else {
                        delete_post_meta($license_id, '_customer_id');
                    }
                }
            }

            if ($license_id) {
                $plugin_code = get_post_meta($plugin_id, 'code', true);
                $plugin = get_post($plugin_id);
                $plugin_name = $plugin ? $plugin->post_title . ' License' : 'License';
                update_post_meta($license_id, '_plugin_code', $plugin_code);
                update_post_meta($license_id, '_plugin_name', $plugin_name);
                update_post_meta($license_id, '_license_type', $license_type);
                update_post_meta($license_id, '_starts_at', $starts_at);
                update_post_meta($license_id, '_expires_at', $expires_at);
                update_post_meta($license_id, '_duration', $duration);
                update_post_meta($license_id, '_status', $status);
                update_post_meta($license_id, '_version', $version_string);
                update_post_meta($license_id, '_version_id', $version_detail_id);
                update_post_meta($license_id, '_version_url', $version_detail_url);
                if ($customer_id > 0) {
                    update_post_meta($license_id, '_customer_id', $customer_id);
                } else {
                    delete_post_meta($license_id, '_customer_id');
                }
            }
            wp_send_json_success(array('license_id' => $license_id));
            break;
    }

    wp_send_json_error();
}
add_action('wp_ajax_cdw_handle_license_action', 'cdw_handle_license_action');
function cdw_get_version_id_by_string($license_id)
{
    $license = get_post($license_id);
    $stored_version_string = get_post_meta($license->ID, '_version', true);
    $version_detail_id_for_form = '';

    $plugin_code = get_post_meta($license->ID, '_plugin_code', true);
    $plugin_id = cdw_get_plugin_id_by_code($plugin_code);
    $module_id = get_post_meta($plugin_id, 'module_id', true);

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
                array(
                    'key' => 'version-id',
                    'value' => $module_id,
                    'compare' => '=',
                ),
            ),
        );
        $version_details_posts = get_posts($args);

        if (!empty($version_details_posts)) {
            $found_vd_post = $version_details_posts[0];
            $version_detail_id_for_form = $found_vd_post->ID;
        }
    }
    return $version_detail_id_for_form;
}
