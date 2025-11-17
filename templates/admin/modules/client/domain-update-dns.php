<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id = $_GET['id'];
    $url = get_post_meta($id, 'url', true);
?>
    <div class="client-domain-update-dns file_manager">
        <?php wp_nonce_field('ajax-client-domain-update-dns-nonce', 'nonce'); ?>
        <input type="hidden" name="domain" id="domain" value="<?php echo $url; ?>">
        <h1 class="text-center text-primary mb-4">Cập nhật DNS</h1>
        <div class="row clearfix">
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                <div class="card bg-white">
                    <div class="header">
                        <h2><b>Tên miền:</b><small><?php echo $url; ?></small></h2>
                    </div>
                    <div class="body pt-0">
                        <div class="row clearfix dns-list">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-6 col-12">
                <div class="card bg-white">
                    <div class="header">
                        <h2><b>DNS mới</b></h2>
                    </div>
                    <div class="body pt-0">
                        <form id="dns-form" method="post" novalidate="">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Name Server 1 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control dns-server-1" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 2 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control dns-server-2" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 3</label>
                                        <input type="text" class="form-control dns-server-3">
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 4</label>
                                        <input type="text" class="form-control dns-server-4">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Name Server 5</label>
                                        <input type="text" class="form-control dns-server-5">
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 6</label>
                                        <input type="text" class="form-control dns-server-6">
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 7</label>
                                        <input type="text" class="form-control dns-server-7">
                                    </div>

                                    <div class="form-group">
                                        <label>Name Server 8</label>
                                        <input type="text" class="form-control dns-server-8">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <button class="btn btn-success btn-update" data-id="<?php echo $id; ?>"><i class="fa fa-cloud-upload pr-2"></i>Cập nhật</button>
                                    <button class="btn btn-info btn-default" data-id="<?php echo $id; ?>"><i class="fa fa-info pr-2"></i>Mặc định</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Không tồn tại';
}
?>