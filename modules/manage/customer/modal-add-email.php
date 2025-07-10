<div class="modal fade" id="modal-add-email" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-email-form" class="form">
                    <div class="card">
                        <div class="header">
                            <h2>Thông tin gói</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="email-type" class="control-label">Gói</label>
                                        <select id='email-type' name='email-type' class='select2 form-control' required></select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="price" class="control-label">Giá</label>
                                        <input type="text" id="price" name="price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label for="buy-date" class="control-label">Thời gian mua</label>
                                        <input type="text" id="buy-date" name="buy-date" class="form-control datepicker date-now">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label for="expiry-date" class="control-label">Thời gian hết hạn</label>
                                        <input type="text" id="expiry-date" name="expiry-date" class="form-control datepicker date-now">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="header">
                            <h2>Thông tin kỹ thuật</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="url_admin" class="control-label">Url Admin</label>
                                        <input type="text" id="url_admin" name="url_admin" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="user" class="control-label">Tài khoản</label>
                                        <input type="text" id="user" name="user" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="pass" class="control-label">Mật khẩu</label>
                                        <input type="password" id="pass" name="pass" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="url_client" class="control-label">Url Client</label>
                                        <input type="text" id="url_client" name="url_client" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close">Đóng</button>
                <button type="button" class="btn btn-primary btn-add">Thêm</button>
                <button type="button" class="btn btn-primary btn-add-close">Thêm và đóng</button>
            </div>
        </div>
    </div>
</div>