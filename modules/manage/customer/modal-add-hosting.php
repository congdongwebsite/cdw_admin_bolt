<div class="modal fade" id="modal-add-hosting" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-hosting-form" class="form">
                    <div class="card">
                        <div class="header">
                            <h2>Thông tin gói</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="type" class="control-label">Gói cước</label>
                                        <select id='type' name='type' class='select2 form-control' required></select>
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
                                <div class="col-lg-3 col-md-8 col-sm-8 col-8">
                                    <div class="form-group">
                                        <label for="ip" class="control-label">IP</label>
                                        <input type="text" id="ip" name="ip" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-4">
                                    <div class="form-group">
                                        <label for="port" class="control-label">Port</label>
                                        <input type="number" id="port" name="port" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="user" class="control-label">Tài khoản</label>
                                        <input type="text" id="user" name="user" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label for="pass" class="control-label">Mật khẩu</label>
                                        <input type="text" id="pass" name="pass" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="justify-content: space-between;">
                <div>
                    <button type="button" class="btn btn-primary btn-change-pass">Đổi mật khẩu</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary btn-close">Đóng</button>
                    <button type="button" class="btn btn-primary btn-add">Thêm</button>
                    <button type="button" class="btn btn-primary btn-add-close">Thêm và đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>