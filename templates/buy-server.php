<?php
defined('ABSPATH') || exit;
/*
 Template Name: Buy Server
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
					<h1 class="my-0 text-shadow">SERVER VPS CAO CẤP</h1>
					<h2 class="my-0">Giúp Website tăng tốc độ xử lý Và Thứ Hạng Google</h2>
					<ul class="domain hosting">
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Tăng doanh thu bán hàng và traffic trên website</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Giữ chân khách hàng tiềm năng ở lại website</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Nâng cao thứ hạng từ khóa và chất lượng site </li>
					</ul>
					<a href="#bang-gia-vps" class="link-cdt"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
							<path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"></path>
						</svg> Đăng Ký Ngay</a>
					<smal class="d-block mt-2"><i class="fa fa-gift" aria-hidden="true"></i> Tặng bộ theme & plugin WordPress bất kỳ trị giá hơn 500$</smal>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-12">
				<div class="col-inner">
					<img src="<?php echo THEME_URL_F . "/images/server-vps.png"; ?>" alt="server cao cấp cho donah nghiệp" width="auto" />
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-5">
				<div class="col-inner">
					<img class="border-radius-cdw shadow-cdw" src="<?php echo THEME_URL_F . '/images/server-vps-cao-cap.jpg'; ?>" alt="Server cao cấp cho doanh nghiệp và cá nhân" title="Đăng Ký Tên Miền">
				</div>
			</div>
			<div class="col-md-7">
				<div class="col-inner about-us" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
					<h2 class="sub-services">Hệ Thống Server Cao Cấp</h2>
					<h3 class="section-title">Server Cấu Hình Khủng <span class="title-color"> Bảo Mật Và An Ninh</span></h3>
					<p>VPS chúng tôi là dịch vụ VPS có cấu hình rất cao, phù hợp với tất cả loại hình doanh nghiệp vì không có loại server thấp hơn, nhưng giá phải chăng, không chia phân khúc, VPS chỉ 1 dùng 1 cấu hình duy nhất và khủng nhất tại việt nam, giá rẻ nhất so với cấu hình cùng phân khúc,<b> cộng đồng web</b> không muốn khách hàng phải sử dụng server kém chất lượng</p>
					<p>VPS sử dụng 100% ổ cứng SSD và <strong> công nghệ ảo hóa KVM </strong> mang lại hiệu suất vượt trội cho hệ thống và đặc biệt có sẵn hệ thống tường lửa chống DDos giúp bạn an tâm sử dụng </p>
				</div>
			</div>
		</div>
</section>
<section class="container-lg" data-theme="light">
	<div class="row align-center">
		<div class="col-md-10">
			<div class="text-center col-inner services-name" id="bang-gia-vps">
				<div class="note-title mt-3">Bảng Giá Server</div>
				<h3 class="section-title mb-0">Đăng Ký VPS Cao cấp Nhanh Với cấu hình cao và Giá Rẻ Nhất</h3>
				<p>Với cấu hình server khủng dành cho công ty doanh nghiệp lớn mà bất cứ ai cũng có thể sở hữu để tăng tốc độ cho website của mình, cho dù là sinh viên cũng có thể mua.</p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-inner">
				<table class="price-by-server form-table-domain" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500" data-aos-delay="300">
					<thead>
						<tr>
							<th>Gói dịch vụ</th>
							<th class="text-center">CPU</th>
							<th class="text-center">RAM</th>
							<th class="text-center">Ổ cứng SSD</th>
							<th>Giá</th>
							<th>Phần Mềm Quản Trị</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$arr = array(
							'post_type' => 'hosting',
							'post_status' => 'publish',
							'fields' => 'ids',
							'posts_per_page' => -1,
							'meta_key' => 'stt',
							'orderby' => 'meta_value',
							'order' => 'ASC',
						);
						$ids = get_posts($arr);
						foreach ($ids as $id) {
							$feature = get_post_meta($id, 'feature', true);
							$cpu = get_post_meta($id, 'cpu', true);
							$ram = get_post_meta($id, 'ram', true);
							$hhd = get_post_meta($id, 'hhd', true);
						?>
							<tr class="<?php echo $feature; ?>">
								<td data-label="Gói Dịch VỤ">
									<div><strong><?php echo get_the_title($id); ?></strong></div>
									<small><?php echo get_post_meta($id, 'sub-title', true); ?></small>
								</td>
								<td data-label="CPU">
									<div class="text-center">
										<img alt="icon Core CPU bảng giá VPS Server" width='25px' src="<?php echo THEME_URL_F . '/images/icon-chip.png' ?>">
										<?php echo -1 == $cpu ? "Tùy chỉnh" : $cpu . " Cores"; ?>
									</div>
								</td>
								<td data-label="RAM">
									<div class="text-center">
										<img alt="icon RAM bảng giá VPS Server" width='25px' src="<?php echo THEME_URL_F . '/images/icon-ram.png' ?>">
										<?php echo -1 == $ram ? "Tùy chỉnh" : $ram . "GB"; ?>
									</div>
								</td>
								<td data-label="Ổ cứng SSD">
									<div class="text-center">
										<img alt="icon ổ cứng SSD bảng giá VPS GPU" width='25px' src="<?php echo THEME_URL_F . '/images/icon-ssd.png' ?>">
										<?php echo -1 == $hhd ? "Tùy chỉnh" : $hhd . "MB"; ?>
									</div>
								</td>
								<td data-label="Giá">
									<?php
									$pricesr = get_post_meta($id, 'gia', true);
									if ($pricesr == -1) {
										$pricesr = 'Liên Hệ Chúng Tôi';
									} else {
										$pricesr = number_format($pricesr, 0, ',', '.');
									?><span><strong class="text-danger"><?php echo $pricesr; ?></strong> vnđ</span>
										<span>/Năm</span><?php
														} ?>
									<div>
										<?php
										switch ($feature) {
											case 'goi-pho-bien':
										?>
												<small>Yêu thích nhất</small>
											<?php
												break;
											case 'goi-cao-cap':
											?>

												<span><strong class="text-danger"><?php echo $pricesr; ?></strong></span>

										<?php
												break;
										}
										?>
									</div>
								</td>
								<td data-label="Quản trị">
									<?php echo get_post_meta($id, 'note', true); ?>
								</td>
								<td>
									<a rel="nofollow" data-idh="<?php echo $id; ?>" href="javascript:void(0);" class="link-cdt2 btn-buy">Đăng Ký Ngay</a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<div class="note-cdw border-radius-cdw mt-3 p-3">
					<strong class="text-warning">Lưu ý</strong> <span>Bảng giá VPS Server chưa bao gồm VAT</span>
				</div>
			</div>
		</div>
	</div>
</section>

<!--Tại sao chọn chúng tôi-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-xl-5 col-lg-5 col-md-4 col-sm-12 col-12">
				<div class="col-inner" data-aos="fade-up" data-aos-duration="3000">
					<h2 class="note-title">Lợi ích Sử Dụng VPS Server</h2>
					<h3 class="m-0">Vì Sao Chọn chúng tôi</h3>
					<p class="text-justify">Hãy thử sử dụng VPS server cộng đồng web, chúng tôi sẽ không làm bạn phải thất vọng với cấu hình khủng trên, kể bạn là cá nhân hay sinh viên đều có cơ hội sử dụng.</p>
					<a href="#bang-gia-vps" class="link-cdt1">Mua Ngay</a>
				</div>
			</div>
			<div class="col-xl-7 col-lg-7 col-md-8 col-sm-12 col-12">
				<div class="col-inner">
					<div class="row align-equal">
						<div class="loi-ich col-md-6 col-cdw">
							<div class="col-inner  shadow-cdw mb-4 hover-top p-3 dark border-radius-cdw">
								<div class="image-loi-ich">
									<img src="<?php echo THEME_URL_F . '/images/hosting-icon.png'; ?>" alt="Không Giới Hạn Data Transfer">
								</div>
								<div class="right-reason mt-4">
									<h4 class="size-cdw mt-0 title-color">Không Giới Hạn Data Transfer</h4>
									<p class="text-justify">Dịch vụ VPS Server của Cộng Đồng Web không bị giới hạn lượng Data Transfer. Vì vậy bạn không cần lo lắng về việc dữ liệu trao đổi bị ảnh hưởng đến hiệu suất của doanh nghiệp.</p>
								</div>
							</div>
						</div>
						<div class="loi-ich col-md-6 col-cdw">
							<div class="col-inner shadow-cdw mb-4 hover-top p-3 dark border-radius-cdw">
								<div class="image-loi-ich">
									<img src="<?php echo THEME_URL_F . '/images/security-icon.png'; ?>" alt="Hệ Thống Bảo Mật Tường Lửa">
								</div>
								<div class="right-reason mt-4">
									<h4 class="size-cdw mt-0 title-color">Hệ Thống Bảo Mật Tường Lửa</h4>
									<p class="text-justify">Hạ tầng máy chủ ở Cộng Đồng Web được bảo vệ bởi hệ thống chống tấn công DDoS chuyên nghiệp do chúng tôi phát triển, giúp nâng cao bảo mật và chống tấn công cho website của bạn.</p>
								</div>
							</div>
						</div>
						<div class="loi-ich col-md-6 col-cdw">
							<div class="col-inner  shadow-cdw mb-4 hover-top p-3 dark border-radius-cdw">
								<div class="image-loi-ich">
									<img src="<?php echo THEME_URL_F . '/images/Seo-icon.png'; ?>" alt="Tối Ưu Tốc Độ Load Và Thứ Hạng">
								</div>
								<div class="right-reason mt-4">
									<h4 class="size-cdw mt-0 title-color">Tối Ưu Tốc Độ Load Và Thứ Hạng</h4>
									<p class="text-justify">Chúng tôi sử dụng công nghệ ảo hóa KVM, mang lại hiệu suất vượt trội, không giới hạn traffic, website bạn load nhanh dưới 3s và cải thiện thứ hạng từ khoá google.</p>
								</div>
							</div>
						</div>
						<div class="loi-ich col-md-6 col-cdw">
							<div class="col-inner  shadow-cdw mb-4 hover-top p-3 dark border-radius-cdw">
								<div class="image-loi-ich">
									<img src="<?php echo THEME_URL_F . '/images/chat-icon.png'; ?>" alt="Thiết Kế Website Chuẩn SEO">
								</div>
								<div class="right-reason mt-4">
									<h4 class="size-cdw mt-0 title-color">Đội Ngũ Hỗ Trợ 24/24 Chuyên Nghiệp</h4>
									<p class="text-justify">Cộng Đồng Web sở hữu đội ngũ kỹ thuật nhiều kinh nghiệp luôn sẵn sàng hỗ trợ bạn thông qua Ticket, Livechat, Hotline,… hỗ trợ giải quyết các vấn đề một cách nhanh chóng.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--khách hàng-->

<!--Câu Hỏi-->
<section class="section">
	<div class="container-lg">
		<div class="row align-middle">
			<div class="col-md-12">
				<div class="text-center">
					<h2 class="section-title">Những Câu Hỏi Thường Gặp Về <span class="title-color"> VPS Server Hosting</span></h2>
					<p class="section-title-description">
						nhưng câu hỏi và thắc mắc các bạn, chúng tôi sẽ cố gắng hỗ trợ 24/24, nếu có câu hỏi khác bạn có thể liên hệ với chúng tôi qua ticket
					</p>
				</div>
				<div class="icon-cdw mb-3" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-delay="300">
					<div class="accordion" id="accordionExample">
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading1">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Dịch vụ VPS Server của Cộng Đồng Web được đặt ở đâu?
								</button>
							</h4>
							<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Hệ thống máy chủ của chúng tôi được đặt tại Singapore, nơi được ưu tiên nhất, đảm bảo tốc độ đường truyền Internet mạnh mẽ, nhanh, ổn định giữa các nước quốc tế, không bị chi phối giữa nước này qua nước khác, dù khách hàng bạn ở bất cứ đâu truy cập web không quá 3s.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading2">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Dịch vụ VPS tại Cộng Đồng Web hỗ trợ những hệ điều hành nào?
								</button>
							</h4>
							<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									– CentOS 7 <br>
									– Ubuntu Server 16, 18, 20 <br>
									– Windows Server 2008, 2012, 2016, 2019 <br>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading3">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									Cộng Đồng Web có hỗ trợ khách hàng cài đặt VPS và chuyển dữ liệu không?
								</button>
							</h4>
							<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Chúng tôi hỗ trợ cài đặt và chuyển dữ liệu cho bạn hoàn toàn miễn phí. Sau khi đăng ký dịch vụ VPS, bạn chỉ cần gửi ticket đến phòng kỹ thuật để yêu cầu hỗ trợ, đội ngũ kỹ thuật chuyên nghiệp tại Cộng Đồng Web sẽ giúp bạn chuyển dữ liệu một cách nhanh chóng.
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h4 class="accordion-header m-0" id="heading4">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
									Tôi có được hỗ trợ khi đăng ký VPS không?
								</button>
							</h4>
							<div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									Đăng ký VPS là loại dịch vụ “Tự quản lý” có nghĩa là bạn toàn quyền quản trị và chịu trách nhiệm khi sử dụng VPS. nhưng bên kỹ thuật cộng đồng web có thể hỗ trợ bạn sau khi mua và trong quá trình sử dụng, chi phí phát sinh sẽ được tính phụ thêm nếu không nằm trong giới hạn dịch vụ chúng tôi.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>
<?php get_footer(); ?>