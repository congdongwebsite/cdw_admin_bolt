<?php
global $CDWFunc, $CDWConst, $CDWCart;

$customer_id = $CDWFunc->getCustomer();

$name = get_post_meta($customer_id, 'name', true);
$phone = get_post_meta($customer_id, 'phone', true);
$email = get_post_meta($customer_id, 'email', true);
$dvhc_tp = get_post_meta($customer_id, 'dvhc_tp', true);
$dvhc_qh = get_post_meta($customer_id, 'dvhc_qh', true);
$dvhc_px = get_post_meta($customer_id, 'dvhc_px', true);
$address = get_post_meta($customer_id, 'address', true);
$address .= $CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp);

$items = $CDWCart->get();
$total = $CDWCart->getTotal();
$note = $CDWCart->getNote();
$tax = $CDWCart->getTax();
$total = $total->amount;
$total = $total ? $total : 0;
$vat_percent =  $CDWConst->vatPercent;
if ($tax->has) {
    $note  .= 'Thông tin xuất HD:&#13;Tên công ty: '.$tax->company . '&#13;Mã số thuế: ' . $tax->code . '&#13;Email: ' . $tax->email ;
}
?>
<div class="client-checkout">
    <?php wp_nonce_field('ajax-client-checkout-nonce', 'nonce'); ?>
    <div class="row clearfix row-deck">
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <h3>Chi tiết thanh toán</h3>
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
                                        <p class="mb-0"><strong>Ngày thanh toán: </strong><?php echo $CDWFunc->date->getCurrentDateTime('Y-m-d H:i:s'); ?></p>
                                        <p class="mb-0"><strong>Trạng thái: </strong> <span class="badge  badge-warning mb-0">Khởi tạo</span></p>
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

                                                            <td class="quantity text-right"><input type="number" name="quantity" style="width: 80px;" min="1" step="1" id="quantity" value="<?php echo $item["quantity"]; ?>" class="form-control text-right m-auto"></td>

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
                                        <textarea class="form-control notes"><?php echo $note; ?></textarea>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p class="mb-0 sub-total" data-total="<?php echo $total; ?>"><b>Tổng tiền:</b> <span><?php echo  $CDWFunc->number->amount($total); ?></span></p>
                                        <p class="mb-0">Giảm giá: 0</p>

                                        <div class="d-flex justify-content-between">

                                            <div class="fancy-checkbox text-danger">

                                                <label><input class="chk-vat" disabled checked type="checkbox" data-percent="<?php echo $vat_percent; ?>"><span>Thêm VAT <?php echo  $CDWFunc->number->percent($vat_percent); ?>%</span></label>

                                            </div>
                                            <p class="mb-0 vat"><b>VAT:</b> <span><?php echo  $CDWFunc->number->amount(($total * ($vat_percent / 100))); ?></span></p>
                                        </div>
                                        <h3 class="mb-0 m-t-10 total" data-total="<?php echo $total; ?>">Tổng cộng: <span><?php echo  $CDWFunc->number->amount($total + ($total * ($vat_percent / 100))); ?></span></h3>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="hidden-print col-md-12 text-right">
                                        <span class="mb-0 text-danger"><strong>Chú ý:</strong> Tổng tộng được tính tạm thời và được tính lại sau khi bạn ấn vào nút thanh toán. **Hóa đơn sẽ được tải lại sau khi ấn thanh toán.</span>
                                        <hr>

                                    </div>
                                    <?php
                                    require('billing-payment.php');
                                    ?>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="hidden-print col-md-12 text-right">

                                    <button class="btn btn-outline-secondary btn-print"><i class="icon-printer"></i></button>
                                    <button class="btn btn-primary btn-checkout">Thanh toán</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>