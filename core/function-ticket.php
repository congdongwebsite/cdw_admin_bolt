<?php
defined('ABSPATH') || exit;
class FunctionTicket
{
    private $post_type = 'ticket';
    private $post_type_detail = 'ticket-detail';
    public $post_per_page = 12;
    public $post_detail_per_page = -1;
    public $default_types = [
        'domain' => [
            'icon' => 'fa fa-circle text-danger',
            'text' => 'Domain',
            'color' => 'danger'
        ],
        'hosting' => [
            'icon' => 'fa fa-circle text-info',
            'text' => 'Hosting',
            'color' => 'info'
        ],
        'theme' => [
            'icon' => 'fa fa-circle text-dark',
            'text' => 'Theme',
            'color' => 'dark'
        ],
        'billing' => [
            'icon' => 'fa fa-circle text-primary',
            'text' => 'Thanh toán',
            'color' => 'primary'
        ],
        'technical' => [
            'icon' => 'fa fa-circle text-secondary',
            'text' => 'Kỹ thuật',
            'color' => 'secondary'
        ]
    ];
    public function __construct()
    {
    }
    public function getDefaultType()
    {
        return $this->default_types;
    }
    public function getCountTicketStatus()
    {
        $result = new stdClass();
        $result->processing = 0;
        $result->pending = 0;
        $result->success = 0;
        $result->important = 0;
        $result->archive = 0;
        $result->trash = 0;
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );

        $ids = get_posts($arr);

        foreach ($ids as $id) {
            $status = get_post_meta($id, 'status');
            if (is_array($status)) {
                foreach ($status as $item) {
                    switch ($item) {
                        case 'pending':
                        case 'processing':
                            if (get_post_meta($id, 'read', true))
                                $result->processing += 1;
                            else
                                $result->pending += 1;
                            break;
                        case 'success':
                            $result->success += 1;
                            break;
                        case 'important':
                            $result->important += 1;
                            break;
                        case 'archive':
                            $result->archive += 1;
                            break;
                        case 'trash':
                            $result->trash += 1;
                            break;
                    }
                }
            }
        }
        $result->important = $this->countTicketImportant();
        return $result;
    }
    public function getCountTicketStatuUsers()
    {
        $userCurrent = wp_get_current_user();


        $result = new stdClass();
        $result->pending = 0;
        $result->success = 0;
        $result->important = 0;

        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'user-id',
                    'value'   => $userCurrent->ID,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($arr);

        foreach ($ids as $id) {
            $status = get_post_meta($id, 'status');
            if (is_array($status)) {
                foreach ($status as $item) {
                    switch ($item) {
                        case 'pending':
                            $result->pending += 1;
                            break;
                        case 'success':
                            $result->success += 1;
                            break;
                        case 'important':
                            $result->important += 1;
                            break;
                    }
                }
            }
        }
        $result->important = $this->countTicketImportantUser();
        return $result;
    }
    public function countTicketByStatus($status = null)
    {
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );
        if ($status != null) {
            $arr['meta_query'][] = array(
                'key'     => 'status',
                'value'   => $status,
                'compare' => '='
            );
        }
        $ids = get_posts($arr);
        return count($ids);
    }
    public function countTicketImportant()
    {
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'important',
                    'value'   => true,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($arr);
        return count($ids);
    }
    public function countTicketImportantUser()
    {
        $userCurrent = wp_get_current_user();
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'user-important',
                    'value'   => true,
                    'compare' => '=',
                ), array(
                    'key'     => 'user-id',
                    'value'   => $userCurrent->ID,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($arr);
        return count($ids);
    }

    public function getCountTicketTypes($status = null)
    {
        $result = [];
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
        );

        if ($status != null) {
            if ($status == 'important') {
                $arr['meta_query'][] = array(
                    'key'     => 'important',
                    'value'   => true,
                    'compare' => '='
                );
            } else {

                if ($status == 'processing') {

                    $arr['meta_query'][] = array(
                        'key'     => 'read',
                        'value'   => true,
                        'compare' => '='
                    );
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => 'pending',
                        'compare' => '='
                    );
                } else
                if ($status == 'pending') {

                    $arr['meta_query'][] = array(
                        'key'     => 'read',
                        'value'   => false,
                        'compare' => '='
                    );
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => 'pending',
                        'compare' => '='
                    );
                } else
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => $status,
                        'compare' => '='
                    );
            }
        }
        $ids = get_posts($arr);

        foreach ($ids as $id) {
            $types = get_post_meta($id, 'type');
            if (is_array($types)) {
                foreach ($types as $type) {
                    $result[$type] += isset($result[$type]) ? 1 : 1;
                }
            }
        }
        return $result;
    }
    public function getCountTicketTypeUser()
    {
        $userCurrent = wp_get_current_user();

        $result = [];
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'user-id',
                    'value'   => $userCurrent->ID,
                    'compare' => '=',
                )
            )
        );

        $ids = get_posts($arr);

        foreach ($ids as $id) {
            $types = get_post_meta($id, 'type');
            if (is_array($types)) {
                foreach ($types as $type) {
                    $result[$type] += isset($result[$type]) ? 1 : 1;
                }
            }
        }
        return $result;
    }
    public function countTicketByType($type)
    {
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'type',
                    'value'   => $type,
                    'compare' => '=',
                )
            )
        );
        $ids = get_posts($arr);
        return count($ids);
    }
    public function getListIDs($offset = 0,  $post_per_page = null, $status = 'pending', $type = null)
    {
        if ($post_per_page == null) $post_per_page = $this->post_per_page;
        $arr = array(
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'fields' => 'ids',
            'offset' => $offset,
            'posts_per_page' => $post_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );
        if ($status !== null) {
            $arr['meta_query'][] = array(
                'key'     => 'status',
                'value'   => $status,
                'compare' => '='
            );
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
    public function getTickets($page = 1, $status = 'pending', $type = null, $search = null,  $post_per_page = null)
    {
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
        );
        if ($status != null) {
            if ($status == 'important') {
                $arr['meta_query'][] = array(
                    'key'     => 'important',
                    'value'   => true,
                    'compare' => '='
                );
            } else {

                if ($status == 'processing') {

                    $arr['meta_query'][] = array(
                        'key'     => 'read',
                        'value'   => true,
                        'compare' => '='
                    );
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => 'pending',
                        'compare' => '='
                    );
                } else
                if ($status == 'pending') {

                    $arr['meta_query'][] = array(
                        'key'     => 'read',
                        'value'   => false,
                        'compare' => '='
                    );
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => 'pending',
                        'compare' => '='
                    );
                } else
                    $arr['meta_query'][] = array(
                        'key'     => 'status',
                        'value'   => $status,
                        'compare' => '='
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
            'post_found' => $wp->found_posts,
            'continue' => ($offset + $wp->post_count) < $wp->found_posts
        ];
    }

    public function getTicketUsers($page = 1, $status = 'pending', $type = null, $search = null,  $post_per_page = null)
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
        );

        $arr['meta_query'][] = array(
            'key'     => 'user-id',
            'value'   => $userCurrent->ID,
            'compare' => '='
        );
        if ($status != null) {
            if ($status == 'important') {
                $arr['meta_query'][] = array(
                    'key'     => 'user-important',
                    'value'   => true,
                    'compare' => '='
                );
            } else
                $arr['meta_query'][] = array(
                    'key'     => 'status',
                    'value'   => $status,
                    'compare' => '='
                );
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
            'post_found' => $wp->found_posts,
            'continue' => ($offset + $wp->post_count) < $wp->found_posts
        ];
    }

    public function getItem($id)
    {
        global $CDWFunc;
        $item = (object) [
            "isRead" => false,
            "id" => "",
            "isImportant" => false,
            "link" => "",
            "title" => "",
            "content" => "",
            "types" => [],
            "hasAttach" => "",
            "date" => "",
            "isArchive" => "",
            "isTrash" => "",
            "template" => "ticket-item-template"
        ];
        $attach = get_post_meta($id, 'ticket-images');
        $ticket_archive_current =  get_post_meta($id, 'status', true);

        $dep = wp_trim_words(get_post_meta($id, 'last-detail', true), 20, '...');
        if (empty($dep)) $dep = wp_trim_words(get_the_content(null, false, $id), 20, '...');

        $item->isRead = get_post_meta($id, 'read', true);
        $item->id = $id;
        $item->isImportant = get_post_meta($id, 'important', true);
        $item->link = $CDWFunc->getURL('detail', 'ticket', 'id=' . $id);
        $item->title =  get_the_title($id);
        $item->content =  $dep;
        $item->hasAttach = is_array($attach) && count($attach) > 0;
        $item->date = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));
        $item->isArchive = $ticket_archive_current == 'archive';
        $item->isTrash = $ticket_archive_current == 'trash';


        $types = get_post_meta($id, 'type');
        foreach ($types as $value) {
            $data_type = $this->default_types[$value];
            $type = new stdClass();
            $type->color = $data_type['color'];
            $type->name = $data_type['text'];
            $type->template = "ticket-item-type-template";
            $item->types[] = $type;
        }

        return $item;
    }

    public function getItemUser($id)
    {
        global $CDWFunc;
        $item = (object) [
            "isRead" => false,
            "id" => "",
            "isImportant" => false,
            "link" => "",
            "title" => "",
            "content" => "",
            "types" => [],
            "hasAttach" => "",
            "date" => "",
            "template" => "ticket-item-user-template"
        ];
        $attach = get_post_meta($id, 'ticket-images');

        $dep = wp_trim_words(get_post_meta($id, 'last-detail', true), 20, '...');
        if (empty($dep)) $dep = wp_trim_words(get_the_content(null, false, $id), 20, '...');

        $item->isRead = get_post_meta($id, 'user-read', true);
        $item->id = $id;
        $item->isImportant = get_post_meta($id, 'user-important', true);
        $item->link = $CDWFunc->getURL('detail', 'ticket', 'id=' . $id);
        $item->title =  get_the_title($id);
        $item->content =  $dep;
        $item->hasAttach = is_array($attach) && count($attach) > 0;
        $item->date = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));

        $types = get_post_meta($id, 'type');
        foreach ($types as $value) {
            $data_type = $this->default_types[$value];
            $type = new stdClass();
            $type->color = $data_type['color'];
            $type->name = $data_type['text'];
            $type->template = "ticket-item-type-template";
            $item->types[] = $type;
        }

        return $item;
    }

    public function getTicketDetails($id, $page = 1, $search = null,  $post_detail_per_page = null)
    {
        if ($post_detail_per_page == null) $post_detail_per_page = $this->post_detail_per_page;
        $offset = ($page - 1) * $this->post_detail_per_page;
        $arr = array(
            'post_type' => $this->post_type_detail,
            'post_status' => 'publish',
            'fields' => 'ids',
            'offset' => $offset,
            'posts_per_page' => $post_detail_per_page,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );

        $arr['meta_query'][] = array(
            'key'     => 'ticket-id',
            'value'   => $id,
            'compare' => '='
        );
        if ($search != null) {
            $arr['s'] = $search;
        }
        $wp = new WP_Query($arr);
        return (object) [
            'ids' => $wp->posts,
            'offset' => $offset,
            'post_count' => $wp->post_count,
            'post_found' => $wp->found_posts,
            'continue' => ($offset + $wp->post_count) < $wp->found_posts
        ];
    }
    public function getDetailItemLeft($id)
    {
        global $CDWFunc;
        $user_id = get_post_meta($id, 'user-id', true);
        $user_avatar = get_user_meta($user_id, 'avatar-custom', true);
        $attachmentUrl = wp_get_attachment_image_url($user_avatar, 'large');
        $note = get_the_content(null, false, $id);
        $date_time = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));
        $user_info = get_userdata($user_id);
        $name = $user_info->first_name;
        if (empty($name)) $name = $user_info->user_email;
        ob_start();
?>
        <div class="row clearfix ticket-item mt-4">
            <div class="col-lg-12 col-md-12">
                <div class="testimonial2 primary">
                    <div class="testimonial-section">
                        <?php echo $note; ?>
                    </div>
                    <div class="testimonial-desc">
                        <?php
                        if (!$attachmentUrl) { ?>
                            <img class="media-object rounded-circle shadow float-left" src="<?PHP echo ADMIN_CHILD_THEME_URL_F; ?>/assets/images/user.png" alt="">
                        <?php } else {
                        ?>
                            <img class="media-object rounded-circle shadow float-left" src="<?php echo $attachmentUrl; ?>" alt="<?php echo $name; ?>">
                        <?php
                        } ?>
                        <div class="testimonial-writer d-flex flex-row justify-content-between">
                            <h6><?php echo $name; ?></h6>
                            <ul class="list-inline d-flex flex-row">
                                <li class="mr-2"><a href="javascript:void(0);"><?php echo $date_time; ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }
    public function getDetailItemRight($id)
    {
        global $CDWFunc, $blogInfo;

        $user_id = get_post_meta($id, 'user-id', true);
        $user_avatar = get_user_meta(get_post_meta($id, 'user-id', true), 'avatar-custom', true);
        $attachmentUrl = wp_get_attachment_image_url($user_avatar, 'large');
        $note = get_the_content(null, false, $id);
        $date_time = $CDWFunc->date->human_display(get_post_meta($id, 'date', true));
        $name = get_user_meta($user_id, 'first_name', true);
        ob_start();
    ?>
        <div class="row clearfix ticket-item mt-4">
            <div class="col-lg-12 col-md-12">
                <div class="testimonial3 warning">
                    <div class="testimonial-section">
                        <?php echo $note; ?>
                    </div>
                    <div class="testimonial-desc">
                        <?php
                        if (!$attachmentUrl) { ?>
                            <img class="media-object rounded-circle shadow" src="<?PHP echo $blogInfo->icon; ?>" alt="">
                        <?php } else {
                        ?>
                            <img class="media-object rounded-circle shadow" src="<?php echo $attachmentUrl; ?>" alt="<?php echo $name; ?>">
                        <?php
                        } ?>
                        <div class="testimonial-writer d-flex flex-row justify-content-between">
                            <ul class="list-inline d-flex flex-row">
                                <li class="mr-2"><a href="javascript:void(0);"><?php echo $date_time; ?></a></li>
                            </ul>
                            <h6><?php echo $name; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }
    public function getDetailDate($date)
    {
        global $CDWFunc;
        $date_time = $CDWFunc->date->convertDateTimeDisplay($date);
        ob_start();
    ?>

        <div class="d-flex flex-column my-2 align-center border-bottom border-dash w-50 m-auto">
            <a href="javascript:void(0);"><span>Ngày: </span><?php echo $date_time; ?></a>
        </div>
<?php
        return ob_get_clean();
    }
}
