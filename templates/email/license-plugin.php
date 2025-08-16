<?php
global $CDWFunc;
$id = $arg['plugin-id'];
$customer_id =  $arg['customer-id'];
$name = get_post_meta($customer_id, 'name', true);
$date = $CDWFunc->date->convertDateTimeDisplay($arg['plugin-date']);
$date_expiry = $CDWFunc->date->convertDateTimeDisplay($arg['plugin-expiry-date']);

?>

<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Cộng Đồng Web gửi giấy phép: <b id="order-cdw"><?php echo $arg['title']; ?></b>
<p>Ngày mua: <b> <?php echo $date; ?> </b></p>

<table class="order" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th class="center">Plugin</th>
      <th class="center">Giấy phép</th>
      <th class="center">Hết hạn</th>
      <th class="center">Link download</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="center"><?php echo $arg['plugin-name']; ?></td>
      <td class="center"><?php echo $arg['plugin-license']; ?></td>
      <td class="center"><?php echo $date_expiry; ?></td>
      <td class="center"><a target="_blank" href="<?php echo $arg['plugin-url']; ?>">Tải về</a></td>
    </tr>
  </tbody>
</table>
<p style="text-align: center;"><strong>Cảm ơn Quý khách đã tin tưởng sử dụng dịch vụ của Cộng Đồng Web!</strong></p>
<p>Quý khách có thể theo dõi tình trạng và chi tiết trực tiếp trên website của chúng tôi.</p>
<a class="botton-cdw" href="<?php echo $CDWFunc->getUrl('', '').'?urlredirect='.urlencode($CDWFunc->getUrl('plugin', 'client'));?>" target="_blank">Đăng Nhập </a>
<p>Nếu cần hỗ trợ thêm thông tin, vui lòng phản hồi qua ticket này hoặc liên hệ Hotline <b>(+84) 38 627 0225</b>.</p>
<p>Xin chân thành cảm ơn Quý khách!</p>