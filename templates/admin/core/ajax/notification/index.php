<?php
defined('ABSPATH') || exit;
class AjaxNotification
{
    private $postType = 'notification';
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-list-notification',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_update-read-notification',  array($this, 'func_update_reads'));
        add_action('wp_ajax_ajax_delete-notification',  array($this, 'func_delete'));
        add_action('wp_ajax_ajax_delete-bell-notification',  array($this, 'func_delete_bell'));
        add_action('wp_ajax_ajax_top-navbar-notification',  array($this, 'func_top_navbar_notification'));
        add_action('wp_ajax_ajax_top-navbar-notification-read',  array($this, 'func_top_navbar_notification_read'));
    }

    public function func_get_list()
    {
        global $CDWFunc, $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-notification-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $page = isset($_POST["page"]) ? $_POST["page"] : 1;
        $search = isset($_POST["search"]) ? $_POST["search"] : null;
        $type = isset($_POST["type"]) ? $_POST["type"] : null;

        $notifications = $CDWNotification->getNotifications($page, $search,  $type);

        $data = [];
        foreach ($notifications->ids as $id) {
            $data[] = $CDWNotification->getItem($id);
        }
        if (count($notifications->ids) == 0)
            $data[] = ['notFound' => true];
        $template = [
            'item' => 'notification-item-template',
            'pagination' => 'notification-pagination-item-template',
        ];
        $paginations =  $CDWNotification->getPagination($page, $notifications->max_num_pages,  $notifications->continue);
        wp_send_json_success(['items' => $data, 'template' => $template, 'count_from' => $notifications->offset == 0 ? 1 : $notifications->offset, 'count_to' => ($notifications->offset == 0 ? 0 : $notifications->offset) + $notifications->post_count, 'total' => $notifications->post_found, 'continue' => $notifications->continue, 'paginations' => $paginations]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }

    public function func_delete()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-notification-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $id = $_POST['id'];

        $check = wp_delete_post($id, true);

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }
    public function func_update_reads()
    {
        global $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-notification-nonce', 'security');
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
    public function func_delete_bell()
    {
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không thấy danh sách bài đăng']);
        }
        $id = $_POST['id'];

        $check = wp_delete_post($id, true);

        if (!$check ||  $check == null) {
            wp_send_json_error(['msg' => 'Lỗi không xóa được bài đăng']);
        } else
            wp_send_json_success(['msg' => 'Xóa thành công', 'id' => $id]);

        wp_die();
    }

    public function func_top_navbar_notification()
    {
        global $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }

        $items = [];
        $ids = $CDWNotification->getItems();

        foreach ($ids as $key => $id) {
            $items[] = $CDWNotification->getItemTopNavbar($id);
        }
        $header = $CDWNotification->getHeaderInfo();

        $footer = $CDWNotification->getFooterInfo();
        $template = [
            'dot' => 'notification-top-navbar-dot-template',
            'header' => 'notification-top-navbar-header-template',
            'item' => 'notification-top-navbar-item-template',
            'footer' => 'notification-top-navbar-footer-template'
        ];
        $CDWNotification->setStatus(false);

        wp_send_json_success(['items' => $items, 'header' => $header, 'footer' => $footer, 'template' => $template]);

        wp_send_json_error(['msg' => 'Lỗi, vui lòng liên hệ với quản trị viên']);

        wp_die();
    }
    public function func_top_navbar_notification_read()
    {
        global $CDWNotification;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-nonce', 'security');

        if (!post_type_exists($this->postType)) {
            wp_send_json_error(['msg' => 'Không tìm thấy loại bài đăng: ' . $this->postType]);
        }
        if (!isset($_POST['id'])) {
            wp_send_json_error(['msg' => 'Không tìm thấy bài đăng']);
        }
        $id = $_POST['id'];
        if ($CDWNotification->setReadItem($id)) {
            wp_send_json_success(['status' => true]);
        }
        wp_send_json_error(['msg' => 'Lỗi không cập nhật trạng thái.']);


        wp_die();
    }
}
new AjaxNotification();
