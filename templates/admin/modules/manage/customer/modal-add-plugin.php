<div class="modal fade" id="modal-add-plugin" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-plugin-form" class="form">
                    <input type="hidden" id="Id">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="date" class="control-label">Ngày mua</label>
                                <input type="text" id="date" name="date" class="form-control datepicker date-now">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                            <div class="form-group">
                                <label for="expiry-date" class="control-label">Thời gian hết hạn</label>
                                <input type="text" id="expiry-date" name="expiry-date" class="form-control datepicker date-now">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="plugin-type" class="control-label">Plugin</label>
                                <select id='plugin-type' name='plugin-type' class='select2 form-control'></select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="price" class="control-label">Giá</label>
                                <input type="text" id="price" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Thông tin</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="license" class="control-label">Giấy phép</label>
                                <input type="text" id="license" name="license" class="form-control" disabled required>
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