<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-finance-type-small" method="POST">
        <?php wp_nonce_field('ajax-detail-finance-type-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'finance-type'); ?>">
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
                            <label for="name" class="control-label">Tên loại</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo get_post_meta($id_detail, 'name', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="note" class="control-label">Ghi chú</label>
                            <input type="text" id="note" name="note" value="<?php echo get_post_meta($id_detail, 'note', true); ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $CDWFunc->getComponent('button-post-type'); ?>
    </form>
<?php
} else {
    echo 'Không tồn tại';
}
?>