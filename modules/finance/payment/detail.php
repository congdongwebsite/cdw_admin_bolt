<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-payment-small" method="POST">
        <?php wp_nonce_field('ajax-detail-payment-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'payment'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <h2>Thông Tin </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="date" class="control-label">Ngày</label>
                            <input type="text" id="date" name="date" class="form-control datepicker" value="<?php echo get_post_meta($id_detail, 'date', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="type" class="control-label">Loại</label>
                            <select id='type' name='type' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'type', true); ?>" required></select>
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
                    <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#details">Chi tiết</a></li>
                </ul>
                <div class="tab-content m-0 px-0">
                    <div class="tab-pane show active" id="details">
                        <table id="tb-details" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
                    </div>
                </div>
            </div>
        </div>
        <?php $CDWFunc->getComponent('button-post-type'); ?>
    </form>
    <?php require_once('modal-add-detail.php'); ?>
<?php
} else {
    echo 'Không tồn tại';
}
?>