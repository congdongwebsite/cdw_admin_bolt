<div class="modal fade" id="modal-add-billing" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-billing-form" class="form">
                    <input type="hidden" id="Id">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="date" class="control-label">Ngày thanh toán</label>
                                <input type="text" id="date" name="date" class="form-control datepicker date-now">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="note" class="control-label">Nội dung thanh toán</label>
                                <input type="text" id="note" name="note" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="amount" class="control-label">Tiền</label>
                                <input type="text" id="amount" name="amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="status" class="control-label">Trạng thái</label>
                                <select id='status' name='status' class='select2 form-control'></select>
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