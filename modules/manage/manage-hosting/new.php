<?php
global $CDWFunc;
$arr = array(
    'post_type' => 'hosting',
    'post_status' => 'publish',
    'fields' => 'ids',
    'posts_per_page' => -1
);
$ids = get_posts($arr);
?>
<form class="form-new-manage-hosting-small" method="POST">
    <?php wp_nonce_field('ajax-new-manage-hosting-nonce', 'nonce'); ?>
    <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'manage-hosting'); ?>">

    <?php $CDWFunc->getComponent('button-new-post-type'); ?>
    <div class="card">
        <div class="header">
            <h2>Thông Tin</h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="title" class="control-label">Tên</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="sub-title" class="control-label">Sub Title</label>
                        <input type="text" id="sub-title" name="sub-title" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="gia" class="control-label">Giá</label>
                        <input type="text" id="gia" name="gia" class="form-control" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="gia_han" class="control-label">Gia hạn</label>
                        <input type="text" id="gia_han" name="gia_han" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row clearfix">

                <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                    <div class="form-group">
                        <label for="cpu" class="control-label">CPU</label>
                        <input type="number" id="cpu" name="cpu" class="form-control">
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                    <div class="form-group">
                        <label for="ram" class="control-label">RAM</label>
                        <input type="number" id="ram" name="ram" class="form-control">
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-4 col-4">
                    <div class="form-group">
                        <label for="hhd" class="control-label">Dung lượng</label>
                        <input type="number" id="hhd" name="hhd" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="feature" class="control-label">Tính năng</label>
                        <select id='feature' name='feature' class='select2 form-control'>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-2 col-2">
                    <div class="form-group">
                        <label for="stt" class="control-label">STT</label>
                        <input type="number" id="stt" name="stt" class="form-control" value="<?php echo count($ids) + 1; ?>">
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-10">
                    <div class="form-group">
                        <label for="note" class="control-label">Ghi chú</label>
                        <input type="text" id="note" name="note" class="form-control">
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