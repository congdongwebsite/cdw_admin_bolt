<?php
global $CDWFunc;
$user_id  =  $arg['user-id'];
$password  =  $arg['password'];
$user_data = $CDWFunc->wpdb->get_info_user($user_id);

?>

<p>Kính gửi: <strong><?php echo $user_data->name; ?></strong></p>
<p>
  Chào quý khách, bạn vừa chọn khôi phục mật khẩu, thông tin của bạn
  đang đề cập phía dưới. Nếu đây không phải yêu cầu của bạn thì vui lòng
  bạn hãy đăng nhập và đổi mật khẩu để tăng cường bảo mật tài khoản của
  bạn.
</p>

<table cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th>Tài khoản đăng nhập</th>
      <th>Mật khẩu</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $user_data->username; ?></td>
      <td><?php echo $password; ?></td>
    </tr>
  </tbody>
</table>
<p style="text-align: center">
  <strong>Cảm ơn Quý khách đã sử dụng dịch vụ của Cộng Đồng Web!</strong><br />
  Thank you for using the service with congdongweb.com!
</p>
<a class="botton-cdw" style="    margin: auto;display: table;" href="<?php echo $CDWFunc->getUrl('', ''); ?>" target="_blank">Đăng Nhập
</a>
<p>
  Quý khách vui lòng phản hồi cho chúng tôi qua Ticket, Email:
  hotro@congdongweb.com hoặc theo Hotline (+84) 38.627.0225 trường hợp
  cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!
</p>