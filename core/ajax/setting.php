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

    private function _handle_file_upload($file_key, $user_id, $customer_id, $meta_key)
    {
        global $CDWFunc, $CDWConst;

        if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['size'] == 0) {
            return; // No file uploaded for this key
        }

        $file = $_FILES[$file_key];

        // Check file size
        if ($CDWConst->limit_file_size < $file['size']) {
            wp_send_json_error(['msg' => 'Tập tin ' . $file['name'] . ' có dung lượng lớn']);
        }

        // Check file type
        $file_name_parts = explode('.', $file['name']);
        $file_ext = end($file_name_parts);
        if (!$CDWFunc->is_valid_file_type($file_ext)) {
            wp_send_json_error(['msg' => 'Vui lòng tải lên hình ảnh PNG, JPG, JPEG, GIF cho ' . $file['name']]);
        }

        // Handle upload
        // For customer-related files, parent post ID is 0 (no specific post)
        $attachmentId = media_handle_upload($file_key, ($meta_key === 'avatar-custom' ? $user_id : 0));

        if (!is_wp_error($attachmentId)) {
            if ($meta_key === 'avatar-custom') {
                update_user_meta($user_id, $meta_key, $attachmentId);
            } else if ($customer_id) {
                update_post_meta($customer_id, $meta_key, $attachmentId);
            }
        } else {
            wp_send_json_error(['msg' => $attachmentId->get_error_code() . " " . $attachmentId->get_error_message()]);
        }
    }

    public function func_setting_base()
    {
        global $CDWFunc, $CDWEmail;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-setting-base-nonce', 'security');

        $user_id = wp_get_current_user()->ID;
        if ($user_id < 1)
            wp_send_json_error(['msg' => 'Không tìm thấy tài khoản cần cập nhật']);
        else {
            wp_update_user([
                'ID' => $user_id, // this is the ID of the user you want to update.
                'first_name' => isset($_POST['first-name']) ? $_POST['first-name'] : '',
                'user_email' => isset($_POST['email']) ? $_POST['email'] : '',
            ]);
            update_user_meta($user_id, 'phone', isset($_POST['phone']) ? $_POST['phone'] : '');

            // Common updates for both admin and non-admin
            update_user_meta($user_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : '');
            update_user_meta($user_id, 'birthdate', $CDWFunc->date->convertDateTime(isset($_POST['birthdate']) ? $_POST['birthdate'] : ''));
            update_user_meta($user_id, 'website', isset($_POST['website']) ? $_POST['website'] : '');

            $customer_id = get_user_meta($user_id, 'customer-id', true);

            if ($customer_id) {
                $kyc_status = get_post_meta($customer_id, 'status-kyc', true);
                if ($kyc_status != '3') {
                    update_post_meta($customer_id, 'name', isset($_POST['first-name']) ? $_POST['first-name'] : '');
                    update_post_meta($customer_id, 'dvhc_tp', isset($_POST['dvhc-tp']) ? $_POST['dvhc-tp'] : '');
                    update_post_meta($customer_id, 'dvhc_qh', isset($_POST['dvhc-qh']) ? $_POST['dvhc-qh'] : '');
                    update_post_meta($customer_id, 'dvhc_px', isset($_POST['dvhc-px']) ? $_POST['dvhc-px'] : '');
                    update_post_meta($customer_id, 'dvhc_tp_label', isset($_POST['dvhc-tp-label']) ? $_POST['dvhc-tp-label'] : '');
                    update_post_meta($customer_id, 'dvhc_qh_label', isset($_POST['dvhc-qh-label']) ? $_POST['dvhc-qh-label'] : '');
                    update_post_meta($customer_id, 'dvhc_px_label', isset($_POST['dvhc-px-label']) ? $_POST['dvhc-px-label'] : '');
                    update_post_meta($customer_id, 'address', isset($_POST['address']) ? $_POST['address'] : '');
                    update_post_meta($customer_id, 'cmnd', isset($_POST['cmnd_cccd']) ? $_POST['cmnd_cccd'] : '');
                    update_post_meta($customer_id, 'company_name', isset($_POST['company_name']) ? $_POST['company_name'] : '');
                    update_post_meta($customer_id, 'company_phone', isset($_POST['company_phone']) ? $_POST['company_phone'] : '');
                    update_post_meta($customer_id, 'mst', isset($_POST['company_tax_code']) ? $_POST['company_tax_code'] : '');
                    update_post_meta($customer_id, 'company_address', isset($_POST['company_address']) ? $_POST['company_address'] : '');
                    update_post_meta($customer_id, 'phone', isset($_POST['phone']) ? $_POST['phone'] : '');
                    update_post_meta($customer_id, 'gender', isset($_POST['gender']) ? $_POST['gender'] : '');
                    update_post_meta($customer_id, 'birthdate', $CDWFunc->date->convertDateTime(isset($_POST['birthdate']) ? $_POST['birthdate'] : ''));
                    update_post_meta($customer_id, 'website', isset($_POST['website']) ? $_POST['website'] : '');

                    update_post_meta($customer_id, 'status-kyc', '2');
                    cdw_create_customer_log($customer_id, 'Cập nhật thông tin', 'Người dùng đã cập nhật thông tin cá nhân.');
                    $CDWEmail->sendAdminNotificationCustomerUpdate($customer_id);

                    // Handle file uploads using the helper method
                    $this->_handle_file_upload('id_card_front', $user_id, $customer_id, 'id_card_front');
                    $this->_handle_file_upload('id_card_back', $user_id, $customer_id, 'id_card_back');
                }
                $this->_handle_file_upload('shw_file', $user_id, $customer_id, 'avatar-custom');
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

        $user_id = wp_get_current_user()->ID;

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

            $customer_id = get_user_meta($user_id, 'customer-id', true);


            if ($customer_id) {
                $kyc_status = get_post_meta($customer_id, 'status-kyc', true);
                if ($kyc_status != '3') {
                    update_post_meta($customer_id, 'email', isset($_POST['email']) ? $_POST['email'] : '');
                }
            }
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
