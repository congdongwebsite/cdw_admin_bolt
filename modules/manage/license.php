<div class="wrap">
    <h1>Quản lý Giấy phép</h1>

    <div class="row">
        <div class="col-md-12 col-lg-9">
            <div class="table-responsive">
                <table class="table table-striped" id="license-table">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Mã giấy phép</th>
                            <th>Khách hàng</th>
                            <th>Plugin</th>
                            <th>Loại</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Phiên bản</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Licenses will be loaded here by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4 id="license-form-title">Thêm mới Giấy phép</h4>
                </div>
                <div class="card-body">
                    <form id="license-form">
                        <?php wp_nonce_field('cdw_license_ajax_nonce', 'nonce-license'); ?>
                        <input type="hidden" id="license_id" name="license_id" value="">
                        <div class="form-group">
                            <label for="license_title">Tiêu đề</label>
                            <input type="text" id="license_title" name="license_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="plugin_id">Plugin</label>
                            <select id="plugin_id" name="plugin_id" class="form-control" required>
                                <?php
                                $plugins = get_posts(array('post_type' => 'plugin', 'posts_per_page' => -1));
                                echo '<option value="">Chọn Plugin</option>';
                                foreach ($plugins as $plugin) {
                                    echo '<option value="' . esc_attr($plugin->ID) . '">' . esc_html($plugin->post_title) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customer_id">Khách hàng</label>
                            <select id='customer_id' name='customer_id' class='select2 form-control'></select>
                        </div>
                        <div class="form-group">
                            <label for="version_detail_id">Phiên bản</label>
                            <select id="version_detail_id" name="version_detail_id" class="form-control" required>
                                <option value="">Chọn phiên bản</option>
                            </select>
                        </div>
                        <input type="hidden" id="selected_version_detail_id" name="selected_version_detail_id" value="">
                        <div class="form-group">
                            <label for="license_type">Loại giấy phép</label>
                            <select id="license_type" name="license_type" class="form-control">
                                <option value="free">Miễn phí</option>
                                <option value="premium">Trả phí</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="starts_at">Ngày bắt đầu</label>
                            <input type="date" id="starts_at" name="starts_at" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="duration">Thời hạn</label>
                            <select id="duration" name="duration" class="form-control">
                                <option value="1 year">1 Năm</option>
                                <option value="2 years">2 Năm</option>
                                <option value="3 years">3 Năm</option>
                                <option value="lifetime">Trọn đời</option>
                                <option value="free">Miễn phí</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active">Kích hoạt</option>
                                <option value="inactive">Vô hiệu hóa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu Giấy phép</button>
                        <button type="button" id="clear-form" class="btn btn-secondary">Làm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="view-details-modal" tabindex="-1" role="dialog" aria-labelledby="view-details-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="view-details-modal-label">Chi tiết Xác thực</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Mã giấy phép:</strong> <span id="modal-license-key"></span></p>
        <p><strong>Plugin ID:</strong> <span id="modal-plugin-id"></span></p>
        <hr>
        <p>Sử dụng các chi tiết này để kiểm tra endpoint `verify_license_key`.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>