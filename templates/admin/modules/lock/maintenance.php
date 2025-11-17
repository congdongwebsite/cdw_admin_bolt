<?php
global $CDWFunc;
?>
<div class="card">
    <div class="header">
        <h1>Chùng tôi sẽ trở lại sớm!</h1>
    </div>
    <div class="body">
        <p>Xin lỗi vì sự cố này.<br> Chúng tôi đang bảo trì hệ thống, sẽ mất vài phút để hệ thống hoạt động trở lại bình thường.<br> Nếu bạn cần gấp vui lòng liên hệ <a href="https://www.congdongweb.com/lien-he/">Liên hệ</a>, hoặc chờ cho tới khi chúng tôi hoạt động trở lại!</p>
        <p>&mdash; CongDongWeb</p>
        <div class="margin-top-30">
            <a href="javascript:history.go(-1)" class="btn btn-default"><i class="fa fa-arrow-left"></i>
                <span>Trở lại</span></a>
            <a href="<?php echo $CDWFunc->getUrl('', ''); ?>" class="btn btn-info"><i class="fa fa-home"></i> <span>Trang chủ</span></a>
        </div>
    </div>
</div>