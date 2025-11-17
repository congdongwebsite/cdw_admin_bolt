<?php
global $CDWFunc;
$ids = $arg['ids'];
$customer_id = $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$total = 0;
$vat = 10;
?>
<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Chào Quý khách, Hosting của bạn <b>Sắp Hết Hạn</b> và đã được đưa vào trạng thái chờ xử lý.</p>


<table class="order" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="center">Thông Tin Gói</th>
      <th class="center">Thông Tin Hosting</th>
      <th class="center">Gia Hạn</th>
      <th class="center">Giá Tiền</th>

    </tr>
  </thead>
  <tbody>
    <?php 
       foreach ($ids as $id) {
        $hosting = get_post_meta($id, "type", true);
        $buy_date = get_post_meta($id, "buy_date", true);
        $expiry_date = get_post_meta($id, "expiry_date", true);
        $ip_hosting = get_post_meta($id, "ip", true);
        $ip_cpu = get_post_meta($hosting, "cpu", true);
        $ip_ram = get_post_meta($hosting, "ram", true);
        $ip_ssd = get_post_meta($hosting, "hhd", true);
        $price =  get_post_meta($id, "price", true);
        $total += $price;
        
        $buy_date = $CDWFunc->date->convertDateTimeDisplay($buy_date);
        $expiry_date = $CDWFunc->date->convertDateTimeDisplay($expiry_date);
        $price =  $CDWFunc->number->amountDisplay($price); 
    ?>
      <tr>
        <td>
          <?php echo get_the_title($hosting); ?><br><small>Ngày đăng ký: <b>   <?php echo $buy_date; ?> </b></small><br><small>Ngày hết hạn <b> <?php echo $expiry_date; ?></b></small>
        </td>
        <td><b>IP: </b><?php echo $ip_hosting; ?><br><small>CPU: <?php echo $ip_cpu; ?> - Ram: <?php echo $ip_ram; ?> - SSD: <?php echo $ip_ssd; ?></small></td>
        <td class="center">1 Năm</td>
        <td class="right"><?php echo $price; ?></td>
      </tr>
     <?php 
     }
     ?>
     <tr>
      <td colspan="3" class="right">Tạm tính:</td>
      <td class="right"><?php echo $CDWFunc->number->amountDisplay($total); ?></td>
    </tr>
    <tr>
      <td colspan="3" class="right">VAT:</td>
      <td class="right"><?php echo $CDWFunc->number->amount($vat); ?> %</td>
    </tr>
    <tr>
      <td colspan="3" class="right">
        <div>Tổng Tiền:</div>
      </td>
      <td class="right">
        <div><b><?php echo $CDWFunc->number->amountDisplay($total + ($vat/100 * $total)); ?></b></div>
      </td>
    </tr>
  </tbody>
</table>
<p>Cộng Đồng Web thông báo Hosting của bạn sắp hết hạn, để quá trình hệ thống sử dụng không bị ngắt quãng mong bạn có thể đăng nhập vào quản trị để duy trì và gia hạn hoá đơn trên trong thời gian sớm nhất.</p>
<table style="margin-bottom: 10px;">
  <thead>
    <tr>
      <th colspan="3">TÀI KHOẢN NGÂN HÀNG</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Tên Bank</td><td>CONG TY CO PHAN YOUNG PLUS</td>
      <tr><td>Ngân Hàng</td><td>MBBank</td></tr>
      <tr><td>Số Tài Khoản</td><td>11011.99.77.99.79</td></tr>  
      <tr><td>Nội dung</td><td>Thanh Toán Đơn Hàng <b id="order-cdw">#<?php echo $id; ?></b> </td></tr>
    </tr>
  </tbody>
</table>
<a class="botton-cdw" href="<?php echo $CDWFunc->getUrl('', '').'?urlredirect='.urlencode($CDWFunc->getUrl('hosting', 'client', ''));?>" target="_blank">Đăng Nhập </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket, Email: hotro@congdongweb.com hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>