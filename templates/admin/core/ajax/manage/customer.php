<?php
defined('ABSPATH') || exit;
class AjaxCustomer
{
    private $postType = 'customer';
    public function __construct()
    {
        global $CDWFunc;
        $CDWFunc->getDataDVHC();
        add_action('wp_ajax_ajax_get-list-customer',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-customer',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_create-user-customer',  array($this, 'func_create_user'));
        add_action('wp_ajax_ajax_new-customer',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_new-image-customer',  array($this, 'func_new_image'));
        add_action('wp_ajax_ajax_update-customer',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_update-image-customer',  array($this, 'func_update_image'));
        add_action('wp_ajax_ajax_delete-customer',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_load-customer-hosting',  array($this, 'func_load_customer_hosting'));
        add_action('wp_ajax_ajax_load-customer-domain',  array($this, 'func_load_customer_domain'));
        add_action('wp_ajax_ajax_load-customer-theme',  array($this, 'func_load_customer_theme'));
        add_action('wp_ajax_ajax_load-customer-billing',  array($this, 'func_load_customer_billing'));
        add_action('wp_ajax_ajax_load-customer-email',  array($this, 'func_load_customer_email'));
        add_action('wp_ajax_ajax_load-customer-plugin',  array($this, 'func_load_customer_plugin'));
        add_action('wp_ajax_ajax_update-info-by-billing-customer',  array($this, 'func_update_info_by_billing_customer'));
        add_action('wp_ajax_ajax_load-customer-log',  array($this, 'func_load_customer_log'));
        add_action('wp_ajax_ajax_register_domain_to_inet',  array($this, 'func_register_domain_to_inet'));
        add_action('wp_ajax_ajax_sync_domain_inet_info',  array($this, 'func_sync_domain_inet_info'));
        add_action('wp_ajax_ajax_renew_domain_inet',  array($this, 'func_renew_domain_inet'));
        add_action('wp_ajax_ajax_reset_kyc_status',  array($this, 'func_reset_kyc_status'));
        add_action('wp_ajax_ajax_sync_customer_to_inet',  array($this, 'func_sync_customer_to_inet'));
        add_action('wp_ajax_ajax_change_email_plan', array($this, 'func_change_email_plan'));
        add_action('wp_ajax_ajax_check_email_domain_available_inet', array($this, 'func_check_email_domain_available_inet'));
        add_action('wp_ajax_ajax_get_email_records_inet', array($this, 'func_get_email_records_inet'));
        add_action('wp_ajax_ajax_create_email_package_inet', array($this, 'func_create_email_package_inet'));
        add_action('wp_ajax_ajax_get_email_detail_inet', array($this, 'func_get_email_detail_inet'));
        add_action('wp_ajax_ajax_sync_inet_emails', array($this, 'func_sync_inet_emails'));
        add_action('wp_ajax_ajax_gen_dkim_email_inet', array($this, 'func_gen_dkim_email_inet'));
        add_action('wp_ajax_ajax_reset_email_password_inet', array($this, 'func_reset_email_password_inet'));

        add_action('cdw_save_inet_email_details', array($this, '_save_inet_email_details'), 10, 2);
        add_action('rest_api_init', function () {
            register_rest_route('cdw/v1', '/momo-ipn', array(
                'methods' => 'POST',
                'callback' => array($this, 'handle_momo_ipn'),
                'permission_callback' => '__return_true',
            ));
        });
    }
    public function func_get_list()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-list-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $limit = $_POST['length'];
        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $limit,
            'offset' => $_POST['start']
        );
        $search = $_POST['search'];
        if (is_array($search) && $search['value'] != '') {
            $fieldSearch = ['name', 'phone', 'email', 'address', 'cmnd'];
            $arr['meta_query']['relation'] = 'OR';

            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search['value'],
                        'compare' => 'like',
                    );
            }
        }

        $wp = new WP_Query($arr);
        $posts = $wp->posts;
        $data = [];

        foreach ($posts as $post) {
            $urlredirect = $CDWFunc->getUrl('detail', 'customer', 'id=' . $post->ID);
            $id = $post->ID;
            $name = get_post_meta($post->ID, 'name', true);
            $phone = get_post_meta($post->ID, 'phone', true);
            $email = get_post_meta($post->ID, 'email', true);
            $dvhc_tp = get_post_meta($post->ID, 'dvhc_tp', true);
            $dvhc_qh = get_post_meta($post->ID, 'dvhc_qh', true);
            $dvhc_px = get_post_meta($post->ID, 'dvhc_px', true);
            $address = get_post_meta($post->ID, 'address', true);
            $cmnd = get_post_meta($post->ID, 'cmnd', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $id,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' =>  $address . ", " . $CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp),
                'cmnd' => $cmnd,

            ];
        }
        $response = array('success' => true);
        $response['data'] = $data;
        $response['draw'] = $_POST['draw'];
        $response['recordsTotal'] = $wp->found_posts;
        $response['recordsFiltered'] = $wp->found_posts;
        wp_send_json($response);
        wp_die();
    }
    public function func_new()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_insert_post($arr);

        if ($id) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $company_name = isset($_POST['company-name']) ? $_POST['company-name'] : '';
            $mst = isset($_POST['mst']) ? $_POST['mst'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $dvhc_tp = isset($_POST['dvhc-tp']) ? $_POST['dvhc-tp'] : '';
            $dvhc_qh = isset($_POST['dvhc-qh']) ? $_POST['dvhc-qh'] : '';
            $dvhc_px = isset($_POST['dvhc-px']) ? $_POST['dvhc-px'] : '';
            $address = isset($_POST['address']) ? $_POST['address'] : '';
            $cmnd = isset($_POST['cmnd']) ? $_POST['cmnd'] : '';
            $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
            $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $company_phone = isset($_POST['company-phone']) ? $_POST['company-phone'] : '';
            $company_address = isset($_POST['company-address']) ? $_POST['company-address'] : '';

            add_post_meta($id, 'name', $name);
            add_post_meta($id, 'company_name', $company_name);
            add_post_meta($id, 'mst', $mst);
            add_post_meta($id, 'phone', $phone);
            add_post_meta($id, 'email', $email);
            add_post_meta($id, 'dvhc_tp', $dvhc_tp);
            add_post_meta($id, 'dvhc_qh', $dvhc_qh);
            add_post_meta($id, 'dvhc_px', $dvhc_px);
            add_post_meta($id, 'dvhc_tp_label', isset($_POST['dvhc-tp-label']) ? $_POST['dvhc-tp-label'] : '');
            add_post_meta($id, 'dvhc_px_label', isset($_POST['dvhc-px-label']) ? $_POST['dvhc-px-label'] : '');
            add_post_meta($id, 'address', $address);
            add_post_meta($id, 'cmnd', $cmnd);
            add_post_meta($id, 'birthdate', $CDWFunc->date->convertDateTime($birthdate));
            add_post_meta($id, 'gender', $gender);
            add_post_meta($id, 'note', $note);
            add_post_meta($id, 'company_phone', $company_phone);
            add_post_meta($id, 'company_address', $company_address);

            //Hosting
            $hostings = isset($_POST['hostings']) ? $_POST['hostings'] : [];
            $hostingColumns = ['ip', 'port', 'user', 'pass', 'type', 'price'];
            $hostingColumnDates = ['buy_date', 'expiry_date'];
            $hostings = $CDWFunc->wpdb->func_new_detail_post('customer-hosting', 'customer-id', $id, $hostings, $hostingColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-hosting', 'customer-id', $id, $hostings, $hostingColumnDates);

            //Domain
            $domains = isset($_POST['domains']) ? $_POST['domains'] : [];
            $domainColumns = ['url', 'price', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type'];
            $domainColumnDates = ['buy_date', 'expiry_date'];
            $domains = $CDWFunc->wpdb->func_new_detail_post('customer-domain', 'customer-id', $id, $domains, $domainColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-domain', 'customer-id', $id, $domains, $domainColumnDates);

            foreach ($domains as $keyItem => $valueItem) {
                if (!empty($valueItem['domain-type'])) continue;
                $id_domain = $CDWFunc->wpdb->get_id_domain($valueItem['url']);
                if ($id_domain && !empty($valueItem['id']))
                    update_post_meta($valueItem['id'], "domain-type", $id_domain);
            }
            //Theme
            $themes = isset($_POST['themes']) ? $_POST['themes'] : [];
            $themeColumns = ['name', 'price', 'site-type'];
            $themeColumnDates = ['date'];
            $themes =  $CDWFunc->wpdb->func_new_detail_post('customer-theme', 'customer-id', $id, $themes, $themeColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-theme', 'customer-id', $id, $themes, $themeColumnDates);

            //Billing
            $billings = isset($_POST['billings']) ? $_POST['billings'] : [];
            $billingColumns = ['note', 'amount'];
            $billingColumnDates = ['date'];
            $billings = $CDWFunc->wpdb->func_new_detail_post('customer-billing', 'customer-id', $id, $billings, $billingColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-billing', 'customer-id', $id, $billings, $billingColumnDates);

            //Email
            $emails = isset($_POST['emails']) ? $_POST['emails'] : [];
            $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price', 'domain'];
            $emailColumnDates = ['buy_date', 'expiry_date'];
            $emails = $CDWFunc->wpdb->func_new_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);

            //Plugin
            $plugins = isset($_POST['plugins']) ? $_POST['plugins'] : [];
            $pluginColumns = ['name', 'price', 'plugin-type'];
            $pluginColumnDates = ['date', 'expiry_date'];
            $plugins =  $CDWFunc->wpdb->func_new_detail_post('customer-plugin', 'customer-id', $id, $plugins, $pluginColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-plugin', 'customer-id', $id, $plugins, $pluginColumnDates);

            foreach ($billings as $keyItem => $valueItem) {
                $idBilling = isset($valueItem['id']) ? $valueItem['id'] : '';
                $code = "HD" . $idBilling;
                add_post_meta($idBilling, "code", $code);
                add_post_meta($idBilling, "status", 'publish');
            }
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }
    public function func_new_image_old()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $arr = array(
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        if ($id) {
            if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
                $id_exsits = [];
                $files = $_FILES['files'];
                foreach ($files['name'] as $key => $value) {
                    if ($files['error'][$key] === 0) {
                        $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                        );
                        $upload_overrides = array('test_form' => false);
                        $movefile = wp_handle_upload($file, $upload_overrides);
                        if ($movefile && !isset($movefile['error'])) {
                            $wp_filetype = $movefile['type'];
                            $filename = $movefile['file'];
                            $wp_upload_dir = wp_upload_dir();
                            $attachment = array(
                                'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                'post_mime_type' => $wp_filetype,
                                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attach_id = wp_insert_attachment($attachment, $filename);
                            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                            wp_update_attachment_metadata($attach_id, $attach_data);
                            update_post_meta($attach_id, 'id-parent', $id);
                            $id_exsits[] = $attach_id;
                        }
                    }
                }
            }

            wp_send_json_success(['msg' => 'Tải ảnh thành công', 'id' => $id_exsits]);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_new_image()
    {
        check_ajax_referer('ajax-new-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $arr = [
            'ID'         => $_POST['id'],
            'post_type'  => $this->postType,
            'post_status' => 'publish'
        ];
        $id = wp_update_post($arr);

        if (!$id) {
            wp_send_json_error(['msg' => 'Lỗi cập nhật bài viết']);
        }

        $id_exsits = [];
        if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
            $files = $_FILES['files'];

            foreach ($files['name'] as $key => $value) {
                if ($files['error'][$key] === 0) {
                    $file = [
                        'name'     => $files['name'][$key],
                        'type'     => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error'    => $files['error'][$key],
                        'size'     => $files['size'][$key]
                    ];

                    $attach_id = custom_upload_private_image($file);

                    if (!is_wp_error($attach_id)) {
                        update_post_meta($attach_id, 'id-parent', $id);
                        $id_exsits[] = $attach_id;
                    }
                }
            }
        }

        wp_send_json_success([
            'msg' => 'Tải ảnh riêng tư thành công',
            'id'  => $id_exsits
        ]);

        wp_die();
    }
    public function func_update()
    {
        global $CDWFunc, $CDWEmail;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');
        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $name         = isset($_POST['name']) ? $_POST['name'] : '';
        $company_name = isset($_POST['company-name']) ? $_POST['company-name'] : '';
        $mst          = isset($_POST['mst']) ? $_POST['mst'] : '';
        $phone        = isset($_POST['phone']) ? $_POST['phone'] : '';
        $email        = isset($_POST['email']) ? $_POST['email'] : '';
        $dvhc_tp      = isset($_POST['dvhc-tp']) ? $_POST['dvhc-tp'] : '';
        // $dvhc_qh      = isset($_POST['dvhc-qh']) ? $_POST['dvhc-qh'] : '';
        $dvhc_px      = isset($_POST['dvhc-px']) ? $_POST['dvhc-px'] : '';
        $address      = isset($_POST['address']) ? $_POST['address'] : '';
        $cmnd         = isset($_POST['cmnd']) ? $_POST['cmnd'] : '';
        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $note         = isset($_POST['note']) ? $_POST['note'] : '';
        $company_phone = isset($_POST['company-phone']) ? $_POST['company-phone'] : '';
        $company_address = isset($_POST['company-address']) ? $_POST['company-address'] : '';
        $id = $_POST['id'];

        // KYC Action
        $kyc_action = isset($_POST['kyc_action']) ? $_POST['kyc_action'] : '';
        if ($kyc_action === 'confirm') {

            $id_card_front_url = wp_get_attachment_url(get_post_meta($id, 'id_card_front', true));
            $id_card_back_url = wp_get_attachment_url(get_post_meta($id, 'id_card_back', true));
            if (empty($name) || empty($phone) || empty($email) || empty($dvhc_tp) ||  empty($dvhc_px) || empty($address) || empty($cmnd) || empty($birthdate) || empty($gender) || empty($id_card_front_url) || empty($id_card_back_url)) {
                wp_send_json_error(['msg' => 'Thông tin người dùng chưa đủ điều kiện KYC.']);
            }
            update_post_meta($id, 'status-kyc', '3');
            cdw_create_customer_log($id, 'Xác thực KYC', 'Tài khoản đã được xác thực thành công.');
            $result = $CDWFunc->inet->sync_customer($id);
            if (!$result['success']) {
                cdw_create_customer_log($id, 'Xác thực KYC', 'Đồng bộ thất bại.');
                wp_send_json_error(['msg' => 'Xác thực KYC thành công, nhưng đồng bộ iNET thất bại: ' . $result['msg'], 'data' => isset($result['data']) ? $result['data'] : null]);
            }
        } elseif ($kyc_action === 'reject') {
            $reason = isset($_POST['kyc_rejection_reason']) ? $_POST['kyc_rejection_reason'] : 'Không có lý do.';
            update_post_meta($id, 'status-kyc', '1');
            update_post_meta($id, 'kyc-rejection-reason', $reason);
            cdw_create_customer_log($id, 'Từ chối KYC', 'Lý do: ' . $reason);
            $CDWEmail->sendEmailKYCRejected($id, $reason);
        }

        $kyc_status = get_post_meta($id, 'status-kyc', true);


        $arr = array(
            'ID' => $id,
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        write_syslog(__METHOD__ . " => START WITH POST => " . print_r($_POST, true));

        if ($id) {


            if ($kyc_status !== '3') {

                update_post_meta($id, 'name', $name);
                update_post_meta($id, 'phone', $phone);
                update_post_meta($id, 'email', $email);
                update_post_meta($id, 'dvhc_tp', $dvhc_tp);
                // update_post_meta($id, 'dvhc_qh', $dvhc_qh);
                update_post_meta($id, 'dvhc_px', $dvhc_px);
                update_post_meta($id, 'dvhc_tp_label', isset($_POST['dvhc-tp-label']) ? $_POST['dvhc-tp-label'] : '');
                update_post_meta($id, 'dvhc_px_label', isset($_POST['dvhc-px-label']) ? $_POST['dvhc-px-label'] : '');
                update_post_meta($id, 'address', $address);
                update_post_meta($id, 'cmnd', $cmnd);
                update_post_meta($id, 'birthdate', $CDWFunc->date->convertDateTime($birthdate));
                update_post_meta($id, 'gender', $gender);

                $user_id = get_post_meta($id, 'user-id', true);
                update_user_meta($user_id, 'nickname', get_post_meta($id, 'name', true));
                update_user_meta($user_id, 'first_name', get_post_meta($id, 'name', true));
                update_user_meta($user_id, 'phone', get_post_meta($id, 'phone', true));
                update_user_meta($user_id, 'address', get_post_meta($id, 'address', true));
            }
            update_post_meta($id, 'company_name', $company_name);
            update_post_meta($id, 'mst', $mst);
            update_post_meta($id, 'company_phone', $company_phone);
            update_post_meta($id, 'company_address', $company_address);
            update_post_meta($id, 'note', $note);
            //Hosting
            $hostings = isset($_POST['hostings']) ? $_POST['hostings'] : [];
            foreach ($hostings as $valueItem) {
                if (empty($valueItem) || empty($valueItem['id']) || empty($valueItem['user'])) {
                    continue;
                }

                $id_hosting   = $valueItem['id'];
                $old_password = get_post_meta($id_hosting, 'pass', true);
                if ($old_password !== $valueItem['pass']) {

                    write_syslog(__METHOD__ . "old pass => $old_password => new pass => " . $valueItem['pass']);
                    $response = $CDWFunc->directAdmin->changeResellerPassword($valueItem['user'], $valueItem['pass']);

                    update_post_meta($id_hosting, 'audit', json_encode($response));
                    update_post_meta($id_hosting, 'update_by', get_current_user_id());
                    update_post_meta($id_hosting, 'update_date', current_time('mysql'));
                }
            }

            $hostingColumns = ['ip', 'port', 'user', 'pass',  'type', 'price'];
            $hostingColumnDates = ['buy_date', 'expiry_date'];
            $hostings = $CDWFunc->wpdb->func_update_detail_post('customer-hosting', 'customer-id', $id, $hostings, $hostingColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-hosting', 'customer-id', $id, $hostings, $hostingColumnDates);

            //Domain
            $domains = isset($_POST['domains']) ? $_POST['domains'] : [];
            $domainColumns = ['url', 'price', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type'];
            $domainColumnDates = ['buy_date', 'expiry_date'];
            $domains = $CDWFunc->wpdb->func_update_detail_post('customer-domain', 'customer-id', $id, $domains, $domainColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-domain', 'customer-id', $id, $domains, $domainColumnDates);

            foreach ($domains as $keyItem => $valueItem) {
                if (!empty($valueItem['domain-type'])) continue;
                $id_domain = $CDWFunc->wpdb->get_id_domain($valueItem['url']);
                if ($id_domain && !empty($valueItem['id']))
                    update_post_meta($valueItem['id'], "domain-type", $id_domain);
            }
            //Theme
            $themes = isset($_POST['themes']) ? $_POST['themes'] : [];
            $themeColumns = ['name', 'price', 'site-type'];
            $themeColumnDates = ['date'];
            $themes = $CDWFunc->wpdb->func_update_detail_post('customer-theme', 'customer-id', $id, $themes, $themeColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-theme', 'customer-id', $id, $themes, $themeColumnDates);

            //Billing
            $billings = isset($_POST['billings']) ? $_POST['billings'] : [];
            $billingColumns = ['note', 'amount', 'status'];
            $billingColumnDates = ['date'];
            $billings = $CDWFunc->wpdb->func_update_detail_post('customer-billing', 'customer-id', $id, $billings, $billingColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-billing', 'customer-id', $id, $billings, $billingColumnDates);

            //Email
            $emails = isset($_POST['emails']) ? $_POST['emails'] : [];
            $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price', 'domain'];
            $emailColumnDates = ['buy_date', 'expiry_date'];
            $emails = $CDWFunc->wpdb->func_update_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);

            //Plugin
            $plugins = isset($_POST['plugins']) ? $_POST['plugins'] : [];
            $pluginColumns = ['name', 'price', 'plugin-type'];
            $pluginColumnDates = ['date', 'expiry_date'];
            $plugins =  $CDWFunc->wpdb->func_update_detail_post('customer-plugin', 'customer-id', $id, $plugins, $pluginColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-plugin', 'customer-id', $id, $plugins, $pluginColumnDates);


            foreach ($billings as $keyItem => $valueItem) {
                $idBilling = isset($valueItem['id']) ? $valueItem['id'] : '';
                $code = get_post_meta($idBilling, "code", true);
                if (!$code && empty($code)) {
                    $code = "HD" . $idBilling;
                    update_post_meta($idBilling, "code", $code);
                }
                $status = get_post_meta($idBilling, "status", true);
                if (empty($status)) {
                    update_post_meta($idBilling, "status", 'publish');
                    //$CDWEmail->sendEmailOrderNew($idBilling);
                    //update_post_meta($idBilling, "email-success-sended", false);
                } else {
                    $sended = get_post_meta($idBilling, "email-success-sended", true);
                    if ($status == "success" && !$sended) {

                        $CDWEmail->sendEmailOrderComplete($idBilling);
                        update_post_meta($idBilling, "email-success-sended", true);
                    }
                }
                //Cập nhật thông tin theo hóa đơn

                if ($status == 'success') {
                    cdw_process_successful_payment($idBilling);
                }
            }

            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_update_image_old()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $arr = array(
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        if ($id) {

            $id_exsits = isset($_POST['id_exsits']) ? $_POST['id_exsits'] : [];
            if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
                $files = $_FILES['files'];
                foreach ($files['name'] as $key => $value) {
                    if ($files['error'][$key] === 0) {
                        $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                        );
                        $upload_overrides = array('test_form' => false);
                        $movefile = wp_handle_upload($file, $upload_overrides);
                        if ($movefile && !isset($movefile['error'])) {
                            $wp_filetype = $movefile['type'];
                            $filename = $movefile['file'];
                            $wp_upload_dir = wp_upload_dir();
                            $attachment = array(
                                'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                                'post_mime_type' => $wp_filetype,
                                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                'post_content' => '',
                                'post_status' => 'inherit'
                            );
                            $attach_id = wp_insert_attachment($attachment, $filename);
                            $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                            wp_update_attachment_metadata($attach_id, $attach_data);
                            update_post_meta($attach_id, 'id-parent', $id);
                            $id_exsits[] = $attach_id;
                        }
                    }
                }
            }
            $this->delete_images_not_exsits($id, $id_exsits);

            wp_send_json_success(['msg' => 'Tải ảnh thành công', 'id' => $id_exsits]);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_update_image()
    {
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $arr = [
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            'post_status' => 'publish'
        ];
        $id = wp_update_post($arr);

        if (!$id) {
            wp_send_json_error(['msg' => 'Lỗi cập nhật bài viết']);
        }

        $id_exsits = isset($_POST['id_exsits']) ? $_POST['id_exsits'] : [];
        if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
            $files = $_FILES['files'];

            foreach ($files['name'] as $key => $value) {
                if ($files['error'][$key] === 0) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];

                    $attach_id = custom_upload_private_image($file);

                    if (!is_wp_error($attach_id)) {
                        update_post_meta($attach_id, 'id-parent', $id);
                        $id_exsits[] = $attach_id;
                    }
                }
            }
        }

        $this->delete_images_not_exsits($id, $id_exsits);

        wp_send_json_success([
            'msg' => 'Tải ảnh riêng tư thành công',
            'id' => $id_exsits
        ]);

        wp_die();
    }
    public function func_check_use_customer($customer_id)
    {
        global $CDWFunc;
        if (
            $CDWFunc->wpdb->func_exist_post('customer-hosting', 'customer-id', $customer_id) ||
            $CDWFunc->wpdb->func_exist_post('customer-doamin', 'customer-id', $customer_id) ||
            $CDWFunc->wpdb->func_exist_post('customer-theme', 'customer-id', $customer_id) ||
            $CDWFunc->wpdb->func_exist_post('customer-builling', 'customer-id', $customer_id) ||
            $CDWFunc->wpdb->func_exist_post('customer-plugin', 'customer-id', $customer_id) ||
            $CDWFunc->wpdb->func_exist_post('customer-email', 'customer-id', $customer_id)
        )
            return true;
        return false;
    }
    public function func_delete()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        if ($this->func_check_use_customer($id))
            wp_send_json_error(['msg' => 'Khách hàng đã có phát sinh, không thể xoá']);

        $user_id = get_post_meta($id, 'user-id', true);
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;

        $check = wp_delete_post($id, true);
        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else {

            if (!in_array('administrator', $user_roles, true)) {
                require_once(ABSPATH . 'wp-admin/includes/user.php');
                wp_delete_user($user_id);
                $CDWFunc->wpdb->func_delete_detail_post('customer-hosting', 'customer-id', $id, false);
                $CDWFunc->wpdb->func_delete_detail_post('customer-domain', 'customer-id', $id, false);
                $CDWFunc->wpdb->func_delete_detail_post('customer-theme', 'customer-id', $id, false);
                $CDWFunc->wpdb->func_delete_detail_post('customer-billing', 'customer-id', $id, false);
                $CDWFunc->wpdb->func_delete_detail_post('customer-plugin', 'customer-id', $id, false);
                $CDWFunc->wpdb->func_delete_detail_post('customer-email', 'customer-id', $id, false);
                $this->delete_images_not_exsits($id, [], false);

                $this->setFeatureStatus(true);
            }

            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }


    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-customer-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $ids = $_POST['ids'];

        require_once(ABSPATH . 'wp-admin/includes/user.php');
        foreach ($ids as $id) {
            $user_id = get_post_meta($id, 'user-id', true);
            $user_meta = get_userdata($user_id);
            $user_roles = $user_meta->roles;
            if ($this->func_check_use_customer($id))
                break;
            if (in_array('administrator', $user_roles, true)) {
                break;
            }
            $check = wp_delete_post($id);
            if (!$check ||  $check == null) break;

            wp_delete_user($user_id);
            $CDWFunc->wpdb->func_delete_detail_post('customer-hosting', 'customer-id', $id, false);
            $CDWFunc->wpdb->func_delete_detail_post('customer-domain', 'customer-id', $id, false);
            $CDWFunc->wpdb->func_delete_detail_post('customer-theme', 'customer-id', $id, false);
            $CDWFunc->wpdb->func_delete_detail_post('customer-billing', 'customer-id', $id, false);
            $CDWFunc->wpdb->func_delete_detail_post('customer-plugin', 'customer-id', $id, false);
            $CDWFunc->wpdb->func_delete_detail_post('customer-email', 'customer-id', $id, false);
            $this->delete_images_not_exsits($id, [], false);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else {
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }
    public function func_load_customer_hosting()
    {
        global $CDWFunc;
        $postType = 'customer-hosting';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['ip', 'port', 'user', 'pass', 'type', 'buy_date', 'expiry_date', 'price'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'buy_date');
        // foreach ($data as $key => $value) {
        //     $data[$key]["ip"] =  $data[$key]["ip"] . '<a class="ml-2  text-success" target="_blank" href="' . $CDWFunc->getUrl('hosting', 'histories', 'id=' . $value["id"]) . '"><i class="fa fa-eye" title="Xem lịch sữ"></i></a>';
        // }
        foreach ($data as $key => $value) {
            $data[$key]["type_label"] = get_the_title($value["type"]);
            $data[$key]["cpu"] = get_post_meta($value["type"], "cpu", true);
            $data[$key]["ram"] = get_post_meta($value["type"], "ram", true);
            $data[$key]["hhd"] = get_post_meta($value["type"], "hhd", true);
        }
        wp_send_json_success($data);
        wp_die();
    }
    public function func_load_customer_domain()
    {
        global $CDWFunc;
        $postType = 'customer-domain';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type', 'inet_domain_id'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'buy_date');

        foreach ($data as $key => $value) {
            $item_label = "";
            if (!empty($value['domain-type'])) $item_label = get_the_title($value['domain-type']);
            $data[$key]["domain-type_label"] = $item_label;
            $data[$key]['action'] = '';
            $data[$key]['urlUpdateDNS'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-dns&id=' . $value['id']);
            $data[$key]['urlUpdateRecord'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-record&id=' . $value['id']);
            $data[$key]['has_inet_domain_id'] = !empty($value['inet_domain_id']);
        }

        wp_send_json_success($data);
        wp_die();
    }

    public function func_register_domain_to_inet()
    {
        global $CDWFunc;
        // check_ajax_referer('ajax-detail-customer-nonce', 'security');
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy domain']);
        }

        $domain_post_id = $_POST['id'];
        $customer_id = get_post_meta($domain_post_id, 'customer-id', true);
        $domain_name = get_post_meta($domain_post_id, 'url', true);
        $buy_date = get_post_meta($domain_post_id, 'buy_date', true);
        $expiry_date = get_post_meta($domain_post_id, 'expiry_date', true);
        $period = $CDWFunc->date->diffInYears($buy_date, $expiry_date);
        if ($period < 1) {
            wp_send_json_error(['msg' => 'Thời gian mua domain phải lớn hơn 1 năm']);
        }
        $availability_check = $CDWFunc->inet->check_domain($domain_name);

        error_log('[func_register_domain_to_inet] availability_check: ' . print_r($availability_check, true));
        if (!$availability_check['success'] || $availability_check['data']['status'] != 'available') {
            $error_msg = $availability_check['data']['status'] == 'error' ? $availability_check['data']['message'] : 'Tên miền ' . $domain_name . ' không có sẵn hoặc có lỗi khi kiểm tra';
            cdw_create_customer_log($customer_id, 'Đăng ký tên miền iNET thất bại', $error_msg);
            wp_send_json_error(['msg' => $error_msg]);
        }
        $registration_result = $CDWFunc->inet->create_domain($customer_id, $domain_name, $period);

        error_log('[func_register_domain_to_inet] registration_result: ' . print_r($registration_result, true));

        if ($registration_result['success']) {
            $inet_domain_id = $registration_result['data']['id'] ?? null;
            if ($inet_domain_id) {
                update_post_meta($domain_post_id, 'inet_domain_id', $inet_domain_id);
                $CDWFunc->inet->upload_documents_for_domain($inet_domain_id, $customer_id);
                cdw_create_customer_log($customer_id, 'Đăng ký tên miền iNET thành công', 'Tên miền: ' . $domain_name . '. Thời gian: ' . $period . ' năm. Chi tiết: ' . json_encode($registration_result));
                wp_send_json_success(['msg' => 'Đăng ký tên miền thành công, đã gửi giấy tờ.']);
            } else {
                cdw_create_customer_log($customer_id, 'Đăng ký tên miền iNET thất bại', 'Tên miền: ' . $domain_name . '. Lỗi: Không nhận được domainId từ iNET.');
                wp_send_json_error(['msg' => 'Đăng ký tên miền thất bại: Không nhận được domainId từ iNET.']);
            }
        } else {
            cdw_create_customer_log($customer_id, 'Đăng ký tên miền iNET thất bại', 'Tên miền: ' . $domain_name . '. Lỗi: ' . $registration_result['msg']);
            wp_send_json_error(['msg' => 'Đăng ký tên miền thất bại: ' . $registration_result['msg']]);
        }
        wp_send_json_success(['msg' => 'Đăng ký tên miền thành công']);

        wp_die();
    }

    public function func_sync_domain_inet_info()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy domain']);
        }

        $domain_post_id = $_POST['id'];
        $domain_name = get_post_meta($domain_post_id, 'url', true);

        $inet_domain_info_result = $CDWFunc->inet->get_domain_by_name($domain_name);

        if ($inet_domain_info_result['success'] && !empty($inet_domain_info_result['data']['id'])) {
            $inet_domain_id = $inet_domain_info_result['data']['id'];
            update_post_meta($domain_post_id, 'inet_domain_id', $inet_domain_id);
            wp_send_json_success(['msg' => 'Đồng bộ thông tin domain thành công']);
        } else {
            wp_send_json_error(['msg' => 'Không tìm thấy thông tin domain trên iNET hoặc có lỗi xảy ra.']);
        }

        wp_die();
    }

    public function func_renew_domain_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy domain']);
        }

        $domain_post_id = $_POST['id'];
        $domain_name = get_post_meta($domain_post_id, 'url', true);

        $inet_domain_info_result = $CDWFunc->inet->get_domain_by_name($domain_name);
        if ($inet_domain_info_result['success'] && !empty($inet_domain_info_result['data']['id'])) {
            $inet_domain_id = $inet_domain_info_result['data']['id'];
            update_post_meta($domain_post_id, 'inet_domain_id', $inet_domain_id);
        } else {
            wp_send_json_error(['msg' => 'Domain không tồn tại trên iNET']);
        }

        $quantity = (int)get_post_meta($domain_post_id, 'quantity', true);

        if (empty($quantity)) {
            $buy_date = get_post_meta($domain_post_id, 'buy_date', true);
            $expiry_date = get_post_meta($domain_post_id, 'expiry_date', true);
            $quantity = $CDWFunc->date->diffInYears($buy_date, $expiry_date);
        }
        error_log('[func_renew_domain_inet]: ' . print_r(['domain_name' => $domain_name, 'quantity' => $quantity], true));
        $renewal_result = $CDWFunc->inet->renew_domain($inet_domain_id, $quantity);
        error_log('[func_renew_domain_inet] renewal_result: ' . print_r($renewal_result, true));

        if ($renewal_result['success']) {
            cdw_create_customer_log(get_post_meta($domain_post_id, 'customer-id', true), 'Gia hạn tên miền iNET thành công', 'Tên miền: ' . $domain_name . '. Thời gian: ' . $quantity . ' năm. Chi tiết: ' . json_encode($renewal_result));
            wp_send_json_success(['msg' => 'Gia hạn tên miền thành công']);
        } else {
            cdw_create_customer_log(get_post_meta($domain_post_id, 'customer-id', true), 'Gia hạn tên miền iNET thất bại', 'Tên miền: ' . $domain_name . '. Lỗi: ' . $renewal_result['msg']);
            wp_send_json_error(['msg' => 'Gia hạn tên miền thất bại: ' . $renewal_result['msg']]);
        }

        wp_die();
    }


    public function func_load_customer_theme()
    {
        global $CDWFunc;
        $postType = 'customer-theme';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['date', 'name', 'price', 'site-type'];

        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'date');
        foreach ($data as $key => $value) {
            $item_label = "";
            if (!empty($value['site-type'])) $item_label = get_post_meta($value['site-type'], 'name', true);
            $data[$key]["site-type_label"] = $item_label;
        }
        wp_send_json_success($data);
        wp_die();
    }
    public function func_load_customer_billing()
    {
        global $CDWFunc;
        $postType = 'customer-billing';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['code', 'date', 'note', 'amount', 'status'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'date');
        foreach ($data as $key => $value) {
            $data[$key]["code"] =  '<a class="mr-3  text-primary" target="_blank" href="' . $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $value["id"]) . '"><i class="fa fa-eye" title="Xem chi tiết"></i></a>' . $data[$key]["code"];
            $data[$key]["status_label"] = $CDWFunc->get_lable_status($value["status"]);
            if ($value["status"] == 'success') {
                $data[$key]["status_label"] = $data[$key]["status_label"] . '<a class="ml-3 update-date-by-billing' . (!get_post_meta($value["id"], 'is-update', true) ? '' : '-success') . ' ' . (!get_post_meta($value["id"], 'is-update', true) ? 'text-primary' : 'text-secondary') . ' " href="javascript:void(0);"><i class="fa fa-cloud-upload" title="Cập nhật thông tin hóa đơn"></i></a>';
            }
        }
        wp_send_json_success($data);
        wp_die();
    }
    public function func_load_customer_email()
    {
        global $CDWFunc;
        $postType = 'customer-email';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'buy_date', 'expiry_date', 'price', 'inet_plan_id', 'inet_email_id', 'domain'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'buy_date');
        foreach ($data as $key => $value) {
            $data[$key]["email-type_label"] = get_the_title($value["email-type"]);
            $data[$key]['action'] = '';
        }
        wp_send_json_success($data);
        wp_die();
    }
    public function func_load_customer_plugin()
    {
        global $CDWFunc;
        $postType = 'customer-plugin';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['date', 'expiry_date', 'name', 'price', 'license', 'plugin-type'];

        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'date');
        foreach ($data as $key => $value) {
            $item_label = "";
            if (!empty($value['plugin-type'])) $item_label = get_post_meta($value['plugin-type'], 'name', true);
            $data[$key]["plugin-type_label"] = $item_label;
        }
        wp_send_json_success($data);
        wp_die();
    }

    public function func_create_user()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không thấy khách hàng']);
        }
        if (!isset($_POST['email'])) {
            wp_send_json_error(['msg' => 'Vui lòng cập chật email cho khách hàng trước khi tạo tài khoản']);
        }
        $result = [];
        $id = $_POST['id'];
        $email = $_POST['email'];

        $random_password = $CDWFunc->generatePassword(12);

        $user_id = username_exists($email);

        if (! $user_id && false == email_exists($email)) {
            $user_id = wp_create_user($email, $random_password, $email);
            if (!$user_id) {
                wp_send_json_error(['msg' => 'Lỗi không tạo được tài khoản!']);
            }
        } else {
            wp_set_password($random_password, $user_id);
        }

        $result["id"] = $user_id;
        $result["username"] = $email;
        $result["email"] = $email;
        $result["password"] =  $random_password;

        update_post_meta($id, 'user-id', $user_id);
        update_user_meta($user_id, 'customer-id', $id);
        update_user_meta($user_id, 'nickname', get_post_meta($id, 'name', true));
        update_user_meta($user_id, 'first_name', get_post_meta($id, 'name', true));
        update_user_meta($user_id, 'phone', get_post_meta($id, 'phone', true));
        update_user_meta($user_id, 'address', get_post_meta($id, 'address', true));

        wp_send_json_success(['msg' => 'Tạo thành công', 'user' => (object)$result]);


        wp_die();
    }
    public function delete_images_not_exsits($idParent, $id_exsits, $force_delete = true)
    {
        global $CDWFunc, $CDWEmail;
        $args = array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post__not_in' => $id_exsits,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'id-parent',
                    'value' => $idParent,
                    'compare' => '='
                )
            )
        );

        $ids = get_posts($args);

        foreach ($ids as $id) {
            wp_delete_attachment($id, $force_delete);
        }
    }
    public function func_update_info_by_billing_customer()
    {
        check_ajax_referer('ajax-detail-customer-nonce', 'security');
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $billing_id = intval($_POST['id']);
        if (!get_post_meta($billing_id, 'is-update', true)) {
            cdw_process_successful_payment($billing_id);
            wp_send_json_success(['msg' => 'Cập nhật thông tin thành công']);
        } else {
            wp_send_json_error(['msg' => 'Đã cập nhật thông tin từ tờ hóa đơn này.']);
        }
        wp_die();
    }

    public function setFeatureStatus($status, $user_id = "")
    {
        if (empty($user_id)) {
            $userC = wp_get_current_user();
            $user_id = $userC->ID;
        }
        return update_user_meta($user_id, "feature-status", $status);
    }



    public function func_load_customer_log()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy khách hàng']);
        }
        $customer_id = $_POST['id'];

        $limit = isset($_POST['length']) ? $_POST['length'] : -1;
        $offset = isset($_POST['start']) ? $_POST['start'] : 0;

        $args = array(
            'post_type' => 'customer-log',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'offset' => $offset,
            'meta_query' => array(
                array(
                    'key'     => 'customer-id',
                    'value'   => $customer_id,
                    'compare' => '=',
                ),
            ),
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $query = new WP_Query($args);
        $logs = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $logs[] = [
                    'id' => $post_id,
                    'date' => get_the_date('Y-m-d H:i:s'),
                    'title' => get_the_title(),
                    'content' => get_the_content(),
                    'user' => get_post_meta($post_id, 'user-name', true),
                ];
            }
        }
        wp_reset_postdata();

        $response = array('success' => true);
        $response['data'] = $logs;
        $response['draw'] = isset($_POST['draw']) ? (int)$_POST['draw'] : 0;
        $response['recordsTotal'] = $query->found_posts;
        $response['recordsFiltered'] = $query->found_posts;
        wp_send_json($response);
        wp_die();
    }

    public function func_reset_kyc_status()
    {
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy khách hàng']);
        }

        $customer_id = intval($_POST['id']);

        // Update status to '2' (Pending Verification)
        update_post_meta($customer_id, 'status-kyc', '2');

        // Delete the old rejection reason, if any
        delete_post_meta($customer_id, 'kyc-rejection-reason');

        // Log the action
        cdw_create_customer_log($customer_id, 'Yêu cầu nhập lại KYC', 'Trạng thái KYC đã được quản trị viên đặt lại thành "Đang Xác Thực".');

        wp_send_json_success(['msg' => 'Trạng thái KYC đã được cập nhật thành công.']);

        wp_die();
    }

    public function func_sync_customer_to_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'nonce');

        $customer_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if (empty($customer_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID khách hàng']);
        }

        cdw_create_customer_log($customer_id, 'Đồng bộ iNET', 'Bắt đầu đồng bộ thông tin khách hàng với iNET.');

        $result = $CDWFunc->inet->sync_customer($customer_id);

        if ($result['success']) {
            cdw_create_customer_log($customer_id, 'Đồng bộ iNET thành công', $result['msg']);
            wp_send_json_success(['msg' => $result['msg'], 'data' => isset($result['data']) ? $result['data'] : null]);
        } else {
            cdw_create_customer_log($customer_id, 'Đồng bộ iNET thất bại', $result['msg']);
            wp_send_json_error(['msg' => $result['msg'], 'data' => isset($result['data']) ? $result['data'] : null]);
        }
        wp_die();
    }

    public function func_change_email_plan()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        $customer_email_id = isset($_POST['customer_email_id']) ? intval($_POST['customer_email_id']) : 0;
        $new_plan_wp_id = isset($_POST['new_plan_id']) ? intval($_POST['new_plan_id']) : 0;

        if (empty($customer_email_id) || empty($new_plan_wp_id)) {
            wp_send_json_error(['msg' => 'Thiếu thông tin cần thiết.']);
        }

        $customer_id = get_post_meta($customer_email_id, 'customer-id', true);
        $inet_email_id = get_post_meta($customer_email_id, 'inet_email_id', true);
        $old_plan_wp_id = get_post_meta($customer_email_id, 'email-type', true);

        if (empty($inet_email_id) || empty($old_plan_wp_id)) {
            wp_send_json_error(['msg' => 'Không thể xác định gói email hiện tại hoặc thông tin iNET.']);
        }

        $old_inet_plan_id = get_post_meta($old_plan_wp_id, 'inet_plan_id', true);
        $new_inet_plan_id = get_post_meta($new_plan_wp_id, 'inet_plan_id', true);

        if (empty($new_inet_plan_id)) {
            wp_send_json_error(['msg' => 'Gói email mới không hợp lệ.']);
        }

        if ($old_inet_plan_id == $new_inet_plan_id) {
            wp_send_json_error(['msg' => 'Bạn đang chọn gói email hiện tại, vui lòng chọn gói khác.']);
        }

        $expiry_date_str = get_post_meta($customer_email_id, 'expiry_date', true);
        $expiry_date = new DateTime($expiry_date_str);
        $current_date = new DateTime(current_time('mysql'));

        $remaining_days = 0;
        if ($expiry_date > $current_date) {
            $interval = $current_date->diff($expiry_date);
            $remaining_days = $interval->days;
        }

        $remaining_months = (int) ceil($remaining_days / 30);

        if ($remaining_months < 1) {
            wp_send_json_error(['msg' => 'Gói email hiện tại còn dưới 1 tháng sử dụng, không thể đổi gói. Vui lòng gia hạn trước.']);
        }

        $period = max(1, $remaining_months);
        error_log('[func_change_email_plan] inet_email_id: ' . $inet_email_id . ', new_inet_plan_id: ' . $new_inet_plan_id . ', period: ' . $period);
        $response = $CDWFunc->inet->change_email_plan($inet_email_id, $new_inet_plan_id, $period);
        error_log('[func_change_email_plan] API Response: ' . print_r($response, true));

        if ($response['success']) {
            update_post_meta($customer_email_id, 'email-type', $new_plan_wp_id);
            update_post_meta($customer_email_id, 'inet_plan_id', $new_inet_plan_id);

            $detail_response = $CDWFunc->inet->get_email_detail($inet_email_id);
            if ($detail_response['success'] && isset($detail_response['data'])) {
                $this->_save_inet_email_details($customer_email_id, $detail_response['data']);
            } else {
                error_log('[func_change_email_plan] Failed to fetch updated email details after plan change for inet_email_id: ' . $inet_email_id);
            }

            $old_plan_name = get_the_title($old_plan_wp_id);
            $new_plan_name = get_the_title($new_plan_wp_id);
            cdw_create_customer_log($customer_id, 'Đổi gói Email thành công', "Đã đổi từ gói '{$old_plan_name}' sang gói '{$new_plan_name}'.");

            wp_send_json_success(['msg' => 'Đổi gói email thành công.']);
        } else {
            cdw_create_customer_log($customer_id, 'Đổi gói Email thất bại', 'Lỗi: ' . ($response['msg'] ?? 'Không xác định'));
            wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi khi đổi gói email.']);
        }
        wp_die();
    }

    public function handle_momo_ipn(WP_REST_Request $request)
    {
        $json_data = $request->get_json_params();
        error_log('[handle_momo_ipn] json_data: ' . print_r($json_data, true));

        $partnerCode = $json_data['partnerCode'] ?? '';
        $orderId = $json_data['orderId'] ?? '';
        $requestId = $json_data['requestId'] ?? '';
        $amount = $json_data['amount'] ?? '';
        $orderInfo = $json_data['orderInfo'] ?? '';
        $orderType = $json_data['orderType'] ?? '';
        $transId = $json_data['transId'] ?? '';
        $resultCode = $json_data['resultCode'] ?? -1;
        $message = $json_data['message'] ?? '';
        $payType = $json_data['payType'] ?? '';
        $responseTime = $json_data['responseTime'] ?? '';
        $extraData = $json_data['extraData'] ?? '';
        $signature = $json_data['signature'] ?? '';

        // !!! QUAN TRỌNG: Chuỗi rawHash phải được tạo chính xác theo thứ tự các tham số mà tài liệu MoMo IPN quy định.
        // Thứ tự dưới đây được sắp xếp theo bảng chữ cái và cần được xác minh lại với tài liệu chính thức.
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;
        $momoSignature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        if ($signature === $momoSignature) {
            $parts = explode('_', $orderId);
            $billing_code = $parts[0];
            $billing_posts = get_posts([
                'post_type' => 'customer-billing',
                'meta_key' => 'code',
                'meta_value' => $billing_code,
                'posts_per_page' => 1,
                'fields' => 'ids'
            ]);
            $billing_id = !empty($billing_posts) ? $billing_posts[0] : 0;

            if ($billing_id > 0) {
                $status = get_post_meta($billing_id, 'status', true);
                if ($status !== 'pending') {
                    error_log('[handle_momo_ipn] Order #' . $billing_id . ' already processed with status: ' . $status . '. Ignoring IPN.');
                    $response = new WP_REST_Response();
                    $response->set_status(204);
                    return $response;
                }
            }

            $customer_id = get_post_meta($billing_id, 'customer-id', true);

            if ($resultCode == 0 && $billing_id > 0) {
                update_post_meta($billing_id, 'momo_transId', $transId);
                update_post_meta($billing_id, 'momo_payType', $payType);
                update_post_meta($billing_id, 'momo_responseTime', $responseTime);
                update_post_meta($billing_id, 'momo_ipn_data', $json_data);

                cdw_process_successful_payment($billing_id);
                cdw_create_customer_log($customer_id, 'Thanh toán MoMo thành công (IPN)', 'Đơn hàng #' . $billing_id . ' đã được thanh toán thành công qua MoMo IPN. TransID: ' . $transId);
            } else {
                update_post_meta($billing_id, 'momo_ipn_data', $json_data);
                cdw_create_customer_log($customer_id, 'Thanh toán MoMo thất bại (IPN)', 'IPN nhận được cho đơn hàng #' . $billing_id . ' với mã lỗi: ' . $resultCode . ' - ' . $message);
            }
        } else {
            error_log('[handle_momo_ipn] $signature !== $momoSignature: ' . print_r($json_data, true));
        }

        $response = new WP_REST_Response();
        $response->set_status(204);
        return $response;
    }



    public function func_sync_inet_emails()
    {
        global $CDWFunc;
        // check_ajax_referer('ajax-list-manage-email-nonce', 'security');

        $search_response = $CDWFunc->inet->search_email();

        if (!$search_response['success']) {
            wp_send_json_error(['msg' => 'Lỗi khi lấy danh sách email từ iNET: ' . ($search_response['msg'] ?? 'Unknown error')]);
        }

        $inet_emails = $search_response['data'];
        $synced_count = 0;
        $error_count = 0;
        $errors = [];

        foreach ($inet_emails as $inet_email) {
            if (!isset($inet_email['id'])) {
                continue;
            }

            $inet_email_id = $inet_email['id'];
            $detail_response = $CDWFunc->inet->get_email_detail($inet_email_id);

            if ($detail_response['success'] && isset($detail_response['data'])) {
                $details = $detail_response['data'];
                $domain_name = $details['domainName'] ?? null;
                $inet_customer_id = $details['customerId'] ?? null;

                if (!$inet_customer_id) {
                    $error_count++;
                    $errors[] = "iNET email ID {$inet_email_id} (Domain: {$domain_name}) không có customerId.";
                    continue;
                }

                // Requirement: Find local customer by iNET customer ID. If not found, skip.
                $customer_args = [
                    'post_type' => 'customer',
                    'post_status' => 'any',
                    'meta_key' => 'inet_customer_id',
                    'meta_value' => $inet_customer_id,
                    'posts_per_page' => 1,
                    'fields' => 'ids',
                ];
                $customer_query = new WP_Query($customer_args);

                if (empty($customer_query->posts)) {
                    $error_count++;
                    $errors[] = "Không tìm thấy khách hàng cục bộ với iNET customer ID: {$inet_customer_id} (cho domain: {$domain_name}).";
                    continue;
                }

                $wp_customer_id = $customer_query->posts[0];

                // If customer is found, THEN proceed to find the email by domain.
                if (!$domain_name) {
                    $error_count++;
                    $errors[] = "iNET email ID {$inet_email_id} không có tên miền.";
                    continue;
                }

                $email_args = [
                    'post_type' => 'customer-email',
                    'post_status' => 'any',
                    'posts_per_page' => 1,
                    'fields' => 'ids',
                    'meta_query' => [
                        'relation' => 'AND',
                        [
                            'key' => 'domain',
                            'value' => $domain_name,
                            'compare' => '=',
                        ],
                        [
                            'key' => 'customer-id',
                            'value' => $wp_customer_id,
                            'compare' => '=',
                        ],
                    ],
                ];
                $email_query = new WP_Query($email_args);

                if (!empty($email_query->posts)) {
                    $customer_email_id = $email_query->posts[0];

                    // Update the iNET email ID on the local post
                    update_post_meta($customer_email_id, 'inet_email_id', $inet_email_id);

                    // Save all other details from iNET
                    $this->_save_inet_email_details($customer_email_id, $details);

                    $synced_count++;
                } else {
                    $error_count++;
                    $errors[] = "Đã tìm thấy khách hàng, nhưng không tìm thấy customer_email cục bộ cho tên miền: {$domain_name}";
                }
            } else {
                $error_count++;
                $errors[] = "Lỗi khi lấy chi tiết cho iNET ID: {$inet_email_id}. " . ($detail_response['msg'] ?? '');
            }
        }

        wp_send_json_success([
            'msg' => "Đồng bộ hoàn tất. Đã xử lý: {$synced_count}. Lỗi/Bỏ qua: {$error_count}.",
            'errors' => $errors
        ]);
    }

    public function func_check_email_domain_available_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');
        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($customer_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID của email khách hàng.']);
        }

        $email_type_id = get_post_meta($customer_email_id, 'email-type', true);

        if (empty($email_type_id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy gói email được liên kết.']);
        }

        $plan_id = get_post_meta($email_type_id, 'inet_plan_id', true);

        if ($plan_id === '') {
            wp_send_json_error(['msg' => 'Không tìm thấy iNET Plan ID cho gói email này.']);
        }

        $response = $CDWFunc->inet->check_email_domain($domain, $plan_id);
        error_log('[iNET Check Domain Available] Response: ' . json_encode($response));
        if ($response['success']) {
            if (isset($response['data']['status']) && $response['data']['status'] == 'available') {
                wp_send_json_success($response['data']);
            } else {
                $message = isset($response['data']['message']) ? $response['data']['message'] : 'Tên miền không hợp lệ hoặc đã được sử dụng';
                wp_send_json_error(['msg' => $message]);
            }
        } else {
            wp_send_json_error(['msg' => isset($response['msg']) ? $response['msg'] : 'Lỗi khi tìm kiếm tên miền hoặc không có kết quả.']);
        }
    }

    public function func_get_email_records_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');
        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';
        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }

        error_log('[iNET Get Email Records] Fetching records for inet_email_id: ' . $inet_email_id);
        $response = $CDWFunc->inet->get_email_detail($inet_email_id);
        error_log('[iNET Get Email Records] Response: ' . json_encode($response));

        if (!$response['success'] || empty($response['data'])) {
            wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không lấy được bản ghi DNS từ iNET']);
        }

        $details = $response['data'];
        $domain_name = $details['domainName'];
        $config = $details['emailConfig'];

        $required_records = [];

        if (isset($config['recordA'])) {
            $required_records[] = [
                'type' => 'a',
                'name' => ($config['subDomain'] ?? 'mail') . '.' . $domain_name,
                'value' => $config['recordA']
            ];
        }
        if (isset($config['recordMxReseller'])) {
            $mx_records = explode(',', $config['recordMxReseller']);
            foreach ($mx_records as $mx) {
                $mx_parts = explode(':', $mx);
                $required_records[] = [
                    'type' => 'mx',
                    'name' => $domain_name,
                    'value' => $mx_parts[0],
                    'priority' => $mx_parts[1] ?? 0
                ];
            }
        }
        if (isset($config['recordSPFReseller'])) {
            $required_records[] = [
                'type' => 'txt',
                'name' => $domain_name,
                'value' => $config['recordSPFReseller']
            ];
        }

        // Add saved DKIM record if available
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';
        if (!empty($customer_email_id)) {
            $dkim_record_name = get_post_meta($customer_email_id, 'dkim_record_name', true);
            $dkim_record_value = get_post_meta($customer_email_id, 'dkim_record_value', true);
            $dkim_record_type = get_post_meta($customer_email_id, 'dkim_record_type', true);


            if (!empty($dkim_record_name) && !empty($dkim_record_value) && !empty($dkim_record_type)) {
                $dkim_record = [
                    'type' => strtolower($dkim_record_type),
                    'name' => $dkim_record_name,
                    'value' => $dkim_record_value,
                ];
                error_log('[iNET DKIM Record] dkim_record: ' . json_encode($dkim_record));
                $required_records[] = $dkim_record;
            }
        }

        $verified_records = [];
        $all_verified = true;

        foreach ($required_records as $record) {
            $record['verified'] = false;

            $record_name = $record['name'];
            $record_type = $record['type'];
            error_log('[iNET NSLookup] Looking up ' . $record_type . ' for ' . $record_name);
            $nslookup_response = $CDWFunc->inet->nslookup($record_name, $record_type);
            error_log('[iNET NSLookup] Response: ' . json_encode($nslookup_response));

            if ($nslookup_response['success'] && isset($nslookup_response['data'])) {
                $expected_value = $record['value'];
                $lookup_results = $nslookup_response['data'];

                if (!is_array($lookup_results)) {
                    $lookup_results = [['value' => $lookup_results]];
                }

                foreach ($lookup_results as $dns_record) {
                    $actual_value = '';

                    if (is_string($dns_record)) { // A record as string in an array
                        $actual_value = $dns_record;
                    } else if (is_array($dns_record)) {
                        if (isset($dns_record['value'])) {
                            $actual_value = $dns_record['value'];
                        } elseif (isset($dns_record['target'])) {
                            $actual_value = $dns_record['target'];
                        } elseif (isset($dns_record['txt'])) {
                            $actual_value = $dns_record['txt'];
                        } elseif (isset($dns_record['ip'])) {
                            $actual_value = $dns_record['ip'];
                        } elseif (isset($dns_record['exchange'])) {
                            $actual_value = $dns_record['exchange'];
                        } elseif ($record['type'] === 'txt' && isset($dns_record[0])) { // TXT record in nested array
                            $actual_value = $dns_record[0];
                        }
                    }

                    if (empty($actual_value)) {
                        error_log('[iNET NSLookup] Could not extract actual value from dns_record: ' . json_encode($dns_record));
                        continue;
                    }

                    $expected_value_trimmed = rtrim($expected_value, '.');
                    $actual_value_trimmed = rtrim($actual_value, '.');
                    
                    if ($actual_value_trimmed == $expected_value_trimmed || strpos($expected_value_trimmed, $actual_value) !== false) {
                        $record['verified'] = true;
                        break;
                    }
                }
            } else {
                error_log('[iNET NSLookup] Failed or empty response for ' . $record_name . ': ' . json_encode($nslookup_response));
            }

            if (!$record['verified']) {
                $all_verified = false;
            }
            $verified_records[] = $record;
        }

        if ($all_verified) {
            update_post_meta($customer_email_id, '_inet_records_verified', true);
        }

        wp_send_json_success(['records' => $verified_records, 'all_verified' => $all_verified, 'is_verified' => get_post_meta($customer_email_id, '_inet_records_verified', true)]);
    }

    public function func_create_email_package_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');
        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($customer_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID của khách hàng.']);
        }

        $kyc_status = get_post_meta($customer_id, 'status-kyc', true);
        if ($kyc_status != '3') {
            wp_send_json_error(['msg' => 'Khách hàng chưa hoàn tất xác minh KYC. Vui lòng xác minh trước khi đăng ký dịch vụ.']);
        }

        if (empty($customer_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID của email khách hàng.']);
        }

        $email_type_id = get_post_meta($customer_email_id, 'email-type', true);

        if (empty($email_type_id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy gói email được liên kết.']);
        }

        $plan_id = get_post_meta($email_type_id, 'inet_plan_id', true);

        if ($plan_id === '') {
            wp_send_json_error(['msg' => 'Không tìm thấy iNET Plan ID cho gói email này.']);
        }

        $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);
        if (empty($inet_customer_id)) {
            wp_send_json_error(['msg' => 'Khách hàng chưa được đồng bộ với iNET. Vui lòng đồng bộ trước.', 'inet_customer_id' => $inet_customer_id, 'customer_id' => $customer_id]);
        }

        $buy_date = get_post_meta($customer_email_id, 'buy_date', true);
        $expiry_date = get_post_meta($customer_email_id, 'expiry_date', true);
        $period = 1; // Default to 1 month

        if ($buy_date && $expiry_date) {
            $calculated_period = $CDWFunc->date->diffInMonths($buy_date, $expiry_date);
            if ($calculated_period >= 1) {
                $period = $calculated_period;
            }
        }

        $response = $CDWFunc->inet->create_email($domain, $inet_customer_id, $plan_id, $period);
        error_log('[iNET Email Registration] Create Response for domain ' . $domain . ': ' . json_encode($response));

        if ($response['success'] && isset($response['data']['id'])) {
            $inet_email_id = $response['data']['id'];
            update_post_meta($customer_email_id, 'inet_email_id', $inet_email_id);

            $detail_response = $CDWFunc->inet->get_email_detail($inet_email_id);
            error_log('[iNET Email Registration] Detail Response for id ' . $inet_email_id . ': ' . json_encode($detail_response));

            if ($detail_response['success'] && isset($detail_response['data'])) {
                $this->_save_inet_email_details($customer_email_id, $detail_response['data']);
            }

            wp_send_json_success(['id' => $inet_email_id]);
        } else {
            wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không xác định từ iNET khi tạo gói email']);
        }
    }

    public function _save_inet_email_details($customer_email_id, $details)
    {
        if (empty($customer_email_id) || empty($details)) {
            return;
        }

        $domain_name = $details['domainName'] ?? '';
        $config = $details['emailConfig'] ?? [];
        $sub_domain = $config['subDomain'] ?? 'mail';

        $client_url = "https://{$sub_domain}.{$domain_name}";
        $admin_url = "{$client_url}/admin";
        $admin_email = "admin@{$domain_name}";

        update_post_meta($customer_email_id, 'inet_status', $details['status'] ?? '');
        update_post_meta($customer_email_id, 'issue_date', $details['issueDate'] ?? '');
        update_post_meta($customer_email_id, 'expiry_date', $details['expireDate'] ?? '');
        update_post_meta($customer_email_id, 'domain', $domain_name);
        update_post_meta($customer_email_id, 'url_admin', $admin_url);
        update_post_meta($customer_email_id, 'url_client', $client_url);
        update_post_meta($customer_email_id, 'user', $admin_email);
        update_post_meta($customer_email_id, 'quota_limit', $config['quotaLimit'] ?? '');
        update_post_meta($customer_email_id, 'account_limit', $config['accountLimit'] ?? '');
        update_post_meta($customer_email_id, 'group_limit', $config['distributionListLimit'] ?? '');
        update_post_meta($customer_email_id, 'quota_used', $config['totalQuotaUsed'] ?? 0);
        update_post_meta($customer_email_id, 'account_used', $config['accountCurent'] ?? 0);
        update_post_meta($customer_email_id, 'group_used', $config['distributionListCurent'] ?? 0);
    }

    public function func_get_email_detail_inet()
    {
        global $CDWFunc;
        // check_ajax_referer('ajax-detail-customer-nonce', 'security');
        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }

        $details = [];
        $config = [];
        $loaded_from_meta = false;

        if (!empty($customer_email_id)) {
            $details_from_meta = [
                'status' => get_post_meta($customer_email_id, 'inet_status', true),
                'issueDate' => get_post_meta($customer_email_id, 'issue_date', true),
                'expireDate' => get_post_meta($customer_email_id, 'expiry_date', true),
                'domainName' => get_post_meta($customer_email_id, 'domain', true),
                'admin_url' => get_post_meta($customer_email_id, 'url_admin', true),
                'client_url' => get_post_meta($customer_email_id, 'url_client', true),
                'admin_email' => get_post_meta($customer_email_id, 'user', true),
                'planName' => get_the_title(get_post_meta($customer_email_id, 'email-type', true)),
            ];

            $config_from_meta = [
                'quotaLimit' => get_post_meta($customer_email_id, 'quota_limit', true),
                'accountLimit' => get_post_meta($customer_email_id, 'account_limit', true),
                'distributionListLimit' => get_post_meta($customer_email_id, 'group_limit', true),
                'totalQuotaUsed' => get_post_meta($customer_email_id, 'quota_used', true),
                'accountCurent' => get_post_meta($customer_email_id, 'account_used', true),
                'distributionListCurent' => get_post_meta($customer_email_id, 'group_used', true),
                'subDomain' => explode('.', $details_from_meta['admin_url'] ?? '')[0] ?? 'mail',
            ];

            // Check if essential meta data is present
            if (!empty($details_from_meta['domainName']) && !empty($details_from_meta['status'])) {
                $details = $details_from_meta;
                $details['emailConfig'] = $config_from_meta;
                $config = $config_from_meta;
                $loaded_from_meta = true;
            }
        }

        if (!$loaded_from_meta) {
            $response = $CDWFunc->inet->get_email_detail($inet_email_id);
            error_log('[iNET Get Email Detail] Response for id ' . $inet_email_id . ': ' . json_encode($response));

            if (!$response['success'] || empty($response['data'])) {
                wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không lấy được chi tiết gói email']);
            }

            $details = $response['data'];
            if (!empty($customer_email_id)) {
                $this->_save_inet_email_details($customer_email_id, $details);
            }
            $config = $details['emailConfig'] ?? [];
        }

        $domain_name = $details['domainName'] ?? '';
        $sub_domain = $config['subDomain'] ?? 'mail';

        $quota_used_gb = round(($config['totalQuotaUsed'] ?? 0) / 1024, 2);
        $quota_limit_gb = round(($config['quotaLimit'] ?? 0) / 1024, 2);
        $email_type = get_post_meta($customer_email_id, 'email-type', true);
        $clean_response = [
            'quota' => "{$quota_used_gb} GB / {$quota_limit_gb} GB",
            'accounts' => ($config['accountCurent'] ?? 0) . " / " . ($config['accountLimit'] ?? 0),
            'groups' => ($config['distributionListCurent'] ?? 0) . " / " . ($config['distributionListLimit'] ?? 0),
            'status' => $details['status'] ?? '',
            'expiry_date' => date('d/m/Y', strtotime($details['expireDate'] ?? '')),
            'admin_url' => $details['admin_url'] ?? "https://{$sub_domain}.{$domain_name}/admin",
            'admin_email' => $details['admin_email'] ?? "admin@{$domain_name}",
            'client_url' => $details['client_url'] ?? "https://{$sub_domain}.{$domain_name}",
            'plan' => get_the_title($email_type) ?? ($details['planName'] ?? ''),
            'domain' => $domain_name,
            'created_date' => date('d/m/Y', strtotime($details['issueDate'] ?? '')),
            'is_verified' => get_post_meta($customer_email_id, '_inet_records_verified', true)
        ];

        wp_send_json_success($clean_response);
    }

    public function func_gen_dkim_email_inet()
    {
        global $CDWFunc;
        // check_ajax_referer('ajax-detail-customer-nonce', 'security'); // Assuming this nonce is appropriate

        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }
        if (empty($customer_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu Customer Email ID.']);
        }

        $response = $CDWFunc->inet->gen_dkim($inet_email_id);
        error_log('[iNET Gen DKIM] Response for id ' . $inet_email_id . ': ' . json_encode($response));

        if ($response['success']) {
            $dkim_data = $response['data'];
            update_post_meta($customer_email_id, 'dkim_record_name', $dkim_data['recordName'] ?? '');
            update_post_meta($customer_email_id, 'dkim_record_value', $dkim_data['recordValue'] ?? '');
            update_post_meta($customer_email_id, 'dkim_record_type', $dkim_data['recordType'] ?? '');

            wp_send_json_success(['msg' => $response['msg'], 'data' => $response['data']]);
        } else {
            wp_send_json_error(['msg' => $response['msg'], 'data' => $response['data']]);
        }
        wp_die();
    }

    public function func_reset_email_password_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }

        $response = $CDWFunc->inet->reset_email_password($inet_email_id);

        if ($response['success']) {
            wp_send_json_success(['msg' => $response['msg'], 'newPassword' => $response['data']['newPassword']]);
        } else {
            wp_send_json_error($response);
        }
        wp_die();
    }
}
new AjaxCustomer();
