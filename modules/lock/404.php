<?php
global $CDWFunc;
?>
<div class="card">
    <div class="header">
        <h3>
            <span class="clearfix title">
                <span class="number left">404</span> <span class="text">Oops! <br />Không tìm thấy dữ liệu yêu cầu</span>
            </span>
        </h3>
    </div>
    <div class="body">
        <p>Trang bạn tìm kiếm hiện tại<strong> không tìm thấy</strong>, vui lòng <a href="https://www.congdongweb.com/lien-he/">liên hệ</a> tới Adminstrator.</p>
        <div class="margin-top-30">
            <a href="javascript:history.go(-1)" class="btn btn-default"><i class="fa fa-arrow-left"></i> <span>Trờ về</span></a>
            <a href="<?php echo $CDWFunc->getUrl(''); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <span>Trang chủ</span></a>
        </div>
    </div>
</div>