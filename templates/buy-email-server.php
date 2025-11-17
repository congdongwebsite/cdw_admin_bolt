<?php
defined('ABSPATH') || exit;
/*
 Template Name: Email Server
 */
get_header();
global $CDWFunc;

$arr = array(
	'post_type' => 'domain',
	'post_status' => 'publish',
	'meta_key' => 'stt',
	'orderby' => 'meta_value',
	'order' => 'ASC',
	'fields' => 'ids',
	'posts_per_page' => -1,
);

$ids = get_posts($arr);
$domain = isset($_GET['ten-mien']) ? $_GET['ten-mien'] : "";
?>
<section class="check-domain background-1 section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-xl-8 col-lg-8 col-md-7 col-sm-12 col-12">
				<div class="col-inner about-us dark">
					<h1 class="my-0">EMAIL THEO TÊN MIỀN</h1>
					<p class="my-0">Tạo Sự chuyên nghiệp và uy tín cho doanh nghiệp của bạn qua email tên miền</p>
					<ul class="domain hosting">
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Tỉ lệ email vào inbox 99%</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Chống spam/virus</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Backup tự động hàng tuần</li>
					</ul>
					<a href="#bang-gia-email" class="link-cdt"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
							<path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"></path>
						</svg> Đăng Ký Gói Email</a>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-12">
				<div class="col-inner">
					<img src="<?php echo THEME_URL_F . "/images/email-server-banner.png"; ?>" alt="Email server doanh nghiệp" width="auto" />
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-12">
				<div class="text-center col-inner">
					<h2 class="sub-services">Dịch Vụ Email Theo Tên Miền</h2>
					<h3 class="section-title">Bản Quyền Thương Hiệu <span class="title-color">Email Doanh Nghiệp</span></h3>
				</div>
			</div>
			<div class="col-md-5">
				<div class="col-inner">
					<img class="border-radius-cdw shadow-cdw" src="<?php echo THEME_URL_F . '/images/email-server.jpeg'; ?>" alt="Email theo tên miền" title="Đăng ký email server">
				</div>
			</div>
			<div class="col-md-7">
				<div class="col-inner about-us" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
					<h2>Email Domain là gì?</h2>
					<p><strong class="text-success">Email Domain</strong> là dịch vụ email theo tên miền riêng của bạn, phù hợp với các doanh nghiệp cần email có chứa đuôi tên miền luôn thay vì gmail hay bất cứ tên nào khác</p>
					<div class="email-domain">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-paper" viewBox="0 0 16 16">
							<path d="M4 0a2 2 0 0 0-2 2v1.133l-.941.502A2 2 0 0 0 0 5.4V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.4a2 2 0 0 0-1.059-1.765L14 3.133V2a2 2 0 0 0-2-2H4Zm10 4.267.47.25A1 1 0 0 1 15 5.4v.817l-1 .6v-2.55Zm-1 3.15-3.75 2.25L8 8.917l-1.25.75L3 7.417V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5.417Zm-11-.6-1-.6V5.4a1 1 0 0 1 .53-.882L2 4.267v2.55Zm13 .566v5.734l-4.778-2.867L15 7.383Zm-.035 6.88A1 1 0 0 1 14 15H2a1 1 0 0 1-.965-.738L8 10.083l6.965 4.18ZM1 13.116V7.383l4.778 2.867L1 13.117Z" />
						</svg> abc@tenmiencuaban.com
					</div>
					<p>Các gói dịch vụ Email Hosting, Email Server, Email theo tên miền được Cộng Đồng Web thiết kế đảm bảo tỷ lệ email vào hộp thư đến 99%, hỗ trợ chống spam và virus hiệu quả.<br /> Ngoài ra chúng tôi hỗ trợ cài đặt email theo tên miền chạy trên nền email google, giúp khách hàng nhận biết thương hiệu của bạn 1 cách nhanh chóng, tạo uy tín tuyệt đối với khách hàng</p>
				</div>
			</div>
		</div>
</section>
<section class="container-lg" data-theme="light">
	<div class="row align-center">
		<div class="col-md-10">
			<div class="text-center col-inner services-name" id="bang-gia-email">
				<div class="note-title mt-3">Bảng Giá Email Doanh Nghiệp</div>
				<h3 class="section-title mb-0">Đăng Ký Email Theo Tên Miền Doanh Nghiệp</h3>
				<p>Tạo dựng 1 thương hiệu uy tín bền vững thông qua Email theo đuôi tên miền, giúp doanh nghiệp tăng sự chuyên nghiệp trong từng lĩnh vực</p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-inner">
				<table class="price-by-email-server price-by-server form-table-domain" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500" data-aos-delay="300">
					<thead>
						<tr>
							<th>Gói dịch vụ</th>
							<th class="text-center">Tài Khoản</th>
							<th class="text-center">Domain</th>
							<th class="text-center">Ổ cứng SSD</th>
							<th>Giá</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
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
							$acc = get_post_meta($id, 'account', true);
							$hhd = get_post_meta($id, 'hhd', true);
						?>
							<tr>
								<td data-label="Gói Dịch VỤ">
									<div><strong><?php echo get_the_title($id); ?></strong></div>
									<small><?php echo get_post_meta($id, 'sub-title', true); ?></small>
								</td>
								<td data-label="Tài Khoản">
									<div class="text-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-paper" viewBox="0 0 16 16">
											<path d="M4 0a2 2 0 0 0-2 2v1.133l-.941.502A2 2 0 0 0 0 5.4V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.4a2 2 0 0 0-1.059-1.765L14 3.133V2a2 2 0 0 0-2-2H4Zm10 4.267.47.25A1 1 0 0 1 15 5.4v.817l-1 .6v-2.55Zm-1 3.15-3.75 2.25L8 8.917l-1.25.75L3 7.417V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v5.417Zm-11-.6-1-.6V5.4a1 1 0 0 1 .53-.882L2 4.267v2.55Zm13 .566v5.734l-4.778-2.867L15 7.383Zm-.035 6.88A1 1 0 0 1 14 15H2a1 1 0 0 1-.965-.738L8 10.083l6.965 4.18ZM1 13.116V7.383l4.778 2.867L1 13.117Z" />
										</svg>
										<?php echo -1 == $acc ? "Liên Hệ" : $acc . " Tài khoản"; ?>
									</div>
								</td>
								<td data-label="Doamin">
									<div class="text-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
											<path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
										</svg>
										1 domain
									</div>
								</td>
								<td data-label="Ổ cứng SSD">
									<div class="text-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hdd-network" viewBox="0 0 16 16">
											<path d="M4.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
											<path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8.5v3a1.5 1.5 0 0 1 1.5 1.5h5.5a.5.5 0 0 1 0 1H10A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5H.5a.5.5 0 0 1 0-1H6A1.5 1.5 0 0 1 7.5 10V7H2a2 2 0 0 1-2-2V4zm1 0v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1zm6 7.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5z" />
										</svg>
										<?php echo -1 == $hhd ? "Liên Hệ" : $hhd . "MB"; ?>
									</div>
								</td>
								<td data-label="Giá">
									<?php
									$pricemail = get_post_meta($id, 'gia', true);
									if ($pricemail == -1) {
										$pricemail = 'Liên Hệ Chúng Tôi';
										echo '<span class="text-danger">' . $pricemail . '</span';
									} else {
										$pricemail = number_format($pricemail, 0, ',', '.');
									?><span><strong class="text-danger"><?php echo $pricemail; ?></strong> vnđ</span>
										<span>/Năm</span><?php
														} ?>
								</td>
								<td>
									<a rel="nofollow" href="<?php echo get_site_url(); ?>/admin/" data-ide="<?php echo $id; ?>" class="link-cdt2 btn-buy">Đăng Ký Ngay</a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<div class="note-cdw border-radius-cdw mt-3 p-3">
					<strong class="text-warning">Lưu ý</strong> <span>Bảng giá Email theo tên miền chưa bao gồm VAT</span>
				</div>
			</div>
		</div>
	</div>
</section>

<!--Lợi Thế Công Ty-->
<section class="section">
	<div class="container-lg">
		<div class="row align-center">
			<div class="col-md-7 text-center" data-aos="fade-up" data-aos-anchor-placement="bottom-bottom">
				<h2 class="section-title">Tại Sao Nên Đăng Ký <span class="title-color"> Email Tên Miền Chúng Tôi</span></h2>
				<p class="section-title-description">Cung cấp các dịch vụ tối ưu, hiệu quả nhất cho việc Kinh doanh, Marketing và Vận hành Doanh nghiệp của bạn</p>
			</div>
		</div>
		<div class="row align-equal">
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/security-icon.png'; ?>" alt="Email bảo Mật, Chống Spam" title="Hệ thống bảo mật email">
					</div>
					<div class="services-name">
						<h3 class="section-title mb-0">
							Email Bảo Mật, Chống Spam
						</h3>
						<small>
							Hệ thống an toàn bảo mật
						</small>
						<p class="text-justify">
							Cộng Đồng Web được tích hợp sẵn công nghệ Antispam, Antivirus cùng phần mềm bảo mật cao cấp, giúp ngăn chặn virus, chống email spam
						</p>
						<p class="sale-services">
							HOT
						</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/chat-icon.png'; ?>" alt="email gửi vào inbox cao" title="tỉ lệ vào hộp thư đến cao">
					</div>
					<div class="services-name">
						<h3 class="section-title mb-0">
							Tăng Tỉ Lệ Vào Inbox 99%
						</h3>
						<small>
							Email vào hộp thư đến, không vào thư rác
						</small>
						<p class="text-justify">
							Dịch vụ Email theo tên miền cho công ty của chúng tôi giúp doanh nghiệp tăng tỷ lệ gửi email thành công vào inbox đến 99% nhờ vào IP uy tín.
						</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/responsive-icon.png'; ?>" alt="giao diện dễ quản lý" title="Giao diện dễ sử dụng và quản lý">
					</div>
					<div class="services-name">
						<h3 class="section-title mb-0">
							Giao Diện Dễ Quản lý
						</h3>
						<small>
							hệ thống dễ sử dụng, thân thiện
						</small>
						<p class="text-justify">
							Giao diện quản trị độc lập, thân thiện giúp cho việc quản lý email đơn giản, dễ dàng, toàn quyền quản lý tất cả các địa chỉ email trong hệ thống
						</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/hosting-icon.png'; ?>" alt="Backup email tự động" title="Sao lưu email hàng tuần">
					</div>
					<div class="services-name">
						<h3 class="section-title mb-0">
							Backup Tự Động Email Server
						</h3>
						<small>
							Sao lưu dữ liệu hàng tuần bảo mật an toàn
						</small>
						<p class="text-justify">
							Bạn sẽ luôn có sẵn một bản sao dữ liệu hàng tuần khi cần nếu xảy ra trường hợp không mong muốn. CDW còn các dịch vụ email google
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--Câu Hỏi-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-12">
				<div class="text-center">
					<h2 class="section-title">Những Câu Hỏi Thường Gặp Về <span class="title-color"> Email Tên Miền</span></h2>
					<p class="section-title-description">
						nhưng câu hỏi và thắc mắc các bạn, chúng tôi sẽ cố gắng hỗ trợ 24/24, nếu có câu hỏi khác bạn có thể liên hệ với chúng tôi qua ticket
					</p>
				</div>
				<div class="icon-cdw mb-3" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-delay="300">
					<div class="accordion" id="accordionExample">
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading1">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Tại sao nên sử dụng dịch vụ mail tên miền?
								</button>
							</h4>
							<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Sử dụng dịch vụ Email Domain giúp cho doanh nghiệp tăng sự uy tín chuyên nghiệp trong quá trình vận hành của bạn, tạo độ tin cậy của doanh nghiệp đối với khách hàng, tăng tỷ lệ tương tác từ khách hàng, thay vì bạn sử dụng email @gmail.com thì email theo tên miền vẫn uy tín hơn nhiều đúng không ạ!
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading2">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Sự khác biệt giữa dịch vụ Email Tên Miền và các dịch vụ email khác là gì?
								</button>
							</h4>
							<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									- Các email thông thường chỉ là tài khoản email của một cá nhân theo tên miền từ nhà cung cấp nào đó. Ví dụ: @gmail.com, @yahoo.vn.<br />
									- Các email này thường không đại diện trực tiếp cho doanh nghiệp, dễ bị spam, virus, giả mạo email.
									Trong khi đó, email theo tên miền là hệ thống email theo đuôi phía sau của doanh nghiệp, mang đến sự chuyên nghiệp, tin cậy và đảm bảo thông tin đúng sự thật và chính chủ, ví dụ: admin@tenmiencuaban.com.<br />- bởi vì email theo tên miền chỉ có doanh nghiệp sử hữu tên miền đó rồi mới tạo được email domain, chứ không phải ai cũng tạo được email của 1 doanh nghiệp khác.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading3">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									Tôi có thể nâng cấp gói Email Theo Tên Miền đang sử dụng lên gói cao hơn không?
								</button>
							</h4>
							<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Khi sử dụng dịch vụ Email Doamin tại Cộng Đồng Web, bạn hoàn toàn có thể nâng cấp dịch vụ khi có nhu cầu. Quá trình nâng cấp đơn giản và nhanh chóng, không ảnh hưởng đến dữ liệu của bạn trong quá trình sử dụng. Chi phí sẽ được hệ thống tự động tính toán và thông báo đến bạn.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading4">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
									Bản backup Email doanh nghiệp sẽ được lưu trữ trong bao nhiêu ngày?
								</button>
							</h4>
							<div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Tất cả dữ liệu trong gói Emai theo tên miền của khách hàng luôn được tự động backup mỗi ngày và lưu trữ trong 7 ngày gần nhất, quý khách hàng có thể khôi phục hoặc tải về bản backup bất kỳ lúc nào.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading5">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapseThree">
									Nếu dịch vụ E-mail Server không đáp ứng được yêu cầu của tôi thì tôi có được hoàn tiền không?
								</button>
							</h4>
							<div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Có. Cộng Đồng Web cam kết hoàn tiền 100% cho khách hàng không hài lòng với chất lượng dịch vụ trong thời gian sử dụng. Chi tiết bạn có thể xem tại phần <a href="/chinh-sach-hoan-tien"> Chính sách hoàn tiền </a> của chúng tôi.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading6">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapseThree">
									Nếu dữ liệu email bị mất hoặc xóa nhầm thì có khôi phục được không?
								</button>
							</h4>
							<div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Với Cộng Đồng Web, chúng tôi quan trọng tính bảo mật và riêng tư của khách hàng khi sử dụng dịch vụ của chúng tôi, nên phía chúng tôi sẽ không can thiệp vào hệ thống cá nhân của từng khách hàng, đồng nghĩa với việc chúng tôi sẽ không có quyền khôi phục, tìm email thất lạc, mất email hay bất kỳ thao tác nào trong email quản trị khách hàng.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>
<?php get_footer(); ?>