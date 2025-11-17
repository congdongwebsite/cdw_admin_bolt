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
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span> <span class="font-12 text-muted"><i class="fa level"></i></span></h4>
                    <small class="text-muted name">Dịch vụ cung cấp</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card top_widget page-secondary-2">
            <div class="body">
                <div class="icon"><i class="fa fa-shopping-cart"></i> </div>
                <div class="content">
                    <div class="text mb-2 text-uppercase title">Hóa đơn</div>
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span> <span class="font-12 text-muted"><i class="fa level"></i></span></h4>
                    <small class="text-muted name">Số hóa đơn</small>
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
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span> <span class="font-12 text-muted"><i class="fa level"></i> </span></h4>
                    <small class="text-muted name">Yêu cầu được hỗ trợ</small>
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
                    <h4 class="number mb-0 "><i class="fa fa-spinner fa-spin i-loading"></i><span class="value"></span> <span class="font-12 text-muted"><i class="fa level"></i></span></h4>
                    <small class="text-muted name">Tổng doanh thu</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix widgets-money">

    <div class="col-xl-3 col-lg-4 col-md-4 col-6 dt">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-danger"><i class="fa fa-shopping-cart"></i> </div>
                <div class="content">
                    <div class="text">Doanh thu phát sinh </div>
                    <h5 class="number">0</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-6 ps-thu">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-warning"><i class="fa fa-area-chart"></i> </div>
                <div class="content">
                    <div class="text">Phát sinh thu</div>
                    <h5 class="number">0</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-6 ps-chi">
        <div class="card top_counter">
            <div class="body">
                <div class="icon text-warning"><i class="fa fa-area-chart"></i> </div>
                <div class="content">
                    <div class="text">Phát sinh chi</div>
                    <h5 class="number">0</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-6 ck">
        <div class="card top_counter">
            <div class="body">
                <div class="icon"><i class="fa fa-tag"></i> </div>
                <div class="content">
                    <div class="text">Cuối kỳ</div>
                    <h5 class="number">0</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix row-deck">
    <div class="col-lg-4 col-md-12">
        <div class="card page-list-vps">
            <div class="header">
                <h2>Danh sách VPS</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);" class="reload"><i class="icon-refresh mr-2"></i>Tải lại</a></li>
                            <li><a href="<?php echo $CDWFunc->getUrl('index', 'manage-vps'); ?>">Xem tất cả</a></li>
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
    <div class="col-lg-8 col-md-12">
        <div class="card page-list-domain">
            <div class="header">
                <h2>Danh sách tên miền</h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);" class="reload"><i class="icon-refresh mr-2"></i>Tải lại</a></li>
                            <li><a href="<?php echo $CDWFunc->getUrl('index', 'manage-report'); ?>">Xem tất cả</a></li>
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
</div>