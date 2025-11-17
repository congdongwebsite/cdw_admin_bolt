<?php
/*
 Template Name: Thanh Toán
 */
global $current_user, $CDWFunc, $CDWCart;

$useremail = $current_user->user_email;
$username = $current_user->user_login;

$firstname = $current_user->user_firstname;
$lastname = $current_user->user_lastname;
$display_name = $current_user->display_name;

$avatar = get_field('user_avatar', 'user_' . $current_user->ID);
$user_phone = get_user_meta($current_user->ID, 'phone',  true);
$user_address = get_user_meta($current_user->ID, 'address', true);

$dvhc_tp = get_user_meta($current_user->ID, 'dvhc_tp', true);
$dvhc_qh = get_user_meta($current_user->ID, 'dvhc_qh', true);
$dvhc_px = get_user_meta($current_user->ID, 'dvhc_px', true);
$user_address .= ", " .$CDWFunc->getPX($dvhc_px) . ", " . $CDWFunc->getQH($dvhc_qh)  . ", " . $CDWFunc->getTP($dvhc_tp);

$tax = $CDWCart->getTax();
$total = $CDWCart->getTotal();
$total = $total->amount;
$total = $total ? $total : 0;
$vat_percent =  $CDWConst->vatPercent;
$vat = $total * ($vat_percent / 100);

get_header(); ?>
<div class="container-md client-cart">
	<?php wp_nonce_field('ajax-client-cart-nonce', 'nonce'); ?>
	<div class="row" style="margin:30px 0;">
		<div class="col-md-12 summary">
			<h1 class="sub-services mb-2">Thanh Toán Đơn Hàng</h1>
		</div>
		<div class="col-md-6 my-3">
			<h2 class="title-small">Thông Tin Cá Nhân</h2>
			<table class="table-infomation" cellpadding="2">
				<tbody>
					<tr>
						<td>User:</td>
						<td><?php echo $firstname . " " . $lastname; ?></td>
					</tr>
					<tr>
						<td>Điện Thoại:</td>
						<td><?php echo $user_phone; ?></td>
					</tr>
					<tr>
						<td>E-mail:</td>
						<td><?php echo $useremail; ?></td>
					</tr>
					<tr>
						<td>Địa Chỉ:</td>
						<td><?php echo $user_address; ?></td>
					</tr>
				</tbody>
			</table>
            <a class="note-title mt-2" href="<?php echo get_home_url() ?>/gio-hang">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart2"
                     viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"></path>
                </svg>
                Quay lại giỏ hàng</a>
			<div class="my-2 xuat-vat">
				<input type="checkbox" id="xuat-vat" <?php echo $tax->has ? "checked" : ""; ?> data-percent="<?php echo $vat_percent; ?>" onclick="myFunction()">

				<label for="xuat-vat">Xuất hoá đơn ( VAT )</label>
				<div id="form-vat" <?php echo $tax->has ? "" : 'style="display:none"'; ?>>
					<div class="form-floating">
						<input type="text" class="form-control" id="name-company" placeholder="Tên Công Ty" value="<?php echo $tax->company; ?>">
						<label for="name-company">Tên Công Ty</label>
					</div>
					<div class="form-floating my-2">
						<input type="text" class="form-control" id="mst-company" placeholder="Mã Số Thuế" value="<?php echo $tax->code; ?>">
						<label for="mst-company">Mã Số Thuế</label>
					</div>
					<div class="form-floating">
						<input type="text" class="form-control" id="email-company" placeholder="Email nhận VAT" value="<?php echo $tax->email; ?>">
						<label for="email-compan">Email Nhận Hoá Đơn</label>
					</div>
				</div>

				<script>
					function myFunction() {
						var checkBox = document.getElementById("xuat-vat");
						var text = document.getElementById("form-vat");
						if (checkBox.checked == true) {
							text.style.display = "block";
						} else {
							text.style.display = "none";
						}
					}
				</script>
			</div>
		</div>
		<div class="col-md-6 my-3">
			<div class="summary">
				<h2 class="title-small">Hình Thức Thanh Toán</h2>
				<div class="summary-bank summary-bank-option">
					<ul>
						<li>
							<input type="radio" id="bank-online" name="delivery-collection" class="payment-item" value="bank" checked>
							<label for="bank-online"><i class="fa fa-university" aria-hidden="true"></i> Chuyển khoản ngân hàng</label>
						</li>
						<li>
							<input type="radio" id="bank-qrcode" name="delivery-collection" class="payment-item" value="bank-qrcode">
							<label for="bank-qrcode"><i class="fa fa-qrcode" aria-hidden="true"></i> Chuyển khoản online QRcode</label>
						</li>
						<li>
							<input type="radio" id="payment-momo" name="delivery-collection" class="payment-item" value="momo">
							<label for="payment-momo"><img src="<?php echo get_template_directory_uri(); ?>/templates/admin/assets/images/payment/momo.png" width="20" style="margin-right: 5px; transform: translateY(-2px);"> Thanh toán Momo</label>
						</li>
					</ul>
				</div>
				<div class="summary-subtotal" data-total="<?php echo $total?>">
				</div>
				<div class="summary-vat">
						</div>
				<div class="summary-total border-top mt-2">
				</div>
				<div class="form-check mb-3">
					<input class="form-check-input" type="checkbox" value="" id="acceptTerms" required>
					<label class="form-check-label" for="acceptTerms">
						Tôi xác nhận đã đọc và chấp nhận với các <a style=" color: blue; " href="https://www.congdongweb.com/dieu-khoan-su-dung/" target="_blank">Điều khoản sử dụng dịch vụ</a>, <a style=" color: blue; " href="https://www.congdongweb.com/chinh-sach-thu-thap-va-xu-ly-du-lieu-ca-nhan/" target="_blank">Chính sách quyền riêng tư</a>
					</label>
					<div class="invalid-feedback">
						Bạn phải chấp nhận các điều khoản và chính sách để tiếp tục.
					</div>
				</div>
				<div class="summary-checkout">
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="momo-qr-modal" tabindex="-1" role="dialog" aria-labelledby="momo-qr-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quét mã QR để thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card profile-header">
                    <div class="body m-0 p-0">
                        <div class="row clearfix m-0 p-0">
                            <div class="col-lg-12 pt-5 payment-momo">
                                <div class="payment-cta p-0 text-center">
                                    <div class="qrcode_scan_container">
                                        <div class="qrcode_scan">
                                            <div class="qrcode_gradient"><img alt="" src="<?php echo get_template_directory_uri(); ?>/templates/admin/assets/images/payment/qrcode-gradient.png" class=" img-fluid">
                                            </div>
                                            <div class="qrcode_border"><img alt="" src="<?php echo get_template_directory_uri(); ?>/templates/admin/assets/images/payment/border-qrcode.svg" class=" img-fluid">
                                            </div>
                                            <div class="qrcode_image">
                                                <img alt="paymentcode" class="image-qr-code" src="data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAASYAAAEmCAIAAABApqqNAAAABnRSTlMA/wD/AP83WBt9AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAWp0lEQVR4nO3de3Bc1X0H8LMvrVarpyXLloxsWVi2ZVMC2DwSgh+UR1I6aacZQlpCMrRMM9Ak0Glap20oKW3TpA8ybSlM6FAoE9IWpqSdDkkJbjA2Dg/HgIuf2MaWJUu2JVmvXUm7q723f9zVouzqrs7Zc36/e2V/P39dLueec+6uft7zu+ec3YBt2wIAuAS97gDAxQUhB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfACiEHwAohB8AKIQfAKqx6QSAQoOgHtu0BKf/83SqHHIP8q2PbtuqxTJ0ybZnqW6n7nI9Mn031zVRbqvfiVXkPYWAJwAohB8AqoPpBPHtMrPMhXjC29v94ABY0//zdXgi5nNu1ps6byi39lvOo9pn6tfLqNWSGgSUAK4QcACtjuZzMvEeJ8v4cA8AFwz9/t37P5WTOu5XhzP10+qOTe1DkSBRzdH6o0ycwsARghZADYIVcDi4K/vm79WMuNxvnekXqOS6da6nXkXqVp8mcd0OxrpUBBpYArBByAKyQy8FFwT9/t37P5WbjzA10+qnaN4q1izJ9kOkz57yfan9kysvUzwwDSwBWCDkAVtgvBxcF//zd+jGX81ueQL02UqeMTD9Vy+iUN9UfivfaJzCwBGCFkANgpZXLGbSwxgaw4Pjn79bvuRzO4zz2ywFA+RByAKyQy8FFwT9/t8ohx4BiHsxUW6bWHKr2Tae8qb12nH2jXkfqIQwsAVgh5ABY+XFgCXABMzkvZ2r87dXeMNX7kqlftR5Te9UupL2COu3qvD5EzxQwsARghZADYIVcDoAV1RpL6vkut2t1+qZTp+q1FPvWTM3LUb8XFPfCOa+IXA5gIUHIAbBCLgfAqpxc7kKdC1Kt36s1mdR5iKn3heK1kuknxfMCgzCwBGCFkANghVwOgJVuLud2nnl8bBzFPXqVU6nWqZN36bSrei1nnZiXA1ioEHIArJDLAbDiWGPpdt6rnIF6v9lCqYd6ztCrtZrUa3o1YWAJwAohB8AKuRwAK91cjmLtok67FPMtFPNCpuqhuC+Ztrya1zKV23u4NhgDSwBWCDkAVsjlAFhR7Zejnpej2EOler+q/fQqd5JpS5Wf8yiK3FWmvCQMLAFYIeQAWCGXA2DF/T2WpurhzP0o1nbqnJfpJ8W1XvWfcz0kdQ4sMLAEYIaQA2CFXA6AFVUuNxt1zmCqDxS5og7qeTyK3Ik6H+Oc/ySa88TAEoAVQg6AFXI5AFYmv8fS1LWmxvo6dZpaj0cxp8c5l2iqfs65WZ06ZcqrtlUAA0sAVgg5AFbI5QBYUf0mgdt56rkdmXqo+ybzmrjhXM9JPVdGUUYGxdyjwTlYDCwBWCHkAFghlwNgRfU9ln6Y7+LsM8X8oWr91PmSV/Njfvi7wrwcwEKFkANghVwOgBXHGkvO/MSr+k3V6YZiLpRhDmre/sjci04ZznYlYWAJwAohB8AKuRwAK5PzchT1UK8nNNUf6r1nqm2Zymc413y6XetWnmJukKLPBTCwBGCFkANghVwOgJXJ35eTKePn/WnUczgU90g9p0SxJpOiLZl6OOdRS8DAEoAVQg6AFXI5AFYcvy9nqh6/5Xsq9yFbp6kckjrHlkExp6p6Lzp1qt6LJAwsAVgh5ABYIZcDYGVyXo5ivsgPc1l+3henU8bPc24Uc5vUOb8kDCwBWCHkAFghlwNgRbVfztRYXKYMxRyOG7/lWhR5qalrveqPTruq9aheKzCwBGCGkANghVwO... [truncated]">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="jsx-d22f6bd0771ae323 mr-1 inline h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" class="jsx-d22f6bd0771ae323"></path>
                                        </svg>
                                        <a>Sử dụng <b> App MoMo </b> hoặc ứng dụng camera hỗ trợ QR code để quét mã</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="expire-content mb-3">
                                    <div class="box-expire">
                                        <div class="expire-text">
                                            <div>
                                                Đơn hàng sẽ hết hạn sau:
                                                <br>
                                                <div class="font-weight-bold time-expire-text d-inline-flex">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>