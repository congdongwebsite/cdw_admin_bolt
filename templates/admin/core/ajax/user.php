<?php
defined('ABSPATH') || exit;
class UserAdmin
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_frontend_login', array($this, 'func_login'));
        add_action('wp_ajax_nopriv_ajax_frontend_login', array($this, 'func_login'));
        add_action('wp_ajax_nopriv_ajax_frontend_register', array($this, 'func_register'));
    }
    public function func_register()
    {
        check_ajax_referer('ajax-register-nonce', 'security');
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $urlRedirect = sanitize_text_field($_POST['urlRedirect']);
        if ($this->func_check_customer($email, $phone)) {
            wp_send_json_error(['msg' => "Đã có khách hàng với email trên, liên hệ quản trị để tạo tài khoản"]);
        }

        $recaptcha = $_POST['grecaptcha'];
        if (!empty($recaptcha)) {
            $google_url = "https://www.google.com/recaptcha/api/siteverify";
            $secret = get_field('secret_key', 'option');
            $url = $google_url . '?secret=' . $secret . '&response=' . $recaptcha;

            $verifyResponse = file_get_contents($url);
            $responseData   = json_decode($verifyResponse);
            if (!$responseData->success) {
                wp_send_json_error(['msg' => 'reCAPTCHA không chính xác']);
                wp_die();
            }
        } else {
            wp_send_json_error(['msg' => 'Vui lòng hoàn thành reCAPTCHA']);
            wp_die();
        }

        $info = array();
        $info['user_nicename'] = $info['nickname'] =  $info['user_login'] = sanitize_user($_POST['username']);
        $info['display_name'] = $info['first_name'] = sanitize_text_field($_POST['name']);
        $info['user_pass'] = sanitize_text_field($_POST['password']);
        $info['user_email'] = $email;
        $address = sanitize_text_field($_POST['address']);
        // Register the user
        $user_id = wp_insert_user($info);
        if (is_wp_error($user_id)) {
            $error = $user_id->get_error_codes();
            if (in_array('empty_user_login', $error)) {
                wp_send_json_error(['msg' => $user_id->get_error_message('empty_user_login')]);
                wp_die();
            } elseif (in_array('existing_user_login', $error)) {
                wp_send_json_error(['msg' => 'Tài khoản đã được đăng ký.']);
                wp_die();
            } elseif (in_array('existing_user_email', $error)) {
                wp_send_json_error(['msg' => 'Email đã được sử dụng.']);
                wp_die();
            }
        } else {
            add_user_meta($user_id, 'phone',  $phone);
            add_user_meta($user_id, 'address', $address);
            $tp =  isset($_POST['tp']) ? $_POST['tp'] : '';
            $px =  isset($_POST['px']) ? $_POST['px'] : '';

            $this->func_new_customer($user_id, $info['first_name'], $phone,  $info['user_email'], $address, $tp, $px);
            $creds = array(
                'user_login'    => $info['nickname'],
                'user_password' => $info['user_pass'],
                'remember'      => true
            );

            $user = wp_signon($creds, false);

            if (is_wp_error($user)) {
                wp_send_json_error(['msg' => $user->get_error_message()]);
            }
        }
        wp_send_json_success(['msg' => 'Đăng ký thành công','urlRedirect' => $urlRedirect ?? home_url()]);

        wp_die();
    }
    public function func_check_customer($email, $phone)
    {
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );
        $arr['meta_query']['relation'] = 'OR';
        $arr['meta_query'][] =
            array(
                'key' => 'phone',
                'value' => $phone,
                'compare' => 'like',
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
    public function func_new_customer($user_id, $name, $phone, $email, $address, $tp, $px)
    {

        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );
        $arr['meta_query']['relation'] = 'OR';
        $arr['meta_query'][] =
            array(
                'key' => 'phone',
                'value' => $phone,
                'compare' => 'like',
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
            update_post_meta($id, 'name', $name);
            update_post_meta($id, 'phone', $phone);
            update_post_meta($id, 'email', $email);
            update_post_meta($id, 'dvhc_tp', $tp);
            update_post_meta($id, 'dvhc_px', $px);
            update_post_meta($id, 'address', $address);

            update_post_meta($id, 'user-id', $user_id);
            update_user_meta($user_id, 'customer-id', $id);
            return true;
        } else return false;
    }
    public function func_login()
    {
        global $CDWFunc;
        // check_ajax_referer('ajax-login-nonce', 'security');
        $urlRedirect = sanitize_text_field($_POST['urlRedirect']);
        $creds = array(
            'user_login'    => $_POST['username'],
            'user_password' => $_POST['password'],
            'remember'      =>  $_POST['remember']
        );

        $user = wp_signon($creds, true);

        if (is_wp_error($user)) {
            wp_send_json_error(['msg' => $user->get_error_message()]);
        }

        $customer_id =  get_user_meta($user->ID, 'customer-id', true);

        if (empty($customer_id) && !$CDWFunc->isAdministrator($user->ID)) {
            wp_send_json_error(['msg' => 'Tài khoản chưa tạo khách hàng vui lòng liên hệ quản trị viên']);
        }
        wp_clear_auth_cookie();

        wp_set_current_user($user->ID, $user->user_login);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login, $user);

        wp_send_json_success(['msg' => 'Đăng nhập thành công','urlRedirect' => $urlRedirect ?? home_url()]);

        wp_die();
    }
}

new UserAdmin();
