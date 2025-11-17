<?php
global $CDWFunc;
// var_dump(get_list_abc());

if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];

    $kyc_status = get_post_meta($id_detail, 'status-kyc', true);
    switch ($kyc_status) {
        case '2':
            $kyc_status_text = 'Đang Xác Thực';
            $kyc_status_class = 'warning';
            break;
        case '3':
            $kyc_status_text = 'Đã Xác Thực';
            $kyc_status_class = 'success';
            break;
        case '1':
        default:
            $kyc_status_text = 'Chưa Xác Thực';
            $kyc_status_class = 'secondary';
            break;
    }
    $is_readonly = ($kyc_status == '3') ? 'readonly' : '';
    $is_disabled = ($kyc_status == '3') ? 'disabled' : '';

    $birthdate = $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id_detail, 'birthdate', true));
    $gender = get_post_meta($id_detail, 'gender', true);

    $id_card_front_attachment_id = get_post_meta($id_detail, 'id_card_front', true);
    $id_card_back_attachment_id = get_post_meta($id_detail, 'id_card_back', true);

    if ($id_card_front_attachment_id) {
        $id_card_front_url = wp_get_attachment_url($id_card_front_attachment_id);
    }
    if ($id_card_back_attachment_id) {
        $id_card_back_url = wp_get_attachment_url($id_card_back_attachment_id);
    }
?>
    <form class="form-detail-customer-small" method="POST">
        <?php wp_nonce_field('ajax-detail-customer-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'customer'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <input type="hidden" id="kyc_action" name="kyc_action" value="">
        <input type="hidden" id="kyc_rejection_reason" name="kyc_rejection_reason" value="">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Thông Tin </h2>
                    <?php if ($kyc_status == '3') : ?>
                        <button type="button" class="btn btn-warning btn-reset-kyc">Nhập lại KYC</button>
                    <?php endif; ?>
                </div>
                <small class="mb-0">
                    Trạng thái KYC: <span class="text-<?php echo $kyc_status_class; ?> font-weight-bold"><?php echo $kyc_status_text; ?></span>
                    <?php
                    if ($kyc_status == '3') {
                        echo '<span class="mx-2">|</span>';
                        $inet_customer_id = get_post_meta($id_detail, 'inet_customer_id', true);

                        if (!empty($inet_customer_id)) {
                            echo '<span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> Đã Đồng Bộ</span> (ID: ' . esc_html($inet_customer_id) . ')';
                            echo '<button type="button" class="btn btn-info ml-2 btn-sync-inet btn-sm">Đồng bộ lại iNET</button>';
                        } else {
                            // echo '<span class="text-danger font-weight-bold"><i class="fa fa-times-circle"></i> Chưa Đồng Bộ</span>';
                            echo '<button type="button" class="btn btn-info btn-sync-inet btn-sm">Đồng bộ với iNET</button>';
                        }
                    }
                    ?>
                </small>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Họ và tên <small class="text-danger">*</small></label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo get_post_meta($id_detail, 'name', true); ?>" required <?php echo $is_readonly; ?>>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="phone" class="control-label">Điện thoại <small class="text-danger">*</small></label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo get_post_meta($id_detail, 'phone', true); ?>" required <?php echo $is_readonly; ?>>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="email" class="control-label">Email <small class="text-danger">*</small></label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo get_post_meta($id_detail, 'email', true); ?>" required <?php echo $is_readonly; ?>>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="cmnd" class="control-label">CMND / CCCD <small class="text-danger">*</small></label>
                            <input type="text" id="cmnd" name="cmnd" value="<?php echo get_post_meta($id_detail, 'cmnd', true); ?>" class="form-control" <?php echo $is_readonly; ?> required maxlength="20" pattern="\d{1,20}" data-parsley-pattern-message="Số CMND/CCCD phải bao gồm 20 ký tự số." inputmode="numeric">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="birthdate" class="control-label">Ngày sinh</label>
                            <input type="text" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>" class="form-control datepicker" <?php echo $is_disabled; ?>>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="control-label">Giới tính</label>
                            <div>
                                <label class="fancy-radio">
                                    <input name="gender" value="male" type="radio" <?php echo $gender == 'male' ? 'checked' : ''; ?> <?php echo $is_disabled; ?>>
                                    <span><i></i>Nam</span>
                                </label>
                                <label class="fancy-radio">
                                    <input name="gender" value="female" type="radio" <?php echo $gender == 'female' ? 'checked' : ''; ?> <?php echo $is_disabled; ?>>
                                    <span><i></i>Nữ</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="dvhc-tp" class="control-label">Tỉnh/Thành phố <small class="text-danger">*</small></label>
                            <select id='dvhc-tp' name='dvhc-tp' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'dvhc_tp', true); ?>" <?php echo $is_disabled; ?> required></select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="dvhc-px" class="control-label">Phường/Xã <small class="text-danger">*</small></label>
                            <select id='dvhc-px' name='dvhc-px' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'dvhc_px', true); ?>" <?php echo $is_disabled; ?> required></select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="address" class="control-label">Địa chỉ <small class="text-danger">*</small></label>
                            <input type="text" id="address" name="address" class="form-control" value="<?php echo get_post_meta($id_detail, 'address', true); ?>" <?php echo $is_readonly; ?> required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="note" class="control-label">Ghi chú</label>
                            <?php

                            $settings = array(
                                'textarea_name' => 'note',
                                'editor_class'  => 'i18n-multilingual',
                                'textarea_rows' => 3,
                                'quicktags' => false, // Remove view as HTML button.
                                'media_buttons' => false,
                                'tinymce'       => array(
                                    'toolbar1'      => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,separator,alignleft,aligncenter,alignright,separator,wp_more,fullscreen,wp_adv',
                                    'toolbar2'      => 'strikethrough,hr,forecolor,removeformat,charmap,outdent,indent,undo,redo',
                                    'toolbar3'      => '',
                                    'paste_as_text' => true,
                                ),
                            );
                            wp_editor(htmlspecialchars_decode(get_post_meta($id_detail, 'note', true)), 'note', $settings);


                            \_WP_Editors::enqueue_scripts();
                            print_footer_scripts();
                            \_WP_Editors::editor_js();
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="control-label">Ảnh CMND/CCCD mặt trước</label>
                            <div class="media">
                                <div class="media-left m-r-15">
                                    <img class="id-card-front-preview" src="<?php echo $id_card_front_url ? $id_card_front_url : 'data:image/png;base64,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'; ?>" class="user-photo media-object" alt="Mặt trước CMND/CCCD" width="150">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label class="control-label">Ảnh CMND/CCCD mặt sau</label>
                            <div class="media">
                                <div class="media-left m-r-15">
                                    <img class="id-card-back-preview" src="<?php echo $id_card_back_url ? $id_card_back_url : 'data:image/png;base64,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'; ?>" class="user-photo media-object" alt="Mặt sau CMND/CCCD" width="150">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="company-name" class="control-label">Pháp danh</label>
                            <input type="text" id="company-name" name="company-name" class="form-control" value="<?php echo get_post_meta($id_detail, 'company_name', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="mst" class="control-label">Mã số thuế</label>
                            <input type="text" id="mst" name="mst" class="form-control" value="<?php echo get_post_meta($id_detail, 'mst', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="company-phone" class="control-label">Điện thoại công ty</label>
                            <input type="text" id="company-phone" name="company-phone" class="form-control" value="<?php echo get_post_meta($id_detail, 'company_phone', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="company-address" class="control-label">Địa chỉ công ty</label>
                            <input type="text" id="company-address" name="company-address" class="form-control" value="<?php echo get_post_meta($id_detail, 'company_address', true); ?>">
                        </div>
                    </div>
                </div>
                <?php if ($kyc_status !== '3') : ?>
                    <button type="button" class="btn btn-success btn-confirm-kyc">Xác Nhận</button>
                    <button type="button" class="btn btn-danger btn-reject-kyc" data-toggle="modal" data-target="#modal-reject-kyc">Từ Chối</button>
                    <small id="rejection-reason-display" class="text-muted ml-2" style="display: none;"></small>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs-new2">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#hostings">Hosting</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#domains">Domain</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#emails">Email</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#themes">Theme</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#plugins">Plugin</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#billings">Thanh toán</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#images">Hình ảnh</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logs">Logs</a></li>
                </ul>
                <div class="tab-content m-0 px-0">
                    <div class="tab-pane show active" id="hostings">
                        <table id="tb-hostings" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="domains">
                        <table id="tb-domains" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="emails">
                        <table id="tb-emails" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="themes">
                        <table id="tb-themes" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="plugins">
                        <table id="tb-plugins" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="billings">
                        <table id="tb-billings" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                    <div class="tab-pane" id="images">
                        <div class="card">
                            <div class="header">
                                <h2>Lưu trữ file <small>tải lên png, jpg, jpeg hoặc pdf</small></h2>
                            </div>
                            <div class="body">
                                <input id="file-images" name="file-images" type="file" class="dropify" data-allowed-file-extensions="pdf png jpg jpeg" data-max-file-size="3072k" multiple>
                            </div>
                        </div>
                        <div class="card">
                            <div class="header">
                                <h2>Danh sách đã tải lên </h2>
                            </div>
                            <div class="body">
                                <div id="aniimated-thumbnials" class="list-unstyled row clearfix file_manager ">
                                    <?php
                                    $args = array(
                                        'post_type' => 'attachment',
                                        'posts_per_page' => -1,
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'id-parent',
                                                'value' => $id_detail,
                                                'compare' => '='
                                            )
                                        )
                                    );
                                    $attachments = get_posts($args);
                                    foreach ($attachments as $attachment) {

                                        $attachmentMetadata = wp_get_attachment_metadata($attachment->ID);
                                        $attachmentSizeFormatted = size_format($attachmentMetadata['filesize']);

                                    ?>
                                        <div class="col-lg-3 col-md-4 col-sm-12 item" data-id-file="<?php echo $attachment->ID; ?>">
                                            <div class="card ">
                                                <div class="file">
                                                    <a class="image" href="<?php echo get_private_image_link($attachment->ID); ?>">
                                                        <div class="hover">
                                                            <button type="button" class="btn btn-icon btn-danger remove">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <div class="image">
                                                            <img src="<?php echo $attachment->post_mime_type == "application/pdf" ? "/wp-content/uploads/2023/02/free-pdf-file-icon-thumb.png" : get_private_image_link($attachment->ID); ?>" alt="<?php echo $attachment->post_title; ?>" class="img-fluid img-thumbnail">

                                                        </div>
                                                        <div class="file-name">
                                                            <p class="m-b-5 text-muted"><?php echo $attachment->post_title; ?></p>
                                                            <small>Size: <?php echo $attachmentSizeFormatted; ?> <span class="date text-muted"><?php echo $CDWFunc->date->convertDateTimeDisplay($attachment->post_date); ?></span></small>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="logs">
                        <table id="tb-logs" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php $CDWFunc->getComponent('button-post-type'); ?>
    </form>
    <?php require_once('modal-add-hosting.php'); ?>
    <?php require_once('modal-add-domain.php'); ?>
    <?php require_once('modal-add-theme.php'); ?>
    <?php require_once('modal-add-billing.php'); ?>
    <?php require_once('modal-add-plugin.php'); ?>
    <?php require_once('modal-add-email.php'); ?>
    <?php require_once('modal-reject-kyc.php'); ?>
    <?php require_once('modal-register-email.php'); ?>
    <?php require_once('modal-change-email-plan.php'); ?>
<?php
} else {
    echo 'Không tồn tại';
}
?>