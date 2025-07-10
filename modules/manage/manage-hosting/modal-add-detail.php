<div class="modal fade" id="modal-add-detail" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-detail-form" class="form">
                    <input type="hidden" id="Id">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="date" class="control-label">Ngày</label>
                                        <input type="text" id="date" name="date" class="form-control datepicker" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="gia" class="control-label">Giá</label>
                                <input type="text" id="gia" name="gia" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="gia_han" class="control-label">Gia hạn</label>
                                <input type="text" id="gia_han" name="gia_han" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="note" class="control-label">Ghi chú</label>
                                <input type="text" id="note" name="note" class="form-control">
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