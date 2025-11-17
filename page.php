<?php
global $CDWFunc;
?>
<div class="row clearfix row-deck page-secondary">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card top_widget page-secondary-1">
            <div class="body">
                <div class="icon"><i class="fa fa-list-alt"></i> </div>
                <div class="content">
                    <div class="text mb-2 text-uppercase title">Dịch Vụ</div>
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span></h4>
                    <small class="text-muted name">Dịch vụ đang sử dụng</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card top_widget page-secondary-2">
            <div class="body">
                <div class="icon"><i class="fa fa-shopping-cart"></i> </div>
                <div class="content">
                    <div class="text mb-2 text-uppercase title">Hoá Đơn</div>
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span></h4>
                    <small class="text-muted name">Đơn hàng đã thanh toán</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card top_widget page-secondary-3">
            <div class="body">
                <div class="icon"><i class="fa fa-comments"></i> </div>
                <div class="content">
                    <div class="text mb-2 text-uppercase title">Hỗ Trợ</div>
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span> </h4>
                    <small class="text-muted name">Gửi yêu cầu giúp đỡ</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card top_widget page-secondary-4">
            <div class="body">
                <div class="icon"><i class="fa fa-credit-card"></i> </div>
                <div class="content">
                    <div class="text mb-2 text-uppercase title">Tổng Tiền</div>
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span></h4>
                    <small class="text-muted name">Chi phí đã sử dụng</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix row-deck">
    <div class="col-lg-6 col-md-12">
        <div class="card page-list-hosting">
            <div class="header">
                <h2>Danh sách Hosting</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);" class="reload"><i class="icon-refresh mr-2"></i>Tải lại</a></li>
                            <li><a href="<?php echo $CDWFunc->getUrl('hosting', 'client'); ?>">Xem tất cả</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table mb-0 text-wrap tb-data">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Thông tin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="i-loading">
                                <td> <i class="fa fa-spinner fa-spin "></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="card page-list-domain">
            <div class="header">
                <h2>Danh sách tên miền</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);" class="reload"><i class="icon-refresh mr-2"></i>Tải lại</a></li>
                            <li><a href="<?php echo $CDWFunc->getUrl('domain', 'client'); ?>">Xem tất cả</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table mb-0 text-wrap tb-data">
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Trạng thái</th>
                                <th>Thời gian mua</th>
                                <th>Thời gian hết hạn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="i-loading text-center">
                                <td> <i class="fa fa-spinner fa-spin "></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="card page-list-billing">
            <div class="header">
                <h2>Danh sách thanh toán</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);" class="reload"><i class="icon-refresh mr-2"></i>Tải lại</a></li>
                            <li><a href="<?php echo $CDWFunc->getUrl('billing', 'client'); ?>">Xem tất cả</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table mb-0 text-wrap tb-data">
                        <thead>
                            <tr>
                                <th>Trạng thái</th>
                                <th>Mã thanh toán</th>
                                <th>Ngày thanh toán</th>
                                <th>Nội dung</th>
                                <th>Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="i-loading">
                                <td> <i class="fa fa-spinner fa-spin "></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>