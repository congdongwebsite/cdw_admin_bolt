<address>
    <?php
    if ($CDWFunc->isAdministrator()) {
    ?>
        <strong>Họ và tên: </strong><a href="<?php echo $CDWFunc->getURL('detail', 'customer', 'id=' . $customer_id); ?>"><?php echo $name; ?></a><br>
    <?php
    } else {
    ?>
        <strong>Họ và tên: </strong><?php echo $name; ?><br>
    <?php
    }
    ?>
    <strong>Địa chỉ: </strong><?php echo $address; ?><br>
    <strong>Email:</strong> <?php echo $email; ?><br>
    <strong>Điện thoại:</strong> <?php echo $phone; ?>
</address>