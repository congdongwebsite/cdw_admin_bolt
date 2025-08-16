<?php
global $CDWFunc;
?>
<form class="form-new-manage-plugin-small" method="POST">
    <?php wp_nonce_field('ajax-new-manage-plugin-nonce', 'nonce'); ?>
    <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'manage-plugin'); ?>">

    <?php $CDWFunc->getComponent('button-new-post-type'); ?>
    <div class="card">
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-6 col-sm-12 col-12">

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="title" class="control-label">Tiêu đề</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Tên plugin</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="sub_domain" class="control-label">URL</label>
                                <input type="url" id="sub_domain" name="sub_domain" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="price" class="control-label">Giá</label>
                                <input type="text" id="price" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="module-version" class="control-label">Module Version</label>
                                <select id='module-version' name='module-version' class='select2 form-control' required></select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="type" class="control-label">Loại</label>
                                <select id='type' name='type' class='select2 form-control' multiple required></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 p-0">
                        <div class="form-group">
                            <label for="note" class="control-label">Mô tả</label>
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
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="media d-flex flex-column">
                        <div class="media-left m-r-15 w-100 overflow-hidden thumbnail-manage-plugin">
                            <img class="thumbnail w-100" src="<?PHP echo ADMIN_CHILD_THEME_URL_F; ?>/assets/images/user.png" class="user-photo media-object">
                        </div>
                        <div class="media-body">
                            <p>Tải lên ảnh của bạn.
                                <br> <em>Ảnh JPG, PNG, JPEG, GIF</em>
                            </p>
                            <button type="button" class="btn btn-default" id="btn-upload-photo">Ảnh tải lên</button>
                            <input type="file" id="thumbnail-custom" accept="image/*" class="sr-only">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="body">
            <ul class="nav nav-tabs-new2">
                <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#images">Hình ảnh</a></li>
            </ul>
            <div class="tab-content m-0 px-0">
                <div class="tab-pane show active" id="images">
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