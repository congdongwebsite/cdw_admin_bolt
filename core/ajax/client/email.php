<?php
defined('ABSPATH') || exit;
class AjaxClientEmail
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-client-email',  array($this, 'func_get_list'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-email-nonce', 'security');

        $columns = ['url_admin', 'url_client', 'user', 'pass',  'email-type', 'price', 'buy_date', 'expiry_date'];
        $fieldSearch = ['ip', 'port'];

        $userC = wp_get_current_user();
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'user-id',
                    'value'   => $userC->ID,
                    'compare' => '=',
                )
            )
        );
        $id_customers = get_posts($arr);
        $arr = array(
            'post_type' => 'customer-email',
            'post_status' => 'publish',
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

        if (count($id_customers) == 0) $id_customers = -1;
        if (!$CDWFunc->isAdministrator($userC->ID))
            $arr['meta_query'][] =
                array(
                    'key' => "customer-id",
                    'value' => $id_customers,
                    'compare' => 'in',
                );

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

        $posts = get_posts($arr);
        $data = [];
        foreach ($posts as $post) {
            $item = [];
            $item['id'] = $post->ID;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($post->ID, $column, true);
            }

            if (!empty($item["port"]))
                $item["ip"] .= ":" . $item["port"];
            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["email-type_label"] = get_the_title($item["email-type"]) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $item["account"] = get_post_meta($item["email-type"], "account", true);
            $item["hhd"] = get_post_meta($item["email-type"], "hhd", true);

            $status = 'Chưa xác định';
            $date = get_post_meta($post->ID, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_email =  $CDWFunc->date->create_datetime_from_string($date);
            if ($date_email >= $date_now) {
                $status = '<span class="badge badge-primary">Đang hoạt động</span>';
            }
            if ($date_email < $date_now) {
                $status = '<span class="badge badge-danger">Hết hạn</span>';
            }
            if ($date_email >= $date_now && $date_email <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="badge badge-warning">Sắp hết hạn</span>';
            }

            $item['status'] = $status;
            $customer = get_post_meta(get_post_meta($post->ID, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($post->ID, 'customer-id', true));
            $item['urlEmail'] = str_starts_with($item["ip"], 'http') ? ($item["url_admin"]??$item["url_client"]) : 'http://' . ($item["url_admin"]??$item["url_client"]);
            $item['action'] = '';
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxClientEmail();
