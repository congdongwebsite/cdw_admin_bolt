<div class="card filter">
    <div class="body">
        <div class="form-row">
            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <label for="from-date" class="control-label">Mua từ ngày</label>
                    <input type="text" id="from-date" name="from-date" class="form-control datepicker " autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <label for="until-date" class="control-label">Mua đến ngày</label>
                    <input type="text" id="until-date" name="until-date" class="form-control datepicker " autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <label for="from-expiry-date" class="control-label">Hết hạn từ ngày</label>
                    <input type="text" id="from-expiry-date" name="from-expiry-date" class="form-control datepicker " autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                <div class="form-group">
                    <label for="until-expiry-date" class="control-label">Hết hạn đến ngày</label>
                    <input type="text" id="until-expiry-date" name="until-expiry-date" class="form-control datepicker " autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12 col-12">
                <div class="form-group">
                    <label for="type" class="control-label">Loại dịch vụ</label>
                    <select id="type" name="type" class="select2 form-control">
                        <option value="">Chọn loại dịch vụ</option>
                        <option value="customer-hosting">Hosting</option>
                        <option value="customer-domain">Domain</option>
                        <option value="customer-email">Email</option>
                        <option value="customer-theme">Theme</option>
                        <option value="customer-plugin">Plugin</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12 col-12">
                <div class="form-group">
                    <label for="status" class="control-label">Trạng thái</label>
                    <select id='status' name='status' class='select2 form-control' data-value="closetoexpiration"></select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-12 col-md-12 mb-2">
                <a class="btn btn-reload btn-primary w-100" title="Tải dữ liệu (Z+R)" href="#"><i class="fal fa-sync"></i><span> Tải dữ liệu</span></a>
            </div>
        </div>
    </div>
</div>