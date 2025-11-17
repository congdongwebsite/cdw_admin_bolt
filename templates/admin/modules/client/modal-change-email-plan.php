<?php defined('ABSPATH') || exit; ?>
<div class="modal fade" id="modal-change-email-plan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modal-change-email-plan-title">Đổi gói Email</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="change-email-plan-customer-email-id">
                <input type="hidden" id="change-email-plan-inet-email-id">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <h5>Thông tin gói hiện tại</h5>
                        <ul class="list-unstyled">
                            <li><strong>Tên gói:</strong> <span id="current-plan-name"></span></li>
                            <li><strong>Tên miền:</strong> <span id="current-plan-domain"></span></li>
                            <li><strong>Ngày hết hạn:</strong> <span id="current-plan-expiry-date"></span></li>
                            <li><strong>Số ngày còn lại:</strong> <span id="current-plan-remaining-days"></span> ngày</li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <h5>Chọn gói mới</h5>
                        <div class="form-group">
                            <label for="new-email-plan" class="control-label">Gói Email mới</label>
                            <select id="new-email-plan" class="form-control select2" style="width: 100%;"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-confirm-change-email-plan">Thêm vào giỏ hàng</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>