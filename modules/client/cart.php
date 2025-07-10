<?php
global $userCurrent, $CDWCart, $CDWFunc;
?>
<div class="client-cart">
    <?php wp_nonce_field('ajax-client-cart-nonce', 'nonce'); ?>
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
                                            <th></th>
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
                                <button class="btn btn-danger btn-delete-all">Xóa giỏ hàng</button>
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