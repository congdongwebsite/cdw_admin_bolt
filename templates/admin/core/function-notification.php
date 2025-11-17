<?php
defined('ABSPATH') || exit;
class FunctionNotification
{
    private $post_type = 'notification';
    public $post_per_page = 8;
    public $list_type_notification = [
        'info' => [
            'icon' => 'icon-info text-info',
            'color' => 'info',
            'text' => 'Thông tin'
        ],
        'success' => [
            'icon' => 'icon-info text-success',
            'color' => 'success',
            'text' => 'Hoàn tất'
        ],
        'warning' => [
            'icon' => 'icon-info text-warning',
            'color' => 'warning',
            'text' => 'Cảnh báo'
        ],
        'danger' => [
            'icon' => 'icon-info text-danger',
            'color' => 'danger',
            'text' => 'Lỗi'
        ],
        'secondary' => [
            'icon' => 'icon-info text-secondary',
            'color' => 'secondary',
            'text' => 'Tin phụ'
        ],
        'primary' => [
            'icon' => 'icon-info text-primary',
            'color' => 'primary',
            'text' => 'Tin chính'
        ]
    ];
    public function __construct()
    {
    }

    public function getHasNotificationNew()
    {
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'read',
                        'value'   => $userCurrent->ID,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'read',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            )
        );

        $ids = get_posts($arr);

        return count($ids) > 0;
    }
    public function getCountNotificationNew()
    {
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'read',
                        'value'   => $userCurrent->ID,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'read',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            )
        );

        $ids = get_posts($arr);

        return count($ids);
    }
    public function getHasNotificationUserNew()
    {
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'user-to-id',
                        'value'   => $userCurrent->ID,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => 'user-to-id',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'read',
                        'value'   => $userCurrent->ID,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'read',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            )
        );

        $ids = get_posts($arr);

        return count($ids) > 0;
    }
    public function getCountNotificationUserNew()
    {
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'user-to-id',
                        'value'   => $userCurrent->ID,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => 'user-to-id',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'read',
                        'value'   => $userCurrent->ID,
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'read',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            )
        );

        $ids = get_posts($arr);

        return count($ids);
    }
    public function getListIDs($post_per_page = null, $type = null, $read = null)
    {
        if ($post_per_page == null) $post_per_page = $this->post_per_page;
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => $post_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
        );

        if ($read !== null) {

            if ($read) {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            } else {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            }
        }
        if ($type !== null) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '='
            );
        }
        $ids = get_posts($arr);
        return $ids;
    }
    public function getListIDUsers($post_per_page = null, $type = null, $read = null)
    {
        if ($post_per_page == null) $post_per_page = $this->post_per_page;
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => $post_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'user-to-id',
                        'value'   => $userCurrent->ID,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => 'user-to-id',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            )
        );

        if ($read !== null) {

            if ($read) {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            } else {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            }
        }
        if ($type !== null) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '='
            );
        }

        $ids = get_posts($arr);
        return $ids;
    }
    public function getIcon($type)
    {
        if (!isset($this->list_type_notification[$type])) $type = 'secondary';
        return $this->list_type_notification[$type];
    }
    public function getItems()
    {
        global $CDWFunc;
        if ($CDWFunc->isAdministrator())
            $ids = $this->getListIDs(-1, null, false);
        else
            $ids = $this->getListIDUsers(-1, null, false);

        return $ids;
    }
    public function getHeaderInfo()
    {
        global $CDWFunc;

        $count = 0;
        if ($CDWFunc->isAdministrator()) {
            $count = $this->getCountNotificationNew();
            $has = $count > 0;
        } else {
            $count = $this->getCountNotificationUserNew();
            $has = $count > 0;
        }
        $header = new stdClass();
        $header->count = $count;
        $header->has = $has;
        return $header;
    }
    public function getItemTopNavbar($id)
    {
        global $CDWFunc;
        $item = new stdClass();
        $item->id = $id;
        $item->title = get_the_title($id);
        $item->content = wp_trim_words(get_the_content(null, false, $id), 20, '...');
        $item->type =  get_post_meta($id, 'type', true);
        $item->url =  get_post_meta($id, 'url', true);
        $item->time = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));

        $item->icon = $this->getIcon($item->type)['icon'];
        $item->isAdministrator = $CDWFunc->isAdministrator();
        return $item;
    }
    public function getFooterInfo()
    {
        global $CDWFunc;
        $url = $CDWFunc->getURL('index', 'notification');
        if (!$CDWFunc->isAdministrator()) {
            $url = $CDWFunc->getURL('notification', 'client');
        }

        $footer = new stdClass();
        $footer->url = $url;
        return $footer;
    }
    public function setReadItem($id, $user_id = null)
    {
        if ($user_id == null) {
            $userCurrent = wp_get_current_user();
            $user_id  = $userCurrent->ID;
        }
        $user_id_reads = get_post_meta($id, 'read');
        $this->setStatus(true, $user_id);
        if (!in_array($user_id, $user_id_reads)) {
            return add_post_meta($id, 'read', $user_id);
        } else return true;
        return false;
    }
    public function updateRead($id, $user_id = null)
    {
        if ($user_id == null) {
            $userCurrent = wp_get_current_user();
            $user_id  = $userCurrent->ID;
        }
        $user_id_reads = get_post_meta($id, 'read');
        $this->setStatus(true, $user_id);
        if (in_array($user_id, $user_id_reads)) {
            if (delete_post_meta($id, 'read', $user_id)) {
                return false;
            }
        } else {
            return add_post_meta($id, 'read', $user_id);
        }
        return false;
    }
    public function getItem($id)
    {
        global $CDWFunc;

        $type =  get_post_meta($id, 'type', true);
        $item = new stdClass();
        $item->id = $id;
        $item->link = get_post_meta($id, 'url', true);
        $icon = $this->getIcon($type);
        $item->icon = $icon['icon'];
        $item->color = $icon['color'];
        $item->title = get_the_title($id);
        $item->date = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));
        $item->content = wp_trim_words(get_the_content(null, false, $id), 20, '...');
        $item->read = $this->checkIsRead($id);

        return $item;
    }

    public function getPagination($page, $max_num_pages, $continue)
    {
        $result = [];

        $item = new stdClass();
        $item->back = true;
        $item->active = false;
        $item->page = -1;
        $item->next = false;
        $item->continue = '';
        $result[] = $item;
        for ($i = 1; $i <= $max_num_pages; $i++) {
            $item = new stdClass();
            $item->back = false;
            $item->active = $page == $i;
            $item->page = $i;
            $item->next = false;
            $item->continue = '';
            $result[] = $item;
        }

        $item = new stdClass();
        $item->back = false;
        $item->active = false;
        $item->page = -1;
        $item->next = true;
        $item->continue = $continue ? '1' : '';
        $result[] = $item;
        return $result;
    }
    public function getNotifications($page = 1, $search = null, $type = null, $read = null,  $post_per_page = null)
    {
        if ($post_per_page == null) $post_per_page = $this->post_per_page;
        $offset = ($page - 1) * $this->post_per_page;
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'offset' => $offset,
            'posts_per_page' => $post_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
        );
        if ($read != null) {
            if ($read) {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            } else {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            }
        }
        if ($type != null) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '='
            );
        }
        if ($search != null) {
            $arr['s'] = $search;
        }
        $wp = new WP_Query($arr);
        return (object) [
            'ids' => $wp->posts,
            'offset' => $offset,
            'post_count' => $wp->post_count,
            'max_num_pages' => $wp->max_num_pages,
            'post_found' => $wp->found_posts,
            'continue' => ($offset + $wp->post_count) < $wp->found_posts
        ];
    }

    public function getNotificationUsers($page = 1, $search = null, $type = null, $read = null,  $post_per_page = null)
    {
        $userCurrent = wp_get_current_user();
        if ($post_per_page == null) $post_per_page = $this->post_per_page;
        $offset = ($page - 1) * $this->post_per_page;
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'offset' => $offset,
            'posts_per_page' => $post_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query'     => array(
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'user-to-id',
                        'value'   => $userCurrent->ID,
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'     => 'user-to-id',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            ),
        );
        if ($read != null) {

            if ($read) {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            } else {
                $arr['meta_query'][] =
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'read',
                            'value'   => $userCurrent->ID,
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key'     => 'read',
                            'compare' => 'NOT EXISTS',
                        ),
                    );
            }
        }
        if ($type != null) {
            $arr['meta_query'][] = array(
                'key'     => 'type',
                'value'   => $type,
                'compare' => '='
            );
        }
        if ($search != null) {
            $arr['s'] = $search;
        }
        $wp = new WP_Query($arr);
        return (object) [
            'ids' => $wp->posts,
            'offset' => $offset,
            'post_count' => $wp->post_count,
            'max_num_pages' => $wp->max_num_pages,
            'post_found' => $wp->found_posts,
            'continue' => ($offset + $wp->post_count) < $wp->found_posts
        ];
    }
    public function newItem($title, $url, $note, $user_to_id = [], $type = 'secondary')
    {
        global $CDWFunc;

        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'post_title' => $title,
            'post_content' => $note,
        );
        $id = wp_insert_post($arr);
        if ($id) {
            if (!isset($this->list_type_notification[$type])) $type = 'secondary';
            add_post_meta($id, 'type', $type);
            add_post_meta($id, 'url', $url);
            foreach ($user_to_id as $user_id) {
                add_post_meta($id, 'user-to-id', $user_id);
                $this->setStatus(true, $user_id);
            }

            add_post_meta($id, 'date', $CDWFunc->date->getCurrentDateTime());
        }
        return $id;
    }
    public function checkIsRead($id)
    {
        $userCurrent = wp_get_current_user();
        $notification_read = get_post_meta($id, 'read');
        return in_array($userCurrent->ID, $notification_read);
    }
    public function getStatus()
    {
        $userC = wp_get_current_user();
        return get_user_meta($userC->ID, "notification-status", true);
    }
    public function setStatus($status = true, $user_id = "")
    {
        if (empty($user_id)) {
            $userC = wp_get_current_user();
            $user_id = $userC->ID;
        }
        return update_user_meta($user_id, "notification-status", $status);
    }
    public function newNotificationUpdateCheckout($id)
    {
        global $CDWFunc;
        $userCurrent = wp_get_current_user();
        $title = "Hoá Đơn #" . get_post_meta($id, 'code', true);
        $url = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
        $note = "Hóa đơn được tạo và bộ phân kinh doanh đã tiếp nhận";
        $user_tos = [$userCurrent->ID];
        $type = 'success';
        $this->newItem($title, $url, $note, $user_tos, $type);
    }
    // khi khách huỷ đơn hàng
    public function newNotificationCancelCheckout($id)
    {
        global $CDWFunc;
        $userCurrent = wp_get_current_user();
        $title = "Hủy hóa đơn #" . get_post_meta($id, 'code', true);
        $url = $CDWFunc->getUrl('billing', 'client', 'subaction=checkout&id=' . $id);
        $note = "Hóa đơn  #" . get_post_meta($id, 'code', true) . " đã được hủy";
        $type = 'warning';
        $user_tos = [$userCurrent->ID];
        $this->newItem($title, $url, $note, $user_tos, $type);
    }
    // thông báo tạo ticket mới
    public function newNotificationCreateTicket($id)
    {
        global $CDWFunc;
        $user_id = get_post_meta($id, 'user-id', true);
        $title = "Hỗ trợ [" . $id . '] khởi tạo';
        $url = $CDWFunc->getUrl('detail', 'ticket', 'id=' . $id);
        $note = wp_trim_words(get_the_content(null, false, $id), 20, '...');
        $user_tos = [$user_id];
        $type = 'warning';
        $this->newItem($title, $url, $note, $user_tos, $type);
    }
    public function newItemCreateDetailTicket($id)
    {
        global $CDWFunc;
        $title = "Trả lời hỗ trợ [" . $id . ']';
        $url = $CDWFunc->getUrl('detail', 'ticket', 'id=' . $id);
        $note = wp_trim_words(get_the_content(null, false, $id), 20, '...');
        $ticket_id =  get_post_meta($id, 'ticket-id', true);
        $user_ticket_id =  get_post_meta($ticket_id, 'user-id', true);
        $user_tos = [$user_ticket_id];
        $type = 'warning';
        $this->newItem($title, $url, $note, $user_tos, $type);
    }
}
