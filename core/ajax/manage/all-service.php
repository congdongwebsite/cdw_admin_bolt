<?php
defined('ABSPATH') || exit;
class AjaxAllService
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-all-service',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_add-service-cart',  array($this, 'func_add_service_cart'));
        add_action('wp_ajax_ajax_send-email-all-service',  array($this, 'func_send_email'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-all-service-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);
        $fieldSearch = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip'];

        $limit = $_POST['length'];
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        if (empty($type)) {
            $type = ['customer-domain', 'customer-email', 'customer-hosting', 'customer-plugin', 'customer-theme'];
        }
        $arr = array(
            'post_type' => $type,
            'post_status' => 'publish',
            'meta_key' => ['buy_date', 'date'],
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => $limit,
            'offset' => $_POST['start']
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $from_expiry_date = isset($_POST['from_expiry_date']) ? $_POST['from_expiry_date'] : "";
        $until_expiry_date = isset($_POST['until_expiry_date']) ? $_POST['until_expiry_date'] : "";
        $status = isset($_POST['status']) ? $_POST['status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if ($search['value'] != '') {
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
        if (!empty($customer_default_id)) {

            $arr['meta_query'][] =
                array(
                    'key' => "customer-id",
                    'value' => $customer_default_id,
                    'compare' => '=',
                );
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
        switch ($status) {
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

        $wp = new WP_Query($arr);
        $posts = $wp->posts;
        $data = [];
        foreach ($posts as $post) {
            $date_now = $CDWFunc->date->create_datetime_now();
            $item = [];
            $item['id'] = $post->ID;
            $buy_date = get_post_meta($post->ID, 'buy_date', true);
            if (empty($buy_date)) $buy_date = get_post_meta($post->ID, 'date', true);
            $expiry_date = get_post_meta($post->ID, 'expiry_date', true);
            $amount = get_post_meta($post->ID, 'price', true);
            $status = '';
            if (!empty($expiry_date)) {
                $date_domain =  $CDWFunc->date->create_datetime_from_string($expiry_date);

                if ($date_domain >= $date_now) {
                    $status = '<span class="text-primary">Đang hoạt động</span>';
                }
                if ($date_domain < $date_now) {
                    $status = '<span class="text-danger">Hết hạn</span>';
                }
                if ($date_domain >= $date_now && $date_domain <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                    $status = '<span class="text-warning">Sắp hết hạn</span>';
                }
            }
            $buy_date = $CDWFunc->date->convertDateTimeDisplay($buy_date);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($expiry_date);
            $name = 'Chưa xác định';
            switch ($post->post_type) {
                case "customer-domain":
                    $name = get_post_meta($post->ID, 'url', true) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
                    break;
                case "customer-theme":
                    $site_type = get_post_meta($post->ID, 'site-type', true);
                    $name = get_the_title($site_type);
                    break;
                case "customer-email":
                    $name = get_the_title(get_post_meta($post->ID, 'email-type', true)) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
                    break;
                case "customer-plugin":
                    $plugin_type = get_post_meta($post->ID, 'plugin-type', true);
                    $license = get_post_meta($post->ID, 'license', true);
                    $name = get_the_title($plugin_type) . ' - [' . $license . ']';
                    break;
                case "customer-hosting":
                    $name = get_the_title(get_post_meta($post->ID, 'type', true)) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';;
                    break;
            }
            $customer = get_post_meta(get_post_meta($post->ID, 'customer-id', true), 'name', true);
            $item['type'] = $post->post_type;
            $item['type_label'] = $this->get_label_post_type($post->post_type);
            $item['name'] = $name;
            $item['buy_date'] = $buy_date;
            $item['expiry_date'] = $expiry_date;
            $item['customer'] = $customer;
            $item['customer_url'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($post->ID, 'customer-id', true));
            $item['amount'] = $amount;
            $item['status'] = $status;
            $data[] = $item;
        }

        $response = array('success' => true);
        $response['data'] = $data;
        $response['draw'] = $_POST['draw'];
        $response['recordsTotal'] = $wp->found_posts;
        $response['recordsFiltered'] = $wp->found_posts;
        wp_send_json($response);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function get_label_post_type($post_type)
    {
        $labels = get_post_type_object($post_type);
        return $labels->label;
    }

    public function func_add_service_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-all-service-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";
        $items_domains = [];
        $items_emails = [];
        $items_hostings = [];
        $items_plugins = [];
        foreach ($ids as $id) {
            $type = get_post_type($id);
            if (empty($type)) continue;
            // $expiry_date = get_post_meta($id, 'expiry_date', true);
            // if(is_date_greater_than_30_days($expiry_date)){
            //     wp_send_json_error(['msg' => 'Chỉ được thêm dịch vụ hết hạn trong 30 ngày vào giỏ hàng. Vui lòng kiểm tra lại.']);
            // }
            switch ($type) {
                case "customer-domain":
                    $items_domain = AjaxClientCart::get_service_domain_cart([$id]);
                    $items_domains = array_merge($items_domains, $items_domain);
                    break;
                case "customer-theme":
                    break;
                case "customer-email":
                    $items_email = AjaxClientCart::get_service_email_cart([$id]);
                    $items_emails = array_merge($items_emails, $items_email);
                    break;
                case "customer-plugin":
                    $items_plugin = AjaxClientCart::get_service_plugin_cart([$id]);
                    $items_plugins = array_merge($items_plugins, $items_plugin);
                    break;
                case "customer-hosting":
                    $items_hosting = AjaxClientCart::get_service_hosting_cart([$id]);
                    $items_hostings = array_merge($items_hostings, $items_hosting);
                    break;
            }
        }
        if (empty($items_domains) && empty($items_emails) && empty($items_hostings) && empty($items_plugins)) {
            wp_send_json_error(['msg' => 'Dịch vụ không hợp lệ vui lòng liên hệ người bán.']);
        }

        $cart_items = $CDWCart->get();

        if ($CDWFunc->isAdministrator()) {
            foreach ($cart_items as $item) {
                $customer_id = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id)) continue;
                if (empty($customer_default_id)) {
                    $customer_default_id = $customer_id;
                    update_user_meta($userC->ID, 'customer-default-id', $customer_id);
                }
                if ($customer_id != $customer_default_id) {
                    wp_send_json_error(['msg' => 'Chỉ đặt hàng cho 1 khách hàng lần, vui lòng kiểm tra lại giỏ hàng.']);
                    break;
                }
            }
            foreach ($items_domains as $item) {
                $customer_id = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id)) continue;
                if (empty($customer_default_id)) {
                    $customer_default_id = $customer_id;
                    update_user_meta($userC->ID, 'customer-default-id', $customer_id);
                }
                if ($customer_id != $customer_default_id) {
                    wp_send_json_error(['msg' => 'Chỉ đặt hàng cho 1 khách hàng lần, vui lòng kiểm tra lại giỏ hàng.']);
                    break;
                }
            }
            foreach ($items_emails as $item) {
                $customer_id = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id)) continue;
                if (empty($customer_default_id)) {
                    $customer_default_id = $customer_id;
                    update_user_meta($userC->ID, 'customer-default-id', $customer_id);
                }
                if ($customer_id != $customer_default_id) {
                    wp_send_json_error(['msg' => 'Chỉ đặt hàng cho 1 khách hàng lần, vui lòng kiểm tra lại giỏ hàng.']);
                    break;
                }
            }
            foreach ($items_plugins as $item) {
                $customer_id = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id)) continue;
                if (empty($customer_default_id)) {
                    $customer_default_id = $customer_id;
                    update_user_meta($userC->ID, 'customer-default-id', $customer_id);
                }
                if ($customer_id != $customer_default_id) {
                    wp_send_json_error(['msg' => 'Chỉ đặt hàng cho 1 khách hàng lần, vui lòng kiểm tra lại giỏ hàng.']);
                    break;
                }
            }
            foreach ($items_hostings as $item) {
                $customer_id = get_post_meta($item["id"], "customer-id", true);
                if (empty($customer_id)) continue;
                if (empty($customer_default_id)) {
                    $customer_default_id = $customer_id;
                    update_user_meta($userC->ID, 'customer-default-id', $customer_id);
                }
                if ($customer_id != $customer_default_id) {
                    wp_send_json_error(['msg' => 'Chỉ đặt hàng cho 1 khách hàng lần, vui lòng kiểm tra lại giỏ hàng.']);
                    break;
                }
            }
        }
        $CDWCart->addByExsitsField($items_domains, 'domain', 'customer-domain');
        $CDWCart->addByExsitsId($items_emails);
        $CDWCart->addByExsitsId($items_plugins);
        $CDWCart->addByExsitsId($items_hostings);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_send_email()
    {
        global $CDWEmail;
        check_ajax_referer('ajax-all-service-nonce', 'security');

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";
        if (empty($ids))
            wp_send_json_error(['msg' => 'Vui lòng chọn danh sách.']);
        $$customer_id = '';
        foreach ($ids as $id) {
            $customer_id_ = get_post_meta($id, 'customer-id', true);
            if (empty($customer_id_)) {
                wp_send_json_error(['msg' => 'Dịch vụ bị lỗi.']);
            }
            if (!empty($customer_id) && $customer_id_ != $customer_id) {
                wp_send_json_error(['msg' => 'Chỉ chọn 1 khách hàng cho một lần gửi thông báo.']);
            }
            $customer_id = $customer_id_;
        }

        //Hàm gửi email
        $CDWEmail->sendEmailNotificationAllService($ids);
        wp_send_json_success(['msg' => 'Đã gửi email thông báo thành công.']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxAllService();
