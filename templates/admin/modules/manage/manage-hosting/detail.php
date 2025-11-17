<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-manage-hosting-small" method="POST">
        <?php wp_nonce_field('ajax-detail-manage-hosting-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'manage-hosting'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <h2>Thông Tin </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="title" class="control-label">Tên</label>
                            <input type="text" id="title" name="title" value="<?php echo get_the_title($id_detail); ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="sub-title" class="control-label">Sub Title</label>
                            <input type="text" id="sub-title" name="sub-title" class="form-control" value="<?php echo get_post_meta($id_detail, 'sub-title', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="gia" class="control-label">Giá</label>
                            <input type="text" id="gia" name="gia" value="<?php echo get_post_meta($id_detail, 'gia', true); ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="gia_han" class="control-label">Gia hạn</label>
                            <input type="text" id="gia_han" name="gia_han" value="<?php echo get_post_meta($id_detail, 'gia_han', true); ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                        <div class="form-group">
                            <label for="cpu" class="control-label">CPU</label>
                            <input type="number" id="cpu" name="cpu" class="form-control" value="<?php echo get_post_meta($id_detail, 'cpu', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                        <div class="form-group">
                            <label for="ram" class="control-label">RAM</label>
                            <input type="number" id="ram" name="ram" class="form-control" value="<?php echo get_post_meta($id_detail, 'ram', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                        <div class="form-group">
                            <label for="hhd" class="control-label">Dung lượng</label>
                            <input type="number" id="hhd" name="hhd" class="form-control" value="<?php echo get_post_meta($id_detail, 'hhd', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="feature" class="control-label">Tính năng</label>
                            <select id='feature' name='feature' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'feature', true); ?>"></select>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="form-group">
                            <label for="stt" class="control-label">STT</label>
                            <input type="number" id="stt" name="stt" class="form-control" value="<?php echo get_post_meta($id_detail, 'stt', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                        <div class="form-group">
                            <label for="stt" class="control-label">Package</label>
                            <select id='package' name='package' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'package', true); ?>"></select>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                        <div class="form-group">
                            <label for="note" class="control-label">Ghi chú</label>
                            <input type="text" id="note" name="note" value="<?php echo get_post_meta($id_detail, 'note', true); ?>" class="form-control">
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