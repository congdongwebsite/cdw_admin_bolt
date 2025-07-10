<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-manage-version-small" method="POST">
        <?php wp_nonce_field('ajax-detail-manage-version-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'manage-version'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <h2>Thông Tin </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="title" class="control-label">Tên</label>
                                    <input type="text" id="title" name="title" value="<?php echo get_the_title($id_detail); ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="type" class="control-label">Type</label>
                            <select id='type' name='type' class='select2 form-control' data-value="<?php echo get_post_meta($id_detail, 'type', true); ?>" required></select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" id="name" name="name" value="<?php echo get_post_meta($id_detail, 'name', true); ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
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