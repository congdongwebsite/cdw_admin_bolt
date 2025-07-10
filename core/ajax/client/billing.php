<?php
defined('ABSPATH') || exit;
class AjaxClientBilling
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-client-billing',  array($this, 'func_get_list'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-billing-nonce', 'security');

        $columns = ['code', 'date', 'note', 'amount'];
        $fieldSearch = ['code', 'date', 'note', 'amount'];

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
            'post_type' => 'customer-billing',
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
        if (count($id_customers) == 0) $id_customers = -1;
        if (!$CDWFunc->isAdministrator($userC->ID))
            $arr['meta_query'][] =
                array(
                    'key' => "customer-id",
                    'value' => $id_customers,
                    'compare' => 'in',
                );


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
            $item['status'] = $CDWFunc->get_lable_status(get_post_meta($id, "status", true));

            $item['urlredirect'] = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);

            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxClientBilling();
