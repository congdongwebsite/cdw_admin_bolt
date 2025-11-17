<?php
/*
Template Name: Trang chủ
*/
get_header(); ?>
<!--Banner-->
<section class="banner mt-3">
	<div class="container-lg">
		<div class="row align-equal">
			<div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-12 col-cdw dark">
				<div class="col-inner background-1 shadow-cdw">
					<h2 class="section-title">Tên Miền</h2>
					<h3 class="sub-services">
						Sở hữu domain để xây dựng thương hiệu của bạn trên internet.
					</h3>
					<p>Tên miền phổ biến nhất thế giới.</p>
					<a href="/dang-ky-ten-mien" class="link-cdt">Đăng Ký Ngay</a>
					<p>Thanh toán cho năm thứ 2 với giá ‪300.000 ₫‬</p>
				</div>

			</div>
			<div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 col-12 col-cdw">
				<div class="col-inner banner-image shadow-cdw">
					<div class="banner-text">
						<h2 class="my-0">Young Plus JSC</h2>
						<h1 class="sub-services">Cộng Đồng Web Giải Pháp Webite Doanh Nghiệp</h1>
						<a href="/kho-giao-dien" class="link-cdt">Kho Mẫu Website</a>
						<p>Thuộc Công Ty Cổ Phần Young Plus quản lý và phát triển</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row align-center">
			<div class="col-md-12">
				<div class="form-search-baner col-inner">
					<div class="note-title mt-3">Tìm Thương Hiệu Riêng Mình</div>
					<div class="services-name">
						<h3 class="section-title">Đừng bỏ lỡ ý tưởng kinh doanh của mình!</h3>
					</div>
					<div class="input-group md-form form-sm form-2 pl-0">
						<form id="form-check-domain-home" action="/dang-ky-ten-mien" method="get" class="form-search-baner form-check-domain form-check-domain-home">
							<input class="my-0 py-1 red-border" id="ten-mien" name="ten-mien" type="text" placeholder="Tìm kiếm tên miền cho riêng bạn" aria-label="Search">
							<div class="input-group-append">
								<button class="input-group-text red lighten-3 submit" type="basic-text1" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
										<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
									</svg> Tìm Kiếm</button>
							</div>
						</form>
					</div>
				</div>
				<div class="list-price-domain">
					<?php
					// Custom WP query query
					// Query Arguments
					$args_query = array(
						'post_type' => array('domain'),
						'post_status' => array('publish'),
						'meta_key' => 'stt',
						'orderby' => 'meta_value',
						'order' => 'ASC',
					);
					// The Query
					$query = new WP_Query($args_query);

					// The Loop
					if ($query->have_posts()) {
					?>
						<ul>
							<?php
							while ($query->have_posts()) {
								$query->the_post();
								$note = get_field("note", get_the_ID());
								$gia = get_field("gia", get_the_ID());
								$gia_han = get_field("gia_han", get_the_ID());
								if (is_numeric($gia) && $gia <> 0) {

							?>
									<li class="border-radius-cdw shadow-cdw" data-aos="fade-up" data-aos-duration="3000">
										<a href="/dang-ky-ten-mien/"><b><?php echo get_the_title(); ?></b>
											<small class="mt-1"><?php echo $note; ?></small>
											<p class="mb-0"><b class="text-danger"><?php echo number_format($gia, 0, '.', ','); ?> đ</b>/Năm</p>
										</a>
									</li>
								<?php } else { ?>
									<li><a href="#"><?php echo get_the_title(); ?> <b>Free</b></a></li>

							<?php
								}
							}
							?>
						</ul>
					<?php
					} else {
						// no posts found
					}

					/* Restore original Post Data */
					wp_reset_postdata();

					?>
				</div>
			</div>
		</div>
	</div>
</section>
<!--Service-->
<section class="section">
	<div class="container-lg">
		<div class="row align-center">
			<div class="col-md-7 text-center" data-aos="fade-up" data-aos-anchor-placement="bottom-bottom">
				<h2 class="section-title">Dịch Vụ <span class="title-color"> Chúng Tôi</span></h2>
				<p class="section-title-description">Cung cấp các dịch vụ tối ưu, hiệu quả nhất cho việc Kinh doanh, Marketing và Vận hành Doanh nghiệp của bạn</p>
			</div>
		</div>
		<div class="row align-equal">
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/thiet-ke-web-icon.png'; ?>" alt="Thiết Kế website" title="Thiết Kế Web chuyên nghiệp">
					</div>
					<div class="services-name"><a href="/thiet-ke-website/">
							<h3 class="section-title mb-0">
								Thiết Kế Website
							</h3>
							<small>
								Website Designer
							</small>
							<p class="text-justify">
								Đơn vị chuyên Thiết Kế Website bán hàng, giới thiệu doanh nghiệp chất lượng uy tín trên toàn quốc.
							</p>
							<p class="sale-services">
								HOT
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/code-icon.png'; ?>" alt="Code chức năng" title="phát triển tính năng">
					</div>
					<div class="services-name">
						<a href="/kho-giao-dien/">
							<h3 class="section-title mb-0">
								Theme, Plugin, Modul
							</h3>
							<small>
								Tích Hợp Tính Năng Doanh Nghiệp
							</small>
							<p class="text-justify">
								Phát triển chức năng theo yêu cầu và tích hợp các công cụ cho doanh nghiệp như API, CMS, CMR ...
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/dang-ky-ten-mien-icon.png'; ?>" alt="Đăng Ký Tên Miền" title="Mua tên miền cho riêng bạn">
					</div>
					<div class="services-name"><a href="/dang-ky-ten-mien/">
							<h3 class="section-title mb-0">
								Cung Cấp Tên Miền
							</h3>
							<small>
								Mua Domain Xây Dựng Thương Hiệu
							</small>
							<p class="text-justify">
								Có thể mua tên miền và cấu hình domain nhanh chóng, Đăng ký dễ dàng không phức tạp
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img width="253" height="253" src="<?php echo THEME_URL_F . '/images/server-icon.png'; ?>" alt="Mua Server" title="Đăng Ký Hệ Thống Server">
					</div>
					<div class="services-name"> <a href="/vps-server/">
							<h3 class="section-title mb-0">
								Server vps Hosting
							</h3>
							<small>
								Hệ Thống Server Cấu Hình Khủng
							</small>
							<p class="text-justify">
								Server IP quốc tế, tối ưu tốc độ load, hệ thống bảo mật cao, có tường lửa chống xâm nhập
							</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--About us-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-6">
				<div class="col-inner about-us">
					<h2 class="sub-services">Về Chúng Tôi</h2>
					<h3 class="section-title">Hệ Thống Quản Lý <span class="title-color"> Website Chuyên Nghiệp</span></h3>
					<p>Website hiện nay là bộ mặt của Công ty, là một công cụ hỗ trợ kinh doanh bán hàng hoặc giới thiệu công ty đắc lực trong thời kỳ 4.0. Doanh nghiệp thậm chí các cá thể kinh doanh đều đang hướng đến xây dựng thương hiệu riêng để mọi người chỉ cần nhìn thấy là nhớ đến ngành nghề của bạn</p>
					<p>Hiểu được tầm quan trọng của khách hàng, Cộng Đồng Web đã và đang phát triển hệ thống quản lý website chuyên nghiệp để có thể đồng hành cùng quý khách, tích hợp các tiện ích cho website của bạn, hỗ trợ tối đa các tiện ích mà bạn cần phát triển.</p>
					<a href="/gioi-thieu/" class="link-cdt1">Tìm Hiểu Thêm</a>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-inner">
					<img src="<?php echo THEME_URL_F . '/images/cong_dong_theme_thiet_ke_web.jpg'; ?>" alt="Cộng Đồng Web Thiết Kế Website" title="Hệ Thống Quản Lý Website Chuyên Nghiệp">
				</div>
			</div>
		</div>
	</div>
</section>

<!--Đối Tác-->
<section class="section">
	<div class="container-lg">
		<div class="row align-center align-middle">
			<div class="col-md-12 text-center">
				<h2 class="section-title">Đối Tác <span class="title-color"> Chúng Tôi</span></h2>
				<p class="section-title-description">Lựa chọn hàng đầu của 30.000+ Khách hàng, Doanh nghiệp.
				</p>
			</div>
		</div>
		<div class="row align-center">
			<div class="col-md-10">
				<?php echo do_shortcode('[slick_image title="" listId="150,149,148,147,146,145,144,143" url="/doi-tac" show_arrows="true" show_dots="true" show_desktop="6" show_tab="2" show_mobile="2" id_element="id_doi_tac_home"]');
				?>
				<div class="image-cms text-center col-inner">
					<div class="note-title mt-3">Giải pháp website CMS</div>
					<div class="services-name">
						<h3 class="section-title">Mô Hình Quản Trị Hệ Thống Web</h3>
					</div>
					<p>Giúp doanh nghiệp có thể đồng bộ toàn bộ nội dung về website về 1 nơi để quản trị, có thể tích hợp tính năng tiện ích cho website hay mua tên miền hosting hoặc bất cứ về website của bạn.</p>
					<img class="shadow-cdw" src="<?php echo THEME_URL_F . "/images/CMS_Admin_Cong_Dong_Web.jpg"; ?>" width="100%" alt="Cộng Đồng Web Thiết Kế Website" title="Hệ Thống Quản Lý Website Chuyên Nghiệp">
				</div>
			</div>
		</div>
	</div>
</section>
<!--Bảng Giá-->
<section class="section">
	<div class="container-lg">
		<div class="row align-center align-equal cdw-tab-price">
			<div class="col-md-12  text-center">
				<h2 class="sub-services">Bảng Báo Giá</h2>
				<h3 class="section-title">Thiết Kế Website <span class="title-color"> Chuyên Nghiệp</span></h3>
				<p class="section-title-description">Tạo mô hình kinh doanh hiệu quả với các gói thiết kế web, với phương châm " uy tín mang lại thương hiệu ", </p>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
				<div class="col-inner">
					<div class="item">
						<div class="icon-name">
							<div class="icon">
								<svg fill="#4b94cb" width="50px" height="50px" viewBox="-3.2 -3.2 38.40 38.40" version="1.1" xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" stroke="#4b94cb" stroke-width="0.00032">
									<g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(8.16,8.16), scale(0.49)">
										<path transform="translate(-3.2, -3.2), scale(1.2)" d="M16,30.371016398072243C20.309355205610178,30.565379338242767,24.403653742914177,28.212097633521868,26.919120978890934,24.707708410653105C29.293471644154845,21.399913833304446,29.773899750312104,16.99389820005421,28.336769190759682,13.184212937151653C27.087966249243344,9.873764814716733,23.351763356516994,8.86798593559706,20.280932158069202,7.110552776494718C16.67751345750355,5.048320403154053,13.329130063829954,0.9281533811690903,9.434411763479453,2.366415545781445C5.4099587076885935,3.852586846766592,4.399882705916232,8.964873587103394,3.7686858973608146,13.208282372856392C3.2086134334683925,16.973536540377708,4.040601932654001,20.675476830866977,6.249866296493595,23.775472166591793C8.645739160179385,27.137312797337994,11.875975061788171,30.185012326910066,16,30.371016398072243" fill="#7ed0ec" strokewidth="0"></path>
									</g>
									<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
									<g id="SVGRepo_iconCarrier">
										<title>diamond</title>
										<path d="M2.103 12.052l13.398 16.629-5.373-16.629h-8.025zM11.584 12.052l4.745 16.663 4.083-16.663h-8.828zM17.051 28.681l12.898-16.629h-7.963l-4.935 16.629zM29.979 10.964l-3.867-6.612-3.869 6.612h7.736zM24.896 3.973h-7.736l3.867 6.839 3.869-6.839zM19.838 10.964l-3.867-6.612-3.868 6.612h7.735zM14.839 3.973h-7.735l3.868 6.839 3.867-6.839zM5.889 4.352l-3.867 6.612h7.735l-3.868-6.612z"></path>
									</g>
								</svg>
							</div>
							<div class="name-price">
								<div class="name">BASIC</div>
								<div class="price">< 5.000.000 vnđ</div>
							</div>
						</div>
						<div class="info">
							<ul class="pl-0">
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website giới thiệu hoặc Landingpage
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tặng tên miền quốc tế .com hoặc
									.net
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website chuẩn SEO
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tặng VPS Server lưu trữ Website
									(2GB)
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Băng thông không giới hạn
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tối ưu load dưới 3s
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế giao diện theo mẫu có sẵn
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Cài google analytics và google console
								</li>
								<li><i class="fa-solid fa-circle-check"></i> Ngôn ngữ Tiếng Việt</li>
							</ul>
						</div>
						<div class="link-add-cart">
							<a href="#" target="_blank" class="link-cdt1 link-tab-price hover-left d-block">
								<i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="btn_text">Đăng Ký</span>
							</a>
						</div>
					</div>

				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
				<div class="col-inner">
					<div class="item active dark">
						<span class="hot">
							Phổ biến
						</span>
						<div class="icon-name">
							<div class="icon">
								<svg fill="#f3b86d" width="50px" height="50px" viewBox="-3.2 -3.2 38.40 38.40" version="1.1" xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" stroke="#f3b86d" stroke-width="0.00032">
									<g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(8.16,8.16), scale(0.49)">
										<path transform="translate(-3.2, -3.2), scale(1.2)" d="M16,30.371016398072243C20.309355205610178,30.565379338242767,24.403653742914177,28.212097633521868,26.919120978890934,24.707708410653105C29.293471644154845,21.399913833304446,29.773899750312104,16.99389820005421,28.336769190759682,13.184212937151653C27.087966249243344,9.873764814716733,23.351763356516994,8.86798593559706,20.280932158069202,7.110552776494718C16.67751345750355,5.048320403154053,13.329130063829954,0.9281533811690903,9.434411763479453,2.366415545781445C5.4099587076885935,3.852586846766592,4.399882705916232,8.964873587103394,3.7686858973608146,13.208282372856392C3.2086134334683925,16.973536540377708,4.040601932654001,20.675476830866977,6.249866296493595,23.775472166591793C8.645739160179385,27.137312797337994,11.875975061788171,30.185012326910066,16,30.371016398072243" fill="#dfe5e7" strokewidth="0"></path>
									</g>
									<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
									<g id="SVGRepo_iconCarrier">
										<title>diamond</title>
										<path d="M2.103 12.052l13.398 16.629-5.373-16.629h-8.025zM11.584 12.052l4.745 16.663 4.083-16.663h-8.828zM17.051 28.681l12.898-16.629h-7.963l-4.935 16.629zM29.979 10.964l-3.867-6.612-3.869 6.612h7.736zM24.896 3.973h-7.736l3.867 6.839 3.869-6.839zM19.838 10.964l-3.867-6.612-3.868 6.612h7.735zM14.839 3.973h-7.735l3.868 6.839 3.867-6.839zM5.889 4.352l-3.867 6.612h7.735l-3.868-6.612z"></path>
									</g>
								</svg>
							</div>
							<div class="name-price">
								<div class="name">PROFESSIONAL</div>
								<div class="price"> < 9.000.000 vnđ</div>
							</div>
						</div>
						<div class="info">
							<ul class="pl-0">
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website bán hàng hoặc theo yêu cầu
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tính năng bán hàng cơ bản (giỏ hàng, thanh toán, đơn hàng)
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Gửi nhận đơn hàng qua email, CMS Đơn hàng
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tặng tên miền quốc tế .com hoặc .net
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website chuẩn SEO, Responsive
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tặng VPS Server lưu trữ Website (2GB)
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Băng thông không giới hạn và tối ưu load dưới 3s
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Cài google analytics và google console, tag manager
								</li>
								<li><i class="fa-solid fa-circle-check"></i> Hỗ trợ đa ngôn ngữ theo google</li>
							</ul>
						</div>
						<div class="link-add-cart">
							<a href="#" target="_blank" class="link-cdt1 link-tab-price hover-left d-block">
								<i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="btn_text">Đăng Ký</span>
							</a>
						</div>
					</div>

				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
				<div class="col-inner">
					<div class="item">
						<div class="icon-name">
							<div class="icon">
								<svg fill="#4b94cb" width="50px" height="50px" viewBox="-3.2 -3.2 38.40 38.40" version="1.1" xmlns="http://www.w3.org/2000/svg" transform="rotate(-45)" stroke="#4b94cb" stroke-width="0.00032">
									<g id="SVGRepo_bgCarrier" stroke-width="0" transform="translate(8.16,8.16), scale(0.49)">
										<path transform="translate(-3.2, -3.2), scale(1.2)" d="M16,30.371016398072243C20.309355205610178,30.565379338242767,24.403653742914177,28.212097633521868,26.919120978890934,24.707708410653105C29.293471644154845,21.399913833304446,29.773899750312104,16.99389820005421,28.336769190759682,13.184212937151653C27.087966249243344,9.873764814716733,23.351763356516994,8.86798593559706,20.280932158069202,7.110552776494718C16.67751345750355,5.048320403154053,13.329130063829954,0.9281533811690903,9.434411763479453,2.366415545781445C5.4099587076885935,3.852586846766592,4.399882705916232,8.964873587103394,3.7686858973608146,13.208282372856392C3.2086134334683925,16.973536540377708,4.040601932654001,20.675476830866977,6.249866296493595,23.775472166591793C8.645739160179385,27.137312797337994,11.875975061788171,30.185012326910066,16,30.371016398072243" fill="#7ed0ec" strokewidth="0"></path>
									</g>
									<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
									<g id="SVGRepo_iconCarrier">
										<title>diamond</title>
										<path d="M2.103 12.052l13.398 16.629-5.373-16.629h-8.025zM11.584 12.052l4.745 16.663 4.083-16.663h-8.828zM17.051 28.681l12.898-16.629h-7.963l-4.935 16.629zM29.979 10.964l-3.867-6.612-3.869 6.612h7.736zM24.896 3.973h-7.736l3.867 6.839 3.869-6.839zM19.838 10.964l-3.867-6.612-3.868 6.612h7.735zM14.839 3.973h-7.735l3.868 6.839 3.867-6.839zM5.889 4.352l-3.867 6.612h7.735l-3.868-6.612z"></path>
									</g>
								</svg>
							</div>
							<div class="name-price">
								<div class="name">VIP</div>
								<div class="price"> > 12.000.000 vnđ</div>
							</div>
						</div>
						<div class="info">
							<ul class="pl-0">
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website theo yêu cầu (Brand)
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Phát triển Modul CMS (2.000.000đ / 1 CMS)
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Có File thiết kế Figma
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Tặng tên miền quốc tế .com hoặc .net
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Thiết kế website chuẩn SEO, Responsive
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Cấu hình Server riêng theo yêu cầu
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Băng thông không giới hạn và tối ưu load dưới 3s
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i> Cài google analytics và google console, tag manager
								</li>
								<li><i class="fa-solid fa-circle-check"></i> Hỗ trợ đa ngôn ngữ</li>
							</ul>
						</div>
						<div class="link-add-cart">
							<a href="#" target="_blank" class="link-cdt1 link-tab-price hover-left d-block">
								<i class="fa fa-hand-o-right" aria-hidden="true"></i><span class="btn_text">Đăng Ký</span>
							</a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
<!--Tại sao chọn chúng tôi-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-6">
				<div class="col-inner">
					<img src="/wp-content/uploads/2023/07/logo-congdongweb.png" alt="tại sao chọn cộng đồng web" width="200px" class="mb-2"><br>
					<div class="note-title">Lý do bạn nên chọn chúng tôi</div>
					<h2>Vì Sao Bạn Đặt Niềm Tin Vào Chúng Tôi</h2>
					<p class="text-justify">Cộng Đồng Web đã làm rất nhiều dự án lớn nhỏ và thấu hiểu được sự lo lắng của bạn về dịch vụ ngành web này, nên chúng tôi muốn tạo thị trường cạnh tranh lành mạnh và giúp đỡ các khách hàng đang gặp khó khăn về việc lựa chọn 1 doanh nghiệp đủ tốt để bạn đặt niềm tin vào đó. </p>
					<p class="text-justify"> Dù bất cứ khó khăn nào thì <b>Cộng Đồng Web</b> luôn sẵn sàng hỗ trợ ngay cho bạn không quá 15phút. Kể cả khi bạn mua tên miền hosting cũng rất nhanh gọn và không rườm rà, đươc kỹ thuật hỗ trợ liên lạc với bạn ngay khi bạn sử dụng dịch vụ của chúng tôi.</p>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-inner">
					<div class="frame-reason">
						<div class="img-services shadow-cdw mb-4 hover-top">
							<img src="<?php echo THEME_URL_F . '/images/icon6.png'; ?>" alt="Thiết Kế Website Chuẩn SEO">
							<div class="right-reason">
								<h4 class="sub-services mt-0">Thiết Kế Website Chuẩn SEO</h4>
								<p class="text-justify">Nếu phát hiện 1 bên thứ 3 check không chuẩn SEO, chúng tôi sẽ hỗ trợ fix theo yêu cầu mà không phát sinh chi phí.</p>
							</div>
						</div>
						<div class="img-services shadow-cdw mb-4 hover-top">
							<img src="<?php echo THEME_URL_F . '/images/icon1.png'; ?>" alt="Thiết Kế Website Chuẩn SEO">
							<div class="right-reason">
								<h4 class="sub-services mt-0">Source Code Nhẹ Và Tối Ưu Tốc Độ Load</h4>
								<p class="text-justify">Kỹ thuật chúng tôi code trực tiếp không dùng thư viên hay plugin ngoài, bảo mật tuyệt đối và bảo hành trọn đời.</p>
							</div>
						</div>
						<div class="img-services shadow-cdw mb-4 hover-top">
							<img src="<?php echo THEME_URL_F . '/images/icon4.png'; ?>" alt="Thiết Kế Website Chuẩn SEO">
							<div class="right-reason">
								<h4 class="sub-services mt-0">Tiết Kiệm Chi Phí Và Hỗ Trợ Nhanh Nhất</h4>
								<p class="text-justify">Khi bạn sử dụng dịch vụ, Cộng Đồng Web luôn support 24/7, không phát sinh phí khi đã vào hoạt động.</p>
							</div>
						</div>
						<div class="img-services shadow-cdw mb-4 hover-top">
							<img src="<?php echo THEME_URL_F . '/images/icon5.png'; ?>" alt="Thiết Kế Website Chuẩn SEO">
							<div class="right-reason">
								<h4 class="sub-services mt-0">Server Hosting Bảo Mật Tuyệt Đối</h4>
								<p class="text-justify">Server chúng tôi được đặt ở Singapore, cấu hình khủng, Có sẵn phần mềm tường lửa chống DDos xâm nhập nguy hiểm.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--khách hàng-->
<section class="slider-post text-center">
	<h2 class="section-title">Nhận Xét Của <span class="title-color">Khách Hàng</span></h2>
	<p class="section-title-description">Tổng họp một số nhận xét tiêu biểu của khách hàng về chúng tôi</p>
	<section class="container-lg slider-custome">
		<div class="form-khachhang">
			<div class="text-testimonial">Sau nhiều lần tìm kiếm đơn vị thiết kế web thì cộng động web là đơn vị tôi tin tưởng nhất, luôn hỗ trợ trực tiếp và nhanh chóng.</div>
			<div class="img-testimonial">
				<img src="<?php echo THEME_URL_F . '/images/testimonial-website-designer _1_.jpg'; ?>" width="125px" alt="Cộng Đồng Web Thiết Kế Website" title="Thiết kế website chuyên nghiệp">
				<h4 class="sub-services">Ngọc Trinh</h4>
				<p>Cộng đồng shop</p>
			</div>
		</div>
		<div class="form-khachhang">
			<div class="text-testimonial">Thật sự bất ngờ khi làm website tại đây được hỗ trợ trực tiếp đến vậy, gặp trực tiếp giúp mình giải quyết vấn đề, cảm ơn Cộng Đồng Web nhé</div>
			<div class="img-testimonial">
				<img src="<?php echo THEME_URL_F . '/images/testimonial-website-designer _2_.jpg'; ?>" width="125px" alt="Cộng Đồng Web Thiết Kế Website" title="Thiết kế website chuyên nghiệp">
				<h4 class="sub-services">Chị Hằng Japan</h4>
				<p>CEO hangjapan.com</p>
			</div>
		</div>
		<div class="form-khachhang">
			<div class="text-testimonial">Cảm ơn cộng đồng web đã hỗ trợ sửa web và tích hợp chức năng thêm cho em, không ngỡ kỹ thuật có thể sửa và làm nhanh đến như vậy, chúc bạn luôn phát triển nhé</div>
			<div class="img-testimonial">
				<img src="<?php echo THEME_URL_F . '/images/testimonial-website-designer _3_.jpg'; ?>" width="125px" alt="Cộng Đồng Web Thiết Kế Website" title="Thiết kế website chuyên nghiệp">
				<h4 class="sub-services">Hà Ny</h4>
				<p>SEO CT Cường Thịnh Phát</p>
			</div>
		</div>
		<div class="form-khachhang">
			<div class="text-testimonial">Giao diện đẹp chuyên nghiệp, source code nhẹ, không bị phụ thuộc vào plugin nào cả, công nhận các bạn làm rất tốt, chúc cá bạn càng ngày phát triển thêm nữa nhé</div>
			<div class="img-testimonial">
				<img src="<?php echo THEME_URL_F . '/images/testimonial-website-designer _4_.jpg'; ?>" width="125px" alt="Cộng Đồng Web Thiết Kế Website" title="Thiết kế website chuyên nghiệp">
				<h4 class="sub-services">Quang Đạo</h4>
				<p>CEO dojeannam.com</p>
			</div>
		</div>
	</section>
	<script>
		jQuery(document).ready(function($) {
			$('.slider-post>.container-lg').slick({
				infinite: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false,
				dots: true,
			});
		});
	</script>
</section>
<!--Câu Hỏi-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-12 text-center">
				<div class="icon-cdw"><i class="fa fa-commenting-o fa-3x shadow-cdw" aria-hidden="true"></i></div>
				<h2 class="section-title">Câu Hỏi <span class="title-color"> Thường Gặp</span></h2>
				<p class="section-title-description">Những thông tin sau sẽ giúp bạn hiểu rõ hơn về Cộng Đồng Web.<br>
					Chúng tôi luôn sẵn lòng giải đáp các thắc mắc khác của bạn qua Ticket hoặc Email: <a href="mailto:hotro@congdongweb.com" target="_blank"></a>hotro@congdongweb.com</p>
			</div>
			<div class="col-md-6">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h4 class="accordion-header m-0" id="heading1">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Cộng Đồng Web cung cấp những dịch vụ gì?
							</button>
						</h4>
						<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								Chung tôi cung cấp các dịch vụ liên quan đến website như vps, tên miền, hosting, email server, SSL trong đó dịch vụ chính là <strong>Thiết Kế Website</strong> cho quý khách hàng, chúng tôi có thể code tất cả mã nguồn liên quan tới website như reactjs, ajax, Php, Wordpress.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h4 class="accordion-header m-0" id="heading2">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								Cộng Đồng Web có xuất hoá đơn không?
							</button>
						</h4>
						<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								Chúng tôi thuộc quản lý của Công Ty Cổ Phần Young Plus, nên mọi hoá đơn, hợp đồng, giấy tờ pháp lý đầy đủ và có thể xuất hoá đơn VAT cho quý khách nhanh trong ngày.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h4 class="accordion-header m-0" id="heading3">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Cộng Đồng Web Có bảo mật thông tin khách hàng và dữ liệu của tôi?
							</button>
						</h4>
						<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								Đảm bảo an toàn và bảo mật thông tin của Quý khách hàng là nhiệm vụ Cộng Đồng Web luôn đặt lên hàng đầu. Chúng tôi sẽ không xâm phạm vào thông tin và dữ liệu của khách hàng khi chưa được cho phép. Chúng Tôi cam kết không chia sẻ thông tin khách hàng dưới bất kỳ hình thức nào cho bên thứ ba. Mọi hoạt động sẽ tuân thủ đúng pháp luật, điều khoản hợp đồng và Chính sách bảo mật thông tin của chúng tôi.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h4 class="accordion-header m-0" id="heading4">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
								Dịch vụ của tôi đã (sắp) hết hạn, tôi phải làm gì?
							</button>
						</h4>
						<div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								Để đảm bảo được dịch vụ quý khách hoạt động không bị giãn đoạn, hệ thống của cộng đồng shop luôn có thông báo tự động về email khách hàng về dịch vụ sắp hết hạn, khi còn 3 ngày bên nhân viên chăm sóc khách hàng sẽ gọi điện thông báo gia hạn kịp lúc để hoạt động luôn ổn định.
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h4 class="accordion-header m-0" id="heading5">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapseThree">
								Sau bao lâu kể từ khi thanh toán tôi có thể sử dụng dịch vụ?
							</button>
						</h4>
						<div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								Dịch vụ của quý khách sẽ được kích hoạt tự động 5 phút sau khi thanh toán thành công. Riêng với dịch vụ vps server, email server, thời gian kích hoạt sẽ nằm trong khoảng 12h sau khi thanh toán thành công.
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<img src="<?php echo THEME_URL_F . '/images/FAQs.gif'; ?>" alt="Câu hỏi thường gặp thiết kế web" title="Thiết kế website chuyên nghiệp">
			</div>
		</div>
	</div>
</section>
<!--Tin tức-->
<section class="section">
	<div class="container-lg">
		<div class="row align-center">
			<div class="col-md-7 text-center" data-aos="fade-up" data-aos-anchor-placement="bottom-bottom">
				<h2 class="section-title">Tin Tức <span class="title-color"> Mới </span></h2>
				<p class="section-title-description">Nơi để cộng đồng web chia sẻ kiến thức và thông tin mới nhất của chúng tôi</p>
			</div>
			<div class="col-md-12">
				<div class="blog-new-mobi">
					<?php echo do_shortcode('[slick_post show_dots= "true" infinite="false" show_arrows="true" show_desktop=3 show_tab=2 show_mobile=1 id_element="id_slick_post_home" cat="1"]');
					?>
				</div>
				<?php
				$args = array(
					'post_status' => 'publish',
					'showposts' => 6,
				);
				?>
				<?php $getposts = new WP_query($args); ?>
				<?php global $wp_query;
				$wp_query->in_the_loop = true; ?>
				<ul class="blog-new-home">
					<?php while ($getposts->have_posts()) : $getposts->the_post(); ?>
						<li>
							<div class="box has-hover gallery-box box-none">
								<div class="frame-blog-new">
									<div class="img-blog image-cover">
										<?php the_post_thumbnail("full", array("title" => get_the_title(), "alt" => get_the_title(), "class" => 'shadow-cdw')); ?>
										<div class="frame-blog-title">
											<span><?php echo get_the_category_list(', ') ?></span>
											<h3><?php the_title(); ?></h3>
											<a class="link-cdt2 mt-2 px-3 py-1" href="<?php the_permalink(); ?>"> Xem Thêm <i class="fa fa-angle-right" aria-hidden="true"></i></a>
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</ul>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>