<?php
global $CDWFunc;
$customer_id = $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$reason = $arg['reason'];
?>

<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Chúng tôi rất tiếc phải thông báo rằng yêu cầu xác thực tài khoản (KYC) của Quý khách chưa được phê duyệt.</p>
<p><strong>Lý do từ chối:</strong></p>
<div class="mess-ticket">
    <p><?php echo $reason; ?></p>
</div>
<p>Để hoàn tất quá trình xác thực, Quý khách vui lòng đăng nhập vào tài khoản và cập nhật lại thông tin theo hướng dẫn.</p>
<a class="botton-cdw" href="<?php echo $CDWFunc->getUrl('setting', 'profile'); ?>" target="_blank">Cập Nhật Thông Tin</a>
<p>Nếu cần hỗ trợ thêm, vui lòng phản hồi qua ticket hoặc liên hệ Hotline <b>(+84) 38 627 0225</b>.</p>
<p>Xin chân thành cảm ơn!</p>
