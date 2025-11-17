<?php
global $CDWFunc, $blogInfo, $CDWTicket;
$id = $arg['ticket-id'];
$ticket_archive_current =  get_post_meta($id, 'status', true);
$name =  $blogInfo->name;
$date = $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date', true));
$loaiticket = [];
$content = get_the_content(null, false, $id);
$types = get_post_meta($id, 'type');
$defaultType = $CDWTicket->getDefaultType();
foreach ($types as $value) {
    $type = $defaultType[$value];
    $loaiticket[] = $type['text'];
}
$loaiticket = implode(", ", $loaiticket);
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
<p>Hỗ Trợ Ticket <b id="order-cdw">[<?php echo $id; ?>] - <?php echo $loaiticket;  ?> </b> Tình Trạng Ticket <b> <?php echo $trangthai; ?> </b><br />
    Được mở ngày <b> <?php echo $date; ?> </b></p>
<div class="mess-ticket">
    <?php echo $content; ?>
</div>
<a class="botton-cdw" href="<?php echo $CDWFunc->getURL('detail', 'ticket', 'id=' . $id); ?>" target="_blank">Quản Lý Ticket </a>
<p>Quý khách vui lòng phản hồi cho chúng tôi qua Ticket này hoặc theo Hotline (+84) 38.627.0225 trường hợp cần hỗ trợ thêm thông tin. Cảm ơn Quý khách!</p>