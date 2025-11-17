<?php

defined('ABSPATH') || exit;

define('THEME_URL', get_stylesheet_directory());
define('CORE', THEME_URL . '/core');
define('THEME_URL_F', get_template_directory_uri());
define('CHILD_THEME_URL_F', get_stylesheet_directory_uri());
require_once(get_stylesheet_directory() . '/templates/admin/core/init.php');
require_once(CORE . '/acf.php');
require_once(CORE . '/function.php');
require_once(CORE . '/function-ajax.php');
require_once(CORE . '/init.php');
require_once(CORE . '/shortcode.php');
require_once(CORE . '/shw-vote.php');
require_once('rewrite-info-image/rewrite-info-image.php');

require_once(THEME_URL . '/templates/libs/chat-app/php/users.php');
// Add custom Theme Functions here
add_action('wp_head', 'func_get_head', 99, 1);
function func_get_head()
{
?>
    <script src="https://mona.media/template/js/libs/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://mona.media/template/js/libs/swiper/swiper-bundle.min.css">


    <link rel="stylesheet" href="https://mona.media/template/js/libs/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://mona.media/template/js/libs/splide/splide.min.css">
    <script src="https://mona.media/template/js/libs/gsap/gsap.min.js"></script>
    <script src="https://mona.media/template/js/libs/gsap/ScrollTrigger.min.js"></script>
    <script src="https://mona.media/template/js/libs/splide/splide.min.js"></script>
<?php
}
add_action('wp_footer', 'func_get_contact_share', 99, 1);
function func_get_contact_share()
{
    require_once('contact-share.php');
    require_once('menu-popup.php');
}
//Ajax Template admin

add_action('wp_ajax_save_image_html', 'save_image_html');
function save_image_html()
{

    date_default_timezone_set('Asia/Kolkata');
    $u = wp_get_current_user();
    $basefile = trim($_POST['image']);
    $name = trim($_POST['name']);

    include_once('wp-admin/includes/image.php');

    $filename =   $name . date("mdYGis") . rand() . '.jpg';

    $upload_dir       = wp_upload_dir();
    $uploadfile      = str_replace('/', DIRECTORY_SEPARATOR, $upload_dir['path']) . DIRECTORY_SEPARATOR . $filename;

    file_put_contents($uploadfile, file_get_contents($basefile));

    $wp_filetype = wp_check_filetype(basename($filename), null);
    $pttachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_text_field($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $pttach_id = wp_insert_attachment($pttachment, $uploadfile);
    $imagenew = get_post($pttach_id);
    $fullsizepath = get_attached_file($imagenew->ID);
    $pttach_data = wp_generate_attachment_metadata($pttach_id, $fullsizepath);
    wp_update_attachment_metadata($pttach_id, $pttach_data);

    $query_images_args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => 8,
        'author' => $u->ID
    );
    $query_images = new WP_Query($query_images_args);
    $images = array();
    foreach ($query_images->posts as $image) {
        $images[] = wp_get_attachment_url($image->ID);
    }

    wp_send_json_success(['id' => $pttach_id, 'link' => wp_get_attachment_url($pttach_id), 'list' => $images]);

    wp_send_json_success(['id' => false]);
}

if ( ! function_exists( 'write_syslog' ) ) {
	function write_syslog( $message ): void {
		if ( function_exists( 'syslog' ) ) {
			openlog( "DEBUG", LOG_PID | LOG_PERROR, LOG_USER );
			syslog( LOG_INFO, $message );
			closelog();
		}
	}
}


class SHWAPI
{
    var $params = array();

    function __construct($params = array())
    {
        $this->params = $params;
        $this->init();
    }
    function init()
    {
        //add_filter( 'rest_url_prefix', array($this,'my_theme_api_slug')); 
        //flush_rewrite_rules(true);
        /* Require authentication for REST API usage */
        add_filter('rest_authentication_errors', array($this, 'rest_authentication_errors'));
        add_action('rest_api_init', array($this, 'register_rest_api_init'));
    }

    function my_theme_api_slug($slug)
    {
        return 'api';
    }
    function rest_authentication_errors($result)
    {
        if (!empty($result) || $_SERVER['REDIRECT_URL'] !== "/wp-json/cdw/v1") {
            return $result;
        }
        if (!is_user_logged_in() && $_SERVER['REDIRECT_URL'] !== "/wp-json/jwt-auth/v1/token" && $_SERVER['REDIRECT_URL'] !== "/wp-json/jwt-auth/v1/validate") {
            return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
        }
        return $result;
    }
    function register_rest_api_init()
    {
        register_rest_route('v1', '/profile', [
            'methods' => 'GET',
            'callback' => array($this, 'get_profile'),
            'permission_callback' => '__return_true',
        ]);
    }
    function get_profile($params)
    {
        $current_user = wp_get_current_user();
        $avatar = get_field('user_avatar', 'user_' . $current_user->ID);
        $profiles = new stdClass();
        $profiles->id = esc_html($current_user->ID);
        $profiles->userName = esc_html($current_user->user_login);
        $profiles->email = esc_html($current_user->user_email);
        $profiles->displayName = esc_html($current_user->display_name);
        $profiles->avatarUrl = $avatar['url'];

        return $profiles;
    }
}

$SHWAPI = new SHWAPI();

function my_customize_rest_cors()
{
    // preset option for allowed origins for our API server
    $allowed_origins = [
        'https://my.congdongweb.com/',
    ];
    $request_origin = isset($_SERVER['HTTP_ORIGIN'])
        ? $_SERVER['HTTP_ORIGIN']
        : null;
    // if there is no HTTP_ORIGIN, then set current site URL
    if (!$request_origin) {
        $request_origin = site_url('');
    }
    // a fallback value for allowed_origin we will send to the response header
    $allowed_origin = 'https://congdongweb.com/';
    // now determine if request is coming from allowed ones
    if (in_array($request_origin, $allowed_origins)) {
        $allowed_origin = $request_origin;
    }

    // print needed allowed origins
    header("Access-Control-Allow-Origin: {$allowed_origin}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    // if this is a preflight request
    if (
        isset($_SERVER['REQUEST_METHOD'])
        && $_SERVER['REQUEST_METHOD'] === 'OPTIONS'
    ) {
        // need preflight here
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        // add cache control for preflight cache
        // @link https://httptoolkit.tech/blog/cache-your-cors/
        header('Access-Control-Max-Age: 86400');
        header('Cache-Control: public, max-age=86400');
        header('Vary: origin');
        // just exit and CORS request will be okay
        // NOTE: We are exiting only when the OPTIONS preflight request is made
        // because the pre-flight only checks for response header and HTTP status code.
        exit(0);
    }
}
add_action('rest_api_init', 'my_customize_rest_cors', 15);


add_action('show_user_profile', 'cdw_user_edit_section', 999);
add_action('edit_user_profile', 'cdw_user_edit_section', 999);

function cdw_user_edit_section($user)
{
    $customer_id = get_user_meta($user->ID, 'customer-id', true);
?>
    <h3>Thông tin khách hàng</h3>
    <table class="form-table">
        <tr>
            <th><label for="customer-info">Chi tiết</label></th>
            <td>
                <div id="customer-info-wrapper">
                    <?php if (empty($customer_id)) : ?>
                        <p>Chưa có thông tin khách hàng.</p>
                        <button id="create-customer-btn" class="button" data-user-id="<?php echo esc_attr($user->ID); ?>" data-nonce="<?php echo wp_create_nonce('create_customer_for_user_' . $user->ID); ?>">
                            Tạo khách hàng
                        </button>
                    <?php else :
                        $registered = $user->user_registered;
                    ?>
                        <p>
                            <strong>Mã khách hàng:</strong> <?php echo esc_html($customer_id); ?><br>
                            <strong>Tên Khách hàng:</strong> <?php echo esc_html(get_post_meta($customer_id, 'name', true)); ?><br>
                            <strong>Thời gian tạo user:</strong> <?php echo date('Y-m-d H:i:s', strtotime($registered)); ?><br>
                            <strong>Thời gian tạo customer:</strong> <?php echo get_the_date('Y-m-d H:i:s', $customer_id); ?>
                        </p>
                        <button id="create-customer-btn" class="button" data-user-id="<?php echo esc_attr($user->ID); ?>" data-nonce="<?php echo wp_create_nonce('create_customer_for_user_' . $user->ID); ?>">
                            Tạo lại khách hàng
                        </button>
                    <?php endif; ?>
                </div>
                <div id="customer-ajax-response"></div>
            </td>
        </tr>
    </table>
<?php
}

add_action('admin_footer', 'cdw_create_customer_ajax_script');
function cdw_create_customer_ajax_script()
{
    $screen = get_current_screen();
    if (!$screen || ($screen->id !== 'user-edit' && $screen->id !== 'profile')) {
        return;
    }
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#create-customer-btn').on('click', function(e) {
                e.preventDefault();

                var button = $(this);
                var userId = button.data('user-id');
                var nonce = button.data('nonce');
                var responseContainer = $('#customer-ajax-response');

                button.prop('disabled', true);
                responseContainer.html('<p>Đang xử lý...</p>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'cdw_create_customer',
                        user_id: userId,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            responseContainer.html('<div class="updated notice is-dismissible"><p>' + response.data.message + '</p></div>');
                            location.reload();
                        } else {
                            responseContainer.html('<div class="error notice is-dismissible"><p>' + response.data.message + '</p></div>');
                            button.prop('disabled', false);
                        }
                    },
                    error: function() {
                        responseContainer.html('<div class="error notice is-dismissible"><p>Đã xảy ra lỗi. Vui lòng thử lại.</p></div>');
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php
}

add_action('wp_ajax_cdw_create_customer', 'cdw_create_customer_ajax');
function cdw_create_customer_ajax()
{
    if (
        !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'create_customer_for_user_' . $_POST['user_id'])
    ) {
        wp_send_json_error(['message' => 'Lỗi bảo mật.']);
    }

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    if (!current_user_can('edit_user', $user_id)) {
        wp_send_json_error(['message' => 'Bạn không có quyền thực hiện hành động này.']);
    }

    $user = get_userdata($user_id);
    if (!$user) {
        wp_send_json_error(['message' => 'Không tìm thấy người dùng.']);
    }

    $created = func_new_customer($user->ID, $user->display_name, '', $user->user_email, '');

    if ($created) {
        wp_send_json_success(['message' => 'Đã tạo khách hàng thành công.']);
    } else {
        wp_send_json_error(['message' => 'Khách hàng với email này đã tồn tại hoặc có lỗi xảy ra.']);
    }
}

add_action('init',  'add_rewrite_rule_don_hang');
function add_rewrite_rule_don_hang()
{
    add_rewrite_rule('^don-hang/([0-9]+)/?', 'index.php?pagename=don-hang&order-id=$matches[1]', 'top');
}

add_action('query_vars', 'query_vars_don_hang');
function query_vars_don_hang($query_vars)
{
    $query_vars[] = 'order-id';
    return $query_vars;
}

function kho_plugin_query_vars($vars)
{
    $vars[] = 'search';
    return $vars;
}
add_filter('query_vars', 'kho_plugin_query_vars');

function add_rewrite_rules_kho_plugin()
{
    add_rewrite_rule('^kho-plugin/page/([0-9]+)/?$', 'index.php?pagename=kho-plugin&paged=$matches[1]', 'top');
    add_rewrite_rule('^kho-plugin/search/([^/]+)/page/([0-9]+)/?$', 'index.php?pagename=kho-plugin&s=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule('^kho-plugin/search/([^/]+)/?$', 'index.php?pagename=kho-plugin&s=$matches[1]', 'top');
}
add_action('init', 'add_rewrite_rules_kho_plugin');

function func_new_customer($user_id, $name, $phone, $email, $address)
{
    $arr = array(
        'post_type' => 'customer',
        'post_status' => 'publish',
        'fields' => 'ids',
        'posts_per_page' => 1,
    );
    $arr['meta_query'][] =
        array(
            'key' => 'email',
            'value' => $email,
            'compare' => 'like',
        );
    $id_customers = get_posts($arr);

    if (count($id_customers) == 0) {
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish'
        );

        $id = wp_insert_post($arr);
        add_post_meta($id, 'name', $name);
        add_post_meta($id, 'email', $email);
        add_post_meta($id, 'address', $address);

        update_post_meta($id, 'user-id', $user_id);
        update_user_meta($user_id, 'customer-id', $id);
        return true;
    } else {
        update_post_meta($id_customers[0], 'user-id', $user_id);
        update_user_meta($user_id, 'customer-id', $id_customers[0]);
        return true;
    };
    return false;
}

function shw_pagination($wp_query)
{
    if ($wp_query->max_num_pages <= 1) {
        return;
    }

    if (get_query_var('paged')) {
        $paged = get_query_var('paged');
    } elseif (get_query_var('page')) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }

    $max   = intval($wp_query->max_num_pages);

    $links = paginate_links(array(
        'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
        'format'    => '',
        'current'   => max(1, $paged),
        'total'     => $max,
        'prev_text' => '&larr; Trang trước',
        'next_text' => 'Trang sau &rarr;',
        'type'      => 'list',
        'add_args'  => false,
    ));

    if ($links) {
        echo '<div class="pagination-wrapper">' . $links . '</div>';
    }
}

add_action('init', 'cdw_block_admin_access');
function cdw_block_admin_access()
{
    if (current_user_can('manage_options')) {
        return;
    }
    add_filter('show_admin_bar', '__return_false');

    $request_uri = $_SERVER['REQUEST_URI'];
    $is_login_page = in_array($GLOBALS['pagenow'], ['wp-login.php']);
    $is_admin_area = is_admin();

    $is_restricted_slug = (preg_match('#^/login/?#', $request_uri));

    if (($is_admin_area || $is_login_page || $is_restricted_slug) && !wp_doing_ajax()) {

        if ($is_login_page) {
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            if (
                in_array($action, ['logout', 'lostpassword', 'rp', 'resetpass']) ||
                (empty($action) && isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm')
            ) {
                return;
            }
        }

        wp_redirect(home_url());
        exit;
    }
}
