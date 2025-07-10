<?php
defined('ABSPATH') || exit;
class SettingAdmin
{
    public function __construct()
    {
        global $CDWFunc;
        require_once(ADMIN_THEME_URL . '/core/encryption.php');
        $CDWFunc->getDataDVHC();
        add_action('wp_ajax_ajax_setting-base',  array($this, 'func_setting_base'));
        add_action('wp_ajax_ajax_setting-account',  array($this, 'func_setting_account'));
    }
    public function func_setting_base()
    {
        global $CDWFunc, $CDWConst;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-setting-base-nonce', 'security');

        $user_id = wp_get_current_user()->ID;
        if ($user_id < 1)
            wp_send_json_error(['msg' => 'Không tìm thấy tài khoản cần cập nhật']);
        else {
            wp_update_user([
                'ID' => $user_id, // this is the ID of the user you want to update.
                'first_name' => isset($_POST['first-name']) ? $_POST['first-name'] : '',
            ]);

            if ($CDWFunc->isAdministrator($user_id)) {

                update_user_meta($user_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : '');
                update_user_meta($user_id, 'birthdate', $CDWFunc->date->convertDateTime(isset($_POST['birthdate']) ? $_POST['birthdate'] : ''));
                update_user_meta($user_id, 'website', isset($_POST['website']) ? $_POST['website'] : '');
                update_user_meta($user_id, 'dvhc_tp', isset($_POST['dvhc-tp']) ? $_POST['dvhc-tp'] : '');
                update_user_meta($user_id, 'dvhc_qh', isset($_POST['dvhc-qh']) ? $_POST['dvhc-qh'] : '');
                update_user_meta($user_id, 'dvhc_px', isset($_POST['dvhc-px']) ? $_POST['dvhc-px'] : '');
                update_user_meta($user_id, 'address', isset($_POST['address']) ? $_POST['address'] : '');
            } else {
                
                $customer_id = get_user_meta($user_id, 'customer-id', true);
                update_user_meta($user_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : '');
                update_user_meta($user_id, 'birthdate', $CDWFunc->date->convertDateTime(isset($_POST['birthdate']) ? $_POST['birthdate'] : ''));
                update_user_meta($user_id, 'website', isset($_POST['website']) ? $_POST['website'] : '');
                update_post_meta($customer_id, 'name', isset($_POST['first-name']) ? $_POST['first-name'] : '');
                update_post_meta($customer_id, 'dvhc_tp', isset($_POST['dvhc-tp']) ? $_POST['dvhc-tp'] : '');
                update_post_meta($customer_id, 'dvhc_qh', isset($_POST['dvhc-qh']) ? $_POST['dvhc-qh'] : '');
                update_post_meta($customer_id, 'dvhc_px', isset($_POST['dvhc-px']) ? $_POST['dvhc-px'] : '');
                update_post_meta($customer_id, 'address', isset($_POST['address']) ? $_POST['address'] : '');
            }

            if (isset($_FILES) && 0 < count($_FILES)) {
                // check the file size
                if ($CDWConst->limit_file_size < $_FILES['shw_file']['size']) {
                    wp_send_json_error(['msg' => 'Tập tin có dung lượng lớn']);
                }
                // check the file type
                $file_name_parts = explode('.', $_FILES['shw_file']['name']);
                $file_ext = $file_name_parts[count($file_name_parts) - 1];
                if (!$CDWFunc->is_valid_file_type($file_ext)) {
                    wp_send_json_error(['msg' => 'Vui lòng tải lên hình ảnh PNG, JPG, JPEG, GIF']);
                }
                $attachmentId = media_handle_upload('shw_file', $user_id);
                if (!is_wp_error($attachmentId)) {
                    update_user_meta($user_id, 'avatar-custom', $attachmentId);
                } else {
                    wp_send_json_error(['msg' => $attachmentId->get_error_code() . " " . $attachmentId->get_error_message()]);
                }
            }
        }


        wp_send_json_success(['msg' => 'Cập nhật thông tin thành công']);

        wp_die();
    }
    public function func_setting_account()
    {
        global $CDWConst;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-setting-account-nonce', 'security');


        $security = $_POST['security'];
        $Encryption = new Encryption();
        $current_password = $Encryption->decrypt($_POST['current-password'], $security);
        $new_password = $Encryption->decrypt($_POST['new-password'], $security);
        $confirm_password = $Encryption->decrypt($_POST['confirm-password'], $security);

        $user_id = wp_get_current_user()->ID;

        if ($user_id < 1)
            wp_send_json_error(['msg' => 'Không tìm thấy tài khoản cần cập nhật']);
        else {
            wp_update_user([
                'ID' => $user_id, // this is the ID of the user you want to update.
                'user_email' => isset($_POST['email']) ? $_POST['email'] : '',
            ]);

            update_user_meta($user_id, 'phone', isset($_POST['phone']) ? $_POST['phone'] : '');

            if ($new_password != '') {
                if ($new_password != $confirm_password)
                    wp_send_json_error(['msg' => 'Mật khẩu không khớp']);

                if (!preg_match($CDWConst->preg_match_password, $new_password))
                    wp_send_json_error(['msg' => 'Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự.']);

                $user = get_user_by('id', $user_id);
                if (!$user || ($user && !wp_check_password($current_password, $user->data->user_pass, $user->ID))) {
                    wp_send_json_error(['msg' => 'Mật khẩu hiện tại không đúng.']);
                }
                wp_set_password($new_password,  $user_id);
            }
        }

        wp_send_json_success(['msg' => 'Cập nhật thông tin thành công']);

        wp_die();
    }
}

new SettingAdmin();
