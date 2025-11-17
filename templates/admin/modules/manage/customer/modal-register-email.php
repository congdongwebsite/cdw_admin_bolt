<div class="modal fade" id="modal-register-email" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="modal-register-email-title">Đăng ký Email iNET</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="register-email-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#step1-domain">Bước 1: Tên miền</a></li>
                        <li class="nav-item"><a class="nav-link disabled" data-toggle="tab" href="#step2-records">Bước 2: Cấu hình DNS</a></li>
                        <li class="nav-item"><a class="nav-link disabled" data-toggle="tab" href="#step3-finish">Bước 3: Hoàn tất</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane in active" id="step1-domain">
                            <form id="form-step1-register-email" onsubmit="return false;">
                                <input type="hidden" id="register-email-customer-email-id">
                                <input type="hidden" id="register-email-inet-email-id">
                                <p>Hãy nhập tên miền bạn muốn sử dụng cho Email Server (ví dụ: tencongty.com).</p>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="register-email-domain" required placeholder="tencongty.com">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="btn-check-domain-step1">Kiểm tra tên miền</button>
                                        </div>
                                    </div>
                                    <div id="domain-check-message" class="mt-2"></div>
                                </div>
                                <div class="notes mt-4">
                                    <p><strong>🔸 Lưu ý:</strong></p>
                                    <ul>
                                        <li>Domain phải đã được đăng ký và trỏ DNS hợp lệ.</li>
                                        <li>Domain này sẽ được sử dụng để tạo tài khoản email dạng name@tencongty.com.</li>
                                        <li>Nếu domain chưa có DNS, bạn có thể thêm sau khi hoàn tất đăng ký.</li>
                                    </ul>
                                </div>
                                <button type="submit" class="btn btn-success" id="btn-activate-domain" disabled>Bước kế tiếp</button>
                            </form>
                        </div>
                        <style>
                            #tb-email-records td:nth-child(2) {
                                white-space: normal;
                                word-break: break-all;
                                max-width: 100px;
                            }

                            #tb-email-records td:nth-child(3) {
                                white-space: normal;
                                word-break: break-all;
                                max-width: 300px;
                            }

                            #tb-email-records td:nth-child(4) {
                                white-space: normal;
                                word-break: break-all;
                                max-width: 50px;
                            }
                        </style>
                        <div role="tabpanel" class="tab-pane" id="step2-records">
                            <button type="button" class="btn btn-info mb-3" id="btn-check-records-top">Kiểm tra bản ghi</button>
                            <button type="button" class="btn btn-warning mb-3 ml-2" id="btn-gen-dkim-modal">Tạo DKIM</button>
                            <table id="tb-email-records" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Loại</th>
                                        <th>Tên</th>
                                        <th>Giá trị</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="notes mt-4">
                                <p><strong>🔸 Lưu ý:</strong></p>
                                <ul>
                                    <li>Email Server đã được kích hoạt để khởi tạo, vui lòng cập nhập DNS để tiến hành sử dụng email.</li>
                                    <li>Vui lòng bấm Kiểm Tra Bản Ghi để reload lại các trạng thái của DNS.</li>
                                    <li>Các thao tác Huỷ Bỏ & Quay lại sẽ làm gián đoạn việc cài đặt hệ thống, vui lòng kiểm tra và hoàn tất các bước xác minh.</li>
                                    <li>Các vấn đề liên quan cần được hỗ trợ vui lòng liên hệ quản trị viên để giải quyết.</li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-success" id="btn-step2-next" disabled>Bước kế tiếp</button>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="step3-finish">
                            <div class="text-center mb-4">
                                <h4>🎉 Hệ thống Email của bạn đã sẵn sàng sử dụng!</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Thông tin gói cước</h5>
                                    <ul class="list-unstyled" id="email-package-info">
                                        <li><strong>Gói cước:</strong> <span class="plan-name"></span></li>
                                        <li><strong>Tên miền:</strong> <span class="domain"></span></li>
                                        <li><strong>Dung lượng:</strong> <span class="quota"></span></li>
                                        <li><strong>Tài Khoản:</strong> <span class="accounts"></span></li>
                                        <li><strong>Nhóm mail:</strong> <span class="groups"></span></li>
                                        <li><strong>Trạng Thái:</strong> <span class="status"></span></li>
                                        <li><strong>Ngày tạo:</strong> <span class="created-date"></span></li>
                                        <li><strong>Ngày hết hạn:</strong> <span class="expiry-date"></span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Thông tin tài khoản quản trị</h5>
                                    <ul class="list-unstyled" id="email-admin-info">
                                        <li><strong>Web Mail Admin:</strong> <a href="#" class="admin-url" target="_blank"></a></li>
                                        <li>
                                            <strong>Tài khoản Admin:</strong> <span class="admin-email"></span>
                                            <button type="button" class="btn btn-sm btn-primary btn-generate-email-password ml-2">Tạo mật khẩu</button>
                                        </li>
                                        <div id="admin-password-section" style="display: none;">
                                            <li>
                                                <strong>Mật khẩu Admin:</strong>
                                                <div class="input-group">
                                                    <input type="password" class="form-control admin-password-display password-input-small" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary btn-toggle-password-visibility" type="button" title="Hiện/Ẩn mật khẩu">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary btn-copy-password" type="button" title="Sao chép">
                                                            <i class="fa fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </div>
                                        <li><strong>Web Mail Client:</strong> <a href="#" class="client-url" target="_blank"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-success" data-dismiss="modal">Hoàn tất</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>