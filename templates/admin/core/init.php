<?php
defined('ABSPATH') || exit;

if (!function_exists('congdongtheme_load_dotenv')) {
    function congdongtheme_load_dotenv($path)
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, ' \'"');

            if (getenv($name) === false) {
                putenv(sprintf('%s=%s', $name, $value));
            }
            if (!isset($_ENV[$name])) {
                $_ENV[$name] = $value;
            }
            if (!isset($_SERVER[$name])) {
                $_SERVER[$name] = $value;
            }
        }
    }
}

congdongtheme_load_dotenv(__DIR__ . '/../.env');

global $CDWFunc, $CDWNotification, $CDWEmail, $CDWTMPL, $moduleCurrent, $CDWConst, $blogInfo, $CDWCart, $CDWTicket, $CDWUser, $CDWRecaptcha, $CDWQRCode;
define('URL_HOME', get_home_url());
define('URL_ADMIN', 'admin');
define('ADMIN_THEME_URL', get_stylesheet_directory() . '/templates/admin');
define('ADMIN_THEME_URL_F', get_template_directory_uri() . '/templates/admin');
define('ADMIN_CHILD_THEME_URL_F', get_stylesheet_directory_uri() . '/templates/admin');
define('MODULE_ADMIN', isset($_GET['module']) ? $_GET['module'] : '');
define('ACTION_ADMIN', isset($_GET['action']) ? $_GET['action'] : '');
define('SUBACTION_ADMIN', isset($_GET['subaction']) ? $_GET['subaction'] : '');

define('APIINETURL', $_ENV['APIINETURL'] ?? null);
define('APIINETTOKEN', $_ENV['APIINETTOKEN'] ?? null);

define('APIMOMOURL', $_ENV['APIMOMOURL'] ?? null);
define('APIMOMOPARTNERCODE', $_ENV['APIMOMOPARTNERCODE'] ?? null);
define('APIMOMOPARTNERNAME', $_ENV['APIMOMOPARTNERNAME'] ?? null);
define('APIMOMOSTOREID', $_ENV['APIMOMOSTOREID'] ?? null);
define('APIMOMOACCESSKEY', $_ENV['APIMOMOACCESSKEY'] ?? null);
define('APIMOMOSECRETKEY', $_ENV['APIMOMOSECRETKEY'] ?? null);
define('APIMOMOPUBLICKEY', $_ENV['APIMOMOPUBLICKEY'] ?? null);
define('APIMOMOLANG', $_ENV['APIMOMOLANG'] ?? null);
define('APIMOMOTIMEOUT', $_ENV['APIMOMOTIMEOUT'] ?? null);

define('CDW_VERSION', $_ENV['CDW_VERSION'] ?? null);
define('SERVER_IP', $_ENV['SERVER_IP'] ?? null); // Cũ 171.244.8.217
define('SMPT_HOST', $_ENV['SMPT_HOST'] ?? null);
define('SMPT_PORT', $_ENV['SMPT_PORT'] ?? null);
define('SMPT_USERNAME', $_ENV['SMPT_USERNAME'] ?? null);
define('EMAIL_TICKET', $_ENV['EMAIL_TICKET'] ?? null);
define('EMAIL_SUPPORT2', $_ENV['EMAIL_SUPPORT2'] ?? null);
define('SMPT_PASSWORD', $_ENV['SMPT_PASSWORD'] ?? null);
define('SMPT_FROMNAME', $_ENV['SMPT_FROMNAME'] ?? null);
define('SMPT_FROMEMAIL', $_ENV['SMPT_FROMEMAIL'] ?? null);
define('SMPT_SECURE', $_ENV['SMPT_SECURE'] ?? null);

define('RECAPTCHA_URL', $_ENV['RECAPTCHA_URL'] ?? null);
define('RECAPTCHA_SITE', $_ENV['RECAPTCHA_SITE'] ?? null);
define('RECAPTCHA_SECRET', $_ENV['RECAPTCHA_SECRET'] ?? null);


define('DA_HOST', $_ENV['DA_HOST'] ?? null);
define('DA_USER', $_ENV['DA_USER'] ?? null);
define('DA_PASSWORD', $_ENV['DA_PASSWORD'] ?? null);
define('DA_DEFAULT_PORT', $_ENV['DA_DEFAULT_PORT'] ?? null);

require_once('qrcode/chillerlan/autoload.php');
require_once('uuid/uuid.php');

require_once('function-constant.php');
require_once('function-date.php');
require_once('function-wpdb.php');
require_once('function-number.php');
require_once('function-qrcode.php');
require_once('function-notification.php');
require_once('function-email.php');
require_once('function-ticket.php');
require_once('function-tmpl.php');
require_once('function-cart.php');
require_once('vn_charset_conversion.php');
require_once('function.php');
require_once('function-lock.php');
require_once('menu-admin.php');
require_once('register-post-type.php');
require_once('function-recaptcha.php');
require_once('license-functions.php');
//require_once('SQLServerConnection.php');

require_once('function-direct-admin-client.php');
require_once('function-inet-customer.php');
require_once('function-api-momo.php');



$server = '';
$database = '';
$username = '';
$password = '';
// tạo đối tượng SQLServerConnection
// $conn = new SQLServerConnection($server, $database, $username, $password);

// // kiểm tra kết nối
// if ($conn->isConnected()) {
//     // kết nối thành công
//     wp_send_json_success('Kết nối thành công!');
// } else {
//     // kết nối thất bại
//     wp_send_json_error('Kết nối thất bại!');
// }

$blogInfo = (object) ["name" => get_option('blogname'), "description" => get_option('blogdescription'), "logo" => esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))), "icon" => esc_url(wp_get_attachment_url(get_option('site_icon')))];
$CDWConst = new ConstantAdmin();
$CDWFunc = new FunctionAdmin();
$CDWNotification = new FunctionNotification();
$CDWEmail = new FunctionEmail(SMPT_HOST, SMPT_PORT, SMPT_USERNAME, SMPT_PASSWORD, SMPT_FROMEMAIL, SMPT_FROMNAME, SMPT_SECURE);
$CDWTMPL = new FunctionTMPL();
$CDWCart = new FunctionCart();
$CDWTicket = new FunctionTicket();
$CDWRecaptcha = new FunctionRecaptcha();
$CDWQRCode = new FunctionQRCode();

$CDWUser = $CDWFunc->wpdb->get_info_user(get_current_user_id());
$CDWTMPL->initTemplate([
    'cart-top-navbar-dot-template',
    'notification-top-navbar-dot-template',
    'notification-top-navbar-header-template',
    'notification-top-navbar-item-template',
    'notification-top-navbar-footer-template',
    'user-feature-info-template'
]);

$moduleCurrent = $CDWFunc->getModule(ACTION_ADMIN, MODULE_ADMIN);
require_once('ajax.php');