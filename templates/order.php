<?php
/*
 Template Name: View Order
 */
get_header();
$order_id = (int) get_query_var('order-id');

if (empty($order_id)) echo "Không tìm thấy đơn hàng";
if (!is_user_logged_in()) echo "Vui lòng đăng nhập";
$customer_id = get_post_meta($order_id, "customer-id", true);
$user_id = get_post_meta($customer_id, "user-id", true);

// if (get_current_user_id() !== $user_id) echo "Không tồn tại đơn hàng";

$name = get_post_meta($customer_id, 'name', true);
$phone = get_post_meta($customer_id, 'phone', true);
$email = get_post_meta($customer_id, 'email', true);
$dvhc_tp = get_post_meta($customer_id, 'dvhc_tp', true);
$dvhc_qh = get_post_meta($customer_id, 'dvhc_qh', true);
$dvhc_px = get_post_meta($customer_id, 'dvhc_px', true);
$address = get_post_meta($customer_id, 'address', true);
$address .=  ", " .$CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp);


$sub_total = get_post_meta($order_id, 'sub_amount', true);
$total = get_post_meta($order_id, 'amount', true);
$note = get_post_meta($order_id, "note", true);
$items = get_post_meta($order_id, 'items', true);
$code_order = get_post_meta($order_id, "code", true);
$checkoutStatus = get_post_meta($order_id, "status", true);
$vat = get_post_meta($order_id, 'vat', true);
$bank = 'bank-online'; //get_bank_order($id);
?>
<div class="layout-order">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<?php
				switch ($bank) {
					case 'mo-mo':
				?>
						<div class="col-inner">
							<div class="header-order">
								<img src="<?php get_home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="thanh toán đơn hàng">
								<img src="<?php get_home_url(); ?>/wp-content/uploads/2022/07/icon-momo.png" alt="thanh toán đơn hàng">
							</div>
							<div class="content-order">
								<p class="order-title">Quét mã để thanh toán</p>
								<img src="<?php get_home_url(); ?>/wp-content/uploads/2022/07/ma-code-momo.jpg">
								<p class="order-title">Momo: 038 627 0225</p>
							</div>
						</div>
					<?php
						break;
					case 'bank-online':

					?>
						<div class="col-inner">
							<div class="header-order">
								<img src="<?php get_home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="thanh toán đơn hàng">
								<p>
									Thanh Toán Qua Ngân Hàng (Internet Banking)
								</p>
							</div>
							<div class="content-order bank">
								<table class="table-infomation" cellpadding="2" style="margin-bottom: 20px;">
									<tbody>
										<tr>
											<td><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bank/logo-mbbank.png" alt="ngân hàng quân đội mbbank" width="100px"></td>
											<td>Thanh Toán Công Ty</td>
										</tr>
										<tr>
											<td>Ngân Hàng</td>
											<td>Thương Mại Cổ Phần Quân Đội - MBBank</td>
										</tr>
										<tr>
											<td>Số Tài Khoản</td>
											<td>11011.99.77.99.79</td>
										</tr>
										<tr>
											<td>Tên Ngân Hàng</td>
											<td>CONG TY CO PHAN YOUNG PLUS</td>
										</tr>
										<tr>
											<td>Ghi Chú:</td>
											<td><strong>Thanh toán đơn hàng <strong><?php echo $code_order; ?></strong></td>
										</tr>
									</tbody>
								</table>
								<table class="table-infomation" cellpadding="2">
									<tbody>
										<tr>
											<td><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bank/logo-acb.png" alt="ngân hàng thương mại cổ phần á châu" width="100px"></td>
											<td>Thanh Toán Cá Nhân Đại Diện</td>
										</tr>
										<tr>
											<td>Ngân Hàng</td>
											<td>Thương Mại Cổ Phần Á Châu - ACB</td>
										</tr>
										<tr>
											<td>Số Tài Khoản</td>
											<td>256099359</td>
										</tr>
										<tr>
											<td>Tên Ngân Hàng</td>
											<td>NGUYEN TRUNG THONG</td>
										</tr>
										<tr>
											<td>Ghi Chú:</td>
											<td><strong>Thanh toán đơn hàng <strong><?php echo $code_order; ?></strong></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					<?php
						break;
					default:
					?>
						<div class="col-inner">
							<div class="header-order">
								<img src="<?php get_home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="thanh toán đơn hàng">
								<p>
									Gọi cho tôi
								</p>
							</div>
						</div>

				<?php
						break;
				}
				?>

			</div>
			<div class="col-md-7 ">
				<div class="col-inner">
					<div class="header-order services-name">
						<h3>
							Cảm ơn quý khách đã đặt hàng!
						</h3>
						<div class="ma-don-oder">
							Mã Đơn: <strong><?php echo $code_order; ?></strong>
						</div>
					</div>
					<div class="services-name text-center">
						<div class="note-title mt-3 alert <?php echo $checkoutStatus == "success" ? "alert-success" : ($checkoutStatus == "cancel" ? "alert-danger" : "alert-warning"); ?>">Trạng Thái: <span><?php echo $CDWFunc->get_lable_status($checkoutStatus); ?></span></div>
					</div>
					<ul>
						<li>Họ tên: <strong><?php echo $name; ?></strong></li>
						<li>Số điện thoại: <strong><?php echo $phone; ?></strong></li>
						<li>Email: <strong><?php echo $email; ?></strong></li>
						<li>Địa chỉ: <strong><?php echo $address; ?></strong></li>
					</ul>
					<div class="basket order-template">
						<table class="table-infomation basket mb-3" cellpadding="5">
							<thead>
								<tr>
									<th class="item text-center">Stt</th>
									<th class="item text-center">Sản phẩm</th>
									<th class="quantity text-center">Số lượng</th>
									<th class="price text-center">Giá trị</th>
									<th class="subtotal text-center sub-services">Thành tiền</th>
								</tr>
							</thead>
							<tbody class="cartItems">
								<?php
								$stt = 1;
								foreach ($items as $key => $item) {
								?>
									<tr>
										<td class="item text-center"><?php echo $stt; ?></td>
										<td class="item "><?php echo $item["service"]; ?></td>
										<td class="quantity text-center"><?php echo $CDWFunc->number->amount($item["quantity"]); ?></td>
										<td class="price text-right"><?php echo $CDWFunc->number->amount($item["price"]); ?></td>
										<td class="subtotal text-right "><?php echo $CDWFunc->number->amount($item["amount"]); ?></td>
									</tr>
								<?php
								}
								?>

							</tbody>
							<tfoot>
								<tr class="summary-subtotal">
									<td class="item text-right" colspan="4">Tạm tính:</td>
									<td class="item text-right "><?php echo  $CDWFunc->number->amount($sub_total); ?></td>
								</tr>
								<tr class="summary-subtotal">
									<td class="item text-right" colspan="4">VAT:</td>
									<td class="item text-right "><?php echo  $CDWFunc->number->amount($vat); ?></td>
								</tr>
								<tr class="summary-subtotal">
									<td class="item text-right" colspan="4">Thành tiền:</td>
									<td class="item text-right sub-services"><?php echo  $CDWFunc->number->amount($total); ?></td>
								</tr>
								<tr class="summary-subtotal">
									<td class="item" colspan="5"><strong>Ghi chú đơn hàng: </strong><?php echo $note; ?></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<style>
	p.order-title {
		color: #af2070;
		font-weight: bold;
		margin-bottom: 0;
	}

	.content-order {
		width: 60%;
		margin: auto;
		text-align: center;
	}

	.layout-order .container {
		padding: 30px 0;
	}

	.header-order {
		display: flex;
		border-bottom: 1px dashed #cccc;
		width: 100%;
		vertical-align: middle;
		place-content: space-between;
		padding-bottom: 20px;
		align-items: center;
	}

	.content-order.bank {
		width: 100%;
		margin-top: 10px;
		text-align: left;
	}

	.content-order.bank .table-infomation tr td:nth-child(1) {
		width: 30%;
	}

	.ma-don-oder {
		background: #a60202;
		color: #f3ce16;
		font-weight: bold;
		font-size: 15px;
		padding: 5px 10px;
		border-radius: 5px;
	}

	.layout-order .col-inner {
		background: #ffff;
		-webkit-box-shadow: 0 0 44px rgb(144 151 179 / 19%);
		box-shadow: 0 0 44px rgb(144 151 179 / 19%);
		padding: 20px;
		border-radius: 20px;
	}

	.header-order {
		display: flex;
		width: 100%;
		vertical-align: middle;
		place-content: space-between;
	}

	.header-order img {
		width: 40px;
	}
</style>
<?php wp_nonce_field('ajax_momo_url_nonce', 'momo_nonce'); ?>
<script>
jQuery(document).ready(function($) {
    const params = new URLSearchParams(window.location.search);
    const orderId = params.get('orderId');
    const resultCode = params.get('resultCode');

    if (orderId && resultCode) {
        const notice = $('<div class="momo-update-notice">Đang cập nhật trạng thái đơn hàng...</div>');
        $('.ma-don-oder').after(notice); // Display notice

        $.ajax({
            type: 'POST',
            url: cdwObjects.ajax_url,
            data: {
                action: 'ajax_momo_url_result',
                nonce: $('#momo_nonce').val(),
                momo_params: window.location.search,
            },
            success: function(response) {
                if (response.success) {
                    notice.text('Cập nhật thành công: ' + response.data.msg).addClass('alert alert-success');
                } else {
                    notice.text('Lỗi: ' + response.data.msg).addClass('alert alert-danger');
                }
                // Clean the URL in the browser history
                window.history.replaceState({}, document.title, window.location.pathname);
                // Reload to show updated status cleanly after a delay
                setTimeout(() => window.location.reload(), 2000);
            },
            error: function() {
                notice.text('Lỗi không xác định. Vui lòng liên hệ hỗ trợ.').addClass('alert alert-danger');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    }
});
</script>
<?php get_footer(); ?>