<?php
defined('ABSPATH') || exit;
class AjaxManagerVPS
{
    private $postType = 'manage-vps';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-vps',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-vps',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-vps',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-vps',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-vps',  array($this, 'func_delete'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-vps-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'offset' => $_POST['start'],
            'posts_per_page' => $_POST['length'],
        );
        $search = $_POST['search'];
        if (is_array($search) && $search['value'] != '') {
            $fieldSearch = ['ip', 'supplier_name', 'buyer_name', 'url', 'buyer_email', 'buyer_phone', 'buyer_address'];
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
        $wp = new WP_Query($arr);
        $posts = $wp->posts;
        $data = [];
        foreach ($posts as $post) {
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-vps', 'id=' . $post->ID);
            $id = $post->ID;
            $ip = get_post_meta($post->ID, 'ip', true);
            $port = get_post_meta($post->ID, 'port', true);
            $user = get_post_meta($post->ID, 'user', true);
            $pass = get_post_meta($post->ID, 'pass', true);
            $cpu = get_post_meta($post->ID, 'cpu', true);
            $ram = get_post_meta($post->ID, 'ram', true);
            $hhd = get_post_meta($post->ID, 'hhd', true);
            $supplier_name = get_post_meta($post->ID, 'supplier_name', true);
            $service_type = get_post_meta($post->ID, 'service_type', true);
            $url = get_post_meta($post->ID, 'url', true);
            $supplier_user = get_post_meta($post->ID, 'supplier_user', true);
            $supplier_pass = get_post_meta($post->ID, 'supplier_pass', true);
            $service_buy_date = get_post_meta($post->ID, 'service_buy_date', true);
            $service_expiry_date = get_post_meta($post->ID, 'service_expiry_date', true);
            $service_price = get_post_meta($post->ID, 'service_price', true);
            $buyer_name = get_post_meta($post->ID, 'buyer_name', true);
            $buyer_email = get_post_meta($post->ID, 'buyer_email', true);
            $buyer_phone = get_post_meta($post->ID, 'buyer_phone', true);
            $buyer_address = get_post_meta($post->ID, 'buyer_address', true);
            $buyer_card_id = get_post_meta($post->ID, 'buyer_card_id', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $id,
                'ip' => $ip,
                'port' => $port,
                'user' => $user,
                'pass' => $pass,
                'cpu' => $cpu,
                'ram' => $ram,
                'hhd' => $hhd,
                'supplier_name' => $supplier_name,
                'service_type' => $service_type,
                'url' => $url,
                'supplier_user' => $supplier_user,
                'supplier_pass' => $supplier_pass,
                'service_buy_date' => $service_buy_date,
                'service_expiry_date' => $service_expiry_date,
                'service_price' => $service_price,
                'buyer_name' => $buyer_name,
                'buyer_email' => $buyer_email,
                'buyer_phone' => $buyer_phone,
                'buyer_address' => $buyer_address,
                'buyer_card_id' => $buyer_card_id

            ];
        }
        $response = array('success' => true);
        $response['data'] = $data;
        $response['draw'] = $_POST['draw'];
        $response['recordsTotal'] = $wp->found_posts;
        $response['recordsFiltered'] = $wp->found_posts;
        wp_send_json($response);
        wp_die();
    }

    public function func_new()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-vps-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_insert_post($arr);

        if ($id) {

            $ip = isset($_POST['ip']) ? $_POST['ip'] : '';
            $port = isset($_POST['port']) ? $_POST['port'] : '';
            $user = isset($_POST['user']) ? $_POST['user'] : '';
            $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
            $cpu = isset($_POST['cpu']) ? $_POST['cpu'] : '';
            $ram = isset($_POST['ram']) ? $_POST['ram'] : '';
            $hhd = isset($_POST['hhd']) ? $_POST['hhd'] : '';

            $supplier_name = isset($_POST['supplier-name']) ? $_POST['supplier-name'] : '';
            $service_type = isset($_POST['service-type']) ? $_POST['service-type'] : '';
            $url = isset($_POST['url']) ? $_POST['url'] : '';
            $supplier_user = isset($_POST['supplier-user']) ? $_POST['supplier-user'] : '';
            $supplier_pass = isset($_POST['supplier-pass']) ? $_POST['supplier-pass'] : '';

            $service_buy_date =  isset($_POST['service-buy-date']) ? $_POST['service-buy-date'] : '';
            $service_expiry_date =  isset($_POST['service-expiry-date']) ? $_POST['service-expiry-date'] : '';
            $service_price = isset($_POST['service-price']) ? $_POST['service-price'] : '';

            $buyer_name = isset($_POST['buyer-name']) ? $_POST['buyer-name'] : '';
            $buyer_email = isset($_POST['buyer-email']) ? $_POST['buyer-email'] : '';
            $buyer_phone = isset($_POST['buyer-phone']) ? $_POST['buyer-phone'] : '';
            $buyer_address = isset($_POST['buyer-address']) ? $_POST['buyer-address'] : '';
            $buyer_card_id = isset($_POST['buyer-card-id']) ? $_POST['buyer-card-id'] : '';

            add_post_meta($id, 'ip', $ip);
            add_post_meta($id, 'port', $port);
            add_post_meta($id, 'user', $user);
            add_post_meta($id, 'pass', $pass);
            add_post_meta($id, 'cpu', $cpu);
            add_post_meta($id, 'ram', $ram);
            add_post_meta($id, 'hhd', $hhd);


            add_post_meta($id, 'supplier_name', $supplier_name);
            add_post_meta($id, 'service_type', $service_type);
            add_post_meta($id, 'url', $url);
            add_post_meta($id, 'supplier_user', $supplier_user);
            add_post_meta($id, 'supplier_pass', $supplier_pass);


            $CDWFunc->wpdb->add_post_meta_date($id, 'service_buy_date', $service_buy_date);
            $CDWFunc->wpdb->add_post_meta_date($id, 'service_expiry_date', $service_expiry_date);
            add_post_meta($id, 'service_price', $service_price);


            add_post_meta($id, 'buyer_name', $buyer_name);
            add_post_meta($id, 'buyer_email', $buyer_email);
            add_post_meta($id, 'buyer_phone', $buyer_phone);
            add_post_meta($id, 'buyer_address', $buyer_address);
            add_post_meta($id, 'buyer_card_id', $buyer_card_id);

            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }
    public function func_update()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-vps-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $arr = array(
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        if ($id) {

            $ip = isset($_POST['ip']) ? $_POST['ip'] : '';
            $port = isset($_POST['port']) ? $_POST['port'] : '';
            $user = isset($_POST['user']) ? $_POST['user'] : '';
            $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
            $cpu = isset($_POST['cpu']) ? $_POST['cpu'] : '';
            $ram = isset($_POST['ram']) ? $_POST['ram'] : '';
            $hhd = isset($_POST['hhd']) ? $_POST['hhd'] : '';

            $supplier_name = isset($_POST['supplier-name']) ? $_POST['supplier-name'] : '';
            $service_type = isset($_POST['service-type']) ? $_POST['service-type'] : '';
            $url = isset($_POST['url']) ? $_POST['url'] : '';
            $supplier_user = isset($_POST['supplier-user']) ? $_POST['supplier-user'] : '';
            $supplier_pass = isset($_POST['supplier-pass']) ? $_POST['supplier-pass'] : '';
            $service_buy_date = isset($_POST['service-buy-date']) ? $_POST['service-buy-date'] : '';
            $service_expiry_date = isset($_POST['service-expiry-date']) ? $_POST['service-expiry-date'] : '';
            $service_price = isset($_POST['service-price']) ? $_POST['service-price'] : '';

            $buyer_name = isset($_POST['buyer-name']) ? $_POST['buyer-name'] : '';
            $buyer_email = isset($_POST['buyer-email']) ? $_POST['buyer-email'] : '';
            $buyer_phone = isset($_POST['buyer-phone']) ? $_POST['buyer-phone'] : '';
            $buyer_address = isset($_POST['buyer-address']) ? $_POST['buyer-address'] : '';
            $buyer_card_id = isset($_POST['buyer-card-id']) ? $_POST['buyer-card-id'] : '';

            update_post_meta($id, 'ip', $ip);
            update_post_meta($id, 'port', $port);
            update_post_meta($id, 'user', $user);
            update_post_meta($id, 'pass', $pass);
            update_post_meta($id, 'cpu', $cpu);
            update_post_meta($id, 'ram', $ram);
            update_post_meta($id, 'hhd', $hhd);


            update_post_meta($id, 'supplier_name', $supplier_name);
            update_post_meta($id, 'service_type', $service_type);
            update_post_meta($id, 'url', $url);
            update_post_meta($id, 'supplier_user', $supplier_user);
            update_post_meta($id, 'supplier_pass', $supplier_pass);

            $CDWFunc->wpdb->update_post_meta_date($id, 'service_buy_date', $service_buy_date);
            $CDWFunc->wpdb->update_post_meta_date($id, 'service_expiry_date', $service_expiry_date);
            update_post_meta($id, 'service_price', $service_price);


            update_post_meta($id, 'buyer_name', $buyer_name);
            update_post_meta($id, 'buyer_email', $buyer_email);
            update_post_meta($id, 'buyer_phone', $buyer_phone);
            update_post_meta($id, 'buyer_address', $buyer_address);
            update_post_meta($id, 'buyer_card_id', $buyer_card_id);

            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_delete()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-vps-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $check = wp_delete_post($id, true);
        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
    public function func_delete_list()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-vps-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $ids = $_POST['ids'];

        foreach ($ids as $id) {
            $check = wp_delete_post($id, true);
            if (!$check ||  $check == null) break;
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
}
new AjaxManagerVPS();
