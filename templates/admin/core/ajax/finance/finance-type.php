<?php
defined('ABSPATH') || exit;
class AjaxFinanceType
{
    private $postType = 'finance-type';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-finance-type',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-finance-type',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-finance-type',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-finance-type',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-finance-type',  array($this, 'func_delete'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-finance-type-nonce', 'security');

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
            $fieldSearch = ['name', 'note'];
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
            $urlredirect = $CDWFunc->getUrl('detail', 'finance-type', 'id=' . $post->ID);
            $id = $post->ID;
            $name = get_post_meta($post->ID, 'name', true);
            $note = get_post_meta($post->ID, 'note', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $id,
                'name' => $name,
                'note' => $note,

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
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-finance-type-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish'
        );
        $id = wp_insert_post($arr);

        if ($id) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';

            add_post_meta($id, 'name', $name);
            add_post_meta($id, 'note', $note);


            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }

    public function func_update()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-finance-type-nonce', 'security');

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

            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';

            update_post_meta($id, 'name', $name);
            update_post_meta($id, 'note', $note);

            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_delete()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-finance-type-nonce', 'security');

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
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-finance-type-nonce', 'security');

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

new AjaxFinanceType();
