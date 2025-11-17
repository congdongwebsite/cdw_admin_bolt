<?php
/*
 Template Name: Giỏ hàng
 */
get_header(); ?>
<div class="container-md client-cart">
	<?php wp_nonce_field('ajax-client-cart-nonce', 'nonce'); ?>
	<div class="row" style="margin:30px 0;">
		<div class="col-md-12 summary">
			<h1 class="title-small">Giỏ Hàng</h1>
			<h2 class="sub-services mb-2">Thông Tin Đơn Hàng</h2>
			<table class="table-infomation basket mb-3" cellpadding="5">
				<thead>
					<tr>
						<th>Thao Tác</th>
						<th class="item item-heading">Dịch Vụ</th>
						<th class="price">Giá</th>
						<th class="quantity">Số lượng</th>
						<th class="subtotal text-right sub-services">Tổng Tiền: </th>
					</tr>
				</thead>
				<tbody class="cartItems">

				</tbody>
				<tfoot>
					<tr class="summary-subtotal">
					</tr>
				</tfoot>
			</table>
			<div class="footer-check-out">
				<div class="actions">
					<button class="btn note-title btn-update mt-2">Cập nhật giỏ hàng</button>
					<button class="btn note-title btn-delete-all  mt-2"><i class="fa fa-trash" aria-hidden="true"></i> Xóa hết giỏ hàng</button>
				</div>
				<!-- <div class="basket-module mt-2">
					<label for="promo-code">Bạn có mã khuyễn mãi?</label>
					<input id="promo-code" type="text" name="promo-code" maxlength="5" class="promo-code-field">
					<button class="promo-code-cta">áp dụng</button>
				</div> -->
                <div class="order-web"><a href="<?php echo get_home_url() ?>/thanh-toan">Tiến Hành Thanh Toán </a></div>
            </div>
		</div>
	</div>
</div>


<?php get_footer(); ?>