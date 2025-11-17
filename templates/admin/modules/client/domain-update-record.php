<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id = $_GET['id'];
    $url = get_post_meta($id, 'url', true);
?>
    <div class="client-domain-update-record file_manager">
        <?php wp_nonce_field('ajax-client-domain-update-record-nonce', 'nonce'); ?>
        <input type="hidden" name="cd-id" id="cd-id" value="<?php echo $id; ?>">



        <div class="card ">
            <div class="header">
                <h1 class="text-center text-primary mb-4">Cập nhật bản ghi</h1>
                <div class="d-flex justify-content-between">
                    <p class="">
                        <b>Domain:</b>
                        <span><?php echo $url; ?></span>
                    </p>
                    <button class="btn btn-info btn-default" data-id="<?php echo $id; ?>"><i class="fa fa-info pr-2"></i>Mặc định</button>
                </div>
            </div>
            <div class="body pt-0">
                <form id="record-form" method="post" novalidate="">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 record">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="fancy-checkbox">
                                            <input class="select-all" type="checkbox" name="checkbox">
                                            <span></span>
                                        </label>
                                    </th>
                                    <th>Tên bản ghi</th>
                                    <th>Loại bản ghi</th>
                                    <th>Giá trị bản ghi</th>
                                    <th>TTL</th>
                                    <th>
                                        <button type="button" class="btn btn-small btn-danger btn-delete-all" title="Xóa"><i class="fa fa-trash-o"></i></button>
                                        <button type="button" class="btn btn-small btn-info btn-save-all" title="Lưu"><i class="fa fa-cloud"></i></button>
                                        <button type="button" class="btn btn-small btn-success btn-add" title="Thêm"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Không tồn tại';
}
?>