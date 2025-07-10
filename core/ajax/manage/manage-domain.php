<?php
defined('ABSPATH') || exit;
class AjaxManageDomain
{
    private $postType = 'domain';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-manage-domain',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-manage-domain',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-manage-domain',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-manage-domain',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-manage-domain',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_load-manage-domain-detail',  array($this, 'func_load_manage_domain_detail'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-domain-nonce', 'security');

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
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-domain', 'id=' . $post->ID);
            $title = get_the_title($post->ID);
            $gia = get_post_meta($post->ID, 'gia', true);
            $gia_han = get_post_meta($post->ID, 'gia_han', true);
            $note = get_post_meta($post->ID, 'note', true);
            $stt = get_post_meta($post->ID, 'stt', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $post->ID,
                'stt' => $stt,
                'title' => $title,
                'gia' => $gia,
                'gia_han' => $gia_han,
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
        check_ajax_referer('ajax-new-manage-domain-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $postExists = post_exists($title, '', '', $this->postType);
        if ($postExists) {
            wp_send_json_error(['msg' => 'Domain đã tồn tại <a href="' . $CDWFunc->getUrl('detail', 'manage-domain', 'id=' . $postExists) . '">Chuyển tới ' . $title . '</a>']);
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
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $stt = isset($_POST['stt']) ? $_POST['stt'] : '';


            add_post_meta($id, 'gia', $gia);
            add_post_meta($id, 'gia_han', $gia_han);
            add_post_meta($id, 'note', $note);
            add_post_meta($id, 'stt', $stt);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'gia', 'gia_han'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_new_detail_post('domain-detail', 'domain-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('domain-detail', 'domain-id', $id, $details, $detailColumnDates);


            $args = array(
                'post_type' => 'domain-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'domain-id',
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
        check_ajax_referer('ajax-detail-manage-domain-nonce', 'security');

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
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $stt = isset($_POST['stt']) ? $_POST['stt'] : '';

            update_post_meta($id, 'gia', $gia);
            update_post_meta($id, 'gia_han', $gia_han);
            update_post_meta($id, 'note', $note);
            update_post_meta($id, 'stt', $stt);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['note', 'gia', 'gia_han'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_update_detail_post('domain-detail', 'domain-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('domain-detail', 'domain-id', $id, $details, $detailColumnDates);

            $args = array(
                'post_type' => 'domain-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'domain-id',
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
        check_ajax_referer('ajax-detail-manage-domain-nonce', 'security');

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
            $CDWFunc->wpdb->func_delete_detail_post('domain-detail', 'domain-id', $id);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-domain-nonce', 'security');

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

            $CDWFunc->wpdb->func_delete_detail_post('domain-detail', 'domain-id', $id);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
    public function func_load_manage_domain_detail()
    {
        global $CDWFunc;
        $postType = 'domain-detail';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-domain-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['date', 'note', 'gia', 'gia_han'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'domain-id', $_POST['id'], $columns, 'date');

        wp_send_json_success($data);
        wp_die();
    }
}

new AjaxManageDomain();
