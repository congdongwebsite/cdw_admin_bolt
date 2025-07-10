<?php
defined('ABSPATH') || exit;
class AjaxManageReport
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-manage-report-index',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_get-manage-report-hosting-list',  array($this, 'func_get_hosting_list'));
        add_action('wp_ajax_ajax_get-manage-report-billing-list',  array($this, 'func_get_billing_list'));
        add_action('wp_ajax_ajax_get-manage-report-theme-list',  array($this, 'func_get_theme_list'));
        add_action('wp_ajax_ajax_send-email-domain',  array($this, 'func_send_email_domain'));
        add_action('wp_ajax_ajax_send-email-hosting',  array($this, 'func_send_email_hosting'));
        add_action('wp_ajax_ajax_get-manage-report-email-list',  array($this, 'func_get_email_list'));
        add_action('wp_ajax_ajax_get-manage-report-plugin-list',  array($this, 'func_get_plugin_list'));
        add_action('wp_ajax_ajax_send-email-email',  array($this, 'func_send_email_email'));
        add_action('wp_ajax_ajax_check-billing',  array($this, 'func_billing_check'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $columns = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip'];
        $fieldSearch = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip'];
        $arr = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'buy_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $from_expiry_date = isset($_POST['from_expiry_date']) ? $_POST['from_expiry_date'] : "";
        $until_expiry_date = isset($_POST['until_expiry_date']) ? $_POST['until_expiry_date'] : "";
        $domain_status = isset($_POST['domain_status']) ? $_POST['domain_status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search != '') {
            $arr['meta_query']['relation'] = 'OR';
            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
        }

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($from_expiry_date)) {
            $from_expiry_date_d = $CDWFunc->date->convertDateTime($from_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $from_expiry_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_expiry_date)) {
            $until_expiry_date_d = $CDWFunc->date->convertDateTime($until_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $until_expiry_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        switch ($domain_status) {
            case "all":
                break;
            case "runing":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );
                break;
                break;
            case "expired":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<',
                    'type'    => 'DATE'
                );
                break;
            case "closetoexpiration":

                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=',
                    'type'    => 'DATE'
                );
                break;
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($id, $column, true);
            }
            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["url"] .= '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $status = 'Chưa xác định';
            $date = get_post_meta($id, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_domain =  $CDWFunc->date->create_datetime_from_string($date);
            if ($date_domain >= $date_now) {
                $status = '<span class="text-primary">Đang hoạt động</span>';
            }
            if ($date_domain < $date_now) {
                $status = '<span class="text-danger">Hết hạn</span>';
            }
            if ($date_domain >= $date_now && $date_domain <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="text-warning">Sắp hết hạn</span>';
            }

            $item['status'] = $status;
            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['urlDomain'] =  trim($item["url_dns"]);
            $item['action'] = '';
            $item['urlUpdateDNS'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-dns&id=' . $id);
            $item['urlUpdateRecord'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-record&id=' . $id);
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_get_hosting_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-hosting-list-nonce', 'security');

        $columns = ['ip', 'port', 'cpu', 'ram', 'hhd', 'type', 'price', 'buy_date', 'expiry_date'];
        $fieldSearch = ['ip', 'port', 'cpu', 'ram', 'hhd'];
        $arr = array(
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'buy_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $from_expiry_date = isset($_POST['from_expiry_date']) ? $_POST['from_expiry_date'] : "";
        $until_expiry_date = isset($_POST['until_expiry_date']) ? $_POST['until_expiry_date'] : "";
        $domain_status = isset($_POST['domain_status']) ? $_POST['domain_status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search != '') {
            $arr['meta_query']['relation'] = 'OR';
            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
        }

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($from_expiry_date)) {
            $from_expiry_date_d = $CDWFunc->date->convertDateTime($from_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $from_expiry_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_expiry_date)) {
            $until_expiry_date_d = $CDWFunc->date->convertDateTime($until_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $until_expiry_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        switch ($domain_status) {
            case "all":
                break;
            case "runing":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );
                break;
                break;
            case "expired":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<',
                    'type'    => 'DATE'
                );
                break;
            case "closetoexpiration":

                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=',
                    'type'    => 'DATE'
                );
                break;
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($id, $column, true);
            }
            $item["ip"] .= ':' . $item["port"];

            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["type_label"] = get_the_title($item["type"]) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $status = 'Chưa xác định';
            $date = get_post_meta($id, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_hosting =  $CDWFunc->date->create_datetime_from_string($date);
            if ($date_hosting >= $date_now) {
                $status = '<span class="text-primary">Đang hoạt động</span>';
            }
            if ($date_hosting < $date_now) {
                $status = '<span class="text-danger">Hết hạn</span>';
            }
            if ($date_hosting >= $date_now && $date_hosting <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="text-warning">Sắp hết hạn</span>';
            }

            $item['status'] = $status;
            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['urlHosting'] = str_starts_with($item["ip"], 'http') ? $item["ip"] : 'http://' . trim($item["ip"]);
            $item["ip"] .= '<br><small class="text-primary">CPU: ' . $item["cpu"] . '<br>RAM:  ' . $item["ram"] . '<br>HHD:  ' . $item["hhd"] . '</small>';

            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_get_billing_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-billing-list-nonce', 'security');

        $columns = ['code', 'date', 'note', 'amount'];
        $fieldSearch = ['code', 'date', 'note', 'amount'];
        $arr = array(
            'post_type' => 'customer-billing',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $billing_status = isset($_POST['billing_status']) ? $_POST['billing_status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        switch ($billing_status) {
            case "all":
                break;
            case "publish":
            case "pending":
            case "cancel":
            case "success":
                $arr['meta_query'][] = array(
                    'key'     => 'status',
                    'value'   => $billing_status,
                    'compare' => '=',
                );
                break;
        }
        if ($search != '') {
            $arr['meta_query']['relation'] = 'OR';
            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
        }
        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($id, $column, true);
            }

            $item['status'] = 'Tiếp nhận';
            $item['check'] = get_post_meta($id, 'check', true);
            $item['status'] = $CDWFunc->get_lable_status(get_post_meta($id, 'status', true));

            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['urlredirectbilling'] = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_billing_check()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-billing-list-nonce', 'security');

        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách thanh toán']);
        }
        $ids = $_POST['ids'];
        $checked = $_POST['checked'];
        foreach ($ids as $id) {
            update_post_meta($id, 'check', $checked != 'false');
        }

        wp_send_json_success(['msg' => 'Cập nhật đối soát thành công']);

        wp_send_json_error(['msg' => 'Không thành công', 'id' => $id]);

        wp_die();
    }

    public function func_get_theme_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $arr = array(
            'post_type' => 'customer-theme',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search != '') {
            $fieldSearch = ['name', 'sub_domain'];
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

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }


        if (!empty($type)) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
                'type'    => 'type'
            );
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;

            $site_type = get_post_meta($id, 'site-type', true);
            $title = get_the_title($site_type);
            $info = get_post_meta($id, 'name', true);
            $price = get_post_meta($id, 'price', true);
            $date = get_post_meta($id, 'date', true);

            $thumbnail_id = get_post_thumbnail_id($site_type);
            $image =  wp_get_attachment_url($thumbnail_id);

            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['image'] = !$image ? '' : $image;
            $item['title'] = !$title ? '' : $title;
            $item['info'] = $info;
            $item['date'] = $date;
            $item['price'] = $price;
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_send_email_domain()
    {
        global $CDWEmail;
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";
        if (empty($ids))
            wp_send_json_error(['msg' => 'Vui lòng chọn danh sách.']);
        $CDWEmail->sendEmailNotificationDomain($ids);

        wp_send_json_success(['msg' => 'Đã gửi email thông báo thành công.']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_send_email_hosting()
    {
        global $CDWEmail;
        check_ajax_referer('ajax-report-hosting-list-nonce', 'security');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";
        if (empty($ids))
            wp_send_json_error(['msg' => 'Vui lòng chọn danh sách.']);


        //Hàm gửi email
        $CDWEmail->sendEmailNotificationHosting($ids);
        wp_send_json_success(['msg' => 'Đã gửi email thông báo thành công.']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_get_email_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-email-list-nonce', 'security');

        $columns = ['ip', 'port', 'cpu', 'ram', 'hhd', 'email-type', 'price', 'buy_date', 'expiry_date'];
        $fieldSearch = ['ip', 'port', 'cpu', 'ram', 'hhd'];
        $arr = array(
            'post_type' => 'customer-email',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'buy_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $from_expiry_date = isset($_POST['from_expiry_date']) ? $_POST['from_expiry_date'] : "";
        $until_expiry_date = isset($_POST['until_expiry_date']) ? $_POST['until_expiry_date'] : "";
        $domain_status = isset($_POST['domain_status']) ? $_POST['domain_status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search != '') {
            $arr['meta_query']['relation'] = 'OR';
            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
        }

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key' => 'buy_date',
                'value' => $from_date_d,
                'compare' => '>=',
                'type' => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key' => 'buy_date',
                'value' => $until_date_d,
                'compare' => '<=', 'type' => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($from_expiry_date)) {
            $from_expiry_date_d = $CDWFunc->date->convertDateTime($from_expiry_date);
            $arr['meta_query'][] = array(
                'key' => 'expiry_date',
                'value' => $from_expiry_date_d,
                'compare' => '>=',
                'type' => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_expiry_date)) {
            $until_expiry_date_d = $CDWFunc->date->convertDateTime($until_expiry_date);
            $arr['meta_query'][] = array(
                'key' => 'expiry_date',
                'value' => $until_expiry_date_d,
                'compare' => '<=', 'type' => 'DATE'
            );
        }

        switch ($domain_status) {
            case "all":
                break;
            case "runing":
                $arr['meta_query'][] = array(
                    'key' => 'expiry_date',
                    'value' => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type' => 'DATE'
                );
                break;
                break;
            case "expired":
                $arr['meta_query'][] = array(
                    'key' => 'expiry_date',
                    'value' => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<', 'type' => 'DATE'
                );
                break;
            case "closetoexpiration":

                $arr['meta_query'][] = array(
                    'key' => 'expiry_date',
                    'value' => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type' => 'DATE'
                );
                $arr['meta_query'][] = array(
                    'key' => 'expiry_date',
                    'value' => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=', 'type' => 'DATE'
                );
                break;
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($id, $column, true);
            }
            if (!empty($item["port"]))
                $item["ip"] .= ':' . $item["port"];

            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["email-type_label"] = get_the_title($item["email-type"]) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $status = 'Chưa xác định';
            $date = get_post_meta($id, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_email = $CDWFunc->date->create_datetime_from_string($date);
            if ($date_email >= $date_now) {
                $status = '<span class="text-primary">Đang hoạt động</span>';
            }
            if ($date_email < $date_now) {
                $status = '<span class="text-danger">Hết hạn</span>';
            }
            if ($date_email >= $date_now && $date_email <= $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="text-warning">Sắp hết hạn</span>';
            }

            $item['status'] = $status;
            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['urlEmail'] = str_starts_with($item["ip"], 'http') ? $item["ip"] : 'http://' . trim($item["ip"]);

            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_get_plugin_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-plugin-list-nonce', 'security');

        $arr = array(
            'post_type' => 'customer-plugin',
            'post_status' => 'publish',
            'fields' => 'ids',
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search != '') {
            $fieldSearch = ['name', 'sub_domain'];
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

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }


        if (!empty($type)) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
                'type'    => 'type'
            );
        }

        $ids = get_posts($arr);
        $data = [];
        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;

            $plugin_type = get_post_meta($id, 'plugin-type', true);
            $title = get_the_title($plugin_type);
            $info = get_post_meta($id, 'name', true);
            $price = get_post_meta($id, 'price', true);
            $date = get_post_meta($id, 'date', true);

            $thumbnail_id = get_post_thumbnail_id($plugin_type);
            $image =  wp_get_attachment_url($thumbnail_id);

            $customer = get_post_meta(get_post_meta($id, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($id, 'customer-id', true));
            $item['image'] = !$image ? '' : $image;
            $item['title'] = !$title ? '' : $title;
            $item['info'] = $info;
            $item['date'] = $date;
            $item['price'] = $price;
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_send_email_email()
    {
        global $CDWEmail;
        check_ajax_referer('ajax-report-email-list-nonce', 'security');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";
        if (empty($ids))
            wp_send_json_error(['msg' => 'Vui lòng chọn danh sách.']);


        //Hàm gửi email
        $CDWEmail->sendEmailNotificationEmail($ids);
        wp_send_json_success(['msg' => 'Đã gửi email thông báo thành công.']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxManageReport();
