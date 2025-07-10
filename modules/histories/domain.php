<?php

$id_detail = $_GET['id'];
$histories = get_post_meta($id_detail, 'histories', true);
if (!isset($histories) || !is_array($histories)) $histories = [];
?>
<div class="card">
    <div class="body">
        <?php
        foreach ($histories as $history) {
            switch ($history["type"] ?? '') {
                case "success":
        ?>

                    <div class="timeline-item green" date-is="<?php echo $history["date"]; ?>">
                        <h5><?php echo $history["title"] ?? ''; ?> - Bằng hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $history["parent"]); ?>">#<?php echo get_post_meta($history["parent"], 'code', true); ?></a></h5>
                        <span><a href="javascript:void(0);"><?php echo $history["subtitle"] ?? ''; ?></a></span>
                        <div class="msg">
                            <p><?php echo $history["note"]; ?></p>
                        </div>
                    </div>
                <?php
                    break;
                case "pendding":
                    break;
                case "error":

                ?>

                    <div class="timeline-item warning" date-is="<?php echo $history["date"]; ?>">
                        <h5><?php echo $history["title"] ?? ''; ?> - Bằng hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $history["parent"]); ?>">#<?php echo get_post_meta($history["parent"], 'code', true); ?></a></h5>
                        <span><a href="javascript:void(0);"><?php echo $history["subtitle"] ?? ''; ?></a></span>
                        <div class="msg">
                            <p><?php echo $history["note"]; ?></p>
                        </div>
                    </div>
                <?php
                    break;
                default:
                ?>
                    <div class="timeline-item green" date-is="<?php echo $history["date"]; ?>">
                        <h5><?php echo $history["title"] ?? ''; ?> - Bằng hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $history["parent"]); ?>">#<?php echo get_post_meta($history["parent"], 'code', true); ?></a></h5>
                        <span><a href="javascript:void(0);"><?php echo $history["subtitle"] ?? ''; ?></a></span>
                        <div class="msg">
                            <p><?php echo $history["note"]; ?></p>
                        </div>
                    </div>
            <?php
                    break;
            }
            ?>

        <?php
        }
        ?>

    </div>
</div>