<?php
defined('ABSPATH') || exit;
class AjaxTicketClient
{
    private $postType = 'ticket';
    public function __construct()
    {
        add_action('wp_ajax_ajax_user-get-list-ticket',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_user-new-image-ticket',  array($this, 'func_new_image'));
        add_action('wp_ajax_ajax_user-update-important-ticket',  array($this, 'func_update_important'));
        add_action('wp_ajax_ajax_user-load-status-ticket',  array($this, 'func_load_status'));
        add_action('wp_ajax_ajax_user-load-type-ticket',  array($this, 'func_load_types'));
        add_action('wp_ajax_ajax_user-update-unreads-ticket',  array($this, 'func_update_unreads'));
        add_action('wp_ajax_ajax_user-update-reads-ticket',  array($this, 'func_update_reads'));
        add_action('wp_ajax_ajax_user-update-unimportants-ticket',  array($this, 'func_update_unimportants'));
        add_action('wp_ajax_ajax_user-update-importants-ticket',  array($this, 'func_update_importants'));
    }

    public function func_get_list()
    {
        global $CDWFunc, $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $page = isset($_POST["page"]) ? $_POST["page"] : 1;
        $search = isset($_POST["search"]) ? $_POST["search"] : null;
        $status = isset($_POST["status"]) ? $_POST["status"] : null;
        $type = isset($_POST["type"]) ? $_POST["type"] : null;

        $tickets = $CDWTicket->getTicketUsers($page, $status, $type, $search);

        $data = [];
        foreach ($tickets->ids as $id) {
            $data[] = $CDWTicket->getItemUser($id);
        }

        $pagination = new stdClass();
        $pagination->from = $tickets->offset == 0 ? 1 : $tickets->offset;
        $pagination->to = ($tickets->offset == 0 ? 0 : $tickets->offset) + $tickets->post_count;
        $pagination->total = $tickets->post_found;
        $pagination->disabledBack = $page == 1;
        $pagination->pageBack = $page - 1;
        $pagination->disabledNext = !$tickets->continue;
        $pagination->pageNext = $page + 1;
        $pagination->template = 'ticket-pagination-template';

        wp_send_json_success(['items' => $data, 'pagination' => $pagination, 'page' => $page, 'count_from' => $tickets->offset == 0 ? 1 : $tickets->offset, 'count_to' => ($tickets->offset == 0 ? 0 : $tickets->offset) + $tickets->post_count, 'total' => $tickets->post_found, 'continue' => $tickets->continue]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }
    public function func_new_image()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-ticket-nonce', 'security');

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
                        add_post_meta($id, 'ticket-images', $attach_id);
                    }
                }
            }

            wp_send_json_success(['msg' => 'Tải ảnh thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi tải ảnh']);

        wp_die();
    }
    public function func_update_important()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $ticket_important = get_post_meta($id, 'user-important', true);

        if (update_post_meta($id, 'user-important', !$ticket_important)) {
            wp_send_json_success(['important' => !$ticket_important]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không cập nhật hỗ trợ quan trọng.']);
        }

        wp_die();
    }
    public function func_load_status()
    {
        global  $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $status = isset($_POST['status']) ? $_POST['status'] : "pending";

        $data = $CDWTicket->getCountTicketStatuUsers();
        $data->statusActive = $status;
        $template = 'ticket-status-user-template';
        wp_send_json_success(['data' => $data, 'template' => $template]);

        wp_send_json_error(['msg' => 'Lỗi không cập nhật hỗ trợ quan trọng.']);
        wp_die();


        wp_die();
    }

    public function func_load_types()
    {
        global $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $type = isset($_POST['type']) ? $_POST['type'] : "domain";

        $data = $CDWTicket->getCountTicketTypeUser();
        $items = [];

        foreach ($CDWTicket->default_types as $key => $value) {
            $item = new stdClass();
            $item->id = $key;
            $item->icon =  $value['icon'];
            $item->text =  $value['text'];
            $item->color =  $value['color'];
            $item->count = isset($data[$key]) ? $data[$key] : 0;
            $item->activeKey = $type;
            $item->active = $key == $type ? true : false;
            $items[] = $item;
        }

        $template = 'ticket-type-item-template';

        wp_send_json_success(['items' => $items, 'template' => $template]);
        wp_send_json_error(['msg' => 'Lỗi không cập nhật hỗ trợ quan trọng.']);

        wp_die();
    }
    public function func_update_unreads()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'user-read', true) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công', 'ids' => $ids]);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }
    public function func_update_reads()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'user-read', false) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công', 'ids' => $ids]);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu đã đọc thành công']);


        wp_die();
    }
    public function func_update_importants()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'user-important', true) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công', 'ids' => $ids]);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }
    public function func_update_unimportants()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-user-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'user-important', false) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công', 'ids' => $ids]);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }
}
new AjaxTicketClient();
