<?php
defined('ABSPATH') || exit;
class AjaxClientCart
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get_top-navbar-cart',  array($this, 'func_get_top_navbar_cart'));
        add_action('wp_ajax_ajax_get-list-cart',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_client-cart-checkout',  array($this, 'func_client_cart_checkout'));
        add_action('wp_ajax_ajax_add-domain-client-cart',  array($this, 'func_add_domain_client_cart'));
        add_action('wp_ajax_ajax_add-hosting-client-cart',  array($this, 'func_add_hosting_client_cart'));
        add_action('wp_ajax_ajax_add-email-client-cart',  array($this, 'func_add_email_client_cart'));
        add_action('wp_ajax_ajax_add-plugin-client-cart',  array($this, 'func_add_plugin_client_cart'));
        add_action('wp_ajax_ajax_client-cart-delete',  array($this, 'func_client_cart_delete'));
        add_action('wp_ajax_ajax_client-cart-update',  array($this, 'func_client_cart_update'));
        add_action('wp_ajax_ajax_choose-hosting-client-cart',  array($this, 'func_choose_hosting_client_cart'));
        add_action('wp_ajax_ajax_choose-email-client-cart',  array($this, 'func_choose_email_client_cart'));
        add_action('wp_ajax_ajax_choose-domain-client-cart',  array($this, 'func_choose_domain_client_cart'));
        add_action('wp_ajax_ajax_choose-theme-client-cart',  array($this, 'func_choose_theme_client_cart'));
        add_action('wp_ajax_ajax_choose-plugin-client-cart',  array($this, 'func_choose_plugin_client_cart'));
        add_action('wp_ajax_ajax_get-list-cart-main',  array($this, 'func_get_list_main'));
        add_action('wp_ajax_nopriv_ajax_get-list-cart-main',  array($this, 'func_get_list_main'));
        add_action('wp_ajax_ajax_client-cart-delete-item',  array($this, 'func_client_cart_delete_item'));

        add_action('wp_ajax_ajax_choose-domain-frontend-client-cart',  array($this, 'func_choose_domain_frontend_client_cart'));
        add_action('wp_ajax_ajax_choose-hosting-frontend-client-cart',  array($this, 'func_choose_hosting_frontend_client_cart'));
        add_action('wp_ajax_ajax_choose-email-frontend-client-cart',  array($this, 'func_choose_email_frontend_client_cart'));
        add_action('wp_ajax_ajax_choose-plugin-frontend-client-cart',  array($this, 'func_choose_plugin_frontend_client_cart'));
        add_action('wp_ajax_ajax_client_add_email_change_to_cart',  array($this, 'func_client_add_email_change_to_cart'));
    }

    public function func_client_add_email_change_to_cart()
    {
        global $CDWFunc, $CDWCart;
        // check_ajax_referer('ajax-client-email-nonce', 'security');

        $customer_email_id = isset($_POST['customer_email_id']) ? intval($_POST['customer_email_id']) : 0;
        $new_plan_wp_id = isset($_POST['new_plan_id']) ? intval($_POST['new_plan_id']) : 0;

        // Check if a plan change for this specific email service is already in the cart
        $cart_items = $CDWCart->get();
        foreach ($cart_items as $cart_item) {
            if ($cart_item['type'] === 'customer-email-change' && $cart_item['id'] == $customer_email_id) {
                wp_send_json_error(['msg' => 'Yêu cầu thay đổi cho gói email này đã có trong giỏ hàng.']);
                wp_die();
            }
        }

        if (empty($customer_email_id) || empty($new_plan_wp_id)) {
            wp_send_json_error(['msg' => 'Thiếu thông tin cần thiết.']);
        }

        $old_plan_wp_id = get_post_meta($customer_email_id, 'email-type', true);
        if (empty($old_plan_wp_id)) {
            wp_send_json_error(['msg' => 'Không thể xác định gói email hiện tại.']);
        }

        if ($old_plan_wp_id == $new_plan_wp_id) {
            wp_send_json_error(['msg' => 'Bạn đã chọn gói hiện tại.']);
        }

        // Assuming 'gia' meta field stores the MONTHLY price
        $old_price = (float) get_post_meta($old_plan_wp_id, 'gia_han', true);
        $new_price = (float) get_post_meta($new_plan_wp_id, 'gia_han', true);

        $expiry_date_str = get_post_meta($customer_email_id, 'expiry_date', true);
        $expiry_date = new DateTime($expiry_date_str);
        $current_date = new DateTime(current_time('mysql'));

        if ($expiry_date <= $current_date) {
            wp_send_json_error(['msg' => 'Gói email đã hết hạn. Vui lòng gia hạn trước khi thay đổi gói.']);
        }

        $interval = $current_date->diff($expiry_date);
        $remaining_days = $interval->days;

        if ($remaining_days <= 0) {
             wp_send_json_error(['msg' => 'Gói email còn dưới 1 ngày, không thể thay đổi.']);
        }

        // As per user: Upgrade Price = (New Price - Old Price) / 30 * Remaining Days
        $daily_price_diff = ($new_price - $old_price) / 30;
        $daily_price_diff = max(0, $daily_price_diff);
        $rounded_daily_price = round($daily_price_diff);

        $total_change_cost = $rounded_daily_price * $remaining_days;

        $old_plan_name = get_the_title($old_plan_wp_id);
        $new_plan_name = get_the_title($new_plan_wp_id);

        $service_name = "Thay đổi gói Email: " . get_post_meta($customer_email_id, 'domain', true);
        $description = "Từ gói {$old_plan_name} sang gói {$new_plan_name}. Số ngày còn lại: {$remaining_days} ngày.";

        $item = $CDWCart->newItem(
            'customer-email-change',
            $customer_email_id,
            $service_name,
            $description,
            $rounded_daily_price, // Price per day
            $remaining_days,      // Quantity in days
            $total_change_cost    // Total amount
        );
        
        $item['new_plan_id'] = $new_plan_wp_id;
        $item['old_plan_id'] = $old_plan_wp_id;

        $CDWCart->addByExsitsId([$item]);

        wp_send_json_success(['msg' => 'Đã thêm yêu cầu thay đổi gói vào giỏ hàng.', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        wp_die();
    }
    public function func_get_list()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $actionFound = true;
        $data =  $CDWCart->get();
        $total =  $CDWCart->getTotal();
        $items = [];
        $i = 1;
        foreach ($data as $key => $value) {
            $item = new stdClass();
            $item->id = $value['idc'];
            $item->index = $i++;
            $item->service =  $value['service'];
            $item->description =  $value['description'];
            $item->quantity =  $value['quantity'];
            $item->price =  $value['price'];
            $item->price_label =  $CDWFunc->number->amount($value["price"]);
            $item->amount =  $value['amount'];
            $item->amount_label = $CDWFunc->number->amount($value["amount"]);
            $item->type = $value['type'];

            $items[] = $item;
        }
        if (count($data) == 0) {
            $items[] = ['notFound' => true];
            $actionFound = false;
        }
        $checkout = new stdClass();
        $checkout->amount = $total->amount;
        $checkout->amount_label = $CDWFunc->number->amount($total->amount);
        $checkout->actionFound = $actionFound;
        $template = [
            'item' => 'cart-item-template',
            'action' => 'cart-action-template',
            'checkout' => 'cart-checkout-action-template',
        ];
        wp_send_json_success(['items' => $items, 'template' => $template, 'actionFound' => $actionFound, 'checkout' => $checkout]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }
    public function func_get_top_navbar_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $actionFound = true;
        $data =  $CDWCart->get();
        $total =  $CDWCart->getTotal();
        $items = [];
        // $i = 1;
        // foreach ($data as $key => $value) {
        //     $item = new stdClass();
        //     $item->id = $value['idc'];
        //     $item->index = $i++;
        //     $item->service =  $value['service'];
        //     $item->description =  $value['description'];
        //     $item->quantity =  $value['quantity'];
        //     $item->price =  $value['price'];
        //     $item->price_label =  $CDWFunc->number->amount($value["price"]);
        //     $item->amount =  $value['amount'];
        //     $item->amount_label = $CDWFunc->number->amount($value["price"]);

        //     $items[] = $item;
        // }
        if (count($data) == 0) {
            // $item[] = ['notFound' => true];
            $actionFound = false;
        }
        $checkout = new stdClass();
        $checkout->url = $CDWFunc->getUrl('cart', 'client');
        // $checkout->amount = $total->amount;
        // $checkout->amount_label = $CDWFunc->number->amount($total->amount);
        // $checkout->actionFound = $actionFound;
        $template = [
            'dot' => 'cart-top-navbar-dot-template',
        ];
        $CDWCart->setStatus(false);
        wp_send_json_success(['items' => $items, 'count' => $total->quantity, 'template' => $template, 'has' => $actionFound, 'checkout' => $checkout]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }
    public function func_client_cart_checkout()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $data = isset($_POST['data']) ? $_POST['data'] : "";
        $tax_has = isset($_POST['tax_has']) ? $_POST['tax_has'] == 'true' : false;
        $tax_company = isset($_POST['tax_company']) ? $_POST['tax_company'] : "";
        $tax_code = isset($_POST['tax_code']) ? $_POST['tax_code'] : "";
        $tax_email = isset($_POST['tax_email']) ? $_POST['tax_email'] : "";

        if ($tax_has && (empty($tax_has) || empty($tax_company) || empty($tax_code))) {
            wp_send_json_error(['msg' => 'Vui lòng hoàn tất thông tin xuất hoá đơn']);
        }
        $items =  $CDWCart->get();

        $userC = wp_get_current_user();
        $customer_id = get_user_meta($userC->ID, 'customer-id', true);
        if (empty($customer_id))
            wp_send_json_error(['msg' => 'Không có khách hàng để tạo thanh toán']);

        if (count($items) == 0) {
            wp_send_json_error(['msg' => 'Không có dịch vụ trong giỏ hàng']);
        }

        $changePrice = array_column($items, 'price', 'idc');
        $changeQuantity = array_column($items, 'quantity', 'idc');
        foreach ($items as $key => $item) {
            $validated_props = $CDWCart->get_validated_item_properties($item);
            $changePrice[$key] = $validated_props['price'];
            $changeQuantity[$key] = $validated_props['quantity'];
        }

        $CDWCart->changePrice($changePrice);
        $CDWCart->changeQuantity($changeQuantity);
        $CDWCart->setTax($tax_has, $tax_company, $tax_code, $tax_email);
        wp_send_json_success(['msg' => 'Chuyển qua trang thanh toán', 'checkout_url' => $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&step=1'), 'security' => wp_create_nonce('wp_nonce_field')]);

        wp_send_json_error(['msg' => 'Không tạo được thanh toán']);
        wp_die();
    }
    public function func_client_cart_update()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $data = isset($_POST['data']) ? $_POST['data'] : "";

        $ids = array_column($data, 'quantity', 'id');
        $CDWCart->deleteNotExists($ids);

        $items =  $CDWCart->get();
        $changeQuantity = array_column($items, 'quantity', 'idc');
        $changePrice = array_column($items, 'price', 'idc');
        // var_dump($items);
        foreach ($items as $key => $item) {
            if (array_key_exists($key, $ids)) {
                $quantity = (float) $ids[$key];
                $item['quantity'] = $quantity; // Cập nhật số lượng tạm thời để tính toán

                $validated_props = $CDWCart->get_validated_item_properties($item);

                $changePrice[$key] = $validated_props['price'];
                $changeQuantity[$key] = $validated_props['quantity'];
            }
        }
        // var_dump($changePrice, $changeQuantity);
        $CDWCart->changePrice($changePrice);
        $CDWCart->changeQuantity($changeQuantity);


        wp_send_json_success(['msg' => 'Cập nhật giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Cập nhật không thành công']);
        wp_die();
    }
    public function func_client_cart_delete()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');
        $CDWCart->clear();
        wp_send_json_success(['msg' => 'Xóa giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Xóa giỏ hàng không thành công']);
        wp_die();
    }
    public function func_add_domain_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";

        $items = $this->get_service_domain_cart($ids);
        if (!$items) {
            wp_send_json_error(['msg' => 'Domain không hợp lệ vui lòng liên hệ người bán.']);
        }
        // foreach ($ids as $id) {
        //     $type = get_post_type($id);
        //     if (empty($type)) continue;
        //     $expiry_date = get_post_meta($id, 'expiry_date', true);
        //     if (is_date_greater_than_30_days($expiry_date)) {
        //         wp_send_json_error(['msg' => 'Chỉ được thêm dịch vụ hết hạn trong 30 ngày vào giỏ hàng. Vui lòng kiểm tra lại.']);
        //     }
        // }
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
            foreach ($items as $item) {
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
        $CDWCart->addByExsitsField($items, 'domain', 'customer-domain');

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_add_hosting_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";

        $items = $this->get_service_hosting_cart($ids);

        // foreach ($ids as $id) {
        //     $type = get_post_type($id);
        //     if (empty($type)) continue;
        //     $expiry_date = get_post_meta($id, 'expiry_date', true);
        //     if (is_date_greater_than_30_days($expiry_date)) {
        //         wp_send_json_error(['msg' => 'Chỉ được thêm dịch vụ hết hạn trong 30 ngày vào giỏ hàng. Vui lòng kiểm tra lại.']);
        //     }
        // }
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
            foreach ($items as $item) {
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

        $CDWCart->addByExsitsId($items);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_add_email_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";

        $items = $this->get_service_email_cart($ids);

        // foreach ($ids as $id) {
        //     $type = get_post_type($id);
        //     if (empty($type)) continue;
        //     $expiry_date = get_post_meta($id, 'expiry_date', true);
        //     if (is_date_greater_than_30_days($expiry_date)) {
        //         wp_send_json_error(['msg' => 'Chỉ được thêm dịch vụ hết hạn trong 30 ngày vào giỏ hàng. Vui lòng kiểm tra lại.']);
        //     }
        // }
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
            foreach ($items as $item) {
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

        $CDWCart->addByExsitsId($items);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_add_plugin_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $ids = isset($_POST['ids']) ? $_POST['ids'] : "";

        $items = $this->get_service_plugin_cart($ids);

        // foreach ($ids as $id) {
        //     $type = get_post_type($id);
        //     if (empty($type)) continue;
        //     $expiry_date = get_post_meta($id, 'expiry_date', true);
        //     if (is_date_greater_than_30_days($expiry_date)) {
        //         wp_send_json_error(['msg' => 'Chỉ được thêm dịch vụ hết hạn trong 30 ngày vào giỏ hàng. Vui lòng kiểm tra lại.']);
        //     }
        // }
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
            foreach ($items as $item) {
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

        $CDWCart->addByExsitsId($items);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_hosting_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-hosting-choose-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua sản phẩm', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        }
        $item_new = $CDWCart->newItem('manage-hosting', $id, get_the_title($id, "name", true), "Đăng ký mua mới hosting", $price, 1, $price);

        if ($CDWFunc->isAdministrator()) {
            $cart_items = $CDWCart->get();
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
        }
        $CDWCart->addByExsitsId([$item_new]);
        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_hosting_frontend_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua sản phẩm',  'cart_url' => '/gio-hang']);
        }

        $item = $CDWCart->newItem('manage-hosting', $id, get_the_title($id, "name", true), "Đăng ký mua mới hosting", $price, 1, $price);

        $CDWCart->addByExsitsId([$item]);
        wp_send_json_success(['msg' => 'Bạn muốn mở giỏ hàng?',  'cart_url' => '/gio-hang']);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_email_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-email-choose-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua sản phẩm', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        }
        $item_new = $CDWCart->newItem('manage-email', $id, get_the_title($id, "name", true), "Đăng ký mua mới email", $price, 1, $price);

        if ($CDWFunc->isAdministrator()) {
            $cart_items = $CDWCart->get();
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
        }
        $CDWCart->addByExsitsId([$item_new]);
        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_email_frontend_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua sản phẩm',  'cart_url' => '/gio-hang']);
        }
        $item = $CDWCart->newItem('manage-email', $id, get_the_title($id, "name", true), "Đăng ký mua mới email", $price, 1, $price);

        $CDWCart->addByExsitsId([$item]);
        wp_send_json_success(['msg' => 'Bạn muốn mở giỏ hàng?',  'cart_url' => '/gio-hang']);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_choose_plugin_frontend_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'price', true);
        if ($id == -1 || $price == -1) {
            wp_send_json_success([
                'title' => 'Liên hệ',
                'msg' => 'Vui lòng liên hệ để mua plugin',
                'cart_url' => '/gio-hang'
            ]);
        }

        $item = $CDWCart->newItem(
            'manage-plugin',
            $id,
            get_post_meta($id, 'name', true),
            "Đăng ký mua plugin",
            $price,
            1,
            $price
        );

        $CDWCart->addByExsitsId([$item]);
        wp_send_json_success([
            'msg' => 'Bạn muốn mở giỏ hàng?',
            'cart_url' => '/gio-hang'
        ]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_choose_domain_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-choose-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($id == -1 ||  $price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua domain', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        }

        $item_new = $CDWCart->newItem('manage-domain', $id, $domain, "Đăng ký mua mới domain", $price, 1, $price);
        $item_new['domain'] = $domain;

        if ($CDWFunc->isAdministrator()) {
            $cart_items = $CDWCart->get();
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
        }

        $CDWCart->addByExsitsField([$item_new], 'domain', 'manage-domain');

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_domain_frontend_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";

        $price = (float) get_post_meta($id, 'gia', true);
        if ($id == -1 ||  $price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua domain',  'cart_url' => '/gio-hang']);
        }

        $item = $CDWCart->newItem('manage-domain', $id, $domain, "Đăng ký mua mới domain", $price, 1, $price);
        $item['domain'] = $domain;

        $CDWCart->addByExsitsField([$item], 'domain', 'manage-domain');

        wp_send_json_success(['msg' => 'Bạn muốn mở giỏ hàng?', 'cart_url' => '/gio-hang']);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_theme_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-theme-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'price', true);
        if ($id == -1 ||  $price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua domain', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        }
        $item_new = $CDWCart->newItem('site-managers', $id, get_post_meta($id, 'name', true), "Đăng ký mua theme", $price, 1, $price);

        if ($CDWFunc->isAdministrator()) {
            $cart_items = $CDWCart->get();
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
        }
        $CDWCart->addByExsitsId([$item_new]);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }

    public function func_choose_plugin_client_cart()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-plugin-nonce', 'security');

        $userC = wp_get_current_user();
        $customer_default_id = get_user_meta($userC->ID, 'customer-default-id', true);

        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $price = (float) get_post_meta($id, 'price', true);
        if ($id == -1 ||  $price == -1) {
            wp_send_json_success(['title' => 'Liên hệ', 'msg' => 'Vui lòng liên hệ để mua domain', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);
        }
        $item_new = $CDWCart->newItem('manage-plugin', $id, get_post_meta($id, 'name', true), "Đăng ký mua plugin", $price, 1, $price);

        if ($CDWFunc->isAdministrator()) {
            $cart_items = $CDWCart->get();
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
        }

        $CDWCart->addByExsitsId([$item_new]);

        wp_send_json_success(['msg' => 'Thêm vào giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Thêm giỏ hàng không thành công']);
        wp_die();
    }
    public function func_get_list_main()
    {
        global $CDWFunc, $CDWCart, $CDWConst;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $tax_has = true; // isset($_POST['vat']) ? $_POST['vat'] =='true': false;


        $actionFound = true;
        $data =  $CDWCart->get();
        $total =  $CDWCart->getTotal();
        $items = [];
        $i = 1;
        foreach ($data as $key => $value) {
            $item = new stdClass();
            $item->id = $value['idc'];
            $item->index = $i++;
            $item->service =  $value['service'];
            $item->description =  $value['description'];
            $item->quantity =  $value['quantity'];

            switch ($value["type"]) {
                case 'customer-domain':
                case 'customer-hosting':
                case 'manage-hosting':
                case 'manage-domain':
                case 'manage-email':
                case 'manage-plugin':
                    $item->quantity_uom = ' / Năm';
                    break;
                case 'site-managers':
                    $item->quantity_uom = '';
                    break;
            }
            $item->price =  $value['price'];
            $item->price_label =  $CDWFunc->number->amount($value["price"]);
            $item->amount =  $value['amount'];
            $item->amount_label = $CDWFunc->number->amount($value["amount"]);

            $items[] = $item;
        }
        if (count($data) == 0) {
            $items[] = ['notFound' => true];
            $actionFound = false;
        }
        $summary = new stdClass();
        $summary->sub_amount = $total->amount;
        $summary->sub_amount_label = $CDWFunc->number->amount($summary->sub_amount);
        $summary->actionFound = $actionFound;
        $summary->promotion = 0;
        $summary->vat_percent_label = $CDWFunc->number->amount($CDWConst->vatPercent);
        $summary->vat_label = 0;
        if ($tax_has) {
            $summary->vat = $summary->sub_amount * ($CDWConst->vatPercent / 100);
            $summary->vat_label = $CDWFunc->number->amount($summary->vat);
        }
        $summary->amount = $total->amount +  $summary->vat;
        $summary->amount_label = $CDWFunc->number->amount($summary->amount);
        $template = [
            'item' => 'cart-item-template',
            'action' => 'cart-action-template',
            'summary' => 'cart-summary-template',
            'vat' => 'cart-vat-template',
            'summary-total' => 'cart-summary-total-template',
            'checkout' => 'cart-action-checkout-template',
        ];
        wp_send_json_success(['items' => $items, 'template' => $template, 'actionFound' => $actionFound, 'summary' => $summary]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }

    public function func_client_cart_delete_item()
    {
        global $CDWFunc, $CDWCart;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-cart-nonce', 'security');

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $CDWCart->delete($id);
        wp_send_json_success(['msg' => 'Xóa giỏ hàng thành công', 'cart_url' => $CDWFunc->getUrl('cart', 'client')]);

        wp_send_json_error(['msg' => 'Xóa giỏ hàng không thành công']);
        wp_die();
    }
    public static function get_service_domain_cart($ids)
    {
        global $CDWFunc, $CDWCart;
        $items = [];
        foreach ($ids as $id) {

            $domain = get_post_meta($id, 'url', true);
            $type = get_post_meta($id, 'domain-type', true);
            $price = (float) get_post_meta($type, 'gia_han', true);
            if (!$price) {
                $price = (float)  $CDWFunc->wpdb->get_price_domain($domain);
            }
            if (!$price) continue;
            $note = " Hết hạn từ ngày " . $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, "expiry_date", true));
            $item = $CDWCart->newItem('customer-domain', $id, $domain, $note, $price, 1, $price);
            $item['domain'] = $domain;
            $items[] = $item;
        }
        return $items;
    }
    public static function get_service_hosting_cart($ids)
    {
        global $CDWFunc, $CDWCart;
        $items = [];
        foreach ($ids as $id) {
            $type = get_post_meta($id, 'type', true);
            $price = (float) get_post_meta($type, 'gia_han', true);
            if ($price == -1) {
                $price = (float) get_post_meta($id, 'price', true);
            }
            $service = get_the_title($type);
            $service .= "/" . get_post_meta($id, "ip", true);
            //if (empty($service)) 
            $note = " Hết hạn từ ngày " . $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, "expiry_date", true));
            $item = $CDWCart->newItem('customer-hosting', $id, $service, $note, $price, 1, $price);
            $items[] = $item;
        }
        return $items;
    }
    public static function get_service_email_cart($ids)
    {
        global $CDWFunc, $CDWCart;
        $items = [];
        foreach ($ids as $id) {
            $type = get_post_meta($id, 'email-type', true);
            $price = (float) get_post_meta($type, 'gia_han', true);
            if ($price == -1) {
                $price = (float) get_post_meta($id, 'price', true);
            }
            $service = get_the_title($type);
            $service .= "/" . get_post_meta($id, "ip", true);
            // if (empty($service)) 
            $note = " Hết hạn từ ngày " . $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, "expiry_date", true));
            $item = $CDWCart->newItem('customer-email', $id, $service, $note, $price, 1, $price);
            $items[] = $item;
        }
        return $items;
    }
    public static function get_service_plugin_cart($ids)
    {
        global $CDWFunc, $CDWCart;
        $items = [];
        foreach ($ids as $id) {
            $type = get_post_meta($id, 'plugin-type', true);
            $price = (float) get_post_meta($type, 'price', true);
            if ($price == -1) {
                $price = (float) get_post_meta($id, 'price', true);
            }
            $service = get_post_meta($id, "name", true);
            $service .= "/" . mask_license(get_post_meta($id, "license", true));
            // if (empty($service)) 
            $note = " Hết hạn từ ngày " . $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, "expiry_date", true));
            $item = $CDWCart->newItem('customer-plugin', $id, $service, $note, $price, 1, $price);
            $items[] = $item;
        }
        return $items;
    }
}
new AjaxClientCart();
