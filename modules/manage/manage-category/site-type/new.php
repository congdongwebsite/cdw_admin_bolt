<?php
global $CDWFunc;
?>
<form class="form-new-site-type-small" method="POST">
    <?php wp_nonce_field('ajax-new-site-type-nonce', 'nonce'); ?>
    <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'site-type'); ?>">

    <?php $CDWFunc->getComponent('button-new-post-type'); ?>
    <div class="card">
        <div class="header">
            <h2>Thông Tin </h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Tên loại</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-9 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="note" class="control-label">Ghi chú</label>
                        <input type="text" id="note" name="note" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $CDWFunc->getComponent('button-post-type'); ?>
</form>