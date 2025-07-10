<?php
defined('ABSPATH') || exit;
class AjaxNotificationClient
{
    private $postType = 'notification';
    public function __construct()
    {
        add_action('wp_ajax_ajax_user-get-list-notification',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_user-update-read-notification',  array($this, 'func_update_reads'));
    }

    public function func_get_list()
    {
        global $CDWFunc, $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-user-notification-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $page = isset($_POST["page"]) ? $_POST["page"] : 1;
        $search = isset($_POST["search"]) ? $_POST["search"] : null;
        $type = isset($_POST["type"]) ? $_POST["type"] : null;

        $notifications = $CDWNotification->getNotificationUsers($page, $search,  $type);

        $data = [];
        foreach ($notifications->ids as $id) {
            $data[] = $CDWNotification->getItem($id);
        }
        if (count($notifications->ids) == 0)
            $data[] = ['notFound' => true];
        $template = [
            'item' => 'notification-item-user-template',
            'pagination' => 'notification-pagination-item-template',
        ];
        $paginations =  $CDWNotification->getPagination($page, $notifications->max_num_pages,  $notifications->continue);
        wp_send_json_success(['items' => $data, 'template' => $template, 'count_from' => $notifications->offset == 0 ? 1 : $notifications->offset, 'count_to' => ($notifications->offset == 0 ? 0 : $notifications->offset) + $notifications->post_count, 'total' => $notifications->post_found, 'continue' => $notifications->continue, 'paginations' => $paginations]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }

    public function func_update_reads()
    {
        global $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-user-notification-nonce', 'security');
        $userCurrent = wp_get_current_user();

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];

        wp_send_json_success(['status' => $CDWNotification->updateRead($id)]);
        wp_send_json_error(['msg' => 'Lỗi không cập nhật trạng thái.']);


        wp_die();
    }
}
new AjaxNotificationClient();
