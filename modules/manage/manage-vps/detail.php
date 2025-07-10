<?php
global $CDWFunc;
if (isset($_GET['id']) && get_post_status($_GET['id'])) {
    $id_detail = $_GET['id'];
?>
    <form class="form-detail-vps-small" method="POST">
        <?php wp_nonce_field('ajax-detail-vps-nonce', 'nonce'); ?>
        <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('index', 'manage-vps'); ?>">
        <input type="hidden" id='id' name="id" value="<?php echo $id_detail; ?>">
        <?php $CDWFunc->getComponent('button-detail-post-type'); ?>
        <div class="card">
            <div class="header">
                <h2>VPS <small>Thông tin kỹ thuật</small> </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-8 col-sm-8 col-8">
                        <div class="form-group">
                            <label for="ip" class="control-label">IP</label>
                            <input type="text" id="ip" name="ip" class="form-control" value="<?php echo get_post_meta($id_detail, 'ip', true); ?>" value="<?php echo get_post_meta($id_detail, 'ip', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-4">
                        <div class="form-group">
                            <label for="port" class="control-label">Port</label>
                            <input type="number" id="port" name="port" class="form-control" value="<?php echo get_post_meta($id_detail, 'port', true); ?>" required required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="user" class="control-label">Tài khoản</label>
                            <input type="text" id="user" name="user" class="form-control" value="<?php echo get_post_meta($id_detail, 'user', true); ?>" required required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="pass" class="control-label">Mật khẩu</label>
                            <input type="password" id="pass" name="pass" class="form-control" value="<?php echo get_post_meta($id_detail, 'pass', true); ?>" required required>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2>Thông tin nhà cung cấp</h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="supplier-name" class="control-label">Nhà cung cấp</label>
                            <input type="text" id="supplier-name" name="supplier-name" class="form-control" value="<?php echo get_post_meta($id_detail, 'supplier_name', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="service-type" class="control-label">Gói cước</label>
                            <input type="text" id="service-type" name="service-type" class="form-control" value="<?php echo get_post_meta($id_detail, 'service_type', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="url" class="control-label">Website</label>
                            <input type="url" id="url" name="url" class="form-control" value="<?php echo get_post_meta($id_detail, 'url', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="supplier-user" class="control-label">Tài khoản</label>
                            <input type="text" id="supplier-user" name="supplier-user" class="form-control" value="<?php echo get_post_meta($id_detail, 'supplier_user', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="supplier-pass" class="control-label">Mật khẩu</label>
                            <input type="password" id="supplier-pass" name="supplier-pass" class="form-control" value="<?php echo get_post_meta($id_detail, 'supplier_pass', true); ?>" required>
                        </div>
                    </div>
                    <div class="divider"></div>

                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="service-buy-date" class="control-label">Ngày mua</label>
                            <input type="text" id="service-buy-date" name="service-buy-date" class="form-control datepicker" value="<?php echo get_post_meta($id_detail, 'service_buy_date', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="service-expiry-date" class="control-label">Ngày hết hạn</label>
                            <input type="text" id="service-expiry-date" name="service-expiry-date" class="form-control datepicker" value="<?php echo get_post_meta($id_detail, 'service_expiry_date', true); ?>">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="service-price" class="control-label">Giá</label>
                            <input type="text" id="service-price" name="service-price" class="form-control" value="<?php echo get_post_meta($id_detail, 'service_price', true); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2>Thông tin mua hàng</h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="buyer-name" class="control-label">Họ và tên</label>
                            <input type="text" id="buyer-name" name="buyer-name" class="form-control" value="<?php echo get_post_meta($id_detail, 'buyer_name', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="buyer-email" class="control-label">Email</label>
                            <input type="email" id="buyer-email" name="buyer-email" class="form-control" value="<?php echo get_post_meta($id_detail, 'buyer_email', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="buyer-phone" class="control-label">Số điện thoại</label>
                            <input type="text" id="buyer-phone" name="buyer-phone" class="form-control" value="<?php echo get_post_meta($id_detail, 'buyer_phone', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="buyer-address" class="control-label">Địa chỉ</label>
                            <input type="text" id="buyer-address" name="buyer-address" class="form-control" value="<?php echo get_post_meta($id_detail, 'buyer_address', true); ?>" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="form-group">
                            <label for="buyer-card-id" class="control-label">CMND/CCCD</label>
                            <input type="text" id="buyer-card-id" name="buyer-card-id" class="form-control" value="<?php echo get_post_meta($id_detail, 'buyer_card_id', true); ?>" required>
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