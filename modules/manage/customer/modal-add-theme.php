<div class="modal fade" id="modal-add-theme" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm dòng mới</h5>
                <button type="button" class="close btn-close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-add-theme-form" class="form">
                    <input type="hidden" id="Id">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="date" class="control-label">Ngày mua</label>
                                <input type="text" id="date" name="date" class="form-control datepicker date-now">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="site-type" class="control-label">Giao diện</label>
                                <select id='site-type' name='site-type' class='select2 form-control'></select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="price" class="control-label">Giá</label>
                                <input type="text" id="price" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Thông tin</label>
                                <input type="text" id="name" name="name" class="form-control" required>
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