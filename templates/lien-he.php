<?php
/*
 Template Name: Liên Hệ
 */
wp_enqueue_script('captcha-script');
get_header(); ?>
<header>
	<div class="container-md">
		<div class="text-center my-3">
			<?php
			echo '<div class="header-breadcrumb dark">';
			echo '<img src="' . get_home_url() . '/wp-content/themes/CongDongTheme/images/impression-header.png">';
			echo '<div class="breadcrumb-title text-center"><h1>Liên Hệ</h1>';
			echo do_shortcode('[rank_math_breadcrumb]');
			echo '</div></div>';
			?>
		</div>
	</div>
</header>

<div class="section">
	<div class="container">
		<div class="row align-center align-middle">
			<div class="col-md-3">
				<div class="col-inner text-center">
					<div class="icon-contact"><i class="fa fa-map-marker fa-3x" aria-hidden="true"></i>
						<h3 class="section-title">Địa Chỉ</h3>
						<p>168 Nguyễn Gia Trí, P.25<br/>
						Q. Bình Thạnh, HCM</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="col-inner text-center">
					<div class="icon-contact"><i class="fa fa-phone fa-3x" aria-hidden="true"></i>
						<h3 class="section-title">Số Điện Thoại</h3>
						<p>(+84)353 814 306<br/>(+84) 38 627 0225</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="col-inner text-center">
					<div class="icon-contact"><i class="fa fa-envelope-o fa-3x" aria-hidden="true"></i>
						<h3 class="section-title">Email Hỗ Trợ</h3>
						<p>support@congdongweb.com<br/>admin@congdongweb.com</p>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="col-inner text-center">
					<div class="icon-contact"><i class="fa fa-facebook fa-3x" aria-hidden="true"></i>
						<h3 class="section-title">Fanpage</h3>
						<p>Cộng Đồng Web <br/> Young Plus</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="section">
	<div class="container">
		<div class="row align-middle">
			<div class="col-md-6">
				<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15676.28870786861!2d106.7157817!3d10.805784!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4f06118c69e93bd!2zVHJ1bmcgVMOibSBNdWEgU-G6r20gQ-G7mW5nIMSQ4buTbmcgU2hvcA!5e0!3m2!1svi!2s!4v1650874757140!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			<div class="col-md-6">
				<p class="sub-services1">BẠN CẦN CHÚNG TÔI HỖ TRỢ</p>
				<h3 class="section-title1">Send a <span>Message</span></h3>
				<p class="sub-services1" style="margin-bottom:30px;">Hãy gửi email ngay cho chúng tôi khi các bạn cần hỗ trợ, nếu bạn cần gấp có thể gọi ngay hotline cho chúng tôi, Cộng Đồng Web sẽ gửi email phản hồi cho các bạn nhanh nhất có thể.</p>
				<form id="form-contact" class="row g-3 needs-validation" novalidate>  					
					<span id="status-contact" class="d-none"></span>         
					<?php wp_nonce_field('ajax-contact-nonce', 'fn_contact'); ?>				       
					<div class="form-floating">
						<input type="text" class="form-control" id="NameContact" placeholder="Họ và tên" required>
						<label for="NameContact"><i class="fa fa-address-card-o px-2" aria-hidden="true"></i> Họ và tên</label>
						<div class="invalid-feedback">
							Vui lòng nhập họ và tên.
						</div>
					</div>
					<div class="form-floating">
						<input type="Email" class="form-control" id="EmailContact" placeholder="Email" required>
						<label for="EmailContact"><i class="fa fa-envelope-o px-2" aria-hidden="true"></i> Email</label>
						<div class="invalid-feedback">
							Vui lòng nhập Email.
						</div>
					</div>
					<div class="form-floating">
						<input type="tel" class="form-control" id="PhoneContact" placeholder="Số điện thoại" required>
						<label for="PhoneContact"><i class="fa fa-phone  px-2" aria-hidden="true"></i> Số điện thoại</label>
						<div class="invalid-feedback">
							Vui lòng nhập số điện thoại.
						</div>
					</div>
					<div class="form-floating">
						 <textarea class="form-control" id="LoinhanContact" placeholder="lời nhắn" rows="4"></textarea>
						<label for="LoinhanContact"><i class="fa fa-commenting-o  px-2" aria-hidden="true"></i> Lời nhắn</label>
						<div class="invalid-feedback">
							Lời nhắn bạn đang để trống
						</div>
					</div>
					<!-- Message input -->
					<div id="contact-captcha" class="g-recaptcha"></div>
					<button type="submit" class="btn btn-primary btn-contact">Đăng ký</button>
                   </form>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>