<?php
defined('ABSPATH') || exit;
class AjaxClientCheckout
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_client-checkout',  array($this, 'func_client_checkout'));
        add_action('wp_ajax_ajax_client-checkout_frontend',  array($this, 'func_client_checkout_frontend'));
        add_action('wp_ajax_ajax_client-checkout-payment',  array($this, 'func_client_checkout_payment'));
        add_action('wp_ajax_ajax_client-checkout-cancel',  array($this, 'func_client_checkout_cancel'));
        add_action('wp_ajax_ajax_client-checkout-payment-momo',  array($this, 'func_client_checkout_payment_momo'));
        add_action('wp_ajax_ajax_client-checkout-check-payment-momo',  array($this, 'func_client_checkout_check_payment_momo'));
    }

    public function func_client_checkout()
    {
        global $CDWFunc, $CDWConst, $CDWNotification, $CDWEmail, $CDWCart;
        // First check the nonce, if it fails the function will break
        // check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $security = isset($_POST['security']) ? $_POST['security'] : "";
        if (false === wp_verify_nonce($security, 'ajax-client-checkout-nonce') && false ===  wp_verify_nonce($security, 'ajax-client-cart-nonce')) {
            if (wp_doing_ajax()) {
                wp_die(-1, 403);
            } else {
                die('-1');
            }
        }

        $hasvat = true; // filter_var($_POST['hasvat'], FILTER_VALIDATE_BOOLEAN);
        $frontend = isset($_POST['frontend']) ? $_POST['frontend'] : false;
        $data = isset($_POST['data']) ? $_POST['data'] : "";
        $note = isset($_POST['note']) ? $_POST['note'] : "";
        $payment = isset($_POST['payment']) ? $_POST['payment'] : "";

        $items =  $CDWCart->get();
        if ($frontend) {
            $tax = $CDWCart->getTax();
            $note = $CDWCart->getNote();
            if ($tax->has) {
                $note  .= 'Thông tin xuất HD:\nTên công ty: ' . $tax->company . '\nMã số thuế: ' . $tax->code . '\nEmail: ' . $tax->email;
            }
        } else {
            $ids = array_column($data, 'quantity', 'id');
        }
        if (empty($note)) $note = "Thanh toán đơn hàng";
        $customer_id = $CDWFunc->getCustomer();
        if (empty($customer_id))
            wp_send_json_error(['msg' => 'Không có khách hàng để tạo thanh toán']);

        if (count($items) == 0) {
            wp_send_json_error(['msg' => 'Không có dịch vụ trong giỏ hàng']);
        }

        $changeQuantity = array_column($items, 'quantity', 'idc');
        $changePrice = array_column($items, 'price', 'idc');
        foreach ($items as $key => $item) {

            if (!$frontend && array_key_exists($key, $ids)) {
                $quantity = (float) $ids[$key];
                $changeQuantity[$key] = $quantity;
            }
            $price = 0;
            switch ($item["type"]) {
                case 'customer-domain':
                    $domain = $item["domain"];
                    $type = get_post_meta($item["id"], 'domain-type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);
                    if (!$price) {
                        $price = (float)  $CDWFunc->wpdb->get_price_domain($domain);
                    }
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                    }
                    break;
                case 'customer-hosting':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);

                    if ($price == -1) {
                        $price = (float) get_post_meta($id, 'price', true);
                    }
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                    }
                    break;

                case 'customer-email':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'email-type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);

                    if ($price == -1) {
                        $price = (float) get_post_meta($id, 'price', true);
                    }

                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                    }
                    break;
                case 'customer-plugin':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'plugin-type', true);
                    $price = (float) get_post_meta($type, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói plugin không hợp lệ.']);
                    }
                    break;
                case 'manage-hosting':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                    }
                    break;
                case 'manage-email':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                    }
                    break;

                case 'manage-domain':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                    }
                    break;
                case 'site-managers':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Giao diện không hợp lệ.']);
                    }
                    break;

                case 'manage-plugin':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Plugin không hợp lệ.']);
                    }
                    break;
            }
            $changePrice[$key] = $price;
        }
        $CDWCart->changePrice($changePrice);
        if (!$frontend)
            $CDWCart->changeQuantity($changeQuantity);

        $total = $CDWCart->getTotal();
        $idBilling = -1;

        $billings = [
            [
                'date' => $CDWFunc->date->getCurrentDateTime(),
                'note' => $note,
                'amount' => $total->amount,
            ]
        ];

        $billingColumns = ['note', 'amount'];
        $billingColumnDates = ['date'];
        $billings = $CDWFunc->wpdb->func_new_detail_post('customer-billing', 'customer-id', $customer_id, $billings, $billingColumns);
        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-billing', 'customer-id', $customer_id, $billings, $billingColumnDates);

        foreach ($billings as $keyItem => $valueItem) {
            $idBilling = isset($valueItem['id']) ? $valueItem['id'] : '';
            $code = "HD" . $idBilling;
            update_post_meta($idBilling, "code", $code);

            $vat_percent = 0;
            if ($hasvat) {
                $vat_percent = $CDWConst->vatPercent;
                $vat = $total->amount * $vat_percent / 100;

                update_post_meta($idBilling, 'has-vat', $hasvat);
                update_post_meta($idBilling, 'vat-percent', $vat_percent);
                update_post_meta($idBilling, 'vat', $vat);
            }

            update_post_meta($idBilling, "note", $note);
            update_post_meta($idBilling, "items", $CDWCart->get());
            update_post_meta($idBilling, "status", 'pending');
            update_post_meta($idBilling, "sub_amount", $total->amount);
            update_post_meta($idBilling, "amount", $total->amount + $vat);
            update_post_meta($idBilling, 'payment', $payment);
        }

        if (!empty($idBilling) && $idBilling != -1) {
            $CDWCart->clear();
            $CDWNotification->newNotificationUpdateCheckout($idBilling);
            $CDWEmail->sendEmailOrderNew($idBilling, true);
            wp_send_json_success(['msg' => 'Bắt đầu thanh toán', 'checkout_url' => $frontend ? home_url('/don-hang/' . $idBilling) : $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $idBilling . '&step=2&payment=' . $payment)]);
        }

        wp_send_json_error(['msg' => 'Không cập nhật được thanh toán']);
        wp_die();
    }
    public function func_client_checkout_frontend()
    {
        global $CDWFunc, $CDWConst, $CDWNotification, $CDWEmail, $CDWCart;
        // First check the nonce, if it fails the function will break
        // check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $security = isset($_POST['security']) ? $_POST['security'] : "";
        if (false === wp_verify_nonce($security, 'ajax-client-checkout-nonce') && false ===  wp_verify_nonce($security, 'ajax-client-cart-nonce')) {
            if (wp_doing_ajax()) {
                wp_die(-1, 403);
            } else {
                die('-1');
            }
        }

        $hasvat = true; // filter_var($_POST['hasvat'], FILTER_VALIDATE_BOOLEAN);
        $frontend = isset($_POST['frontend']) ? $_POST['frontend'] : false;
        $data = isset($_POST['data']) ? $_POST['data'] : "";
        $note = isset($_POST['note']) ? $_POST['note'] : "";
        $payment = isset($_POST['payment']) ? $_POST['payment'] : "";

        $items =  $CDWCart->get();
        if ($frontend) {
            $tax = $CDWCart->getTax();
            $note = $CDWCart->getNote();
            if ($tax->has) {
                $note  .= 'Thông tin xuất HD:\nTên công ty: ' . $tax->company . '\nMã số thuế: ' . $tax->code . '\nEmail: ' . $tax->email;
            }
        } else {
            $ids = array_column($data, 'quantity', 'id');
        }
        if (empty($note)) $note = "Thanh toán đơn hàng";

        $userID = wp_get_current_user()->ID;
        $customer_id = get_user_meta($userID, 'customer-id', true);
        if (empty($customer_id))
            wp_send_json_error(['msg' => 'Không có khách hàng để tạo thanh toán']);

        if (count($items) == 0) {
            wp_send_json_error(['msg' => 'Không có dịch vụ trong giỏ hàng']);
        }

        $changeQuantity = array_column($items, 'quantity', 'idc');
        $changePrice = array_column($items, 'price', 'idc');
        foreach ($items as $key => $item) {

            if (!$frontend && array_key_exists($key, $ids)) {
                $quantity = (float) $ids[$key];
                $changeQuantity[$key] = $quantity;
            }
            $price = 0;
            switch ($item["type"]) {
                case 'customer-domain':
                    $domain = $item["domain"];
                    $type = get_post_meta($item["id"], 'domain-type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);
                    if (!$price) {
                        $price = (float)  $CDWFunc->wpdb->get_price_domain($domain);
                    }
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                    }
                    break;
                case 'customer-hosting':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);

                    if ($price == -1) {
                        $price = (float) get_post_meta($id, 'price', true);
                    }
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                    }
                    break;

                case 'customer-email':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'email-type', true);
                    $price = (float) get_post_meta($type, 'gia_han', true);

                    if ($price == -1) {
                        $price = (float) get_post_meta($id, 'price', true);
                    }

                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                    }
                    break;
                case 'customer-plugin':
                    $id = $item["id"];
                    $type = get_post_meta($id, 'plugin-type', true);
                    $price = (float) get_post_meta($type, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói plugin không hợp lệ.']);
                    }
                    break;
                case 'manage-hosting':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói hosting không hợp lệ.']);
                    }
                    break;
                case 'manage-email':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Gói email không hợp lệ.']);
                    }
                    break;

                case 'manage-domain':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'gia', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Domain không hợp lệ.']);
                    }
                    break;
                case 'site-managers':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Giao diện không hợp lệ.']);
                    }
                    break;

                case 'manage-plugin':
                    $id = $item["id"];
                    $price = (float) get_post_meta($id, 'price', true);
                    if (!is_numeric($price)) {
                        wp_send_json_error(['msg' => 'Plugin không hợp lệ.']);
                    }
                    break;
            }
            $changePrice[$key] = $price;
        }
        $CDWCart->changePrice($changePrice);
        if (!$frontend)
            $CDWCart->changeQuantity($changeQuantity);

        $total = $CDWCart->getTotal();
        $idBilling = -1;

        $billings = [
            [
                'date' => $CDWFunc->date->getCurrentDateTime(),
                'note' => $note,
                'amount' => $total->amount,
            ]
        ];

        $billingColumns = ['note', 'amount'];
        $billingColumnDates = ['date'];
        $billings = $CDWFunc->wpdb->func_new_detail_post('customer-billing', 'customer-id', $customer_id, $billings, $billingColumns);
        $CDWFunc->wpdb->func_update_detail_post_type_date('customer-billing', 'customer-id', $customer_id, $billings, $billingColumnDates);

        foreach ($billings as $keyItem => $valueItem) {
            $idBilling = isset($valueItem['id']) ? $valueItem['id'] : '';
            $code = "HD" . $idBilling;
            update_post_meta($idBilling, "code", $code);

            $vat_percent = 0;
            if ($hasvat) {
                $vat_percent = $CDWConst->vatPercent;
                $vat = $total->amount * $vat_percent / 100;

                update_post_meta($idBilling, 'has-vat', $hasvat);
                update_post_meta($idBilling, 'vat-percent', $vat_percent);
                update_post_meta($idBilling, 'vat', $vat);
            }

            update_post_meta($idBilling, "note", $note);
            update_post_meta($idBilling, "items", $CDWCart->get());
            update_post_meta($idBilling, "status", 'pending');
            update_post_meta($idBilling, "sub_amount", $total->amount);
            update_post_meta($idBilling, "amount", $total->amount + $vat);
            update_post_meta($idBilling, 'payment', $payment);
        }

        if (!empty($idBilling) && $idBilling != -1) {
            $CDWCart->clear();
            $CDWNotification->newNotificationUpdateCheckout($idBilling);
            $CDWEmail->sendEmailOrderNew($idBilling, true);
            wp_send_json_success(['msg' => 'Bắt đầu thanh toán', 'checkout_url' => $frontend ? home_url('/don-hang/' . $idBilling) : $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $idBilling . '&step=2&payment=' . $payment)]);
        }

        wp_send_json_error(['msg' => 'Không cập nhật được thanh toán']);
        wp_die();
    }
    public function func_client_checkout_payment()
    {
        global $CDWFunc, $CDWConst, $CDWNotification, $CDWEmail, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $payment = isset($_POST['payment']) ? $_POST['payment'] : "";
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        if (empty($id))
            wp_send_json_error(['msg' => 'Không tìm thấy hóa đơn']);

        update_post_meta($id, 'status', 'pending');
        update_post_meta($id, 'payment', $payment);

        wp_send_json_success(['msg' => 'Bắt đầu thanh toán', 'checkout_url' => $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id . '&step=2&payment=' . $payment)]);

        wp_send_json_error(['msg' => 'Không cập nhật được thanh toán']);
        wp_die();
    }
    public function func_client_checkout_cancel()
    {
        global $CDWFunc, $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        update_post_meta($id, "status", 'cancel');
        $CDWNotification->newNotificationCancelCheckout($id); // huỷ đơn hàng thông báo
        wp_send_json_success(['msg' => 'Hoàn tất thanh toán', 'checkout_url' => $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id)]);

        wp_send_json_error(['msg' => 'Không hủy được thanh toán']);
        wp_die();
    }
    public function func_client_checkout_payment_momo()
    {
        global $CDWFunc, $CDWNotification, $CDWQRCode, $CDWConst;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $customer_id = $CDWFunc->getCustomer();

        $name = get_post_meta($customer_id, 'name', true);
        $phone = get_post_meta($customer_id, 'phone', true);
        $email = get_post_meta($customer_id, 'email', true);
        $dvhc_tp = get_post_meta($customer_id, 'dvhc_tp', true);
        $dvhc_qh = get_post_meta($customer_id, 'dvhc_qh', true);
        $dvhc_px = get_post_meta($customer_id, 'dvhc_px', true);
        $address = get_post_meta($customer_id, 'address', true);
        $address .= $CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp);

        $items = get_post_meta($id, 'items', true);
        $checkoutStatus = get_post_meta($id, "status", true);
        $note = get_post_meta($id, "note", true);
        $total = get_post_meta($id, 'amount', true);
        $total = $total ? $total : 0;
        $has_vat = get_post_meta($id, 'has-vat', true);
        $has_vat = $has_vat ? $has_vat : false;
        $vat_percent = get_post_meta($id, 'vat-percent', true);
        $vat_percent = $vat_percent ? $vat_percent : $CDWConst->vatPercent;
        $vat = get_post_meta($id, 'vat', true);
        $vat = $vat ? $vat : 0;
        if ($has_vat && $vat == 0 && $total != 0) {
            $vat = $total * $vat_percent / 100;
        }
        $template = [
            'time' => 'payment-momo-time-expire-text-template'
        ];
        wp_send_json_success(['msg' => '', 'image_src' => '', 'data' => '', 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
        require_once(ADMIN_THEME_URL . '/core/function-api-momo.php');
        $apiMomo = new APIMomo(APIMOMOURL);
        $data = $apiMomo->checkTransactionStatus('a' . $id);
        if (!$data->status) {
            $data = $apiMomo->delivery($total + $vat, '', $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id . '&step=2'),  'a' . $id, $note, $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id . '&step=1'));
            update_post_meta($id, 'payment-momo-requestId', $data->data['requestId']);
            if ($data->status) {
                if (isset($data->data['qrCodeUrl'])) {
                    $out = $CDWQRCode->withLogo($data->data['qrCodeUrl']);
                } else {
                    if (isset($data->data['payUrl'])) {
                        $out = $CDWQRCode->withLogo($data->data['payUrl']);
                    } else
                        wp_send_json_error(['msg' => $data->message, 'data' => $data]);
                }
                $template = [
                    'time' => 'payment-momo-time-expire-text-template'
                ];
                wp_send_json_success(['msg' => $data->data['message'], 'image_src' => $out, 'data' => $data, 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
            } else
                wp_send_json_error(['msg' => $data->message, 'data' => $data]);
        } else {
            $requestId = get_post_meta($id, 'payment-momo-requestId', true);
            $partnerClientId = $customer_id;
            $data = $apiMomo->manageReactivate($requestId, 'a' . $id, $partnerClientId, $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id . '&step=2'), $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id . '&step=2'));

            wp_send_json_error(['msg' => $data->message, 'data' => $data]);
        }

        wp_die();
    }
    public function func_client_checkout_check_payment_momo()
    {
        global $CDWFunc, $CDWNotification, $CDWQRCode, $CDWConst;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        require_once(ADMIN_THEME_URL . '/core/function-api-momo.php');
        $apiMomo = new APIMomo(APIMOMOURL);
        $i = APIMOMOTIMEOUT;
        $done = false;
        $checkout_url = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
        while ($i >= 0 && !$done) {


            if ($i < 50)
                wp_send_json_success(['msg' => 'Hoàn tất thanh toán', 'checkout_url' => $checkout_url]);

            if (!$done) {
                $start_time = microtime(true);
                $data = $apiMomo->checkTransactionStatus('a' . $id);
                $end_time = microtime(true);
                $execution_time = ($end_time - $start_time);
                $i -= $execution_time;
                $done = $data->$status;
                if ($done)
                    wp_send_json_error(['msg' => "Quá thời gian thanh toán", 'checkout_url' => $checkout_url, 'abc' => $data]);
            }

            sleep(1);
            $i--;
        }
        wp_send_json_error(['msg' => "Quá thời gian thanh toán, vui lòng thanh toán lại.", 'checkout_url' => $checkout_url]);


        wp_die();
    }
}
new AjaxClientCheckout();
