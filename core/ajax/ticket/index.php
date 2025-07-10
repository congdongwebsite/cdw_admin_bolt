<?php
defined('ABSPATH') || exit;
class AjaxTicket
{
    private $postType = 'ticket';
    private $post_type_detail = 'ticket-detail';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-ticket',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_new-ticket',  array($this, 'func_new'));
        add_action('wp_ajax_ajax_new-image-ticket',  array($this, 'func_new_image'));
        add_action('wp_ajax_ajax_update-important-ticket',  array($this, 'func_update_important'));
        add_action('wp_ajax_ajax_update-archive-ticket',  array($this, 'func_update_archive'));
        add_action('wp_ajax_ajax_update-trash-ticket',  array($this, 'func_update_trash'));
        add_action('wp_ajax_ajax_load-status-ticket',  array($this, 'func_load_status'));
        add_action('wp_ajax_ajax_load-type-ticket',  array($this, 'func_load_types'));
        add_action('wp_ajax_ajax_update-unreads-ticket',  array($this, 'func_update_unreads'));
        add_action('wp_ajax_ajax_update-reads-ticket',  array($this, 'func_update_reads'));
        add_action('wp_ajax_ajax_update-unimportants-ticket',  array($this, 'func_update_unimportants'));
        add_action('wp_ajax_ajax_update-importants-ticket',  array($this, 'func_update_importants'));
        add_action('wp_ajax_ajax_update-trashs-ticket',  array($this, 'func_update_trashs'));
        add_action('wp_ajax_ajax_new-detail-ticket',  array($this, 'func_new_detail'));
        add_action('wp_ajax_ajax_update-success-ticket',  array($this, 'func_update_success'));
        add_action('wp_ajax_ajax_get-list-detail-ticket',  array($this, 'func_get_list_detail'));
        add_action('wp_ajax_ajax_update-deletes-ticket',  array($this, 'func_delete_list'));
    }

    public function func_get_list()
    {
        global $CDWFunc, $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $page = isset($_POST["page"]) ? $_POST["page"] : 1;
        $search = isset($_POST["search"]) ? $_POST["search"] : null;
        $status = isset($_POST["status"]) ? $_POST["status"] : null;
        $type = isset($_POST["type"]) ? $_POST["type"] : null;

        $tickets = $CDWTicket->getTickets($page, $status, $type, $search);

        $items = [];
        foreach ($tickets->ids as $id) {
            $items[] = $CDWTicket->getItem($id);
        }
        $pagination = new stdClass();
        $pagination->from = $tickets->offset == -12 ? 1 : $tickets->offset;
        $pagination->to = ($tickets->offset == -12 ? 0 : $tickets->offset) + $tickets->post_count;
        $pagination->total = $tickets->post_found;
        $pagination->disabledBack = $page == 1;
        $pagination->pageBack = $page - 1;
        $pagination->disabledNext = !$tickets->continue;
        $pagination->pageNext = $page + 1;
        $pagination->template = 'ticket-pagination-template';
        wp_send_json_success(['items' => $items, 'pagination' => $pagination, 'page' => $page]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }

    public function func_new()
    {
        global $CDWFunc, $CDWNotification, $CDWEmail;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-new-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $types = isset($_POST['type']) ? $_POST['type'] : '';
        if (count($types) == 0) {
            wp_send_json_error(['msg' => 'Vui lòng chọn loại dịch vụ cần hỗ trợ.']);
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
        $userCurrent = wp_get_current_user();
        if ($id) {
            add_post_meta($id, 'user-id', $userCurrent->ID); // get id user
            add_post_meta($id, 'status', 'pending');
            add_post_meta($id, 'read', false);
            add_post_meta($id, 'date', $CDWFunc->date->getCurrentDateTime());
            foreach ($types as $value) {
                add_post_meta($id, 'type', $value);
            }
            $CDWNotification->newNotificationCreateTicket($id);
            $CDWEmail->sendEmailTicketNew($id, true);

            $this->setFeatureStatus(true);
            wp_send_json_success(['msg' => 'Tạo hỗ trợ thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không tạo hỗ trợ, vui lòng liên hệ với quản trị viên']);

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
    public function func_delete_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

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

            $CDWFunc->wpdb->func_delete_detail_post($this->post_type_detail, 'ticket-id', $id);
            $this->delete_images_not_exsits($id);
        }

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
    public function delete_images_not_exsits($id)
    {
        $ticket_images = get_post_meta($id, 'ticket-images');
        if (!empty($ticket_images)) {
            foreach ($ticket_images as $image_id)
                wp_delete_attachment($image_id, true);
        }
    }
    public function func_update_important()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $ticket_important = get_post_meta($id, 'important', true);

        if (update_post_meta($id, 'important', !$ticket_important)) {
            wp_send_json_success(['important' => !$ticket_important]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không cập nhật hỗ trợ quan trọng.']);
        }

        wp_die();
    }
    public function func_update_archive()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $ticket_status = get_post_meta($id, 'status', true);
        $status = 'archive';
        if ($ticket_status == $status) $status = 'pending';
        if (update_post_meta($id, 'status', $status)) {
            wp_send_json_success(['status' => true]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không cập nhật trạng thái hỗ trợ.']);
        }

        wp_die();
    }
    public function func_update_trash()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $ticket_status = get_post_meta($id, 'status', true);
        $status = 'trash';
        if ($ticket_status == $status) $status = 'pending';
        if (update_post_meta($id, 'status', $status)) {
            wp_send_json_success(['status' => true]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không cập nhật trạng thái hỗ trợ.']);
        }

        wp_die();
    }
    public function func_load_status()
    {
        global $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $status = isset($_POST['status']) ? $_POST['status'] : "pending";

        $data = $CDWTicket->getCountTicketStatus();
        $data->statusActive = $status;
        $template = 'ticket-status-template';
        wp_send_json_success(['data' => $data, 'template' => $template]);

        wp_send_json_error(['msg' => 'Lỗi không cập nhật hỗ trợ quan trọng.']);
        wp_die();
    }
    public function func_load_types()
    {
        global $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $type = isset($_POST['type']) ? $_POST['type'] : "domain";

        $data = $CDWTicket->getCountTicketTypes();
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
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'read', true) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công']);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }
    public function func_update_reads()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'read', false) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công']);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu đã đọc thành công']);


        wp_die();
    }

    public function func_update_importants()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'important', true) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công']);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }
    public function func_update_unimportants()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'important', false) || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công']);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);


        wp_die();
    }

    public function func_update_trashs()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-ticket-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        $check = true;
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $check = update_post_meta($id, 'status', 'trash') || $check;
        }
        if ($check)
            wp_send_json_success(['msg' => 'Thành công']);
        else
            wp_send_json_error(['msg' => 'Không đánh dấu sao thành công']);

        wp_die();
    }

    public function func_new_detail()
    {
        global $CDWFunc, $CDWNotification, $CDWEmail;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-ticket-nonce', 'security');

        if (!post_type_exists($this->post_type_detail)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->post_type_detail]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $note = isset($_POST['note']) ? $_POST['note'] : '';
        if ($note == '') {
            wp_send_json_error(['msg' => 'Vui lòng nhập nội dung phản hồi.']);
        }
        $arr = array(
            'post_type' => $this->post_type_detail,
            'post_status' => 'publish',
            'post_content' => $note,
        );
        $id = wp_insert_post($arr);
        $userCurrent = wp_get_current_user();
        if ($id) {
            add_post_meta($id, 'user-id', $userCurrent->ID);
            add_post_meta($id, 'ticket-id', $_POST['id']);
            update_post_meta($_POST['id'], 'last-detail', $note);
            update_post_meta($_POST['id'], 'read', false);
            add_post_meta($id, 'date', $CDWFunc->date->getCurrentDateTime());

            $CDWNotification->newItemCreateDetailTicket($id);

            // var_dump(test_smtp_connection('levantrungeale28595@gmail.com'));
            $CDWEmail->sendEmailTicketDetailNew($id, true);
            wp_send_json_success(['msg' => 'Phản hồi thành công', 'id' => $id]);
        } else
            wp_send_json_error(['msg' => 'Lỗi không phản hồi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }

    public function func_update_success()
    {
        global $CDWEmail, $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-ticket-nonce', 'security');


        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        $ticket_status = get_post_meta($id, 'status', true);
        $status = 'success';
        if ($ticket_status == $status) $status = 'pending';
        if (update_post_meta($id, 'status', $status)) {

            update_post_meta($id, 'date-update', $CDWFunc->date->getCurrentDateTime());
            $CDWEmail->sendEmailTicketUpdateStatus($id);
            wp_send_json_success(['status' => $status]);
        } else {
            wp_send_json_error(['msg' => 'Lỗi không cập nhật trạng thái hỗ trợ.']);
        }

        wp_die();
    }

    public function func_get_list_detail()
    {
        global $CDWFunc, $CDWTicket;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-detail-ticket-nonce', 'security');

        if (!post_type_exists($this->post_type_detail)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->post_type_detail]);
        }

        $id = isset($_POST["id"]) ? $_POST["id"] : -1;
        $page = isset($_POST["page"]) ? $_POST["page"] : 1;
        $search = isset($_POST["search"]) ? $_POST["search"] : null;

        $tickets = $CDWTicket->getTicketDetails($id, $page, $search);

        $data = [];
        $userCurrent = wp_get_current_user();
        $date = '';
        foreach ($tickets->ids as $id) {
            $user_detail_id = get_post_meta($id, 'user-id', true);

            $date_post = get_post_meta($id, 'date', true);
            if ($CDWFunc->date->convertDateTime($date_post, $CDWFunc->date->formatDB,  $CDWFunc->date->format) != $CDWFunc->date->convertDateTime($date, $CDWFunc->date->formatDB,  $CDWFunc->date->format)) {
                $data[] =  $CDWFunc->trim_space_html($CDWTicket->getDetailDate($date_post));
            }
            $date = $date_post;
            if ($user_detail_id ==  $userCurrent->ID) {
                $data[] = $CDWFunc->trim_space_html($CDWTicket->getDetailItemRight($id));
            } else {
                $data[] = $CDWFunc->trim_space_html($CDWTicket->getDetailItemLeft($id));
            }
        }
        wp_send_json_success(['items' => $data, 'count_from' => $tickets->offset == 0 ? 1 : $tickets->offset, 'count_to' => ($tickets->offset == 0 ? 0 : $tickets->offset) + $tickets->post_count, 'total' => $tickets->post_found, 'continue' => $tickets->continue]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

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
new AjaxTicket();
