<?php
defined('ABSPATH') || exit;
class AjaxManageVersion
{
    private $postType = 'version';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-manage-version',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-manage-version',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-manage-version',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-manage-version',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-manage-version',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_load-manage-version-detail',  array($this, 'func_load_manage_version_detail'));
        add_action('wp_ajax_nopriv_manage-version-download',  array($this, 'func_manage_version_download'));
        add_action('wp_ajax_nopriv_manage-version-latest',  array($this, 'func_manage_version_latest'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-version-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $limit = $_POST['length'];
        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'meta_key' => 'type',
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
        $dataType = [
            "theme" => "Theme",
            "plugin" => "Plugin"
        ];
        $wp = new WP_Query($arr);
        $posts = $wp->posts;
        $data = [];
        foreach ($posts as $post) {
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-version', 'id=' . $post->ID);
            $title = get_the_title($post->ID);
            $type = get_post_meta($post->ID, 'type', true);
            $name = get_post_meta($post->ID, 'name', true);
            $last_version = get_post_meta($post->ID, 'last-version', true);
            $note = get_post_meta($post->ID, 'note', true);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $post->ID,
                'title' => $title,
                'type' => isset($dataType[$type]) ? $dataType[$type] : $type,
                'name' => $name,
                'last_version' => $last_version,
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
        check_ajax_referer('ajax-new-manage-version-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $postExists = post_exists($title, '', '', $this->postType);
        if ($postExists) {
            wp_send_json_error(['msg' => 'Version đã tồn tại <a href="' . $CDWFunc->getUrl('detail', 'manage-version', 'id=' . $postExists) . '">Chuyển tới ' . $title . '</a>']);
        }
        $arr = array(
            'post_type' => $this->postType,
            'post_title' => $title,
            'post_status' => 'publish',
        );
        $id = wp_insert_post($arr);

        if ($id) {
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';


            add_post_meta($id, 'type', $type);
            add_post_meta($id, 'name', $name);
            add_post_meta($id, 'note', $note);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['version', 'url'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_new_detail_post('version-detail', 'version-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('version-detail', 'version-id', $id, $details, $detailColumnDates);


            $args = array(
                'post_type' => 'version-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'version-id',
                        'value' => $id,
                        'compare' => '=',
                    )
                )
            );
            $posts = get_posts($args);

            foreach ($posts as $post) {

                update_post_meta($id, 'last-version', get_post_meta($post, 'version', true));
                update_post_meta($id, 'last-url', get_post_meta($post, 'url', true));
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
        check_ajax_referer('ajax-detail-manage-version-nonce', 'security');

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
            'post_title' => $title,
            'post_status' => 'publish'
        );
        $id = wp_update_post($arr);

        if ($id) {

            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';


            update_post_meta($id, 'type', $type);
            update_post_meta($id, 'name', $name);
            update_post_meta($id, 'note', $note);

            //Detail
            $details = isset($_POST['details']) ? $_POST['details'] : [];
            $detailColumns = ['version', 'url'];
            $detailColumnDates = ['date'];
            $details = $CDWFunc->wpdb->func_update_detail_post('version-detail', 'version-id', $id, $details, $detailColumns);
            $CDWFunc->wpdb->func_update_detail_post_type_date('version-detail', 'version-id', $id, $details, $detailColumnDates);

            $args = array(
                'post_type' => 'version-detail',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'meta_query' => array(
                    'relation' => 'and',
                    array(
                        'key' =>  'version-id',
                        'value' => $id,
                        'compare' => '=',
                    )
                )
            );
            $posts = get_posts($args);

            foreach ($posts as $post) {

                update_post_meta($id, 'last-version', get_post_meta($post, 'version', true));
                update_post_meta($id, 'last-url', get_post_meta($post, 'url', true));
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
        check_ajax_referer('ajax-detail-manage-version-nonce', 'security');

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
            $CDWFunc->wpdb->func_delete_detail_post('version-detail', 'version-id', $id);
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-version-nonce', 'security');

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

            $CDWFunc->wpdb->func_delete_detail_post('version-detail', 'version-id', $id);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }

    public function func_load_manage_version_detail()
    {
        global $CDWFunc;
        $postType = 'version-detail';
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-version-nonce', 'security');

        if (!post_type_exists($postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $postType]);
        }

        $columns = ['date', 'note', 'version', 'url'];
        $data =  $CDWFunc->wpdb->func_load_detail($postType, 'version-id', $_POST['id'], $columns, 'date');

        wp_send_json_success($data);
        wp_die();
    }
    public function func_manage_version_download()
    {
        global $CDWFunc;

        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';
        $version = isset($_POST['version']) ? $_POST['version'] : '';

        if ($code != 'E88485932BDB822B2CA53AC8BC11F')
            wp_send_json_error(['msg' => 'Code của bạn đã bị thay đổi hoặc chưa được đăng ký.']);

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'meta_key' => 'type',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'fields' => 'ids',
            'posts_per_page' => 1
        );

        $arr['meta_query'][] =
            array(
                'key' => 'type',
                'value' => $type,
                'compare' => '=',
            );

        $arr['meta_query'][] =
            array(
                'key' => 'name',
                'value' => $name,
                'compare' => '=',
            );
        $ids = get_posts($arr);

        if (count($ids) > 0) {
            $id = $ids[0];
            $arr = array(
                'post_type' => 'version-detail',
                'post_status' => 'publish',
                'fields' => 'ids',
                'posts_per_page' => 1
            );

            $arr['meta_query'][] =
                array(
                    'key' => 'version-id',
                    'value' => $id,
                    'compare' => '=',
                );
            $arr['meta_query'][] =
                array(
                    'key' => 'version',
                    'value' => $version,
                    'compare' => '=',
                );

            $ids = get_posts($arr);
            if (count($ids) > 0) {
                $id = $ids[0];
                wp_send_json_success(['url' => ADMIN_THEME_URL_F . '/uploads/themes/' . get_post_meta($id, 'url', true)]);
            } else {

                wp_send_json_error(['msg' => 'Không tìm thấy version mới']);
            }
        }
        wp_send_json_error(['msg' => 'Không có sản phẩm']);
    }
    public function func_manage_version_latest()
    {
        global $CDWFunc;

        $type = isset($_POST['type']) ? $_POST['type'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $code = isset($_POST['code']) ? $_POST['code'] : '';

        if ($code != 'E88485932BDB822B2CA53AC8BC11F')
            wp_send_json_error(['msg' => 'Code của bạn đã bị thay đổi hoặc chưa được đăng ký. - ' . $code]);

        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'meta_key' => 'type',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'fields' => 'ids',
            'posts_per_page' => 1
        );

        $arr['meta_query'][] =
            array(
                'key' => 'type',
                'value' => $type,
                'compare' => '=',
            );

        $arr['meta_query'][] =
            array(
                'key' => 'name',
                'value' => $name,
                'compare' => '=',
            );
        $ids = get_posts($arr);

        if (count($ids) > 0) {
            wp_send_json_success(['version' => get_post_meta($ids[0], 'last-version', true), 'msg' => 'Vui lòng cập nhật theme.']);
        } else {
            wp_send_json_error(['msg' => 'Không thấy phiên bản mới']);
        }
        wp_send_json_error(['msg' => 'Không lấy được phiên bản mới']);
    }
}

new AjaxManageVersion();
