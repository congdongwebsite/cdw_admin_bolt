<?php
defined('ABSPATH') || exit;
class AjaxSiteType
{
    private $taxonomy = 'site-types';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-site-type',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-site-type',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_new-site-type',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_update-site-type',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_delete-site-type',  array($this, 'func_delete'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-site-type-nonce', 'security');

        if (!taxonomy_exists($this->taxonomy)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại: ' . $this->taxonomy]);
        }
        $start = (int)$_POST['start'];
        $limit = (int)$_POST['length'];
        $arr = array(
            'taxonomy' => $this->taxonomy,
            'offset' => $start,
            'number' => $limit,
            'hide_empty' => false,
        );
        $search = $_POST['search'];
        if (is_array($search) && $search['value'] != '') {
            $arr['name__like'] = $search;
        }
        $wp = new WP_Term_Query($arr);
        $terms = $wp->terms;
        $data = [];
        foreach ($terms as $term) {
            $urlredirect = $CDWFunc->getUrl('detail', 'site-type', 'id=' . $term->term_id);
            $id = $term->term_id;
            $name = $term->name;
            $note = $term->description;
            $count = $term->count;

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $id,
                'name' => $name,
                'count' => $count,
                'note' => $note,

            ];
        }
        $response = array('success' => true);
        $response['data'] = $data;
        $response['draw'] = $_POST['draw'];
        $response['recordsTotal'] = wp_count_terms($this->taxonomy);
        $response['recordsFiltered'] = wp_count_terms($this->taxonomy);
        wp_send_json($response);
        wp_die();
    }
    public function func_new()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-site-type-nonce', 'security');

        if (!taxonomy_exists($this->taxonomy)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại: ' . $this->taxonomy]);
        }


        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';

        if (!term_exists($name, $this->taxonomy)) {
            // Nếu thuật ngữ chưa tồn tại, thực hiện thêm mới.
            $args = array(
                'description' => $note,
                'parent' => 0
            );

            $result = wp_insert_term($name, $this->taxonomy, $args);

            if (!is_wp_error($result)) {
                wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $result->term_id]);
            } else {
                wp_send_json_error(['msg' => 'Lỗi khi thêm mới: ' . $result->get_error_message()]);
            }
        } else {
            wp_send_json_error(['msg' => 'Đã tồn tại.']);
        }
        wp_die();
    }

    public function func_update()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-site-type-nonce', 'security');

        if (!taxonomy_exists($this->taxonomy)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại: ' . $this->taxonomy]);
        }
        $term_id = $_POST['id'];

        $term = get_term($term_id, $this->taxonomy);
        if (is_wp_error($term)) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';

        $args = array(
            'name' => $name,
            'description' => $note,
            'parent' => 0
        );

        $result = wp_update_term($term_id, $this->taxonomy, $args);

        if (!is_wp_error($result)) {
            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $term_id]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi khi thêm mới: ' . $result->get_error_message()]);
        }

        wp_die();
    }
    public function func_delete()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-site-type-nonce', 'security');

        if (!taxonomy_exists($this->taxonomy)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại: ' . $this->taxonomy]);
        }
        $term_id = $_POST['id'];

        $term = get_term($term_id, $this->taxonomy);
        if (is_wp_error($term)) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }

        $result = wp_delete_term($term_id, $this->taxonomy);

        if (!is_wp_error($result)) {
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $term_id]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng:' . $result->get_error_message()]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-site-type-nonce', 'security');

        if (!taxonomy_exists($this->taxonomy)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại: ' . $this->taxonomy]);
        }
        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $ids = $_POST['ids'];

        foreach ($ids as $id) {
            $result = wp_delete_term($id, $this->taxonomy);
            $check = is_wp_error($result);
            if ($check) break;
        }

        if ($check) {
            wp_send_json_error(['msg' => 'Một số bài đăng không xóa được']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công']);

        wp_die();
    }
}

new AjaxSiteType();
