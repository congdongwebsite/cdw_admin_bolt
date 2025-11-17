<?php
global $CDWFunc, $userCurrent;
?>
<div class="client-email-choose file_manager">
    <?php wp_nonce_field('ajax-client-email-choose-nonce', 'nonce'); ?>
    <div class="row clearfix">
        <?php
        $arr = array(
            'post_type' => 'email',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1,
            'meta_key' => 'stt',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );
        $ids = get_posts($arr);
        foreach ($ids as $id) {
            $feature = get_post_meta($id, 'feature', true);
            $account = get_post_meta($id, 'account', true);
            $hhd = get_post_meta($id, 'hhd', true);
            $price = get_post_meta($id, 'gia', true);

            $class = 'secondary';
            $text_feature = '';
            switch ($feature) {
                case 'goi-pho-bien':
                    $class = 'primary';
                    $text_feature = 'Yêu thích nhất';
                    break;
                case 'goi-cao-cap':
                    $class = 'danger';
                    $text_feature = 'Server Riêng';
                    break;
            }
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 item-email" data-hid="<?php echo $id; ?>">
                <div class="card pricing3">
                    <div class="d-flex justify-content-end p-1 position-absolute position-right"><small class="badge badge-<?php echo $class ?> "><?php echo $text_feature; ?></small></div>
                    <div class="body">
                        <div class="pricing-option p-0 pt-2">
                            <i class="fa fa-server text-<?php echo $class ?>" aria-hidden="true"></i>
                            <h5><?php echo get_the_title($id); ?></h5>
                            <small><?php echo get_post_meta($id, 'sub-title', true); ?> </small>
                            <hr>
                            <div class="m-t-30 m-b-30">
                                <span>CPU: <?php echo -1 == $account ? "Tùy chỉnh" : $account . " Cores"; ?> </span><br>
                                <span>Dung lượng: <?php echo -1 == $hhd ? "Tùy chỉnh" : $hhd . "MB SSD"; ?></span><br>
                                <span>Bandwidth: không giới hạn</span><br>
                                <span>Backup: hàng ngày</span><br>
                                <span><?php echo get_post_meta($id, 'note', true); ?></span>
                            </div>
                            <div class="price">
                                <span class="price"><?php echo $price == -1 ? "Liên Hệ" : number_format($price, 0, ',', '.') . "đ"; ?></span>
                            </div>
                            <a href="javascript:void(0)" data-hid="<?php echo $id; ?>" class="btn-choose btn  btn-outline-<?php echo $class ?>">Mua Ngay</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>
</div>