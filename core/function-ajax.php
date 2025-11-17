<?php
defined('ABSPATH') || exit;
function set_ajax_arc_site_managers_pagination_data()
{
    $query_vars = json_decode(stripslashes($_POST['query_vars']), true);

    $query_vars['paged'] = $_POST['page'];
    if ($_POST['cat'] != -1)
        $query_vars['tax_query'] =  array(
            array(
                'taxonomy' => 'site-types',
                'field'    => 'id',
                'terms'    => $_POST['cat'],
            ),
        );
    $posts = new WP_Query($query_vars);
    $GLOBALS['wp_query'] = $posts;
    $GLOBALS['paged'] = $query_vars['paged'];

    if ($_POST['cat'] != -1)
        $GLOBALS['tax_query'] = $query_vars['tax_query'];

    ob_start();
    echo "<div class=\"row content-item\">";
    if (have_posts()) : while (have_posts()) : the_post();
            get_template_part('template-parts/site-managers/archive', 'content-item');
        endwhile;

    else :

        get_template_part('content', 'none');

    endif;
    echo "</div>";
    congdongtheme_post_pagination_ajax();

    $data = ob_get_clean();

    wp_send_json_success($data);

    wp_send_json_error();
    wp_die();
}

function set_ajax_arc_site_order_post()
{
    global $current_user;
    if (!is_user_logged_in()) {
        echo json_encode(array('order' => false, 'login' => false, 'message' => __('Vui lòng đăng nhập, hoặc đăng ký trước khi mua hàng.')));
        wp_die();
    }
    // First check the nonce, if it fails the function will break
    check_ajax_referer('ajax-order-nonce', 'security');

    $recaptcha = $_POST['grecaptcha'];
    if (!empty($recaptcha)) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = get_field('secret_key', 'option');
        $url = $google_url . '?secret=' . $secret . '&response=' . $recaptcha;
        //get verify response data
        $verifyResponse = file_get_contents($url);
        $responseData   = json_decode($verifyResponse);
        if (!$responseData->success) {
            echo json_encode(array('order' => false, 'message' => __('reCAPTCHA không chính xác')));
            wp_die();
        }
    } else {
        echo json_encode(array('order' => false, 'message' => __('Vui lòng hoàn thành reCAPTCHA')));
        wp_die();
    }
    $itemsstr = $_POST['items'];
    $jsonData = stripslashes(html_entity_decode($itemsstr));

    $items = json_decode($jsonData, true);

    if (is_array($items) && count($items) > 0) {
        $i = 0;
        foreach ($items as $item) {
            switch ($item["type"]) {
                case 1:
                    $gia = get_field("price", $item["id"]);

                    $typeDomain = $item["typeDomain"];
                    $domain = 0;
                    $hosting = 0;
                    switch ($typeDomain) {
                        case 0:
                            $domain = 0;
                            break;
                        case 1:
                            $domain = 300000;
                            break;
                        case 2:
                            $domain = 750000;
                            break;
                    }
                    $typeHosting = $item["typeHosting"];
                    switch ($typeHosting) {
                        case 0:
                            $hosting = 0;
                            break;
                        case 1:
                            $hosting = 1200000;
                            break;
                        case 2:
                            $hosting = 1500000;
                            break;
                        case 3:
                            $hosting = 2000000;
                            break;
                    }
                    $items[$i]["price"] = $gia;
                    $items[$i]["domain"] = $domain;
                    $items[$i]["hosting"] = $hosting;
                    $items[$i]["total"] = $gia + $domain + $hosting;
                    break;
                case 2:
                    $gia = get_field("gia", $item["id"]);
                    $items[$i]["total"] = $gia;
                    $items[$i]["price"] = $gia;
                    break;
            }

            $i++;
        }
    }

    $bank = $_POST['bank'];
    $datasze = serialize($items);
    //Ghi kết quả
    $arr_ketqua = array(
        'post_type' => 'site-orders',
        'post_title' => "Đơn hàng: " . date('Ymd'),
        'post_status' => 'publish'
    );

    $id_site_order = wp_insert_post($arr_ketqua);

    update_post_meta($id_site_order, 'customer', $current_user->ID);
    update_post_meta($id_site_order, 'bank', $bank);
    update_post_meta($id_site_order, 'item', $datasze);


    $my_post = array(
        'ID'           => $id_site_order,
        'post_title'   => "Đơn hàng: " . $id_site_order . '/ ' . date('Ymd'),
    );

    // Update the post into the database
    wp_update_post($my_post);

    echo json_encode(array('id' => $id_site_order, 'order' => true, 'message' => __('Tạo đơn hàng thành công')));
    wp_die();

    wp_send_json_error();
    wp_die();
}

function set_ajax_congdongcontact()
{
    // First check the nonce, if it fails the function will break
    check_ajax_referer('ajax-contact-nonce', 'security');
    // Nonce is checked, get the POST data and sign user on

    $recaptcha = $_POST['grecaptcha'];
    if (!empty($recaptcha)) {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = get_field('secret_key', 'option');
        $url = $google_url . '?secret=' . $secret . '&response=' . $recaptcha;
        //get verify response data
        $verifyResponse = file_get_contents($url);
        $responseData   = json_decode($verifyResponse);
        if (!$responseData->success) {
            echo json_encode(array('contact' => false, 'message' => __('reCAPTCHA không chính xác')));
            wp_die();
        }
    } else {
        echo json_encode(array('contact' => false, 'message' => __('Vui lòng hoàn thành reCAPTCHA')));
        wp_die();
    }

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $note = sanitize_text_field($_POST['note']);

    $to = get_field('smtp_to', 'option');

    $site_title = get_bloginfo('name');
    $body = 'Khách hàng: ' . $name . '<br> Số điện thoại: ' . $phone . '<br> Email: ' . $email . '<br> <br>  Tin nhắn: ' . $note;
    $subject =  $name . ' - Liên hệ';
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . $site_title . ' <' . $to . '>');

    if (wp_mail($to, $subject, $body, $headers)) {
        echo json_encode(array('contact' => true, 'message' => __('Gửi email thành công.')));
        wp_die();
    } else {
        echo json_encode(array('contact' => true, 'message' => __('Gửi email không thành công. Vui lòng thử lại.')));
        wp_die();
    }
}

