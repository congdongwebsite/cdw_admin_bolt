<?php
defined('ABSPATH') || exit;
class AjaxManageEmail
{
    private $postType = 'email';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-manage-email',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-manage-email',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-manage-email',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-manage-email',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-manage-email',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_load-manage-email-detail',  array($this, 'func_load_manage_email_detail'));
        add_action('wp_ajax_ajax_get_inet_email_plan_detail',  array($this, 'func_get_inet_email_plan_detail'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-email-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $limit = $_POST['length'];
        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'meta_key' => 'stt',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => $limit,
            'offset' => $_POST['start']
        );
        $search = $_POST['search'];
        if (is_array($search) && $search['value'] != '') {
            $fieldSearch = ['date'];
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
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-email', 'id=' . $post->ID);
            $title = get_the_title($post->ID);
            $gia = get_post_meta($post->ID, 'gia', true);
            $gia_han = get_post_meta($post->ID, 'gia_han', true);
            $account = get_post_meta($post->ID, 'account', true);
            $hhd = get_post_meta($post->ID, 'hhd', true);
            $note = get_post_meta($post->ID, 'note', true);
            $stt = get_post_meta($post->ID, 'stt', true);
            $stt = get_post_meta($post->ID, 'stt', true);
            $inet_plan_id = get_post_meta($post->ID, 'inet_plan_id', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $post->ID,
                'stt' => $stt,
                'inet_plan_id' => $inet_plan_id,
                'title' => $title,
                'gia' => $gia,
                'gia_han' => $gia_han,
                'account' => $account,
                'hhd' => $hhd,
                'note' => $note
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
        check_ajax_referer('ajax-new-manage-email-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $postExists = post_exists($title, '', '', $this->postType);
        if ($postExists) {
            wp_send_json_error(['msg' => 'Email đã tồn tại <a href="' . $CDWFunc->getUrl('detail', 'manage-email', 'id=' . $postExists) . '">Chuyển tới ' . $title . '</a>']);
        }
        $arr = array(
            'post_type' => $this->postType,
            'post_title' => $title,
            'post_status' => 'publish',
        );
        $id = wp_insert_post($arr);

        if ($id) {
            $gia = isset($_POST['gia']) ? $_POST['gia'] : '';
            $gia_han = isset($_POST['gia_han']) ? $_POST['gia_han'] : '';
            $account = isset($_POST['account']) ? $_POST['account'] : '';
            $hhd = isset($_POST['hhd']) ? $_POST['hhd'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $stt = isset($_POST['stt']) ? $_POST['stt'] : '';
            $inet_plan_id = isset($_POST['inet_plan_id']) ? $_POST['inet_plan_id'] : '';


            add_post_meta($id, 'gia', $gia);
            add_post_meta($id, 'gia_han', $gia_han);
            add_post_meta($id, 'account', $account);
            add_post_meta($id, 'hhd', $hhd);
            add_post_meta($id, 'note', $note);
            add_post_meta($id, 'stt', $stt);
            add_post_meta($id, 'inet_plan_id', $inet_plan_id);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'gia', 'gia_han'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_new_detail_post('email-detail', 'email-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('email-detail', 'email-id', $id, $details, $detailColumnDates);


            $args = array(
                'post_type' => 'email-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'email-id',
                        'value' => $id,
                        'compare' => '=',
                    )
                )
            );
            $posts = get_posts($args);

            foreach ($posts as $post) {

                update_post_meta($id, 'gia', get_post_meta($post, 'gia', true));
                update_post_meta($id, 'gia_han', get_post_meta($post, 'gia_han', true));
            }

            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }

    public function func_update()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-email-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $arr = array(
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            //'post_title' => $title,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        if ($id) {

            $gia = isset($_POST['gia']) ? $_POST['gia'] : '';
            $gia_han = isset($_POST['gia_han']) ? $_POST['gia_han'] : '';
            $account = isset($_POST['account']) ? $_POST['account'] : '';
            $hhd = isset($_POST['hhd']) ? $_POST['hhd'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $stt = isset($_POST['stt']) ? $_POST['stt'] : '';
            $inet_plan_id = isset($_POST['inet_plan_id']) ? $_POST['inet_plan_id'] : '';

            update_post_meta($id, 'gia', $gia);
            update_post_meta($id, 'gia_han', $gia_han);
            update_post_meta($id, 'account', $account);
            update_post_meta($id, 'hhd', $hhd);
            update_post_meta($id, 'note', $note);
            update_post_meta($id, 'stt', $stt);
            update_post_meta($id, 'inet_plan_id', $inet_plan_id);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'gia', 'gia_han'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_update_detail_post('email-detail', 'email-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('email-detail', 'email-id', $id, $details, $detailColumnDates);

            $args = array(
                'post_type' => 'email-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'email-id',
                        'value' => $id,
                        'compare' => '=',
                    )
                )
            );
            $posts = get_posts($args);

            foreach ($posts as $post) {

                update_post_meta($id, 'gia', get_post_meta($post, 'gia', true));
                update_post_meta($id, 'gia_han', get_post_meta($post, 'gia_han', true));
            }

            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_delete()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-email-nonce', 'security');

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
        }
        else {
            $CDWFunc->wpdb->func_delete_detail_post('email-detail', 'email-id', $id);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-email-nonce', 'security');

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

            $CDWFunc->wpdb->func_delete_detail_post('email-detail', 'email-id', $id);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        }
        else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
    public function func_load_manage_email_detail()
    {
        global $CDWFunc;
        $postType = 'email-detail';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-email-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['date', 'note', 'gia', 'gia_han'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'email-id', $_POST['id'], $columns, 'date');

        wp_send_json_success($data);
        wp_die();
    }

    public function func_get_inet_email_plan_detail()
    {
        check_ajax_referer('ajax-new-manage-email-nonce', 'security');

        if (!isset($_POST['plan_id'])) {
            wp_send_json_error(['msg' => 'Không có ID gói']);
        }
        $plan_id = $_POST['plan_id'];

        global $CDWFunc;
        $inet_api = $CDWFunc->iNET;

        $response = $inet_api->get_service_plans('EMAIL');

        if ($response['status'] > 200) {
            wp_send_json_error(['msg' => 'Không thể lấy danh sách gói từ iNET', 'data' => json_decode($response['data'])]);
        }

        $all_plans = json_decode($response['data'], true);
        $plan_detail = null;

        foreach ($all_plans as $plan) {
            if (isset($plan['serviceType']) && $plan['serviceType'] == 'EMAIL' && $plan['id'] == $plan_id) {
                $plan_detail = $plan;
                break;
            }
        }

        if ($plan_detail) {
            $response_data = [
                'title' => $plan_detail['name'],
                'id' => $plan_detail['id'],
                'account' => isset($plan_detail['attribute']['emailAccount']) ? $plan_detail['attribute']['emailAccount'] : '',
                'hhd' => isset($plan_detail['attribute']['storage']) ? $plan_detail['attribute']['storage'] : '',
                'gia' => $plan_detail['price'],
                'gia_han' => isset($plan_detail['price_renew']) ? $plan_detail['price_renew'] : $plan_detail['price']
            ];
            wp_send_json_success($response_data);
        } else {
            wp_send_json_error(['msg' => 'Không tìm thấy chi tiết gói']);
        }

        wp_die();
    }
}

new AjaxManageEmail();