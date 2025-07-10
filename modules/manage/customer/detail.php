<?php
global $CDWFunc;
// var_dump(get_list_abc());

if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-customer-small" method="POST">
        <?php wp_nonce_field('ajax-detail-customer-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'customer'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <h2>Thông Tin <small>Khách Hàng</small> </h2>
            </div>
            <div class="body">
                <div class="row clearfix">

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
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Họ và tên</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo get_post_meta($id_detail, 'name', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="phone" class="control-label">Điện thoại</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo get_post_meta($id_detail, 'phone', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo get_post_meta($id_detail, 'email', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="cmnd" class="control-label">CMND / CCCD</label>
                            <input type="text" id="cmnd" name="cmnd" value="<?php echo get_post_meta($id_detail, 'cmnd', true); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="dvhc-tp" class="control-label">Tỉnh/Thành phố</label>
                                    <select id='dvhc-tp' name='dvhc-tp' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'dvhc_tp', true); ?>"></select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="dvhc-qh" class="control-label">Quận/Huyện</label>
                                    <select id='dvhc-qh' name='dvhc-qh' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'dvhc_qh', true); ?>"></select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="dvhc-px" class="control-label">Phường/Xã</label>
                                    <select id='dvhc-px' name='dvhc-px' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'dvhc_px', true); ?>"></select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label for="address" class="control-label">Địa chỉ</label>
                                    <input type="text" id="address" name="address" class="form-control" value="<?php echo get_post_meta($id_detail, 'address', true); ?>">
                                </div>
                            </div>
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
<?php
} else {
    echo 'Không tồn tại';
}
?>