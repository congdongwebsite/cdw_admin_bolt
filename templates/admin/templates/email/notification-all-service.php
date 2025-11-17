<?php
global $CDWFunc;
$ids = $arg['ids'];
$customer_id = $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$total = 0;
$vat = 10;
function get_label_post_type($post_type)
{
  $labels = get_post_type_object($post_type);
  return $labels->label;
}
?>
<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Chào Quý khách, Các dịch vụ của bạn <b>Sắp Hết Hạn</b> và đã được đưa vào trạng thái chờ xử lý.</p>


<table class="order" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="center">Loại dịch vụ</th>
      <th class="center">Thông Tin Gói</th>
      <th class="center">Gia Hạn</th>
      <th class="center">Giá Tiền</th>

    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($ids as $id) {

      $type = get_post_type($id);
      if (empty($type) || $type == 'customer-theme') continue;
      $type_label = get_label_post_type($type);

      $buy_date = get_post_meta($id, "buy_date", true);
      $expiry_date = get_post_meta($id, "expiry_date", true);
      $price =  get_post_meta($id, "price", true);
      $total += $price;

      $buy_date = $CDWFunc->date->convertDateTimeDisplay($buy_date);
      $expiry_date = $CDWFunc->date->convertDateTimeDisplay($expiry_date);
      $price =  $CDWFunc->number->amountDisplay($price);

      $name = 'Chưa xác định';
      $info  = '';
      switch ($type) {
        case "customer-domain":
          $name = get_post_meta($id, 'url', true) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
          break;
        case "customer-theme":
          $site_type = get_post_meta($id, 'site-type', true);
          $name = get_the_title($site_type);
          break;
        case "customer-email":
          $name = get_the_title(get_post_meta($id, 'email-type', true)) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
          break;
        case "customer-plugin":
          $plugin_type = get_post_meta($id, 'plugin-type', true);
          $license = get_post_meta($id, 'license', true);
          $name = get_the_title($plugin_type) . ' - [' . $license . ']';
          break;
        case "customer-hosting":
          $hosting = get_post_meta($id, "type", true);
          $ip_hosting = get_post_meta($id, "ip", true);
          $ip_cpu = get_post_meta($hosting, "cpu", true);
          $ip_ram = get_post_meta($hosting, "ram", true);
          $ip_ssd = get_post_meta($hosting, "hhd", true);
          $name = get_the_title(get_post_meta($id, 'type', true)) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
          $info = '<b>IP: </b>' . $ip_hosting . '<br><small>CPU: ' . $ip_cpu . ' - Ram: ' . $ip_ram . ' - SSD: ' . $ip_ssd . '</small>';
          break;
      }
    ?>
      <tr>
        <td><?php echo $type_label; ?></td>
        <td>
          <?php echo $name; ?>
          <?php echo $info; ?>
        </td>
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
        <div><b><?php echo $CDWFunc->number->amountDisplay($total + ($vat / 100 * $total)); ?></b></div>
      </td>
    </tr>
  </tbody>
</table>
<p>Cộng Đồng Web thông báo các dịch vụ của bạn sắp hết hạn, để quá trình hệ thống sử dụng không bị ngắt quãng mong bạn có thể đăng nhập vào quản trị để duy trì và gia hạn hoá đơn trên trong thời gian sớm nhất.</p>
<a class="botton-cdw" href="<?php echo $CDWFunc->getUrl('', '').'?urlredirect='.urlencode($CDWFunc->getUrl('all-service', 'client', ''));?>" target="_blank">Đăng Nhập </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket, Email: hotro@congdongweb.com hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>