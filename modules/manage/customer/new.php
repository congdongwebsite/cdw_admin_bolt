<?php
global $CDWFunc;
?>
<form class="form-new-customer-small" method="POST">
    <?php wp_nonce_field('ajax-new-customer-nonce', 'nonce'); ?>
    <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'customer'); ?>">

    <?php $CDWFunc->getComponent('button-new-post-type'); ?>
    <div class="card">
        <div class="header">
            <h2>Thông Tin <small>Khách Hàng</small> </h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="company-name" class="control-label">Pháp danh</label>
                        <input type="text" id="company-name" name="company-name" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="mst" class="control-label">Mã số thuế</label>
                        <input type="text" id="mst" name="mst" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Họ và tên</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="phone" class="control-label">Điện thoại</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="cmnd" class="control-label">CMND / CCCD</label>
                        <input type="text" id="cmnd" name="cmnd" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="dvhc-tp" class="control-label">Tỉnh/Thành phố</label>
                                <select id='dvhc-tp' name='dvhc-tp' class='select2 form-control'></select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="dvhc-qh" class="control-label">Quận/Huyện</label>
                                <select id='dvhc-qh' name='dvhc-qh' class='select2 form-control'></select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="dvhc-px" class="control-label">Phường/Xã</label>
                                <select id='dvhc-px' name='dvhc-px' class='select2 form-control'></select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="address" class="control-label">Địa chỉ</label>
                                <input type="text" id="address" name="address" class="form-control">
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
                            <div id="aniimated-thumbnials" class="list-unstyled row clearfix file_manager">
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