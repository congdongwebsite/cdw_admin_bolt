<?php
defined('ABSPATH') || exit;
class IndexAdmin
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_ping',  array($this, 'func_ping'));
        add_action('wp_ajax_nopriv_ajax_ping',  array($this, 'func_ping'));
        add_action('wp_ajax_ajax_feature-info',  array($this, 'func_feature_info'));
        add_action('wp_ajax_ajax_page-secondary',  array($this, 'func_page_secondary'));
        add_action('wp_ajax_ajax_page-list-vps',  array($this, 'func_page_list_vps'));
        add_action('wp_ajax_ajax_page-list-domain',  array($this, 'func_page_list_domain'));
        add_action('wp_ajax_ajax_page-secondary-user',  array($this, 'func_page_secondary_user'));
        add_action('wp_ajax_ajax_page-list-hosting-user',  array($this, 'func_page_list_hosting_user'));
        add_action('wp_ajax_ajax_page-list-domain-user',  array($this, 'func_page_list_domain_user'));
        add_action('wp_ajax_ajax_page-list-billing-user',  array($this, 'func_page_list_billing_user'));
        add_action('wp_ajax_ajax_page-load-widget-data-money',  array($this, 'func_page_load_widget_data_money'));
        add_action('wp_ajax_ajax_admin-update-customer-default',  array($this, 'func_admin_update_customer_default'));
    }
    public function func_ping()
    {
        global $CDWCart, $CDWNotification, $CDWFunc;
        //check_ajax_referer('ajax-index-nonce', 'security');
        $result = new stdClass();

        $userC = wp_get_current_user();
        $result->ping = is_user_logged_in();
        if ($result->ping) {
            $result->cart = $CDWCart->getStatus();
            $result->notification = $CDWNotification->getStatus();
            $result->feature = $this->getFeatureStatus();
        }
        $result->urlLogin = $CDWFunc->getUrl('login', 'lock');

        wp_send_json_success($result);
        wp_send_json_error(['msg' => 'Lỗi thông ping tới server, vui lòng tải lại']);

        wp_die();
    }
    public function getFeatureStatus()
    {
        $userC = wp_get_current_user();
        return get_user_meta($userC->ID, "feature-status", true);
    }
    public function setFeatureStatus($status, $user_id = "")
    {
        if (empty($user_id)) {
            $userC = wp_get_current_user();
            $user_id = $userC->ID;
        }
        return update_user_meta($user_id, "feature-status", $status);
    }
    public function func_feature_info()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');
        if ($CDWFunc->isAdministrator())
            $data = $this->get_feature_info();
        else
            $data = $this->get_feature_info_user();
        $template = 'user-feature-info-template';

        $this->setFeatureStatus(false);
        wp_send_json_success(['data' => $data, 'template' => $template]);

        wp_die();
    }
    public function get_feature_info()
    {
        global $CDWFunc;
        $data = new stdClass();

        $arrTicket = array(
            'post_type' => 'ticket',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );
        $arrHosting = array(
            'post_type' => 'customer-hosting',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $arrEmail = array(
            'post_type' => 'customer-email',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $arrDomain = array(
            'post_type' => 'customer-domain',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $arrTheme = array(
            'post_type' => 'customer-theme',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $arrPlugin = array(
            'post_type' => 'customer-plugin',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $arrReceipt = array(
            'post_type' => 'receipt',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );
        $arrPayment = array(
            'post_type' => 'payment',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );
        $arrBilling = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "status",
                    'value' => 'success',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );

        $idReceipts = get_posts($arrReceipt);
        $idPayments = get_posts($arrPayment);
        $idBillings = get_posts($arrBilling);

        $postTickets = get_posts($arrTicket);
        $postHostings = get_posts($arrHosting);
        $postEmails = get_posts($arrEmail);
        $postDomains = get_posts($arrDomain);
        $postThemes = get_posts($arrTheme);
        $postPlugins = get_posts($arrPlugin);

        $amountReceipt = $CDWFunc->wpdb->get_total_receipts($idReceipts);
        $amountPayment = $CDWFunc->wpdb->get_total_payments($idPayments);
        $totalBilling = $CDWFunc->wpdb->get_total_billings($idBillings);

        $data->info1 = ['found' => true, 'class' => 'col-4', 'text' => 'Hỗ trợ', 'value' => count($postTickets), 'title' => 'Số hỗ trợ'];
        $data->info2 = ['found' => true, 'class' => 'col-4', 'text' => 'Đơn hàng', 'value' => count($postHostings) + count($postEmails) +  count($postDomains) + count($postThemes) + count($postPlugins), 'title' => 'Số dịch vụ gồm domain + vps + theme...'];
        $data->info3 = ['found' => true, 'class' => 'col-4', 'text' => 'Doanh thu', 'value' => $CDWFunc->number->amount($totalBilling + $amountReceipt - $amountPayment), 'title' => 'Tổng số tiền dịch vụ domain + vps + theme...'];
        return $data;
    }
    public function get_feature_info_user()
    {
        global $CDWFunc;
        $data = new stdClass();

        $user_id = wp_get_current_user()->ID;
        $id_customer = get_user_meta($user_id, 'customer-id', true);

        $arrHosting = array(
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );

        $arrEmail = array(
            'post_type' => 'customer-email',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $arrDomain = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $arrTheme = array(
            'post_type' => 'customer-theme',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $arrPlugin = array(
            'post_type' => 'customer-plugin',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );


        $arrBilling = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                ),
                array(
                    'key' => "status",
                    'value' => 'success',
                    'compare' => '=',
                )
            )
        );

        $postHostings = get_posts($arrHosting);
        $postEmails = get_posts($arrEmail);
        $postDomains = get_posts($arrDomain);
        $postThemes = get_posts($arrTheme);
        $postPlugins = get_posts($arrPlugin);
        $postBillings = get_posts($arrBilling);
        $revenue = 0;
        foreach ($postBillings as $postBilling) {
            $revenue += (float) get_post_meta($postBilling, 'amount', true);
        }


        $data->info1 = ['found' => true, 'class' => 'col-6', 'text' => 'Đơn hàng', 'value' => count($postHostings) + count($postEmails) + count($postDomains) + count($postThemes) + count($postPlugins), 'title' => 'Số dịch vụ gồm domain + vps + theme...'];
        $data->info2 = ['found' => true, 'class' => 'col-6', 'text' => 'Chi phí', 'value' =>  $CDWFunc->number->amount($revenue), 'title' => 'Tổng số tiền dịch vụ domain + vps + theme...'];
        $data->info3 = ['found' => false];
        return $data;
    }
    public function func_page_load_widget_data_money()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $arrReceipt = array(
            'post_type' => 'receipt',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );
        $arrPayment = array(
            'post_type' => 'payment',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );
        $arrBilling = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "status",
                    'value' => 'success',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );


        $idReceipts = get_posts($arrReceipt);
        $idPayments = get_posts($arrPayment);
        $idBillings = get_posts($arrBilling);

        $amountReceipt = $CDWFunc->wpdb->get_total_receipts($idReceipts);
        $amountPayment = $CDWFunc->wpdb->get_total_payments($idPayments);
        $totalBilling = $CDWFunc->wpdb->get_total_billings($idBillings);

        $data = [
            "dt" => $CDWFunc->number_format($totalBilling),
            "ps_thu" => $CDWFunc->number_format($amountReceipt),
            "ps_chi" => $CDWFunc->number_format($amountPayment),
            "ck" => $CDWFunc->number_format($totalBilling + $amountReceipt -  $amountPayment),
        ];

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_page_secondary()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');

        $user_id = wp_get_current_user()->ID;
        if ($user_id < 1)
            wp_send_json_error(['msg' => 'Không tìm thấy tài khoản cần cập nhật']);

        $data = (object) [
            'item1_value' => (float) 0,
            'item1_level' => "",
            'item1_percent' => (float) 0,
            'item2_value' => (float) 0,
            'item2_level' => "",
            'item2_percent' => (float) 0,
            'item3_value' => (float) 0,
            'item3_level' => "",
            'item3_percent' => (float) 0,
            'item4_value' => (float) 0,
            'item4_level' => "",
            'item4_percent' => (float) 0,
        ];

        $data->item1_value = 0;
        $data->item1_level = "up";
        $data->item1_percent = (float)0;
        $data->item2_value = 0;
        $data->item2_level = "down";
        $data->item2_percent = (float)0;
        $data->item3_value = 0;
        $data->item3_level = "";
        $data->item3_percent = (float)0;
        $data->item4_value = 0;
        $data->item4_level = "up";
        $data->item4_percent = (float)0;



        $arrTicket = array(
            'post_type' => 'ticket',
            'fields' => 'ids',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );

        $arrHosting = array(
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,

        );
        $arrDomain = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );
        $arrTheme = array(
            'post_type' => 'customer-theme',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );

        $arrBilling = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "status",
                    'value' => 'success',
                    'compare' => '=',
                )
            )
        );

        $postTickets = get_posts($arrTicket);
        $postHostings = get_posts($arrHosting);
        $postDomains = get_posts($arrDomain);
        $postThemes = get_posts($arrTheme);

        $postBillings = get_posts($arrBilling);

        foreach ($postBillings as $postBilling) {
            $data->item4_value += (float) get_post_meta($postBilling, 'amount', true);
        }
        $data->item1_value = count($postHostings) + count($postDomains) + count($postThemes);

        $data->item2_value = count($postBillings);
        $data->item3_value = count($postTickets);
        $data->item4_value = number_format($data->item4_value, 0, ',', '.');

        wp_send_json_success($data);

        wp_die();
    }
    public function func_page_list_vps()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');

        $arr = array(
            'post_type' => 'manage-vps',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => 10,
            'meta_key' => 'service_expiry_date',
            'orderby' => 'meta_value_num',
            'meta_type' => 'DATE',
            'order' => 'DESC'
        );
        $arr['meta_query'] = array(
            'relation'   => 'or',
            array(
                'key'     => 'expiry_date',
                'value'   => $CDWFunc->date->getCurrentDateTime(),
                'compare' => '<',
                'type'    => 'DATE'
            ),
            array(
                'relation'   => 'and',
                array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                ),
                array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {

            $cpu = get_post_meta($id, 'cpu', true);
            $ram = get_post_meta($id, 'ram', true);
            $hhd = get_post_meta($id, 'hhd', true);
            $data[] = (object) ["name" =>  get_post_meta($id, 'ip', true), "info" => "CPU-" . $cpu . "/RAM-" . $ram . "/HHD-" . $hhd];
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_page_list_domain()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');

        $arr = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'meta_key' => 'expiry_date',
            'orderby' => 'meta_value_num',
            'meta_type' => 'DATE',
            'order' => 'DESC',
            'fields' => 'ids',
            'posts_per_page' => -1
        );
        $arr['meta_query'] = array(
            'relation'   => 'or',
            // array(
            //     'key'     => 'expiry_date',
            //     'value'   => $CDWFunc->date->getCurrentDateTime(),
            //     'compare' => '<',
            //     'type'    => 'DATE'
            // ),
            array(
                'relation'   => 'and',
                array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                ),
                array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=',
                    'type'    => 'DATE'
                )
            )
        );


        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {

            $url = get_post_meta($id, 'url', true);
            $buy_date = get_post_meta($id, 'buy_date', true);
            $expiry_date = get_post_meta($id, 'expiry_date', true);

            $status = 'Chưa xác định';
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_domain =  $CDWFunc->date->create_datetime_from_string($expiry_date);
            if ($date_domain >= $date_now) {
                $status = 'Đang hoạt động';
            }
            if ($date_domain < $date_now) {
                $status = 'Hết hạn';
            }
            if ($date_domain >= $date_now && $date_domain <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = 'Sắp hết hạn';
            }
            $data[] = (object) ["domain" => $url, "status" => $status, "buy_date" => $CDWFunc->date->convertDateTimeDisplay($buy_date), "expiry_date" => $CDWFunc->date->convertDateTimeDisplay($expiry_date)];
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_page_secondary_user()
    {
        check_ajax_referer('ajax-index-nonce', 'security');

        $user_id = wp_get_current_user()->ID;
        if ($user_id < 1)
            wp_send_json_error(['msg' => 'Không tìm thấy tài khoản cần cập nhật']);

        $data = (object) [
            'item1_value' => (float) 0,
            'item2_value' => (float) 0,
            'item3_value' => (float) 0,
            'item4_value' => (float) 0
        ];

        $id_customer = get_user_meta($user_id, 'customer-id', true);

        $arrHosting = array(
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $arrDomain = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $arrTheme = array(
            'post_type' => 'customer-theme',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );

        $arrBilling = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                ),
                array(
                    'key' => "status",
                    'value' => 'success',
                    'compare' => '=',
                )
            )
        );

        $postHostings = get_posts($arrHosting);
        $postDomains = get_posts($arrDomain);
        $postThemes = get_posts($arrTheme);

        $postBillings = get_posts($arrBilling);

        foreach ($postBillings as $postBilling) {
            $data->item4_value += (float) get_post_meta($postBilling, 'amount', true);
        }
        $data->item1_value = count($postHostings) + count($postDomains) + count($postThemes);

        $data->item2_value = count($postBillings);
        $data->item3_value = 0;
        $data->item4_value = number_format($data->item4_value, 0, ',', '.');

        wp_send_json_success($data);

        wp_die();
    }
    public function func_page_list_hosting_user()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');

        $user_id = wp_get_current_user()->ID;

        $id_customer = get_user_meta($user_id, 'customer-id', true);

        $arr = array(
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'meta_key' => 'expiry_date',
            'orderby' => 'meta_value_num',
            'meta_type' => 'DATE',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        // $arr['meta_query'][] = array(
        //     'relation'   => 'or',
        //     array(
        //         'key'     => 'expiry_date',
        //         'value'   => $CDWFunc->date->getCurrentDateTime(),
        //         'compare' => '<',
        //         'type'    => 'DATE'
        //     ),
        //     array(
        //         'relation'   => 'and',
        //         array(
        //             'key'     => 'expiry_date',
        //             'value'   => $CDWFunc->date->getCurrentDateTime(),
        //             'compare' => '>=',
        //             'type'    => 'DATE'
        //         ), array(
        //             'key'     => 'expiry_date',
        //             'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
        //             'compare' => '<=',
        //             'type'    => 'DATE'
        //         )
        //     )
        // );
        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {

            $cpu = get_post_meta($id, 'cpu', true);
            $ram = get_post_meta($id, 'ram', true);
            $hhd = get_post_meta($id, 'hhd', true);
            $data[] = (object) ["name" =>  get_post_meta($id, 'ip', true), "info" => "CPU-" . $cpu . "/RAM-" . $ram . "/HHD-" . $hhd];
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_page_list_domain_user()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');

        $user_id = wp_get_current_user()->ID;
        $id_customer = get_user_meta($user_id, 'customer-id', true);

        $arr = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'meta_key' => 'expiry_date',
            'orderby' => 'meta_value_num',
            'meta_type' => 'DATE',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        // $arr['meta_query'][] = array(
        //     'relation'   => 'or',
        //     array(
        //         'key'     => 'expiry_date',
        //         'value'   => $CDWFunc->date->getCurrentDateTime(),
        //         'compare' => '<',
        //         'type'    => 'DATE'
        //     ),
        //     array(
        //         'relation'   => 'and',
        //         array(
        //             'key'     => 'expiry_date',
        //             'value'   => $CDWFunc->date->getCurrentDateTime(),
        //             'compare' => '>=',
        //             'type'    => 'DATE'
        //         ), array(
        //             'key'     => 'expiry_date',
        //             'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
        //             'compare' => '<=',
        //             'type'    => 'DATE'
        //         )
        //     )
        // );
        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {

            $url = get_post_meta($id, 'url', true);
            $buy_date = get_post_meta($id, 'buy_date', true);
            $expiry_date = get_post_meta($id, 'expiry_date', true);

            $status = 'Chưa xác định';
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_domain =  $CDWFunc->date->create_datetime_from_string($expiry_date);
            if ($date_domain >= $date_now) {
                $status = 'Đang hoạt động';
            }
            if ($date_domain < $date_now) {
                $status = 'Hết hạn';
            }
            if ($date_domain >= $date_now && $date_domain <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = 'Sắp hết hạn';
            }
            $data[] = (object) ["domain" => $url, "status" => $status, "buy_date" => $CDWFunc->date->convertDateTimeDisplay($buy_date), "expiry_date" => $CDWFunc->date->convertDateTimeDisplay($expiry_date)];
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_page_list_billing_user()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-index-nonce', 'security');
        $user_id = wp_get_current_user()->ID;
        $id_customer = get_user_meta($user_id, 'customer-id', true);
        $arr = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'posts_per_page' => 10,
            'meta_key' => 'date',
            'orderby' => 'meta_value_num',
            'meta_type' => 'DATE',
            'order' => 'DESC',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => "customer-id",
                    'value' => $id_customer,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {

            $status = get_post_meta($id, 'status', true);
            $code = get_post_meta($id, 'code', true);
            $date = get_post_meta($id, 'date', true);
            $note = get_post_meta($id, 'note', true);
            $amount = get_post_meta($id, 'amount', true);

            $status = $CDWFunc->get_lable_status($status);

            $data[] = (object) ["status" => $status, "code" => $code, "date" => $CDWFunc->date->convertDatetimeDisplay($date), "note" => $note, "amount" =>  $CDWFunc->number_format($amount)];
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_admin_update_customer_default()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');
        if ($CDWFunc->isAdministrator()) {
            $userID = wp_get_current_user()->ID;
            $id = isset($_POST['id']) ? $_POST['id'] : "";

            $cart_items = $CDWCart->get();
            $customer_id = "";
            foreach ($cart_items as $item) {
                $customer_id_cart = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id_cart)) continue;
                $customer_id = $customer_id_cart;
            }

            if (!empty($customer_id) && !empty($id) && $id != $customer_id) {
                wp_send_json_error(['msg' => 'Giỏ hàng hiện tại đang có dữ liệu, không thể thay đổi khách hàng.<br>Vui lòng tải lại trang.']);
            }
            if (empty($id) || !is_numeric($id)) {
                delete_user_meta($userID, 'customer-default-id',  $id);
            } else {
                if (!get_post_status($id)) {
                    wp_send_json_error(['msg' => 'Không tìm thấy khách hàng yêu cầu']);
                }
                update_user_meta($userID, 'customer-default-id',  $id);
            }
            wp_send_json_success(['msg' => 'Đổi khách hàng thành công', 'id' => $id]);
        } else {
            wp_send_json_error(['msg' => 'Bạn không có quyền']);
        }

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}

new IndexAdmin();
