<?php
global $CDWFunc;
if ($CDWFunc->isAdministrator()) {
    $url = $CDWFunc->getUrl('index', 'ticket');
} else
    $url = $CDWFunc->getUrl('ticket', 'client');
?>
<form class="form-new-ticket-small" method="POST">
    <?php wp_nonce_field('ajax-new-ticket-nonce', 'nonce'); ?>

    <input type="hidden" id="urlredirect" value="<?php echo $url; ?>">
    <div class="card">
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="title" class="control-label">Tiêu đề</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="type" class="control-label">Dịch vụ hỗ trợ</label>
                        <select id='type' name='type' class='select2 form-control' multiple required></select>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="note" class="control-label">Chi tiết hỗ trợ</label>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="header">
                            <h2>Tập tin đính kèm <small>tải lên png, jpg, jpeg hoặc pdf</small></h2>
                        </div>
                        <div class="body">
                            <input id="file-images" name="file-images" type="file" class="dropify" data-allowed-file-extensions="pdf png jpg jpeg" data-max-file-size="3072k" multiple>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>Danh sách tập tin</h2>
                        </div>
                        <div class="body">
                            <div id="aniimated-thumbnials" class="list-unstyled row clearfix file_manager">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary btn-send">Gửi yêu cầu</button>
            </div>
        </div>
    </div>
</form>