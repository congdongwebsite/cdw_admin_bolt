<?php
global $CDWFunc, $CDWConst;

$id = isset($_GET["id"]) ? $_GET["id"] : -1;
$customer_id = get_post_meta($id, "customer-id", true);

$name = get_post_meta($customer_id, 'name', true);
$phone = get_post_meta($customer_id, 'phone', true);
$email = get_post_meta($customer_id, 'email', true);
$dvhc_tp = get_post_meta($customer_id, 'dvhc_tp', true);
$dvhc_qh = get_post_meta($customer_id, 'dvhc_qh', true);
$dvhc_px = get_post_meta($customer_id, 'dvhc_px', true);
$address = get_post_meta($customer_id, 'address', true);
$address .= $CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp);

$items = get_post_meta($id, 'items', true);
$checkoutStatus = get_post_meta($id, "status", true);
$total = get_post_meta($id, 'amount', true);
$sub_total = get_post_meta($id, 'sub_amount', true);
$has_vat = get_post_meta($id, 'has-vat', true);
$vat_percent = get_post_meta($id, 'vat-percent', true);
$vat = get_post_meta($id, 'vat', true);
$note = get_post_meta($id, "note", true);
$code = get_post_meta($id, "code", true);
$note = str_replace('\n', '<br>', $note);

// update_post_meta($idBilling, 'has-vat', $hasvat);
// update_post_meta($idBilling, 'vat-percent', $vat_percent);
// update_post_meta($idBilling, 'vat', $vat);

if (isset($_GET['id'])) $step = 2;
?>
<div class="client-checkout">
    <?php wp_nonce_field('ajax-client-checkout-nonce', 'nonce'); ?>
    <?php wp_nonce_field('ajax_momo_url_nonce', 'momo_nonce'); ?>
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

    <div class="row clearfix row-deck">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="body">
                    <h3>Chi tiết thanh toán : <strong class="text-primary">#<?php echo $id == -1 ? "Không tìm thấy hóa đơn" : get_post_meta($id, "code", true); ?></strong></h3>
                    <ul class="nav nav-tabs-new2">
                        <li class="nav-item inlineblock"><a class="nav-link active" data-toggle="tab" href="#details" aria-expanded="true">Chi tiết</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane in active" id="details" aria-expanded="true">
                            <div class="print">
                                <div class="row clearfix">
                                    <div class="col-md-8 col-sm-8 col-8">
                                        <?php
                                        require('billing-address.php');
                                        ?>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-4 text-right">
                                        <p class="mb-0"><strong>Ngày thanh toán: </strong><?php echo $CDWFunc->date->convertDateTimeBillingDisplay(get_post_meta($id, "date", true)); ?></p>
                                        <p class="mb-0"><strong>Trạng thái: </strong> <span class="badge <?php echo $checkoutStatus == "success" ? "badge-success" : ($checkoutStatus == "cancel" ? "badge-danger" : "badge-warning"); ?> mb-0"><?php echo $CDWFunc->get_lable_status($checkoutStatus); ?></span></p>
                                        <p><strong>ID: </strong> #<?php echo $code; ?></p>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Dịch vụ</th>
                                                        <th class="hidden-sm-down">Mô tả</th>
                                                        <th class="text-center">Số năm</th>
                                                        <th class="hidden-sm-down text-center">Giá</th>
                                                        <th class="text-center">Thành tiền</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    if (isset($items) && is_array($items))
                                                        foreach ($items as $p_id => $item) {
                                                    ?>
                                                        <tr id="id-<?php echo $p_id; ?>" class="item" data-item="<?php echo $p_id; ?>">
                                                            <td class="index"><?php echo $i++; ?></td>
                                                            <td class="service"><?php echo $item["service"]; ?></td>
                                                            <td class="description hidden-sm-down"><?php echo $item["description"]; ?></td>
                                                            <td class="quantity text-right"><?php echo $CDWFunc->number->quantity($item["quantity"]); ?></td>
                                                            <td class="price hidden-sm-down text-right" data-price="<?php echo (float) $item["price"]; ?>"><?php echo $CDWFunc->number->amount($item["price"]); ?></td>
                                                            <td class="amount text-right" data-amount="<?php echo (float) $item["amount"]; ?>"><span><?php echo $CDWFunc->number->amount($item["amount"]); ?></span></td>
                                                        </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <h5>Ghi chú</h5>
                                        <p><?php echo $note; ?></p>

                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p class="mb-0 sub-total" data-total="<?php echo $sub_total; ?>"><b>Tổng tiền:</b> <span><?php echo  $CDWFunc->number->amount($sub_total); ?></span></p>
                                        <p class="mb-0">Giảm giá: 0</p>

                                        <div class="d-flex justify-content-end">
                                            <p class="mb-0 vat" data-vat="<?php echo $vat; ?>"><b>VAT:</b> <span><?php echo  $CDWFunc->number->amount($vat); ?></span></p>
                                        </div>
                                        <h3 class="mb-0 m-t-10 total" data-total="<?php echo $total; ?>">Tổng cộng: <span><?php echo  $CDWFunc->number->amount($total); ?></span></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="hidden-print col-md-12 text-right">

                                    <span class="mb-0 text-danger"><strong>Chú ý:</strong> Tổng tộng được tính tạm thời và được tính lại sau khi bạn ấn vào nút thanh toán. **Hóa đơn sẽ được tải lại sau khi ấn thanh toán.</span>
                                    <hr>

                                </div>
                                <?php
                                if (($checkoutStatus == 'pending' || $checkoutStatus == 'publish') && $checkoutStatus != 'success' && $checkoutStatus != 'cancel') {
                                    require('billing-payment.php');
                                }
                                ?>
                            </div>
                            <div class="row clearfix">
                                <div class="hidden-print col-md-12 text-right">
                                    <?php
                                    if (($checkoutStatus == 'pending' || $checkoutStatus == 'publish') && $checkoutStatus != 'success' && $checkoutStatus != 'cancel' && !empty($checkoutStatus)) {
                                    ?>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" value="" id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">
                                                Tôi xác nhận đã đọc và chấp nhận với các <a style=" color: blue; " href="https://www.congdongweb.com/dieu-khoan-su-dung/" target="_blank">Điều khoản sử dụng dịch vụ</a>, <a style=" color: blue; " href="https://www.congdongweb.com/chinh-sach-thu-thap-va-xu-ly-du-lieu-ca-nhan/" target="_blank">Chính sách quyền riêng tư</a>
                                            </label>
                                            <div class="invalid-feedback">
                                                Bạn phải chấp nhận các điều khoản và chính sách để tiếp tục.
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <button class="btn btn-outline-secondary btn-print"><i class="icon-printer"></i></button>
                                    <?php
                                    if ($checkoutStatus != 'cancel' && $checkoutStatus != 'success' && !empty($checkoutStatus)) {
                                    ?>
                                        <button class="btn btn-danger btn-cancel">Hủy</button>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (($checkoutStatus == 'pending' || $checkoutStatus == 'publish') && $checkoutStatus != 'success' && $checkoutStatus != 'cancel' && !empty($checkoutStatus)) {
                                    ?>
                                        <button class="btn btn-primary btn-checkout-payment">Thanh toán</button>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>