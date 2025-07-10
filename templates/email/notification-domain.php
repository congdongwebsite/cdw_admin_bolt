<?php
global $CDWFunc;
$ids = $arg['ids'];
$customer_id = $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$total = 0;
$vat = 10;
?>
<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Chào Quý khách, domain của bạn <b>Sắp Hết Hạn</b> và đã được đưa vào trạng thái chờ xử lý.</p>


<table class="order" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="center">Thông Tin Sản Phẩm</th>
      <th class="center">Dịch vụ</th>
      <th class="center">Gia Hạn</th>
      <th class="center">Giá Tiền</th>

    </tr>
  </thead>
  <tbody>
    <?php 
       foreach ($ids as $id) {
        $domain = get_post_meta($id, "url", true);
        $buy_date = get_post_meta($id, "buy_date", true);
        $expiry_date = get_post_meta($id, "expiry_date", true);
        $domain_type = get_post_meta($id, "domain-type", true);
        $price =  get_post_meta($domain_type, "gia_han", true);
        $total += $price;
        
        $buy_date = $CDWFunc->date->convertDateTimeDisplay($buy_date);
        $expiry_date = $CDWFunc->date->convertDateTimeDisplay($expiry_date);
        $price =  $CDWFunc->number->amountDisplay($price); 
    ?>
      <tr>
        <td>
          <?php echo $domain; ?><br><small>Ngày đăng ký: <b>   <?php echo $buy_date; ?> </b></small><br><small>Ngày hết hạn <b> <?php echo $expiry_date; ?></b></small>
        </td>
        <td>Domain</td>
        <td>1 Năm</td>
        <td>   <?php echo $price; ?></td>
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
<p>Cộng Đồng Web thông báo domain của bạn sắp hết hạn, để quá trình hệ thống sử dụng không bị ngắt quãng mong bạn có thể đăng nhập vào quản trị để duy trì và gia hạn hoá đơn trên trong thời gian sớm nhất.</p>
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
<a class="botton-cdw" href="https://www.congdongweb.com/admin/" target="_blank">Đăng Nhập </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket, Email: hotro@congdongweb.com hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>