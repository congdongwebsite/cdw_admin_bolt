<?php
global $CDWFunc;
$id = $arg['order-id'];
$customer_id = get_post_meta($id, "customer-id", true);
$name = get_post_meta($customer_id, 'name', true);
$checkoutStatus = get_post_meta($id, "status", true);
$date = $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, "date", true));

$items = get_post_meta($id, 'items', true);
$checkoutStatus = get_post_meta($id, "status", true);
$total = get_post_meta($id, 'amount', true);
$vat = get_post_meta($id, 'vat', true);
$final = $total + $vat;
$note = get_post_meta($id, "note", true);


?>

<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Cộng Đồng Web Đã Nhận Đơn Hàng Mã: <b id="order-cdw">[#<?php echo $id; ?>]</b> đặt hàng. Tình Trạng: <b> <?php echo $CDWFunc->get_lable_status($checkoutStatus); ?></b>
<p>Ngày đặt hàng: <b> <?php echo $date; ?> </b></p>

<table class="order" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="center">Stt</th>
      <th class="center">Sản Phẩm</th>
      <th class="center">Số Lượng</th>
      <th class="center">Giá Trị</th>
      <th class="center">Thành Tiền</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    if (isset($items) && is_array($items))
      foreach ($items as $p_id => $item) {
    ?>
      <tr id="id-<?php echo $p_id; ?>" class="item" data-item="<?php echo $p_id; ?>">
        <td class="index center"><?php echo $i++; ?></td>
        <td class="service left"><?php echo $item["service"]; ?><br /><small><?php echo $item["description"]; ?></small></td>
        <td class="quantity text-right center"><?php echo $CDWFunc->number->quantity($item["quantity"]); ?></td>
        <td class="price right" data-price="<?php echo (float) $item["price"]; ?>"><?php echo $CDWFunc->number->amount($item["price"]); ?></td>
        <td class="amount right" data-amount="<?php echo (float) $item["amount"]; ?>"><span><?php echo $CDWFunc->number->amount($item["amount"]); ?></span></td>
      </tr>
    <?php
      }
    ?>
    <tr>
      <td colspan="4" class="right">Tạm tính:</td>
      <td class="right"><?php echo $CDWFunc->number->amountDisplay($total); ?></td>
    </tr>
    <tr>
      <td colspan="4" class="right">VAT:</td>
      <td class="right"><?php echo $CDWFunc->number->amountDisplay($vat); ?></td>
    </tr>
    <tr>
      <td colspan="4" class="right">
        <div>Tổng Tiền:</div>
      </td>
      <td class="right">
        <div><b><?php echo $CDWFunc->number->amountDisplay($final); ?></b></div>
      </td>
    </tr>
    <tr>
      <td colspan="5">
       Ghi chú: <?php echo $note; ?>
      </td>
    </tr>
  </tbody>
</table>
<p style="text-align: center;"><strong>Cảm ơn Quý khách đã sử dụng dịch vụ của Cộng Đồng Web!</strong><br>
  Thank you for using the service with congdongweb.com!</p>
<p>Cộng Đồng Web nhận được yêu cầu của quý khách về việc mua sản phẩm dịch vụ chúng tôi về mã đơn: <b id="order-cdw">[#<?php echo $id; ?>]</b>, chúng tôi sẽ tiến hành xử lý và kích hoạt dịch vụ của bạn trong thời gian sớm nhất, bạn có thể theo giõi đơn hàng của bạn và thông tin đơn hàng qua website bên em để theo giõi.</p>
<a class="botton-cdw" href="https://www.congdongweb.com/admin/" target="_blank">Đăng Nhập </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket này hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>