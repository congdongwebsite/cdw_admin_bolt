<?php


global $CDWFunc, $userCurrent, $CDWTicket;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
    if ($CDWFunc->isAdministrator()) {
        $url = $CDWFunc->getUrl('index', 'ticket');
        update_post_meta($id_detail, 'read', true);
    } else {
        $url = $CDWFunc->getUrl('ticket', 'client');
        update_post_meta($id_detail, 'user-read', true);
    }
    $types = get_post_meta($id_detail, 'type');
    $date_time = $CDWFunc->date->human_display(get_post_meta($id_detail, 'date', true));
    $ticket_archive_current =  get_post_meta($id_detail, 'status', true);
    $has_important = get_post_meta($id_detail, 'important', true);
    $user_id = get_post_meta($id_detail, 'user-id', true);
    $ticket_details = $CDWTicket->getTicketDetails($id_detail);
?>
    <form class="form-detail-ticket-small" method="POST">
        <?php wp_nonce_field('ajax-detail-ticket-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $url; ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="header">
                        <h2>Thông tin yêu cầu</h2>
                    </div>
                    <div class="body">
                        <div class="list-group list-widget">
                            <a href="javascript:void(0);" class="list-group-item py-2">
                                <i class="fa fa-user-o text-muted"></i>Khách hàng
                                <span class="badge"><?php echo get_user_meta($user_id, 'first_name', true); ?></span>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item d-flex justify-content-between py-2">
                                <span><i class="fa fa-support text-muted mr-2"></i>Mục hỗ trợ</span>
                                <div>
                                    <?php
                                   $defaultType = $CDWTicket->getDefaultType();
                                    foreach ($types as $value) {
                                        $type = $defaultType[$value];
                                    ?>
                                        <span class="badge badge-<?php echo $type['color']; ?> mb-2">
                                            <?php echo $type['text']; ?>
                                        </span>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item py-2">
                                <i class="fa  fa-clock-o text-muted"></i>Thời gian
                                <span class="badge"><?php echo $date_time; ?></span>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item py-2">
                                <div>
                                    <i class="fa fa-bullhorn text-muted"></i>Trạng thái
                                    <?php
                                    if ($has_important) {
                                    ?>
                                        <span class="badge ">
                                            <i class="fa fa-star text-warning"></i>
                                        </span>
                                    <?php
                                    }
                                    ?>
                                    <span class="status">
                                        <?php
                                        switch ($ticket_archive_current) {
                                            case 'pending':
                                        ?>
                                                <span class="badge text-primary">Yêu cầu mới</span>
                                            <?php
                                                break;
                                            case 'success':
                                            case 'archive':
                                            ?>
                                                <span class="badge text-success">Đã xử lý</span>
                                            <?php
                                                break;
                                            case 'trash':
                                            ?>
                                                <span class="badge text-danger">Đã xóa</span>
                                        <?php
                                                break;
                                        }

                                        ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <?php
                            switch ($ticket_archive_current) {
                                case 'success':
                            ?>
                                    <button type="button" class="btn btn-warning btn-close-ticket">Mở yêu cầu</button>
                                <?php
                                    break;
                                case 'pending':
                                case 'archive':
                                case 'trash':
                                ?>
                                    <button type="button" class="btn btn-danger btn-close-ticket">Đóng yêu cầu</button>
                            <?php
                                    break;
                            }

                            ?>
                            <?php
                            switch ($ticket_archive_current) {
                                case 'success':
                                case 'trash':
                                    break;
                                default:
                            ?>
                                    <a href="#reply" class="btn btn-secondary "><i class="fa fa-hand-o-down mr-1"></i>Trả lời</a>
                            <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2>Danh sách tập tin</h2>
                    </div>
                    <div class="body">
                        <div id="aniimated-thumbnials" class="list-unstyled row clearfix file_manager">
                            <?php
                            $ticket_images = get_post_meta($id_detail, 'ticket-images');
                            if (!empty($ticket_images)) {

                                $args = array(
                                    'post_type' => 'attachment',
                                    'posts_per_page' => -1,
                                    'post__in' => $ticket_images
                                );
                                $attachments = get_posts($args);
                                foreach ($attachments as $attachment) {
                                    $attachmentMetadata = wp_get_attachment_metadata($attachment->ID);
                                    $attachmentSizeFormatted = size_format($attachmentMetadata['filesize']);

                            ?>
                                    <div class="col-lg-6 col-md-4 col-sm-4 col-6 item" data-id-file="<?php echo $attachment->ID; ?>">
                                        <div class="card ">
                                            <div class="file">
                                                <a class="image" href="<?php echo wp_get_attachment_url($attachment->ID); ?>">
                                                    <div class="image">
                                                        <img src="<?php echo $attachment->post_mime_type == "application/pdf" ? "/wp-content/uploads/2023/02/free-pdf-file-icon-thumb.png" : wp_get_attachment_url($attachment->ID); ?>" alt="<?php echo $attachment->post_title; ?>" class="img-fluid img-thumbnail">

                                                    </div>
                                                    <div class="file-name">
                                                        <p class="m-b-5 text-muted text-truncate"><?php echo $attachment->post_title; ?></p>
                                                        <small>Size: <?php echo $attachmentSizeFormatted; ?> <span class="date text-muted"><?php echo $CDWFunc->date->convertDateTimeDisplay($attachment->post_date); ?></span></small>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="header">
                        <h3><strong class="text-primary"><?php echo get_the_title($id_detail); ?></strong></h3>
                    </div>
                    <div class="body py-0">
                        <div class="w-100 mx-2 p-2 pb-4 border border-secondary rounded bg-secondary text-light mb-4 ticket-content"><?php echo get_the_content(null, false, $id_detail); ?></div>
                    </div>
                </div>
                <div class="card has-detail <?php echo count($ticket_details->ids) > 0 ? '' : 'd-none'; ?>">
                    <div class="body ticket-detail-list">
                        <?php
                        $date = '';
                        foreach ($ticket_details->ids as $detail_ticket_id) {
                            //$check = wp_delete_post($detail_ticket_id, true);
                            $user_detail_id = get_post_meta($detail_ticket_id, 'user-id', true);

                            $date_post = get_post_meta($detail_ticket_id, 'date', true);
                            if ($CDWFunc->date->convertDateTime($date_post, $CDWFunc->date->formatDB,  $CDWFunc->date->format) != $CDWFunc->date->convertDateTime($date, $CDWFunc->date->formatDB,  $CDWFunc->date->format)) {
                                echo $CDWTicket->getDetailDate($date_post);
                            }
                            $date = $date_post;
                            if ($userCurrent->ID == (int) $user_detail_id) {
                                echo $CDWTicket->getDetailItemRight($detail_ticket_id);
                            } else {
                                echo $CDWTicket->getDetailItemLeft($detail_ticket_id);
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="card has-detail <?php echo $ticket_details->continue ? '' : 'd-none'; ?>">
                    <div class="body p-1 d-flex d-flex flex-row justify-content-center">
                        <div class="pagination-detail d-flex align-items-center" data-page="1">
                            <a href="javascript:void(0);" class="pagination-back  mr-2" disabled="disabled"><i class="fa fa-angle-left"></i></a>
                            <p class="lable-pagination m-0"><span class="count-from">1</span> - <span class="count-to"><?php echo  $ticket_details->post_count; ?></span>/ <span class="total"><?php echo $ticket_details->post_found; ?></span></p>
                            <a href="javascript:void(0);" class="pagination-next  ml-2 " data-contrinue="<?php echo $ticket_details->continue ? '1' : ''; ?>"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php
                switch ($ticket_archive_current) {
                    case 'success':
                    case 'trash':
                        break;
                    default:
                ?>
                        <div class="card reply">
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div id="reply" class="form-group ">
                                            <label for="note" class="control-label">Trả lời</label>
                                            <?php
                                            $settings = array(
                                                'textarea_name' => 'note',
                                                'editor_class'  => 'i18n-multilingual',
                                                'textarea_rows' => 6,
                                                'quicktags' => false, // Remove view as HTML button.
                                                'media_buttons' => false,
                                                'tinymce'       => array(
                                                    'toolbar1'      => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,separator,alignleft,aligncenter,alignright,separator,wp_more,fullscreen,wp_adv',
                                                    'toolbar2'      => 'strikethrough,hr,forecolor,removeformat,charmap,outdent,indent,undo,redo', //pastetext
                                                    'toolbar3'      => '',
                                                    'paste_as_text' => true,
                                                ),
                                            );
                                            wp_editor('', 'note', $settings);
                                            \_WP_Editors::enqueue_scripts();
                                            print_footer_scripts();
                                            \_WP_Editors::editor_js();

                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <button type="submit" class="btn btn-primary btn-reply"><i class="fa fa-reply-all mr-1"></i>Trả lời</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                <?php
                }

                ?>

            </div>
        </div>
    </form>
<?php
} else {
    echo 'Không tồn tại';
}
?>