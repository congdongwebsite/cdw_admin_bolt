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
            $note = isset($_POST['note']) ? $_POST['note'] : '';

            add_post_meta($id, 'name', $name);
            add_post_meta($id, 'company_name', $company_name);
            add_post_meta($id, 'mst', $mst);
            add_post_meta($id, 'phone', $phone);
            add_post_meta($id, 'email', $email);
            add_post_meta($id, 'dvhc_tp', $dvhc_tp);
            add_post_meta($id, 'dvhc_qh', $dvhc_qh);
            add_post_meta($id, 'dvhc_px', $dvhc_px);
            add_post_meta($id, 'address', $address);
            add_post_meta($id, 'cmnd', $cmnd);
            add_post_meta($id, 'note', $note);

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
            $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price'];
            $emailColumnDates = ['buy_date', 'expiry_date'];
            $emails = $CDWFunc->wpdb->func_new_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);

            //Plugin
            $plugins = isset($_POST['plugins']) ? $_POST['plugins'] : [];
            $pluginColumns = ['name', 'price', 'plugin-type'];
            $pluginColumnDates = ['date'];
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

        wp_send_json_success([
            'msg' => 'Tải ảnh riêng tư thành công',
            'id'  => $id_exsits
        ]);

        wp_die();
    }
    public function func_update()
    {
        global $CDWFunc, $CDWEmail;
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
            $note = isset($_POST['note']) ? $_POST['note'] : '';

            update_post_meta($id, 'name', $name);
            update_post_meta($id, 'company_name', $company_name);
            update_post_meta($id, 'mst', $mst);
            update_post_meta($id, 'phone', $phone);
            update_post_meta($id, 'email', $email);
            update_post_meta($id, 'dvhc_tp', $dvhc_tp);
            update_post_meta($id, 'dvhc_qh', $dvhc_qh);
            update_post_meta($id, 'dvhc_px', $dvhc_px);
            update_post_meta($id, 'address', $address);
            update_post_meta($id, 'cmnd', $cmnd);
            update_post_meta($id, 'note', $note);

            //Hosting
            $hostings = isset($_POST['hostings']) ? $_POST['hostings'] : [];
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
            $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price'];
            $emailColumnDates = ['buy_date', 'expiry_date'];
            $emails = $CDWFunc->wpdb->func_update_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);

            //Plugin
            $plugins = isset($_POST['plugins']) ? $_POST['plugins'] : [];
            $pluginColumns = ['name', 'price', 'plugin-type'];
            $pluginColumnDates = ['date'];
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

                        //$CDWEmail->sendEmailOrderComplete($idBilling);
                        // update_post_meta($idBilling, "email-success-sended", true);
                    }
                }
                //Cập nhật thông tin theo hóa đơn

                if ($status == 'success' && !get_post_meta($idBilling, 'is-update', true)) {
                    $items = get_post_meta($idBilling, 'items', true);
                    if (is_array($items))
                        foreach ($items as $key => $item) {
                            switch ($item["type"]) {
                                case "customer-domain":
                                    $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                                    $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                                    update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                                    // $note = "Gia hạn domain từ ngày " . $CDWFunc->date->convertDateTime($expiry_date, $CDWFunc->date->formatDB, $CDWFunc->date->format) . " tới ngày " . $CDWFunc->date->convertDateTime($expiry_date_new, $CDWFunc->date->formatDB, $CDWFunc->date->format);
                                    // $CDWFunc->wpdb->save_history($item["id"], $id, 'Gia hạn domain ', 'Domain:' . get_post_meta($item["id"], 'url', true), 'success', $CDWFunc->date->getCurrentDateTime($CDWFunc->date->formatDB), $note);

                                    break;

                                case "customer-hosting":
                                    $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                                    $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                                    update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                                    // $note = "Gia hạn domain từ ngày " . $CDWFunc->date->convertDateTime($expiry_date, $CDWFunc->date->formatDB, $CDWFunc->date->format) . " tới ngày " . $CDWFunc->date->convertDateTime($expiry_date_new, $CDWFunc->date->formatDB, $CDWFunc->date->format);
                                    // $CDWFunc->wpdb->save_history($item["id"], $id, 'Gia hạn hosting ', 'Hosting:' . get_post_meta($item["id"], 'ip', true) . ',' . get_post_meta($item["id"], 'port', true), 'success', $CDWFunc->date->getCurrentDateTime($CDWFunc->date->formatDB), $note);

                                    break;


                                case "customer-email":
                                    $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                                    $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                                    update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                                    break;

                                case "manage-hosting":
                                    //Hosting
                                    $buy_date =  $CDWFunc->date->getCurrentDateTime();
                                    $quantity = (float)$item["quantity"];
                                    $hostings = [
                                        [
                                            'ip' => "",
                                            'port' => "",
                                            'user' => "",
                                            'pass' => "",
                                            'type' => $item["id"],
                                            'price' => (float)$item["price"],
                                            'buy_date' => $buy_date,
                                            'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                                        ]
                                    ];

                                    if (is_array($hostings) && count($hostings) > 0) {
                                        $hostingColumns = ['ip', 'port', 'user', 'pass', 'type', 'price'];
                                        $hostingColumnDates = ['buy_date', 'expiry_date'];
                                        $hostings = $CDWFunc->wpdb->func_new_detail_post('customer-hosting', 'customer-id', $id, $hostings, $hostingColumns);
                                        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-hosting', 'customer-id', $id, $hostings, $hostingColumnDates);
                                    }
                                    break;

                                case "manage-email":
                                    $buy_date =  $CDWFunc->date->getCurrentDateTime();
                                    $quantity = (float)$item["quantity"];
                                    $emails = [
                                        [
                                            'url_admin' => "",
                                            'url_client' => "",
                                            'user' => "",
                                            'pass' => "",
                                            'email-type' => $item["id"],
                                            'price' => (float)$item["price"],
                                            'buy_date' => $buy_date,
                                            'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                                        ]
                                    ];

                                    if (is_array($emails) && count($emails) > 0) {
                                        $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price'];
                                        $emailColumnDates = ['buy_date', 'expiry_date'];
                                        $emails = $CDWFunc->wpdb->func_new_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
                                        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);
                                    }
                                    break;
                                case "manage-domain":

                                    $buy_date =  $CDWFunc->date->getCurrentDateTime();
                                    $quantity = (float)$item["quantity"];
                                    $domains = [
                                        [
                                            'url_dns' => "",
                                            'ip' => "",
                                            'user' => "",
                                            'pass' => "",
                                            'url' => $item["service"],
                                            'price' => (float)$item["price"],
                                            'buy_date' => $buy_date,
                                            'domain-type' => empty($item["id"]) ? "" : $item["id"],
                                            'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                                            'note' => "",
                                        ]
                                    ];

                                    if (is_array($domains) && count($domains) > 0) {

                                        $domainColumns = ['url', 'price', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type'];
                                        $domainColumnDates = ['buy_date', 'expiry_date'];
                                        $domains = $CDWFunc->wpdb->func_new_detail_post('customer-domain', 'customer-id', $id, $domains, $domainColumns);
                                        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-domain', 'customer-id', $id, $domains, $domainColumnDates);
                                    }

                                    break;
                                case 'site-managers':

                                    $buy_date =  $CDWFunc->date->getCurrentDateTime();
                                    $quantity = (float)$item["quantity"];
                                    $themes = [
                                        [
                                            'name' => $quantity . ' x ' . $item["service"],
                                            'site-type' => empty($item["id"]) ? "" : $item["id"],
                                            'price' => (float) $item["price"],
                                            'date' => $buy_date
                                        ]
                                    ];

                                    if (is_array($themes) && count($themes) > 0) {
                                        $themeColumns = ['name', 'price', 'site-type'];
                                        $themeColumnDates = ['date'];
                                        $themes =  $CDWFunc->wpdb->func_new_detail_post('customer-theme', 'customer-id', $id, $themes, $themeColumns);
                                        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-theme', 'customer-id', $id, $themes, $themeColumnDates);
                                    }
                                    break;
                            }
                        }
                    update_post_meta($idBilling, 'is-update', true);
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

        $columns = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'buy_date');

        foreach ($data as $key => $value) {
            $item_label = "";
            if (!empty($value['domain-type'])) $item_label = get_the_title($value['domain-type']);
            $data[$key]["domain-type_label"] = $item_label;
            $data[$key]['action'] = '';
            $data[$key]['urlUpdateDNS'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-dns&id=' . $value['id']);
            $data[$key]['urlUpdateRecord'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-record&id=' . $value['id']);
        }

        wp_send_json_success($data);
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

        $columns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'buy_date', 'expiry_date', 'price'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'customer-id', $_POST['id'], $columns, 'buy_date');
        foreach ($data as $key => $value) {
            $data[$key]["email-type_label"] = get_the_title($value["email-type"]);
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

        $columns = ['date', 'name', 'price', 'plugin-type'];

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

        $user = get_user_by('email', $email);
        if ($user) {
            wp_set_password($random_password, $user->ID);
        } else {
            $user = wp_create_user($email, $random_password, $email);
        }

        if (!$user) {
            wp_send_json_error(['msg' => 'Lỗi không tạo được tài khoản!']);
        } else {

            $result["id"] = $user->ID;
            $result["username"] = $email;
            $result["password"] =  $random_password;

            update_post_meta($id, 'user-id', $user->ID);
            update_user_meta($user->ID, 'customer-id', $id);
            update_user_meta($user->ID, 'first_name', get_post_meta($id, 'name', true));
            update_user_meta($user->ID, 'phone', get_post_meta($id, 'phone', true));
            update_user_meta($user->ID, 'address', get_post_meta($id, 'address', true));

            wp_send_json_success(['msg' => 'Tạo thành công', 'user' => (object)$result]);
        }

        wp_die();
    }
    public function delete_images_not_exsits($idParent, $id_exsits, $force_delete = true)
    {
        global $CDWFunc;
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
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-customer-nonce', 'security');

        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $id = $_POST['id'];
        $items = get_post_meta($id, 'items', true);
        if (!get_post_meta($id, 'is-update', true)) {
            $customer_billing_id = get_post_meta($id, "customer-id", true);
            foreach ($items as $key => $item) {
                switch ($item["type"]) {
                    case "customer-domain":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                        // $note = "Gia hạn domain từ ngày " . $CDWFunc->date->convertDateTime($expiry_date, $CDWFunc->date->formatDB, $CDWFunc->date->format) . " tới ngày " . $CDWFunc->date->convertDateTime($expiry_date_new, $CDWFunc->date->formatDB, $CDWFunc->date->format);
                        // $CDWFunc->wpdb->save_history($item["id"], $id, 'Gia hạn domain ', 'Domain:' . get_post_meta($item["id"], 'url', true), 'success', $CDWFunc->date->getCurrentDateTime($CDWFunc->date->formatDB), $note);

                        break;

                    case "customer-hosting":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                        // $note = "Gia hạn domain từ ngày " . $CDWFunc->date->convertDateTime($expiry_date, $CDWFunc->date->formatDB, $CDWFunc->date->format) . " tới ngày " . $CDWFunc->date->convertDateTime($expiry_date_new, $CDWFunc->date->formatDB, $CDWFunc->date->format);
                        // $CDWFunc->wpdb->save_history($item["id"], $id, 'Gia hạn hosting ', 'Hosting:' . get_post_meta($item["id"], 'ip', true) . ',' . get_post_meta($key, 'port', true), 'success', $CDWFunc->date->getCurrentDateTime($CDWFunc->date->formatDB), $note);

                        break;

                    case "customer-email":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                        break;


                    case "manage-hosting":
                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $hostings = [
                            [
                                'ip' => "",
                                'port' => "",
                                'user' => "",
                                'pass' => "",
                                'type' => $item["id"],
                                'price' => (float)$item["price"],
                                'buy_date' => $buy_date,
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                            ]
                        ];

                        if (is_array($hostings) && count($hostings) > 0) {
                            $hostingColumns = ['ip', 'port', 'user', 'pass', 'type', 'price'];
                            $hostingColumnDates = ['buy_date', 'expiry_date'];
                            $hostings = $CDWFunc->wpdb->func_new_detail_post('customer-hosting', 'customer-id', $customer_billing_id, $hostings, $hostingColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-hosting', 'customer-id', $customer_billing_id, $hostings, $hostingColumnDates);
                        }
                        break;

                    case "manage-email":
                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $emails = [
                            [
                                'ip' => "",
                                'port' => "",
                                'user' => "",
                                'pass' => "",
                                'email-type' => $item["id"],
                                'price' => (float)$item["price"],
                                'buy_date' => $buy_date,
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                            ]
                        ];

                        if (is_array($emails) && count($emails) > 0) {
                            $emailColumns = ['ip', 'port', 'user', 'pass', 'email-type', 'price'];
                            $emailColumnDates = ['buy_date', 'expiry_date'];
                            $emails = $CDWFunc->wpdb->func_new_detail_post('customer-email', 'customer-id', $customer_billing_id, $emails, $emailColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $customer_billing_id, $emails, $emailColumnDates);
                        }
                        break;
                    case "manage-domain":
                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $domains = [
                            [
                                'url_dns' => "",
                                'ip' => "",
                                'user' => "",
                                'pass' => "",
                                'url' => $item["service"],
                                'price' => (float)$item["price"],
                                'buy_date' => $buy_date,
                                'domain-type' => empty($item["id"]) ? "" : $item["id"],
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                            ]
                        ];
                        if (is_array($domains) && count($domains) > 0) {
                            $domainColumns = ['url', 'price', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type'];
                            $domainColumnDates = ['buy_date', 'expiry_date'];
                            $domains = $CDWFunc->wpdb->func_new_detail_post('customer-domain', 'customer-id', $customer_billing_id, $domains, $domainColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-domain', 'customer-id', $customer_billing_id, $domains, $domainColumnDates);
                        }
                        break;

                    case 'site-managers':

                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $themes = [
                            [
                                'name' => $quantity . ' x ' . $item["service"],
                                'price' => (float) $item["price"],
                                'site-type' => empty($item["id"]) ? "" : $item["id"],
                                'date' => $buy_date
                            ]
                        ];

                        if (is_array($themes) && count($themes) > 0) {
                            $themeColumns = ['name', 'price', 'site-type'];
                            $themeColumnDates = ['date'];
                            $themes =  $CDWFunc->wpdb->func_new_detail_post('customer-theme', 'customer-id', $customer_billing_id, $themes, $themeColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-theme', 'customer-id', $customer_billing_id, $themes, $themeColumnDates);
                        }
                        break;


                    case 'plugin':

                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $plugins = [
                            [
                                'name' => $quantity . ' x ' . $item["service"],
                                'price' => (float) $item["price"],
                                'plugin-type' => empty($item["id"]) ? "" : $item["id"],
                                'date' => $buy_date
                            ]
                        ];

                        if (is_array($plugins) && count($plugins) > 0) {
                            $pluginColumns = ['name', 'price', 'plugin-type'];
                            $pluginColumnDates = ['date'];
                            $plugins =  $CDWFunc->wpdb->func_new_detail_post('customer-plugin', 'customer-id', $customer_billing_id, $plugins, $pluginColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-plugin', 'customer-id', $customer_billing_id, $plugins, $pluginColumnDates);
                        }
                        break;
                }
            }

            $this->setFeatureStatus(true);
            update_post_meta($id, 'is-update', true);
        } else {
            // update_post_meta($id, 'is-update', false);
            // update_post_meta($id, 'histories', []);
            wp_send_json_error(['msg' => 'Đã cập nhật thông tin từ tờ hóa đơn này.']);
        }

        wp_send_json_success(['msg' => 'Cập nhật thông tin thành công']);
        wp_send_json_error(['msg' => 'Lỗi cập nhật']);

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
}

new AjaxCustomer();
