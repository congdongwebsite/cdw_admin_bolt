<?php
defined('ABSPATH') || exit;
class AjaxAdmin
{
    public function __construct()
    {
        require_once(ADMIN_THEME_URL . '/core/encryption.php');
        add_action('wp_ajax_nopriv_ajax_login',  array($this, 'func_login'));
        add_action('wp_ajax_nopriv_ajax_register',  array($this, 'func_register'));
        add_action('wp_ajax_ajax_unlock',  array($this, 'func_unlock'));
        add_action('wp_ajax_nopriv_ajax_forgot-password',  array($this, 'func_forgot_password'));
    }
    public function func_check_customer($email)
    {
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );
        $arr['meta_query'][] =
            array(
                'key' => 'email',
                'value' => $email,
                'compare' => 'like',
            );
        $id_customers = get_posts($arr);

        return count($id_customers) !== 0;
    }
    public function func_new_customer($user_id, $name, $phone, $email, $address)
    {
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
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
        } else return false;
    }
    public function func_login()
    {
        global $CDWRecaptcha, $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-login-nonce', 'security');
        $CDWRecaptcha->checkPost('login');

        $security = $_POST['security'];
        $Encryption = new Encryption();
        $password = $Encryption->decrypt($_POST['signin-password'], $security);

        $creds = array(
            'user_login'    => $_POST['signin-email'],
            'user_password' => $password,
            'remember'      => $_POST['signin-remember']
        );
        $user = wp_signon($creds, true);

        $customer_id = get_user_meta($user->ID, 'customer-id',  true);
        if (empty($customer_id) && !$CDWFunc->isAdministrator($user->ID)) {

            wp_send_json_error(['msg' => 'Tài khoản chưa tạo khách hàng vui lòng liên hệ quản trị viên']);
        }
        if (is_wp_error($user)) {
            wp_send_json_error(['msg' => $user->get_error_message()]);
        }

        wp_send_json_success(['user' => $user->ID, 'urlredirect' => '/admin/?module=profile&action=index']);

        wp_die();
    }
    public function func_unlock()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-lock-nonce', 'security');

        $userCurrent = wp_get_current_user();
        // print_r($_POST);exit
        $security = $_POST['security'];
        $Encryption = new Encryption();
        $password = $Encryption->decrypt($_POST['lock-password'], $security);

        if (!wp_check_password($password, $userCurrent->data->user_pass, $userCurrent->ID)) {
            wp_send_json_error(['msg' => 'Mật khẩu không đúng vui lòng thử lại']);
        }

        $CDWFunc->updateUserOption($userCurrent->ID, 'lock', false);
        wp_send_json_success(['msg' => 'Bạn sẽ được chuyển sang trang chủ']);

        wp_die();
    }
    public function func_register()
    {
        global $CDWRecaptcha, $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-register-nonce', 'security');
        $CDWRecaptcha->checkPost('register');
        $email = sanitize_email($_POST['signup-email']);

        if ($this->func_check_customer($email)) {
            wp_send_json_error(['msg' => "Đã có khách hàng với email trên, liên hệ quản trị để tạo tài khoản"]);
        }

        $security = $_POST['security'];
        $Encryption = new Encryption();
        $password = $Encryption->decrypt($_POST['signup-password'], $security);
        $passwordRe = $Encryption->decrypt($_POST['signup-password-re'], $security);

        $info = array();
        $info['user_nicename'] = $info['nickname'] =  $info['user_login'] = sanitize_user($_POST['signup-account']);
        $info['display_name'] = $info['first_name'] = sanitize_text_field($_POST['signup-name']);
        $info['user_pass'] = $password;
        $info['user_email'] = $email;

        if ($password != $passwordRe)
            wp_send_json_error(['msg' => 'Mật khẩu không khớp']);

        if (!preg_match('/(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}/', $password))
            wp_send_json_error(['msg' => 'Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự.']);

        //Register the user
        $user_register = wp_insert_user($info);
        if (is_wp_error($user_register)) {
            $error = $user_register->get_error_codes();
            if (in_array('empty_user_login', $error)) {
                wp_send_json_error(['msg' => $user_register->get_error_message('empty_user_login')]);
            } elseif (in_array('existing_user_login', $error)) {
                wp_send_json_error(['msg' => 'Tài khoản đã được đăng ký.']);
            } elseif (in_array('existing_user_email', $error)) {
                wp_send_json_error(['msg' => 'Email đã được sử dụng.']);
            }
        } else {

            $creds = array(
                'user_login'    => $_POST['signup-account'],
                'user_password' => $password,
                'remember'      => true
            );
            $user = wp_signon($creds, false);
            if (is_wp_error($user)) {
                wp_send_json_error(['msg' => $user->get_error_message()]);
            }

            $this->func_new_customer($user->ID, $user->display_name, '', $user->user_email, '');
            wp_send_json_success(['user' => $user->ID, 'urlredirect' => '/admin/?module=profile&action=index']);
        }
        wp_die();
    }
    public function func_forgot_password()
    {
        global  $CDWRecaptcha, $CDWEmail, $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-forgot-password-nonce', 'security');
        //$CDWRecaptcha->checkPost('forgot-password');

        if (!isset($_POST['email']) || empty($_POST['email'])) {
            wp_send_json_error(['msg' => 'Vui lòng nhập email tài khoản!']);
        }
        $email = $_POST['email'];
        $user = get_user_by('email', $email);
        if ($user !== false) {
            $random_password = $CDWFunc->generatePassword(12);
            $user_id = $user->ID;
            wp_set_password($random_password, $user_id);
            $CDWEmail->sendEmailUserForgotPassword($user_id, $random_password);
            wp_send_json_success(['msg' => 'Email khôi phục mật khẩu đã gửi tới email:<br>' . $_POST['email']]);
        } else {
            wp_send_json_error(['msg' => 'Email không tồn tại!']);
        }

        wp_send_json_success(['msg' => 'Email khôi phục mật khẩu đã gửi tới email:<br>' . $_POST['email']]);

        wp_die();
    }
}

new AjaxAdmin();
