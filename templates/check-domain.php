<?php
defined('ABSPATH') || exit;
/*
 Template Name: Check Domain
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
<section class="check-domain background-1">
	<div class="container">
		<div class="row align-middle">
			<div class="col-lg-8 col-md-8 col-sm-12 col-12">
				<div class="col-inner about-us dark">
					<h1 class="my-0 text-shadow">Kiểm Tra Và Đăng Ký Tên Miền</h1>
					<p class="my-0">Mua tên miền để bắt đầu việc kinh doanh của bạn ngay hôm nay!</p>
				</div>
				<ul class="nav nav-tabs" id="domainTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="check-domain-tab" data-bs-toggle="tab" data-bs-target="#check-domain" type="button" role="tab" aria-controls="check-domain" aria-selected="true">
							<i class="fa fa-globe mx-2"></i>Kiểm tra tên miền
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="transfer-domain-tab" data-bs-toggle="tab" data-bs-target="#transfer-domain" type="button" role="tab" aria-controls="transfer-domain" aria-selected="false">
							<i class="fa fa-retweet mx-2 dark"></i>Chuyển về Cộng đồng Web
						</button>
					</li>
				</ul>
				<div class="tab-content" id="domainTabContent">
					<div class="tab-pane fade show active" id="check-domain" role="tabpanel" aria-labelledby="check-domain-tab">
						<div class="input-group form-check-domain">
							<input type="text" id="domain-text" name="domain-text" placeholder="Nhập tên miền bạn cần kiểm tra ..." class="form-control" value="<?php echo $domain; ?>" required="" />
							<button type="submit" class="btn btn-primary btn-check-domain">Kiểm tra</button>
						</div>
					</div>
					<div class="tab-pane fade" id="transfer-domain" role="tabpanel" aria-labelledby="transfer-domain-tab">
						<div class="row clearfix">
							<div class="col-lg-8 col-md-6 col-sm-6 col-12">
								<div class="form-group">
									<label for="transfer-domain-text" class="control-label">Tên miền</label>
									<input type="text" id="transfer-domain-text" name="transfer-domain-text" class="form-control" placeholder="example.com" required />
								</div>
							</div>
							<div class="col-lg-4 col-md-6 col-sm-6 col-12">
								<div class="form-group">
									<label for="transfer-auth-code" class="control-label">Authorization Code</label>
									<input type="text" id="transfer-auth-code" name="transfer-auth-code" class="form-control" placeholder="Epp Code / Auth Code" required />
								</div>
							</div>
							<button type="submit" class="btn btn-primary btn-transfer-domain">Chuyển</button>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="d-none d-xl-block col-inner">
					<img src="<?php echo THEME_URL_F . "/images/check-domain.png"; ?>" alt="tìm kiếm domain" width="auto" />
				</div>
			</div>
		</div>

		<div class="row clearfix result-check-domain mt-3">
		</div>
	</div>
</section>
<section class="section">
	<div class="container">
		<div class="row align-middle">
			<div class="col-md-7">
				<div class="col-inner about-us">
					<h2 class="sub-services">Gợi ý đăng ký tên miền</h2>
					<h3 class="section-title">Mua Tên Miền Xây Dựng <span class="title-color"> Thương Hiệu Của Bạn</span></h3>
					<ul class="domain">
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Càng ngắn gọn càng tốt</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Dễ nhớ, dễ phát âm, dễ đánh vần không, đọc dễ thuận miệng để truyền đạt</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Không nên đặt tên domain khó viết trên thanh trình duyệt web</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Không nên chứa dấu nối "-" hay ký tự lạ</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Nên chứa từ khoá thương hiệu (Brand) hoặc sản phẩm dịch vụ của bạn</li>
						<li><i class="fa fa-check-square-o" aria-hidden="true"></i> Nên đăng ký bao vây các đuôi tên miền để bảo vệ thương hiệu của bạn</li>
					</ul>
				</div>
			</div>
			<div class="col-md-5">
				<div class="col-inner">
					<img class="border-radius-cdw shadow-cdw" src="<?php echo THEME_URL_F . '/images/dang-ky-ten-mien.jpg'; ?>" alt="Giợi ý Mua Tiên Miền" title="Đăng Ký Tên Miền">
				</div>
			</div>
		</div>
</section>
<main class="container" data-theme="light">
	<div class="row align-center">
		<div class="col-md-10">
			<div class="text-center col-inner services-name">
				<div class="note-title mt-3">Bảng Giá Tên Miền</div>
				<h3 class="section-title">Đăng Ký Tên Miền Nhanh Với Nhiều Loại Khác Nhau Với Giá Rẻ Nhất</h3>
				<p>Thủ tục đăng ký tên miền hoàn toàn online. Hệ thống đạt chuẩn cấp độ 4 - cấp độ cao nhất về dịch vụ công trực tuyến, bảo vệ quyền riêng tư và thông tin khách hàng</p>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
			<div class="row align-equal">
				<?php
				foreach ($ids as $id) {
					$title = get_the_title($id);
					$gia = get_post_meta($id, 'gia', true);
					$note = get_post_meta($id, 'note', true);
					$gia = $CDWFunc->number->amountDisplay($gia);
				?>
					<div class="col-md-6 col-6 col-lg-3 mb-3 col-cdw">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title mt-0"><?php echo $title; ?></h5>
								<p class="card-text"><?php echo $note; ?></p>
								<p class="card-subtitle mb-2 text-danger"><?php echo $gia; ?>/Năm</p>
								<a href="#" class="btn btn-primary link-cdt2">Mua Ngay</a>

							</div>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>


</main>

<?php get_footer(); ?>