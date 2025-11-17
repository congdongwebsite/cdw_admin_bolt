<?php
global $userCurrent, $CDWCart, $CDWFunc;
?>
<div class="client-cart">
    <?php wp_nonce_field('ajax-client-cart-nonce', 'nonce'); ?>
    <?php
    if ($CDWFunc->isAdministrator()) {
        $customer_id = get_user_meta($userCurrent->ID, 'customer-default-id', true);
        if ($customer_id) {
            $name = get_post_meta($customer_id, "name", true);
            if ($name) {
                ?>
                <div class="row clearfix">
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            Đang xem giỏ hàng của khách hàng: <strong><?php echo esc_html($name); ?></strong>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    <div class="row clearfix row-deck">
        <div class="col-lg-8">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th><button class="btn btn-light btn-sm btn-delete-all" title="Xóa giỏ hàng"><i class="fa fa-trash"></i></button></th>
                                            <th>#</th>
                                            <th>Dịch vụ</th>
                                            <th class="hidden-sm-down">Mô tả</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="hidden-sm-down text-center">Giá</th>
                                            <th class="text-center">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list-items">
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right actions">
                                <button class="btn btn-secondary btn-update mr-3">Cập nhật giỏ hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ">
            <div class="card profile-header">
                <div class="body h-100 d-flex flex-column justify-content-between align-items-center checkout">
                    
                </div>
            </div>
        </div>
    </div>
</div>