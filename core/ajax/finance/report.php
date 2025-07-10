<?php
defined('ABSPATH') || exit;
class AjaxFinanceReport
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-finance-report-index',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_get-load-widget-data',  array($this, 'func_get_load_widget_data'));
        add_action('wp_ajax_ajax_get-load-widget-type-data',  array($this, 'func_get_load_widget_type_data'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $arr = array(
            'post_type' => ['receipt', 'payment'],
            'post_status' => 'publish',
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => -1
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
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
            $arrBilling['meta_query'][] = array(
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
            $arrBilling['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        if ($type != '') {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
            );
        }

        $total = 0;
        $totalBilling = 0;
        $totalBillingDK = 0;
        $amountReceiptDK = 0;
        $amountPaymentDK = 0;

        $posts = get_posts($arr);
        $idBillings = get_posts($arrBilling);
        $totalBilling = $CDWFunc->wpdb->get_total_billings($idBillings);

        //Lấy đầu kỳ
        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            //Tổng doanh thu
            $arrBillingDK = array(
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
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );

            $arrReceiptDK = array(
                'post_type' => 'receipt',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );
            $arrPaymentDK = array(
                'post_type' => 'payment',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );

            if ($type != '') {
                $arrReceiptDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
                $arrPaymentDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
            }
            $idReceiptDKs = get_posts($arrReceiptDK);
            $idPaymentDKs = get_posts($arrPaymentDK);
            $idBillingDKs = get_posts($arrBillingDK);
            $amountReceiptDK = $CDWFunc->wpdb->get_total_receipts($idReceiptDKs);
            $amountPaymentDK = $CDWFunc->wpdb->get_total_payments($idPaymentDKs);
            $totalBillingDK = $CDWFunc->wpdb->get_total_billings($idBillingDKs);
        }

        $total = $totalBillingDK + $amountReceiptDK - $amountPaymentDK;
        $data = [];
        foreach ($posts as $post) {
            $item = [];
            $item['id'] = $post->ID;
            $item['code'] = "#" . $post->ID;
            $date = get_post_meta($post->ID, 'date', true);
            $item['date'] =  $CDWFunc->date->convertDateTime($date);
            $item['note'] = '<h6><a href="javascript:void(0);">' . get_post_meta(get_post_meta($post->ID, 'type', true), 'name', true) . '</a></h6><small>' . get_post_meta($post->ID, 'note', true) . '</small>';
            switch ($post->post_type) {
                case 'receipt':
                    $receipt = $CDWFunc->wpdb->get_total_receipt($post->ID);
                    $total += $receipt;
                    $item['receipt'] = $receipt;
                    $item['payment'] = '';
                    $item['urlredirect'] = $CDWFunc->getUrl('index', 'receipt', 'id=' . $post->ID);
                    break;
                case 'payment':
                    $payment = $CDWFunc->wpdb->get_total_payment($post->ID);
                    $total -= $payment;
                    $item['receipt'] = '';
                    $item['payment'] = $payment;
                    $item['urlredirect'] = $CDWFunc->getUrl('index', 'payment', 'id=' . $post->ID);
                    break;
            }
            $item['total'] = $total;
            $data[] = $item;
        }
        $item = [];
        $item['id'] = -1;
        $item['code'] = '';
        $item['date'] = '';
        $item['note'] = 'Tổng doanh thu phát sinh';
        $total += $totalBilling;
        $item['receipt'] = $totalBilling;
        $item['payment'] = '';
        $item['total'] = $total;
        $item['urlredirect'] = '';
        $data[] = $item;

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_get_load_widget_data()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $arrReceipt = array(
            'post_type' => 'receipt',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );
        $arrPayment = array(
            'post_type' => 'payment',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
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
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arrReceipt['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
            $arrBilling['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arrReceipt['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
            $arrBilling['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        if ($type != '') {
            $arrReceipt['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
            );
        }
        $total = 0;
        $totalBilling = 0;
        $totalBillingDK = 0;
        $amountReceiptDK = 0;
        $amountPaymentDK = 0;

        $idReceipts = get_posts($arrReceipt);
        $idPayments = get_posts($arrPayment);
        $idBillings = get_posts($arrBilling);

        //lấy ps
        $amountReceipt = $CDWFunc->wpdb->get_total_receipts($idReceipts);
        $amountPayment = $CDWFunc->wpdb->get_total_payments($idPayments);
        $totalBilling = $CDWFunc->wpdb->get_total_billings($idBillings);

        //Lấy đầu kỳ
        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            //Tổng doanh thu
            $arrBillingDK = array(
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
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );

            $arrReceiptDK = array(
                'post_type' => 'receipt',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );
            $arrPaymentDK = array(
                'post_type' => 'payment',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );

            if ($type != '') {
                $arrReceiptDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
                $arrPaymentDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
            }
            $idReceiptDKs = get_posts($arrReceiptDK);
            $idPaymentDKs = get_posts($arrPaymentDK);
            $idBillingDKs = get_posts($arrBillingDK);
            $amountReceiptDK = $CDWFunc->wpdb->get_total_receipts($idReceiptDKs);
            $amountPaymentDK = $CDWFunc->wpdb->get_total_payments($idPaymentDKs);
            $totalBillingDK = $CDWFunc->wpdb->get_total_billings($idBillingDKs);
        }

        $total = $totalBillingDK + $amountReceiptDK - $amountPaymentDK;

        $data = [
            "dk" => $CDWFunc->number_format($total),
            "dk_dt" => $CDWFunc->number_format($totalBillingDK),
            "ps_dt" => $CDWFunc->number_format($totalBilling),
            "ps_thu" => $CDWFunc->number_format($amountReceipt),
            "ps_chi" => $CDWFunc->number_format($amountPayment),
            "ck" => $CDWFunc->number_format($total + $totalBilling + $amountReceipt -  $amountPayment),
        ];

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_get_load_widget_type_data()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-report-index-nonce', 'security');

        $arrReceipt = array(
            'post_type' => 'receipt',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );
        $arrPayment = array(
            'post_type' => 'payment',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );
        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arrReceipt['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arrReceipt['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        if ($type != '') {
            $arrReceipt['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
            );
            $arrPayment['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '=',
            );
        }
        $total = 0;
        $amountReceiptDK = 0;
        $amountPaymentDK = 0;

        $idReceipts = get_posts($arrReceipt);
        $idPayments = get_posts($arrPayment);

        //lấy ps
        $amountReceipt = $CDWFunc->wpdb->get_total_receipts($idReceipts);
        $amountPayment = $CDWFunc->wpdb->get_total_payments($idPayments);

        //Lấy đầu kỳ
        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $arrReceiptDK = array(
                'post_type' => 'receipt',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );
            $arrPaymentDK = array(
                'post_type' => 'payment',
                'post_status' => 'publish',
                'meta_key' => 'date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'fields' => 'ids',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'date',
                        'value'   => $CDWFunc->date->convertDateTime($from_date),
                        'compare' => '<',
                        'type'    => 'DATE'
                    )
                )
            );
            if ($type != '') {
                $arrReceiptDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
                $arrPaymentDK['meta_query'][] = array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                );
            }
            $idReceiptDKs = get_posts($arrReceiptDK);
            $idPaymentDKs = get_posts($arrPaymentDK);
            $amountReceiptDK = $CDWFunc->wpdb->get_total_receipts($idReceiptDKs);
            $amountPaymentDK = $CDWFunc->wpdb->get_total_payments($idPaymentDKs);
        }

        $total = $amountReceiptDK - $amountPaymentDK;

        $data = [
            "dk" => $CDWFunc->number_format($total),
            "ps_thu" => $CDWFunc->number_format($amountReceipt),
            "ps_chi" => $CDWFunc->number_format($amountPayment),
            "ck" => $CDWFunc->number_format($total + $amountReceipt -  $amountPayment),
        ];

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxFinanceReport();
