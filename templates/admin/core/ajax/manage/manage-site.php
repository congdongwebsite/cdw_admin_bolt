<?php
defined('ABSPATH') || exit;
class AjaxManageSite
{
    private $postType = 'site-managers';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-manage-site',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_delete-list-manage-site',  array($this, 'func_delete_list'));
        add_action('wp_ajax_ajax_create-user-manage-site',  array($this, 'func_create_user'));
        add_action('wp_ajax_ajax_new-manage-site',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_new-image-manage-site',  array($this, 'func_new_image'));
        add_action('wp_ajax_ajax_new-thumbnail-manage-site',  array($this, 'func_new_thumbnail'));
        add_action('wp_ajax_ajax_update-manage-site',  array($this, 'func_update'));
        add_action('wp_ajax_ajax_update-image-manage-site',  array($this, 'func_update_image'));
        add_action('wp_ajax_ajax_update-thumbnail-manage-site',  array($this, 'func_update_thumbnail'));
        add_action('wp_ajax_ajax_delete-manage-site',  array($this, 'func_delete'));
    }
    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-site-nonce', 'security');

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
            $fieldSearch = ['name', 'sub_domain'];
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
            $urlredirect = $CDWFunc->getUrl('detail', 'manage-site', 'id=' . $post->ID);
            $id = $post->ID;
            $title = get_the_title($post->ID);
            $name = get_post_meta($post->ID, 'name', true);
            $sub_domain = get_post_meta($post->ID, 'sub_domain', true);
            $price = get_post_meta($post->ID, 'price', true);
            $login_user = get_post_meta($post->ID, 'login_user', true);
            $type = wp_get_post_terms($post->ID, 'site-types', array('fields' => 'names'));;

            $thumbnail_id = get_post_thumbnail_id($post->ID);
            $image =  wp_get_attachment_url($thumbnail_id);

            $data[] = [
                'urlredirect' => $urlredirect,
                'id' => $id,
                'image' => !$image ? '' : $image,
                'title' => $title,
                'name' => $name,
                'type' => implode(",", $type),
                'url' => $sub_domain,
                'price' => $price,
                'username' => $login_user,

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
        check_ajax_referer('ajax-new-manage-site-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $type = isset($_POST['type']) ? $_POST['type'] : '';
        if (count($type) == 0) {
            wp_send_json_error(['msg' => 'Vui lòng chọn loại giao diện']);
        }
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';
        $arr = array(
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'post_title' => $title,
            'post_content' => $note,
        );
        $id = wp_insert_post($arr);

        if ($id) {

            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $sub_domain = isset($_POST['sub_domain']) ? $_POST['sub_domain'] : '';
            $price = isset($_POST['price']) ? $_POST['price'] : '';
            $login_user = isset($_POST['login_user']) ? $_POST['login_user'] : '';
            $login_password = isset($_POST['login_password']) ? $_POST['login_password'] : '';

            add_post_meta($id, 'name', $name);
            add_post_meta($id, 'sub_domain', $sub_domain);
            add_post_meta($id, 'price', $price);
            add_post_meta($id, 'login_user', $login_user);
            add_post_meta($id, 'login_password', $login_password);
            wp_set_post_terms($id,  $type, 'site-types');

            wp_send_json_success(['msg' => 'Tạo thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo được bài đăng']);

        wp_die();
    }
    public function func_new_image()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-manage-site-nonce', 'security');

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
            $id_exsits = [];
            $files = $_FILES['files'];
            foreach ($files['name'] as $key => $value) {
                if ($files['error'][$key] === 0) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $upload_overrides = array('test_form' => false);
                    $movefile = wp_handle_upload($file, $upload_overrides);
                    if ($movefile && !isset($movefile['error'])) {
                        $wp_filetype = $movefile['type'];
                        $filename = $movefile['file'];
                        $wp_upload_dir = wp_upload_dir();
                        $attachment = array(
                            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                            'post_mime_type' => $wp_filetype,
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        $attach_id = wp_insert_attachment($attachment, $filename);
                        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        $id_exsits[] = $attach_id;
                    }
                }
            }
            add_post_meta($id, 'album_image', $id_exsits);

            wp_send_json_success(['msg' => 'Tải ảnh thành công', 'id' => $id_exsits]);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_new_thumbnail()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-manage-site-nonce', 'security');

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
            $limit_file_size = 5000000;
            if (isset($_FILES) && 0 < count($_FILES)) {
                // check the file size
                if ($limit_file_size < $_FILES['file']['size']) {
                    wp_send_json_error(['msg' => 'Tập tin có dung lượng lớn']);
                }
                // check the file type
                $file_name_parts = explode('.', $_FILES['file']['name']);
                $file_ext = $file_name_parts[count($file_name_parts) - 1];
                if (!$CDWFunc->is_valid_file_type($file_ext)) {
                    wp_send_json_error(['msg' => 'Vui lòng tải lên hình ảnh PNG, JPG, JPEG, GIF']);
                }
                $attachmentId = media_handle_upload('file', $id);
                if (!is_wp_error($attachmentId)) {
                    set_post_thumbnail($id, $attachmentId);
                } else {
                    wp_send_json_error(['msg' => $attachmentId->get_error_code() . " " . $attachmentId->get_error_message()]);
                }
            }

            wp_send_json_success(['msg' => 'Tải ảnh thành công']);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_update()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-site-nonce', 'security');
        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        if (!is_array($type) || count($type) == 0) {
            wp_send_json_error(['msg' => 'Vui lòng chọn loại giao diện']);
        }
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';

        $arr = array(
            'ID' => $_POST['id'],
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'post_title' => $title,
            'post_content' => $note,
        );
        $id = wp_update_post($arr);

        if ($id) {

            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $sub_domain = isset($_POST['sub_domain']) ? $_POST['sub_domain'] : '';
            $price = isset($_POST['price']) ? $_POST['price'] : '';
            $login_user = isset($_POST['login_user']) ? $_POST['login_user'] : '';
            $login_password = isset($_POST['login_password']) ? $_POST['login_password'] : '';

            update_post_meta($id, 'name', $name);
            update_post_meta($id, 'sub_domain', $sub_domain);
            update_post_meta($id, 'price', $price);
            update_post_meta($id, 'login_user', $login_user);
            update_post_meta($id, 'login_password', $login_password);
            wp_set_post_terms($id,  $type, 'site-types');

            wp_send_json_success(['msg' => 'Lưu thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không cập nhật được bài đăng']);

        wp_die();
    }
    public function func_update_image()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-site-nonce', 'security');

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

            $id_exsits = isset($_POST['id_exsits']) ? $_POST['id_exsits'] : [];
            $files = $_FILES['files'];
            foreach ($files['name'] as $key => $value) {
                if ($files['error'][$key] === 0) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $upload_overrides = array('test_form' => false);
                    $movefile = wp_handle_upload($file, $upload_overrides);
                    if ($movefile && !isset($movefile['error'])) {
                        $wp_filetype = $movefile['type'];
                        $filename = $movefile['file'];
                        $wp_upload_dir = wp_upload_dir();
                        $attachment = array(
                            'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
                            'post_mime_type' => $wp_filetype,
                            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );
                        $attach_id = wp_insert_attachment($attachment, $filename);
                        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        $id_exsits[] = $attach_id;
                    }
                }
            }
            $this->delete_images_not_exsits($id, $id_exsits);
            update_post_meta($id, 'album_image', $id_exsits);

            wp_send_json_success(['msg' => 'Tải ảnh thành công', 'id' => $id_exsits]);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_update_thumbnail()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-site-nonce', 'security');

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
            $limit_file_size = 5000000;
            if (isset($_FILES) && 0 < count($_FILES)) {
                // check the file size
                if ($limit_file_size < $_FILES['file']['size']) {
                    wp_send_json_error(['msg' => 'Tập tin có dung lượng lớn']);
                }
                // check the file type
                $file_name_parts = explode('.', $_FILES['file']['name']);
                $file_ext = $file_name_parts[count($file_name_parts) - 1];
                if (!$CDWFunc->is_valid_file_type($file_ext)) {
                    wp_send_json_error(['msg' => 'Vui lòng tải lên hình ảnh PNG, JPG, JPEG, GIF']);
                }
                $attachmentId = media_handle_upload('file', $id);
                if (!is_wp_error($attachmentId)) {
                    $thumbnail_id = get_post_thumbnail_id($id);
                    if (!empty($thumbnail_id)) {
                        wp_delete_attachment($thumbnail_id, true);
                    }
                    set_post_thumbnail($id, $attachmentId);
                } else {
                    wp_send_json_error(['msg' => $attachmentId->get_error_code() . " " . $attachmentId->get_error_message()]);
                }
            }

            wp_send_json_success(['msg' => 'Tải ảnh thành công']);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_delete()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-manage-site-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $album_image = get_post_meta($id, 'album_image', true);
        $thumbnail_id = get_post_thumbnail_id($id);

        $check = wp_delete_post($id, true);
        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else {
            if (!empty($album_image)) {
                foreach ($album_image as $album_image_id) {
                    wp_delete_attachment($album_image_id, true);
                }
            }
            if (!empty($thumbnail_id)) {
                wp_delete_attachment($thumbnail_id, true);
            }
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);
        }

        wp_die();
    }

    public function func_delete_list()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-list-manage-site-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['ids'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $ids = $_POST['ids'];

        foreach ($ids as $id) {
            $album_image = get_post_meta($id, 'album_image', true);
            $thumbnail_id = get_post_thumbnail_id($id);
            $check = wp_delete_post($id, true);
            if (!$check ||  $check == null) break;
            if (!empty($album_image)) {
                foreach ($album_image as $album_image_id) {
                    wp_delete_attachment($album_image_id, true);
                }
            }

            if (!empty($thumbnail_id)) {
                wp_delete_attachment($thumbnail_id, true);
            }
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công']);

        wp_die();
    }

    public function delete_images_not_exsits($idParent, $id_exsits)
    {
        $album_image = get_post_meta($idParent, 'album_image', true);
        if (!empty($album_image)) {
            foreach ($album_image as $album_image_id) {
                if (!in_array($album_image_id, $id_exsits))
                    wp_delete_attachment($album_image_id, true);
            }
        }
    }
}

new AjaxManageSite();
