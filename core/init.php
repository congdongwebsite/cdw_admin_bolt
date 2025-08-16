<?php
defined('ABSPATH') || exit;
global $CDWFunc, $CDWNotification, $CDWEmail, $CDWTMPL, $moduleCurrent, $CDWConst, $blogInfo, $CDWCart, $CDWTicket, $CDWUser, $CDWRecaptcha, $CDWQRCode;
define('URL_HOME', 'https://dev2.congdongweb.com');
define('URL_ADMIN', 'admin');
define('ADMIN_THEME_URL', get_stylesheet_directory() . '/templates/admin');
define('ADMIN_THEME_URL_F', get_template_directory_uri() . '/templates/admin');
define('ADMIN_CHILD_THEME_URL_F', get_stylesheet_directory_uri() . '/templates/admin');
define('MODULE_ADMIN', isset($_GET['module']) ? $_GET['module'] : '');
define('ACTION_ADMIN', isset($_GET['action']) ? $_GET['action'] : '');
define('SUBACTION_ADMIN', isset($_GET['subaction']) ? $_GET['subaction'] : '');

define('APIINETURL', "dms.inet.vn/api");
define('APIINETTOKEN', "C28EFE86F1BD78C6DB69343166DA94D851F5F768");


define('APIMOMOURL', "https://test-payment.momo.vn");
define('APIMOMOPARTNERCODE', "MOMO");
define('APIMOMOPARTNERNAME', "MOMOCENM20220730");
define('APIMOMOSTOREID', "MomoTestStore");
define('APIMOMOACCESSKEY', "F8BBA842ECF85");
define('APIMOMOSECRETKEY', "K951B6PE1waDMi640xX08PD3vg6EkVlz");
define('APIMOMOPUBLICKEY', "");
define('APIMOMOLANG', "vi");
define('APIMOMOTIMEOUT', 60);

define('CDW_VERSION', "4.15");
define('SERVER_IP', "139.99.37.67"); // Cũ 171.244.8.217
define('SMPT_HOST', "mail.congdongweb.com");
define('SMPT_PORT', "465");
define('SMPT_USERNAME', "admin@congdongweb.com");
define('EMAIL_TICKET', "support@congdongweb.com");
define('EMAIL_SUPPORT2', "alex.tran0712@gmail.com");
define('SMPT_PASSWORD', "123Zo123Zo123uong");
define('SMPT_FROMNAME', "Cộng Đồng Web");
define('SMPT_FROMEMAIL', "admin@congdongweb.com");
define('SMPT_SECURE', "ssl");

define('RECAPTCHA_URL', "https://www.google.com/recaptcha/api/siteverify");
define('RECAPTCHA_SITE', "6Ld18ZQfAAAAAB7s-AxcdOgWWZK2sj8u1PFPly-r");
define('RECAPTCHA_SECRET', "6Ld18ZQfAAAAAH7ihYMBftSQamB-xBzC3kUq2j7-");

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
