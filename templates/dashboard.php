<?php
defined('ABSPATH') || exit;
/*
 Template Name: Dashboard
 */
get_header();
global $current_user;

$useremail = $current_user->user_email;
$username = $current_user->user_login;

$firstname = $current_user->user_firstname;
$lastname = $current_user->user_lastname;
$display_name = $current_user->display_name;

$avatar = get_field('user_avatar', 'user_' . $current_user->ID);
$user_phone = get_field('user_phone', 'user_' . $current_user->ID);
$user_address = get_field('user_address', 'user_' . $current_user->ID);

?>
<?php

// args
$args = array(
	'numberposts'	=> -1,
	'post_type'		=> 'site-orders',
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key' => 'customer',
			'value' => $current_user->ID,
			'compare' => '='
		)
	)
);


// query
$the_query = new WP_Query($args);
$countInvoice = $the_query->found_posts;
$countService = 0;
$countDomain = 0;
$countHosting = 0;

if ($the_query->have_posts()) :

	while ($the_query->have_posts()) : $the_query->the_post();
		$id = get_the_ID();
		$items = get_item_order($id);
		if (is_array($items) && count($items) > 0) {
			$countService += count($items);
			foreach ($items as $item) {
				if ($item['hosting'] != 0) {
					$countHosting++;
				}
				if ($item["domain"] != 0) {
					$countDomain++;
				}
			}
		}
	endwhile;
endif;
?>
<div class="dashboard">
	<div class="nav-dashboard">
		<div class="nav-sidebar">
			<h3>
				Hello : <?php echo $display_name; ?>
			</h3>
			<i onclick="toggleMenu();" class="fa fa-bars iconbar" aria-hidden="true"></i>
		</div>
		<ul>
			<li>
				<a href=""><i class="fa fa-dashboard"></i> Bảng Điều Khiển</a>
			</li>
			<li>
				<a href="#"><i class="fa fa-user" aria-hidden="true"></i> Thông Tin Tài Khoản<span class="fa arrow"></span></a>
			</li>
			<li>
				<a href="#"><i class="fa fa-server" aria-hidden="true"></i> Dịch Vụ Sử Dụng<span class="fa arrow"></span></a>
			</li>
			<li>
				<a href="#"><i class="fa fa-globe" aria-hidden="true"></i> Tên Miền<span class="fa arrow"></span></a>
			</li>
			<li>
				<a href="#"><i class="fa fa-bar-chart-o"></i> Hỗ Trợ<span class="fa arrow"></span></a>
			</li>
		</ul>
	</div>
	<div class="main-dashboard" id="show-bar">
		<div class="header-dashbord">
			<i style="display: none" id="show-bar1" onclick="toggleMenu1();" class="fa fa-bars toggle" aria-hidden="true"></i>
			<h2 class="section-title">Dashboard</h2>
		</div>
		<div class="content-dashboard">
			<div class="row welcome align-center align-middle">
				<div class="col-md-7">
					<div class="col-inner">
						<h3>Chào <?php echo $display_name; ?></h3>
						<p>Cộng Đồng Theme chân thành cảm ơn quý khách đã đồng hành với chúng tôi, với mục tiêu uy tín mang lại thương hiệu, Chúng tôi luôn nâng cao chất lượng dịch vụ đến khách hàng.</p>
						<p>Cùng tham quan dịch vụ và dự án của chúng tôi đã làm <a class="linkngoai" href="https://congdongtheme.com/kho-giao-dien/"> Tại Đây </a></p>
					</div>
				</div>
				<div class="col-md-5">
					<div class="col-inner">
						<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/05/icon_gender_1.png" style="width: 65%;margin: auto; display: block;" alt="dashboard cá nhân">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="panel">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-comments fa-5x"></i>
								</div>
								<div class="col-md-9 text-right">
									<div class="huge">26</div>
									<div>Hỗ Trợ !</div>
								</div>
							</div>
						</div>
						<a href="#">
							<div class="panel-footer">
								<span class="pull-left">View Details</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-tasks fa-5x"></i>
								</div>
								<div class="col-md-9 text-right">
									<div class="huge"><?php echo $countService + $countHosting; ?></div>
									<div>Dịch Vụ & Hosting</div>
								</div>
							</div>
						</div>
						<a href="#">
							<div class="panel-footer">
								<span class="pull-left">View Details</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-shopping-cart fa-5x"></i>
								</div>
								<div class="col-md-9 text-right">
									<div class="huge"><?php echo $countInvoice; ?></div>
									<div>Hoá Đơn</div>
								</div>
							</div>
						</div>
						<a href="#">
							<div class="panel-footer">
								<span class="pull-left">View Details</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-globe fa-5x" aria-hidden="true"></i>
								</div>
								<div class="col-md-9 text-right">
									<div class="huge"><?php echo $countDomain; ?></div>
									<div>Tên Miền</div>
								</div>
							</div>
						</div>
						<a href="#">
							<div class="panel-footer">
								<span class="pull-left">View Details</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="header-dashbord">
			<i style="display: none" id="show-bar1" onclick="toggleMenu1();" class="fa fa-bars toggle" aria-hidden="true"></i>
			<h2 class="section-title">Thông Tin Tài Khoản</h2>
		</div>
		<div class="content-dashboard">
			<div class="row welcome align-center align-middle">
				<div class="col-md-3 thongtin-image">
					<div class="col-inner">
						<img src="<?php echo $avatar['url']; ?>" alt="<?php echo $display_name; ?>" title="<?php echo $display_name; ?>">
					</div>
				</div>
				<div class="col-md-9">
					<div class="col-inner">
						<ul style="padding: 15px; margin:0">
							<li>Họ Và Tên: <?php echo $firstname . " " . $lastname; ?></li>
							<li>Số Điện Thoại: <?php echo $user_phone; ?></li>
							<li>Địa Chỉ: <?php echo $user_address; ?></li>
							<li>Email: <?php echo $useremail; ?></li>
						</ul>
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Thay Đổi Thông Tin</button>

						<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog form-thaydoi-thongtin">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Thay Đổi Thông Tin</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<form>
											<div class="form-floating">
												<input type="text" class="form-control" id="Namethongtincn" placeholder="Họ và tên" required>
												<label for="Namethongtincn"><i class="fa fa-address-card-o px-2" aria-hidden="true"></i> Họ và tên</label>
												<div class="invalid-feedback">
													Vui lòng nhập họ và tên.
												</div>
											</div>
											<div class="form-floating">
												<input type="tel" class="form-control" id="Phonethongtincn" placeholder="Số điện thoại" required>
												<label for="Phonethongtincn"><i class="fa fa-phone  px-2" aria-hidden="true"></i> Số điện thoại</label>
												<div class="invalid-feedback">
													Vui lòng nhập số điện thoại.
												</div>
											</div>
											<div class="form-floating">
												<input type="text" class="form-control" id="Addressthongtincn" placeholder="Địa chỉ" required>
												<label for="Adressthongtincn"><i class="fa fa-location-arrow  px-2" aria-hidden="true"></i> Địa chỉ</label>
												<div class="invalid-feedback">
													Vui lòng nhập Địa chỉ.
												</div>
											</div>
											<div class="form-floating">
												<input type="password" class="form-control" id="Passwordthongtincn" placeholder="Mật khẩu" required>
												<label for="Passwordthongtincn"><i class="fa fa-key  px-2" aria-hidden="true"></i> Mật khẩu</label>
												<div class="invalid-feedback">
													Vui lòng nhập mật khẩu.
												</div>
												<span id="status-password" class="d-none"></span>
											</div>
											<div class="form-floating">
												<input type="password" class="form-control" id="RePasswordthongtincn" placeholder="Nhập lại mật khẩu" required>
												<label for="RePasswordthongtincn"><i class="fa fa-key  px-2" aria-hidden="true"></i> Nhập lại mật khẩu</label>
												<div class="invalid-feedback">
													Vui lòng nhập lại mật khẩu.
												</div>
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary">Send message</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="header-dashbord">
			<i style="display: none" id="show-bar1" onclick="toggleMenu1();" class="fa fa-bars toggle" aria-hidden="true"></i>
			<h2 class="section-title">Dịch Vụ Sử Dụng <?php echo $current_user->ID; ?></h2>
		</div>
		<div class="content-dashboard">
			<div style="overflow-x:auto;">
				<table cellpadding="6">
					<tr>
						<th>STT</th>
						<th>Số đơn hàng</th>
						<th>Tên Dịch Vụ</th>
						<th>Gói Sử Dụng</th>
						<th>Ngày</th>
						<th>Trạng Thái</th>
						<th>Hành Động</th>
					</tr>

					<?php if ($the_query->have_posts()) : ?>

						<?php while ($the_query->have_posts()) : $the_query->the_post();
							$id = get_the_ID();
							$items = get_item_order($id);
							$timeDiff = get_date_diff_order($id);
							$date = get_the_date("d/m/Y", $id);

						?>
							<?php
							if (is_array($items) && count($items) > 0) {
								$i = 1;
								foreach ($items as $item) {
									$name = "Gói giao diện";
									$vps = 'Đăng Ký: ' . $date . ' (' . $timeDiff . ')<br>';
									$domain = "";
									if ($item['hosting'] != 0) {
										$vps .= '<strong>	- ' . $item["noteHosting"] . ' </strong><br>';
										$name .= '<br> Hosting';
									}
									if ($item["domain"] != 0) {
										$domain = '<strong class="jframe-domain">	- ' . $item["noteDomain"] . ' </strong> 
										<span>Domain: ' .  $item["yourDomain"] . '</span> ';
										$name .= `<br> Domain`;
									}
									// var_dump($item)
							?>
									<tr id="item-<?php echo $item['id']; ?>">
										<td><?php echo $the_query->current_post + 1; ?></td>
										<td><?php echo get_code_order($id); ?></td>
										<td><?php echo $name; ?></td>
										<td>
											<span class="item-quantity">1 </span> x
											<a href="<?php echo $item['url']; ?>">
												<?php echo $item['name'];
												echo '( x ';
												if (is_numeric($item['price']))
													echo number_format($item['price'], 0, ',', '.');
												else
													echo $item['price'];
													
												echo ' VND)';
												?>
												
											</a>

											<?php
											if ($item["hosting"] != 0) {
												echo '<br>';
												echo '- Hosting:  1 x ' . number_format($item['hosting'], 0, ',', '.') . ' VND';
											}
											if ($item["domain"] != 0) {
												echo '<br>';
												echo '- Domain:  1 x ' . number_format($item['domain'], 0, ',', '.') . ' VND';
											}
											?>
											<hr>
											<strong>- Tổng cộng:
												<?php
												if (is_numeric($item['total'])) {
													echo number_format($item['total'], 0, ',', '.');
												} else
													echo $item['total'];
												?>
												VND
											</strong>
										</td>
										<td>
											<?php echo $vps; ?>
											<?php echo $domain; ?>
										</td>
										<td><?php echo get_post_status_object(get_post_status())->label; ?></td>
										<td>...</td>
									</tr>

							<?php
								}
							} ?>


						<?php endwhile; ?>

					<?php endif; ?>

					<?php wp_reset_query();	 // Restore global post data stomped by the_post(). 
					?>
				</table>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function toggleMenu() {
			var iconbar = document.querySelector('.main-dashboard');
			var iconbar3 = document.querySelector('.toggle');
			iconbar.classList.add('active');
			iconbar3.style.display = "block";

		}

		function toggleMenu1() {
			var iconbar1 = document.getElementById("show-bar");
			var iconbar2 = document.getElementById("show-bar1");
			iconbar1.classList.remove('active')
			iconbar2.style.display = "none";
		}
	</script>
	<?php get_footer(); ?>