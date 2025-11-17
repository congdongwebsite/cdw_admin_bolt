<?php
global $CDWFunc;
$customer_id = $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$customer_edit_url = $CDWFunc->getUrl('detail', 'customer', 'id=' . $customer_id);
?>

<p>Xin chào Quản trị viên,</p>
<p>Khách hàng <strong><?php echo $name; ?></strong> (ID: <?php echo $customer_id; ?>) vừa cập nhật thông tin cá nhân của họ.</p>
<p>Vui lòng truy cập vào trang quản trị để xem xét và xác thực thông tin (KYC) của khách hàng.</p>
<a class="botton-cdw" href="<?php echo $customer_edit_url; ?>" target="_blank">Xem Chi Tiết Khách Hàng</a>
<p>Trân trọng,</p>
<p>Hệ thống tự động.</p>
