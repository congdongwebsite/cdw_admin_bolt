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

        add_action('wp_ajax_ajax_client-checkout-payment-momo_frontend',  array($this, 'func_client_checkout_payment_momo_frontend'));
        add_action('wp_ajax_nopriv_ajax_client-checkout-payment-momo_frontend',  array($this, 'func_client_checkout_payment_momo_frontend'));
        add_action('wp_ajax_ajax_client-checkout-check-payment-momo_frontend',  array($this, 'func_client_checkout_check_payment_momo_frontend'));
        add_action('wp_ajax_nopriv_ajax_client-checkout-check-payment-momo_frontend',  array($this, 'func_client_checkout_check_payment_momo_frontend'));

        add_action('wp_ajax_ajax_momo_url_result', array($this, 'func_ajax_momo_url_result'));
        add_action('wp_ajax_nopriv_ajax_momo_url_result', array($this, 'func_ajax_momo_url_result'));
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
        $user = wp_get_current_user();
        $is_kyc = $CDWFunc->isKYC($user->ID);
        if (!$is_kyc) {
            wp_send_json_error(['msg' => 'Vui lòng hoàn tất KYC trước khi thanh toán. <a href="' . $CDWFunc->getUrl('index', 'setting') . '">Cập nhật</a>']);
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
        $changePrice = array_column($items, 'price', 'idc');
        $changeQuantity = array_column($items, 'quantity', 'idc');
        foreach ($items as $key => $item) {

            if (!$frontend && array_key_exists($key, $ids)) {
                $quantity = (float) $ids[$key];
                $changeQuantity[$key] = $quantity;
            }
            $validated_props = $CDWCart->get_validated_item_properties($item);
            $price = $validated_props['price'];
            $changePrice[$key] = $price;

            if ($item["type"] == 'customer-email-change') {
                $changeQuantity[$key] = $validated_props['quantity'];
            }
        }
        $CDWCart->changePrice($changePrice);
        $CDWCart->changeQuantity($changeQuantity);

        $total = $CDWCart->getTotal();
        $vat_percent = 0;
        $vat = 0;
        if ($hasvat) {
            $vat_percent = $CDWConst->vatPercent;
            $vat = $total->amount * $vat_percent / 100;
        }
        if ($payment === 'momo') {
            if ($total->amount + $vat < 1000) {
                wp_send_json_error(['msg' => 'Giá trị thanh toán qua MoMo phải từ 1000.']);
                wp_die();
            }
            // if (fmod($total->amount + $vat, 1) !== 0.0) {
            //     wp_send_json_error(['msg' => 'Giá trị thanh toán qua MoMo không được có số lẻ.']);
            //     wp_die();
            // }
        }

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
            $vat = 0;
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
            update_post_meta($idBilling, "amount", round($total->amount + $vat));
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

        $user = wp_get_current_user();
        $is_kyc = $CDWFunc->isKYC($user->ID);
        if (!$is_kyc) {
            wp_send_json_error(['msg' => 'Vui lòng hoàn tất KYC trước khi thanh toán. <a href="' . $CDWFunc->getUrl('index', 'setting') . '">Cập nhật</a>']);
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

        $changePrice = array_column($items, 'price', 'idc');
        $changeQuantity = array_column($items, 'quantity', 'idc');
        foreach ($items as $key => $item) {

            if (!$frontend && array_key_exists($key, $ids)) {
                $quantity = (float) $ids[$key];
                $changeQuantity[$key] = $quantity;
            }
            $validated_props = $CDWCart->get_validated_item_properties($item);
            $price = $validated_props['price'];
            $changePrice[$key] = $price;

            if ($item["type"] == 'customer-email-change') {
                $changeQuantity[$key] = $validated_props['quantity'];
            }
        }
        $CDWCart->changePrice($changePrice);
        $CDWCart->changeQuantity($changeQuantity);

        $total = $CDWCart->getTotal();
        $vat_percent = 0;
        $vat = 0;
        if ($hasvat) {
            $vat_percent = $CDWConst->vatPercent;
            $vat = $total->amount * $vat_percent / 100;
        }

        if ($payment === 'momo') {
            if ($total->amount + $vat < 1000) {
                wp_send_json_error(['msg' => 'Giá trị thanh toán qua MoMo phải từ 1000.']);
                wp_die();
            }
            // if (fmod($total->amount + $vat, 1) !== 0.0) {
            //     wp_send_json_error(['msg' => 'Giá trị thanh toán qua MoMo không được có số lẻ.']);
            //     wp_die();
            // }
        }

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
            $vat = 0;
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
            update_post_meta($idBilling, "amount", round($total->amount + $vat));
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
        $reason = isset($_POST['reason']) ? $_POST['reason'] : "Người dùng đã hủy.";
        $customer_id = get_post_meta($id, 'customer-id', true);

        update_post_meta($id, "status", 'cancel');
        cdw_create_customer_log($customer_id, 'Hủy đơn hàng', 'Đơn hàng #' . $id . ' đã được hủy. Lý do: ' . $reason);
        $CDWNotification->newNotificationCancelCheckout($id); // huỷ đơn hàng thông báo
        wp_send_json_success(['msg' => 'Hoàn tất thanh toán', 'checkout_url' => $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id)]);

        wp_send_json_error(['msg' => 'Không hủy được thanh toán']);
        wp_die();
    }
    public function func_client_checkout_payment_momo()
    {
        global $CDWFunc, $CDWQRCode;
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (empty($id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy hóa đơn.']);
        }

        $apiMomo = $CDWFunc->momo;
        $momo_orderId = get_post_meta($id, 'momo_orderId', true);

        if (!empty($momo_orderId)) {
            $status_data = $apiMomo->checkTransactionStatus($momo_orderId);
            error_log('[MOMO_STATUS_CHECK] Response for orderId ' . $momo_orderId . ': ' . print_r($status_data, true));

            $resultCode = $status_data->data['resultCode'] ?? null;

            // Pending codes: 1000 (PENDING), 7000 (USER_NOTIFY_SUCCESS), 8000 (WAITING_FOR_RETRY)
            if (in_array($resultCode, [1000, 7000, 8000])) {
                $momo_qrCodeUrl = get_post_meta($id, 'momo_qrCodeUrl', true);
                $momo_payUrl = get_post_meta($id, 'momo_payUrl', true);
                if (!empty($momo_qrCodeUrl)) {
                    $out = $CDWQRCode->withLogo($momo_qrCodeUrl);
                    $template = ['time' => 'payment-momo-time-expire-text-template'];
                    wp_send_json_success(['msg' => 'Giao dịch đang chờ. Vui lòng quét mã để hoàn tất.', 'image_src' => $out, 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
                    wp_die();
                } elseif (!empty($momo_payUrl)) {
                    wp_send_json_success(['msg' => 'Giao dịch đang chờ. Chuyển hướng để thanh toán.', 'pay_url' => $momo_payUrl]);
                    wp_die();
                }
            }

            // Success codes: 0 (SUCCESS), 9000 (APPROVED)
            if (in_array($resultCode, [0, 9000])) {
                wp_send_json_error(['msg' => 'Đơn hàng này đã được thanh toán thành công trước đó.']);
                wp_die();
            }

            // Any other code is a failure/expired code.
            $customer_id = get_post_meta($id, 'customer-id', true);
            $error_message = $status_data->message ?? 'Giao dịch MoMo trước đó đã thất bại hoặc hết hạn.';
            cdw_create_customer_log($customer_id, 'Thanh toán MoMo thất bại', 'Đơn hàng #' . $id . '. ' . $error_message);
            update_post_meta($id, 'status', 'cancel');

            wp_send_json_error(['msg' => 'Giao dịch thanh toán trước đó đã thất bại hoặc hết hạn. Vui lòng tạo một đơn hàng mới.']);
            wp_die();
        }

        $total = get_post_meta($id, 'amount', true);
        if ($total < 1000) {
            wp_send_json_error(['msg' => 'Giá trị thanh toán phải từ 1000.']);
            wp_die();
        }
        if (fmod($total, 1) !== 0.0) {
            wp_send_json_error(['msg' => 'Giá trị thanh toán không được có số lẻ.']);
            wp_die();
        }
        $note = get_post_meta($id, "note", true) ?: "Thanh toan don hang #" . $id;
        $code = get_post_meta($id, "code", true);
        if (empty($code)) {
            $code = "HD" . $id;
            update_post_meta($id, "code", $code);
        }
        $orderId = $code . '_' . time(); // Unique orderId for each new attempt
        $redirectUrl = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
        $ipnUrl = home_url('/wp-json/cdw/v1/momo-ipn');

        $customer_id = get_post_meta($id, 'customer-id', true);
        $userInfo = [
            "name" => get_post_meta($customer_id, 'name', true),
            "phoneNumber" => get_post_meta($customer_id, 'phone', true),
            "email" => get_post_meta($customer_id, 'email', true)
        ];
        $billing_items = get_post_meta($id, 'items', true);
        $momo_items = [];
        if (is_array($billing_items)) {
            foreach ($billing_items as $item) {
                $momo_items[] = [
                    "id" => $item['id'],
                    "name" => $item['service'],
                    "price" => (int)$item['price'],
                    "quantity" => (int)$item['quantity'],
                    "unit" => "năm",
                    "totalPrice" => (int)($item['price'] * $item['quantity']),
                ];
            }
        }

        $data = $apiMomo->delivery($total, '', $ipnUrl, $orderId, $note, $redirectUrl, $momo_items, $userInfo);
        error_log('[func_client_checkout_payment_momo] momo data: ' . print_r($data, true));

        if ($data->status) {
            update_post_meta($id, 'momo_orderId', $orderId);
            if (isset($data->data['requestId'])) {
                update_post_meta($id, 'momo_requestId', $data->data['requestId']);
            }

            if (isset($data->data['qrCodeUrl'])) {
                update_post_meta($id, 'momo_qrCodeUrl', $data->data['qrCodeUrl']);

                $out = $CDWQRCode->withLogo($data->data['qrCodeUrl']);
                $template = ['time' => 'payment-momo-time-expire-text-template'];
                wp_send_json_success(['msg' => $data->message, 'image_src' => $out, 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
            } elseif (isset($data->data['payUrl'])) {
                update_post_meta($id, 'momo_payUrl', $data->data['payUrl']);
                wp_send_json_success(['msg' => $data->message, 'pay_url' => $data->data['payUrl']]);
            } else {
                $customer_id = get_post_meta($id, 'customer-id', true);
                cdw_create_customer_log($customer_id, 'Lỗi tạo thanh toán MoMo', 'Không thể tạo mã thanh toán cho đơn hàng #' . $id . '. Phản hồi không chứa qrCodeUrl hoặc payUrl.');
                wp_send_json_error(['msg' => 'Phản hồi từ MoMo không hợp lệ.', 'data' => $data]);
            }
        } else {
            $customer_id = get_post_meta($id, 'customer-id', true);
            cdw_create_customer_log($customer_id, 'Lỗi tạo thanh toán MoMo', 'Không thể tạo mã thanh toán cho đơn hàng #' . $id . '. Lỗi: ' . ($data->message ?? 'Không rõ'));
            wp_send_json_error(['msg' => $data->message ?? 'Không thể tạo mã thanh toán MoMo.', 'data' => $data]);
        }

        wp_die();
    }
    public function func_client_checkout_check_payment_momo()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-checkout-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        if (empty($id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy hóa đơn.']);
        }

        $apiMomo = $CDWFunc->momo;
        $momo_orderId = get_post_meta($id, 'momo_orderId', true);
        if (empty($momo_orderId)) {
            wp_send_json_error(['msg' => 'Không tìm thấy giao dịch MoMo cho đơn hàng này.']);
        }
        $data = $apiMomo->checkTransactionStatus($momo_orderId);

        if ($data->status && isset($data->data['resultCode']) && ($data->data['resultCode'] == 0 || $data->data['resultCode'] == 9000)) {
            $checkout_url = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
            wp_send_json_success(['msg' => 'Thanh toán thành công!', 'checkout_url' => $checkout_url]);
        } elseif (isset($data->data['resultCode']) && in_array($data->data['resultCode'], [1000, 7000, 8000])) {
            // Pending
            wp_send_json_error(['data' => ['resultCode' => $data->data['resultCode'], 'msg' => 'Giao dịch đang chờ xử lý...']]);
        } else {
            // Other error codes
            $checkout_url = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
            wp_send_json_error(['data' => ['msg' => $data->message ?? 'Giao dịch thất bại hoặc đã bị hủy.', 'checkout_url' => $checkout_url, 'resultCode' => $data->data['resultCode'] ?? -1]]);
        }
        wp_die();
    }

    public function func_client_checkout_payment_momo_frontend()
    {
        global $CDWFunc, $CDWQRCode;
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (empty($id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy hóa đơn.']);
        }

        $apiMomo = $CDWFunc->momo;
        $momo_orderId = get_post_meta($id, 'momo_orderId', true);

        if (!empty($momo_orderId)) {
            $status_data = $apiMomo->checkTransactionStatus($momo_orderId);
            error_log('[MOMO_STATUS_CHECK] Response for orderId ' . $momo_orderId . ': ' . print_r($status_data, true));

            $resultCode = $status_data->data['resultCode'] ?? null;

            if (in_array($resultCode, [1000, 7000, 8000])) {
                $momo_qrCodeUrl = get_post_meta($id, 'momo_qrCodeUrl', true);
                $momo_payUrl = get_post_meta($id, 'momo_payUrl', true);
                if (!empty($momo_qrCodeUrl)) {
                    $out = $CDWQRCode->withLogo($momo_qrCodeUrl);
                    $template = ['time' => 'payment-momo-time-expire-text-template'];
                    wp_send_json_success(['msg' => 'Giao dịch đang chờ. Vui lòng quét mã để hoàn tất.', 'image_src' => $out, 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
                    wp_die();
                } elseif (!empty($momo_payUrl)) {
                    wp_send_json_success(['msg' => 'Giao dịch đang chờ. Chuyển hướng để thanh toán.', 'pay_url' => $momo_payUrl]);
                    wp_die();
                }
            }

            if (in_array($resultCode, [0, 9000])) {
                wp_send_json_error(['msg' => 'Đơn hàng này đã được thanh toán thành công trước đó.']);
                wp_die();
            }

            $customer_id = get_post_meta($id, 'customer-id', true);
            $error_message = $status_data->message ?? 'Giao dịch MoMo trước đó đã thất bại hoặc hết hạn.';
            cdw_create_customer_log($customer_id, 'Thanh toán MoMo thất bại', 'Đơn hàng #' . $id . '. ' . $error_message);
            update_post_meta($id, 'status', 'cancel');

            wp_send_json_error(['msg' => 'Giao dịch thanh toán trước đó đã thất bại hoặc hết hạn. Vui lòng tạo một đơn hàng mới.']);
            wp_die();
        }

        $total = get_post_meta($id, 'amount', true);
        if ($total < 1000) {
            wp_send_json_error(['msg' => 'Giá trị thanh toán phải từ 1000.']);
            wp_die();
        }
        if (fmod($total, 1) !== 0.0) {
            wp_send_json_error(['msg' => 'Giá trị thanh toán không được có số lẻ.']);
            wp_die();
        }
        $note = get_post_meta($id, "note", true) ?: "Thanh toan don hang #" . $id;
        $code = get_post_meta($id, "code", true);
        if (empty($code)) {
            $code = "HD" . $id;
            update_post_meta($id, "code", $code);
        }
        $orderId = $code . '_' . time();
        $redirectUrl = home_url('/don-hang/' . $id);
        $ipnUrl = home_url('/wp-json/cdw/v1/momo-ipn');

        $customer_id = get_post_meta($id, 'customer-id', true);
        $userInfo = [
            "name" => get_post_meta($customer_id, 'name', true),
            "phoneNumber" => get_post_meta($customer_id, 'phone', true),
            "email" => get_post_meta($customer_id, 'email', true)
        ];
        $billing_items = get_post_meta($id, 'items', true);
        $momo_items = [];
        if (is_array($billing_items)) {
            foreach ($billing_items as $item) {
                $momo_items[] = [
                    "id" => $item['id'],
                    "name" => $item['service'],
                    "price" => (int)$item['price'],
                    "quantity" => (int)$item['quantity'],
                    "unit" => "năm",
                    "totalPrice" => (int)($item['price'] * $item['quantity']),
                ];
            }
        }

        $data = $apiMomo->delivery($total, '', $ipnUrl, $orderId, $note, $redirectUrl, $momo_items, $userInfo);
        error_log('[func_client_checkout_payment_momo_frontend] momo data: ' . print_r($data, true));

        if ($data->status) {
            update_post_meta($id, 'momo_orderId', $orderId);
            if (isset($data->data['requestId'])) {
                update_post_meta($id, 'momo_requestId', $data->data['requestId']);
            }

            if (isset($data->data['qrCodeUrl'])) {
                update_post_meta($id, 'momo_qrCodeUrl', $data->data['qrCodeUrl']);

                $out = $CDWQRCode->withLogo($data->data['qrCodeUrl']);
                $template = ['time' => 'payment-momo-time-expire-text-template'];
                wp_send_json_success(['msg' => $data->message, 'image_src' => $out, 'time_out' => APIMOMOTIMEOUT, 'template' => $template]);
            } elseif (isset($data->data['payUrl'])) {
                update_post_meta($id, 'momo_payUrl', $data->data['payUrl']);
                wp_send_json_success(['msg' => $data->message, 'pay_url' => $data->data['payUrl']]);
            } else {
                $customer_id = get_post_meta($id, 'customer-id', true);
                cdw_create_customer_log($customer_id, 'Lỗi tạo thanh toán MoMo', 'Không thể tạo mã thanh toán cho đơn hàng #' . $id . '. Phản hồi không chứa qrCodeUrl hoặc payUrl.');
                wp_send_json_error(['msg' => 'Phản hồi từ MoMo không hợp lệ.', 'data' => $data]);
            }
        } else {
            $customer_id = get_post_meta($id, 'customer-id', true);
            cdw_create_customer_log($customer_id, 'Lỗi tạo thanh toán MoMo', 'Không thể tạo mã thanh toán cho đơn hàng #' . $id . '. Lỗi: ' . ($data->message ?? 'Không rõ'));
            wp_send_json_error(['msg' => $data->message ?? 'Không thể tạo mã thanh toán MoMo.', 'data' => $data]);
        }

        wp_die();
    }

    public function func_client_checkout_check_payment_momo_frontend()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        if (empty($id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy hóa đơn.']);
        }

        $apiMomo = $CDWFunc->momo;
        $momo_orderId = get_post_meta($id, 'momo_orderId', true);
        if (empty($momo_orderId)) {
            wp_send_json_error(['msg' => 'Không tìm thấy giao dịch MoMo cho đơn hàng này.']);
        }
        $data = $apiMomo->checkTransactionStatus($momo_orderId);

        if ($data->status && isset($data->data['resultCode']) && ($data->data['resultCode'] == 0 || $data->data['resultCode'] == 9000)) {
            $checkout_url = home_url('/don-hang/' . $id);
            wp_send_json_success(['msg' => 'Thanh toán thành công!', 'checkout_url' => $checkout_url]);
        } elseif (isset($data->data['resultCode']) && in_array($data->data['resultCode'], [1000, 7000, 8000])) {
            // Pending
            wp_send_json_error(['data' => ['resultCode' => $data->data['resultCode'], 'msg' => 'Giao dịch đang chờ xử lý...']]);
        } else {
            // Other error codes
            $checkout_url = home_url('/don-hang/' . $id);
            wp_send_json_error(['data' => ['msg' => $data->message ?? 'Giao dịch thất bại hoặc đã bị hủy.', 'checkout_url' => $checkout_url, 'resultCode' => $data->data['resultCode'] ?? -1]]);
        }
        wp_die();
    }

    public function func_ajax_momo_url_result()
    {
        check_ajax_referer('ajax_momo_url_nonce', 'nonce');

        $momo_params_str = isset($_POST['momo_params']) ? $_POST['momo_params'] : '';
        parse_str(ltrim($momo_params_str, '?'), $_GET);

        if (isset($_GET['orderId']) && isset($_GET['resultCode'])) {
            global $CDWFunc, $CDWNotification, $CDWEmail;

            $orderId_momo = $_GET['orderId'];
            $resultCode = $_GET['resultCode'];
            $message = isset($_GET['message']) ? $_GET['message'] : '';

            $parts = explode('_', $orderId_momo);
            $code_part = $parts[0];
            $id = (int) filter_var($code_part, FILTER_SANITIZE_NUMBER_INT);

            if (!$id || get_post_type($id) !== 'customer-billing') {
                wp_send_json_error(['msg' => 'Invalid Order ID.']);
            }

            $payment_method = get_post_meta($id, 'payment', true);
            if ($payment_method !== 'momo') {
                wp_send_json_error(['msg' => 'Not a Momo payment.']);
            }

            // Signature verification should be performed here for security.

            $status = get_post_meta($id, 'status', true);
            if ($status !== 'pending') {
                wp_send_json_error(['msg' => 'Order has already been processed.']);
            }

            if ($resultCode == 0 || $resultCode == 9000) { // SUCCESS or APPROVED
                update_post_meta($id, 'status', 'success');
                update_post_meta($id, 'payment_info', $_GET);
                $customer_id = get_post_meta($id, 'customer-id', true);
                $transId = isset($_GET['transId']) ? $_GET['transId'] : 'N/A';
                cdw_create_customer_log($customer_id, 'Thanh toán MoMo thành công', 'Đơn hàng #' . $id . ' đã được thanh toán thành công qua MoMo. (Mã GD: ' . $transId . ')');

                cdw_process_successful_payment($id);

                wp_send_json_success(['msg' => 'Thanh toán đơn hàng thành công.']);
            } else { // Cancellation or Failure
                update_post_meta($id, 'status', 'cancel');
                update_post_meta($id, 'payment_info', $_GET);
                $customer_id = get_post_meta($id, 'customer-id', true);
                $reason = !empty($message) ? sanitize_text_field($message) : 'Không rõ lý do từ MoMo.';
                cdw_create_customer_log($customer_id, 'Thanh toán MoMo thất bại/hủy', 'Đơn hàng #' . $id . ' đã bị hủy hoặc thanh toán thất bại qua MoMo. Lý do: ' . $reason);

                if (class_exists('CDWNotification')) {
                    $CDWNotification->newNotificationCancelCheckout($id);
                }
                // We send success so the frontend can display the reason to the user.
                wp_send_json_success(['msg' => 'Thanh toán bị hủy/thất bại: ' . $reason]);
            }
        }
        wp_send_json_error(['msg' => 'Missing or invalid Momo parameters.']);
    }
}
new AjaxClientCheckout();
