<?php
/*
 Template Name: Whois Domain
 */

wp_enqueue_script('whois-domain');
wp_enqueue_script('order-script');
require_once('libs/inet/inet.php');
if (isset($_GET['ten-mien'])) {

    $parsed = parse_url($_GET['ten-mien']);
	$domain_name = isset($parsed['host']) ?$parsed['host']: $parsed['path'];
    $domain_name = preg_replace('/^www\./', '', $domain_name) ;
    
	$params = [
		'domain' => $domain_name,
	];
	$inet_CheckAvailability = inet_CheckAvailability($params);
	$inet_GetNameservers = inet_GetNameservers($params);
	$inet_GetContactDetails = inet_GetContactDetails($params);
	$inet_Sync = inet_Sync($params);
}
get_header(); ?>

<div class="breadcrumb-domain">
	<div class="container">
		<?php echo do_shortcode('[rank_math_breadcrumb]'); ?>
	</div>
</div>
<main class="container" data-theme="light">
	<form id="form-whois-domain" action="/check-domain/" method="get" class="form-search-baner form-check-domain">
		<input class="form-control my-0 py-1 red-border" type="text" id="ten-mien" name="ten-mien" placeholder="Nhập tên miền bạn cần kiểm tra" value="<?php echo isset($_GET['ten-mien']) ?  $domain_name : ""; ?>" required>
		<button class="input-group-text red lighten-3 submit" type="submit">Kiểm Tra</button>
	</form>
	<div class="frame-whois-domain">
		<div class="row">
			<div class="col-md-8">
				<h1 class="section-title">
					Thông Tin <span class="title-color">Whois Doamin</span>
				</h1>
				<h3 class="sub-services">
					<?php echo isset($_GET['ten-mien']) ?  $domain_name : "Không có tên miền để kiểm tra"; ?>
				</h3>
				<table class="form-table-domain form-whois-domain-result" cellpadding="2">
					<tbody>
						<?php if (isset($_GET['ten-mien']) && $inet_CheckAvailability['status'] == 'STATUS_REGISTERED') { ?>

							<tr>
								<td>
									Tên miền
								</td>
								<td>
									<a href="<?php echo $domain_namel; ?>"><?php echo isset($_GET['ten-mien']) ?  $domain_name : "Không có tên miền để kiểm tra"; ?></a>
								</td>
							</tr>
							<tr>
								<td>
									Ngày đăng ký:
								</td>
								<td>
									<?php
									if (!isset($inet_Sync['error']))
										echo date('d-m-Y', strtotime($inet_Sync['issueDate']));
									else echo 'Không tìm thấy dữ liệu'; ?>
								</td>
							</tr>
							<tr>
								<td>
									Ngày hết hạn:
								</td>
								<td>
									<?php
									if (!isset($inet_Sync['error']))
										echo date('d-m-Y', strtotime($inet_Sync['expirydate']));
									else echo 'Không tìm thấy dữ liệu'; ?>
								</td>
							</tr>
							<tr>
								<td>
									Chủ sở hữu tên miền:
								</td>
								<td>
									<?php
									if (!isset($inet_GetContactDetails['error']))
										echo $inet_GetContactDetails['Registrar']['FullName'];
									else echo 'Không tìm thấy dữ liệu'; ?>
								</td>
							</tr>
							<tr>
								<td>
									Cờ trạng thái
									<a target="_blank" href="/trang-thai-co-ten-mien-la-gi/" title="Cờ trạng thái tên miền là gì?">
										<i class="fa fa-question-circle" aria-hidden="true"></i>
									</a>:
								</td>
								<td>
									<?php
									if (!isset($inet_Sync['error']))
										echo $inet_Sync['domainStatuses'];
									else echo 'Không tìm thấy dữ liệu'; ?>
								</td>
							</tr>
							<tr>
								<td>
									Quản lý tại Nhà đăng ký:
								</td>
								<td>
									<?php
									if (!isset($inet_GetContactDetails['error']))
										echo $inet_GetContactDetails['Registrar']['Registrar'];
									else echo 'Không tìm thấy dữ liệu';
									?>
								</td>
							</tr>
							<tr>
								<td>
									Nameservers:
								</td>
								<td>
									<ul>
										<?php
										if (count($inet_GetNameservers) > 0) {
											foreach ($inet_GetNameservers as $key => $value) {
												echo '<li>' . $key . ': ' . $value . '</li>';
											}
										} else
											echo '<li>Không tìm thấy dữ liệu</li>';
										?>
									</ul>
								</td>
							</tr>


						<?php } else { ?>
							<tr>
								<td>
									Tên miền:
								</td>
								<td>
									Tên miền chưa đăng ký
								</td>
							</tr>
						<?php
						} ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-4">
				<ul class="list-domain-whois">
					<h3 class="title-whois ">
						Các Domain Liên Quan
					</h3>
				</ul>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>