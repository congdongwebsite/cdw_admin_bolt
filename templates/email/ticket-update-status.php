<?php
global $CDWFunc, $blogInfo;
$id = $arg['ticket-id'];
$ticket_archive_current =  get_post_meta($id, 'status', true);
$name =  $blogInfo->name;
$date = $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date-update', true));
$trangthai = '';
switch ($ticket_archive_current) {
    case 'pending':
        $trangthai = 'Yêu Cầu Mới';
        break;
    case 'success':
    case 'archive':
        $trangthai = 'Đã xử lý';
        break;
    case 'trash':
        $trangthai = 'Đã xoá';
        break;
}
?>
<p>Kính gửi: <strong><?php echo $name; ?></strong></p>
<p>Hỗ trợ <b id="order-cdw">[<?php echo $id; ?>] của bạn đã cập nhật Tình Trạng <?php echo $trangthai; ?> </b><br />
    Được cập nhật ngày: <b> <?php echo $date; ?> </b></p>
<a class="botton-cdw" href="<?php echo $CDWFunc->getURL('detail', 'ticket', 'id=' . $id); ?>" target="_blank">Quản Lý Ticket </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket này hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>