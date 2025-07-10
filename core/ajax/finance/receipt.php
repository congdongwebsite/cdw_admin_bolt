<?php
defined('ABSPATH') || exit;
class AjaxReceipt
{
    private $postType = 'receipt';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-receipt',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-receipt',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-receipt',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-receipt',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-receipt',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_load-receipt-detail',  array($this, 'func_load_receipt_detail'));
        add_action('wp_ajax_ajax_check-receipt',  array($this, 'func_check'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-receipt-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $limit = $_POST['length'];
        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $limit,
            'offset' => $_POST['start']
        );
        $search = $_POST['search'];
        if (is_array($search) && $search['value'] != '') {
            $fieldSearch = ['date', 'type', 'note'];
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
            $urlredirect = $CDWFunc->getUrl('detail', 'receipt', 'id=' . $post->ID);
            $date = get_post_meta($post->ID, 'date', true);
            $check = get_post_meta($post->ID, 'check', true);
            $type = get_post_meta(get_post_meta($post->ID, 'type', true), 'name', true);
            $amount = (float) get_post_meta($post->ID, 'total', true);

            $note = get_post_meta($post->ID, 'note', true);
            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $post->ID,
                'code' => "#" . $post->ID,
                'date' => $date,
                'check' => $check,
                'type' => $type,
                'amount' => $amount,
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
        check_ajax_referer('ajax-new-receipt-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_insert_post($arr);

        if ($id) {
            $date = isset($_POST['date']) ? $_POST['date'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';


            $CDWFunc->wpdb->add_post_meta_date($id, 'date', $date);
            add_post_meta($id, 'type', $type);
            add_post_meta($id, 'note', $note);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'amount'];
            $details = $CDWFunc->wpdb->func_new_detail_post('receipt-detail', 'receipt-id', $id, $details, $detailColumns);

            $amount = 0;
            foreach ($details as $detail) {
                $amount += $detail['amount'];
            }
            add_post_meta($id, 'total', $amount);
            
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }

    public function func_update()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-receipt-nonce', 'security');

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

            $date = isset($_POST['date']) ? $_POST['date'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';

            $CDWFunc->wpdb->update_post_meta_date($id, 'date', $date);
            update_post_meta($id, 'type', $type);
            update_post_meta($id, 'note', $note);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'amount'];
            $details = $CDWFunc->wpdb->func_update_detail_post('receipt-detail', 'receipt-id', $id, $details, $detailColumns);

            $amount = 0;
            foreach ($details as $detail) {
                $amount += $detail['amount'];
            }
            update_post_meta($id, 'total', $amount);
            
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_delete()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-receipt-nonce', 'security');

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
        } else {

            $CDWFunc->wpdb->func_delete_detail_post('receipt-detail', 'receipt-id', $id);
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-receipt-nonce', 'security');

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

            $CDWFunc->wpdb->func_delete_detail_post('receipt-detail', 'receipt-id', $id);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else {
            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }
        wp_die();
    }
    public function func_load_receipt_detail()
    {
        global $CDWFunc;
        $postType = 'receipt-detail';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-receipt-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['note', 'amount'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'receipt-id', $_POST['id'], $columns);

        wp_send_json_success($data);
        wp_die();
    }

    public function func_check()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-receipt-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $ids = $_POST['ids'];
        $checked = $_POST['checked'];
        foreach ($ids as $id) {
            update_post_meta($id, 'check', $checked != 'false');
        }

        wp_send_json_success(['msg' => 'Cập nhật đối soát thành công']);

        wp_send_json_error(['msg' => 'Không thành công', 'id' => $id]);

        wp_die();
    }

    public function setFeatureStatus($status, $user_id = "")
    {
        if (empty($user_id)) {
            $userC = wp_get_current_user();
            $user_id = $userC->ID;
        }
        return update_user_meta($user_id, "feature-status", $status);
    }
}

new AjaxReceipt();
