<?php

wp_enqueue_style('sweetalert2-style');
wp_enqueue_script('sweetalert2-script');
wp_enqueue_script('captcha-script');
wp_enqueue_script('order-script');
get_header(); ?>
<!-- Page content-->
<div class="background-header-single">
	<div class="container align-center text-center dark">
		<div class="header-single">
			<a href="<?php echo home_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="Cộng Đồng Theme - <?php echo the_title(); ?>" title="<?php echo the_title(); ?>"></a>
			<h1 class="entry-title">
				Mẫu <?php echo the_title(); ?>
			</h1>
			<?php echo do_shortcode('[rank_math_breadcrumb]'); ?>
		</div>
	</div>
	<div class="trick"></div>
</div>
<!--Single website desinger-->
<div class="section single-template-web">
	<div class="container">
		<div class="row align-center">
			<div class="col-lg-7">
				<h2 class="title-website"><?php echo get_field("name");  ?></h2>
				<div class="category-thietke">
					<?php
					$terms = get_the_terms($post->ID, 'site-types');
					if ($terms && !is_wp_error($terms)) :
						$draught_links = array();
						foreach ($terms as $term) {
							$url = get_term_link($term);
					?>
							<span><a href="<?php echo $url; ?>"><?php echo  $term->name; ?></a></span>
						<?php
						}
						?>

					<?php endif; ?>
				</div>
				<div class="frame-thiet-ke1">
					<div class="img-thiet-ke">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail("full", array("title" => get_the_title(), "alt" => get_the_title())); ?></a>
					</div>
				</div>
			</div>
			<div class="col-lg-5 background-web">
				<div class="frame-price-website">
					<div class="frame-bottom-web align-middle">
						<h4><?php echo the_title(); ?></h4>
						<span class="live-demo"><a href="<?php echo "/demo/?id-mau=" . get_the_id(); ?>"><i class="fa fa-globe"></i> Xem Live Demo</a></span>
					</div>
					<?php
					$valuePrice = get_field("price");
					$price = "";
					if (is_numeric($valuePrice))
						$price = number_format($valuePrice, 0, ',', '.');
					else
						$price = $valuePrice;
					?>
					<input type="hidden" id="id-site" data-price="<?php echo $valuePrice; ?>" value="<?php echo get_the_id(); ?>">
					<p class="title-side-sub">Chức năng có sẵn:</p>
					<ul>
						<li>Website chuẩn SEO</li>
						<li>Giao diện di động thân thiện</li>
						<li>Tối ưu tốc độ tải nhanh nhất</li>
						<li>Bảo mật cao</li>
						<li>Hỗ trợ support 1 năm</li>
					</ul>
					<div class="title-side-sub">Chức năng có thêm:
						<span class="notification-icon"><i class="fa fa-question-circle" aria-hidden="true"></i>
							<p class="notification-text">KHi các bạn chọn mua Hosting/ VPS/ Server hoặc tên miền thì kỹ thuật cộng đồng theme hỗ trợ cài đặt hệ thống hoàn chỉnh website cho quý khách!
							</p>
						</span>
					</div>

					<div class="form-check-price" style="overflow-x:auto;">
						<table class="table-price table-hosting" cellpadding="2">
							<th>Hosting/ VPS/ Server</th>
							<tr class="radio-price">
								<td>
									<p class="name">Chỉ mua theme</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">Miễn phí</span>
										<input class="form-check-input" data-type="0" data-price="0" checked type="radio" name="hosting" id="hosting">
									</label>
								</td>
							</tr>
							<tr class="radio-price">
								<td>
									<p class="name">Gói Hosting tiêu chuẩn</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">1.200.000 VNĐ</span>
										<input class="form-check-input" data-type="1" data-price="1200000" type="radio" name="hosting" id="hosting">
									</label>
								</td>
							</tr>
							<tr class="radio-price">
								<td>
									<p class="name">Gói Hosting doanh nghiệp</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">1.500.000 VNĐ</span>
										<input class="form-check-input" data-type="2" data-price="1500000" type="radio" name="hosting" id="hosting">
									</label>
								</td>
							</tr>
							<tr class="radio-price">
								<td>
									<p class="name">Gói Hosting cao cấp</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">2.000.000 VNĐ</span>
										<input class="form-check-input" data-type="3" data-price="2000000" type="radio" name="hosting" id="hosting">
									</label>
								</td>
							</tr>
						</table>
						<table class="table-price table-domain" cellpadding="2">
							<th>Tên Miền</th>
							<tr class="radio-price">
								<td>
									<p class="name">Tôi đã có tên miền</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">Miễn phí</span>
										<input class="form-check-input" data-type="0" data-price="0" checked type="radio" name="domain" id="domain">
									</label>
								</td>
							</tr>
							<tr class="radio-price">
								<td>
									<p class="name">Domain quốc tế .com .net</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">300.000 VNĐ</span>
										<input class="form-check-input" data-type="1" data-price="300000" type="radio" name="domain" id="domain">
									</label>
								</td>
							</tr>
							<tr class="radio-price">
								<td>
									<p class="name">Domain Việt Nam .vn .com.vn</p>
								</td>
								<td>
									<label class="checklb">
										<span class="txt">7.50.000 VNĐ</span>
										<input class="form-check-input" data-type="2" data-price="750000" type="radio" name="domain" id="domain">
									</label>
								</td>
							</tr>
						</table>
					</div>
					<div class="total-price">
						<span class="lb">Tổng giá:</span>
						<span class="totalprice"><?php echo $price; ?> VNĐ</span>
					</div>
					<small>(Giá Chưa bao gồm VAT)</small>
					<a href="javascript:void(0)" class="add-to-cart-web">
						<b>Thêm vào giỏ hàng</b> <i class="fa fa-shopping-cart" aria-hidden="true"></i>
					</a>
					<div class="order-web text-center">
						<a href="javascript:void(0)" class="btn-modal-order">
							<b>Đặt hàng ngay</b> nhận website sau 7 ngày <i class="fa fa-angle-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="description-single">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<h3 class="section-title">
					Hình ảnh chức năng <?php echo the_title(); ?>
				</h3>
				<div>
					<?php echo do_shortcode('[lightgallery id_element="image-gallery" id_post="' . get_the_ID() . '"]'); ?>
				</div>
			</div>
			<div class="col-lg-6">
				<h3>
					Mô tả <?php echo the_title(); ?>
				</h3>
				<div>
					<?php echo the_content(); ?>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="10"></div>
			<div id="fb-root"></div>
			<script>
				(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s);
					js.id = id;
					js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=1732012147096227";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
		</div>
	</div>
</div>
<!--các mẫu web khác-->
<div class="section">
	<?php
	$postType = 'site-managers';
	$taxonomyName = 'site-types';
	$taxonomy = get_the_terms(get_the_ID(), $taxonomyName);
	if ($taxonomy) {
		echo '<div class="related-website container">';
		$category_ids = array();
		foreach ($taxonomy as $individual_category) $category_ids[] = $individual_category->term_id;
		$args = array(
			'post_type' =>  $postType,
			'post__not_in' => array(get_the_ID()),
			'posts_per_page' => 4,
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomyName,
					'field'    => 'term_id',
					'terms'    => $category_ids,
				),
			)
		);
		$my_query = new wp_query($args);
		if ($my_query->have_posts()) :
			echo '<h3>Các Dự Án Website Khác</h3>';
	?>
			<div class="row">
				<?php
				while ($my_query->have_posts()) : $my_query->the_post();
					get_template_part('template-parts/site-managers/archive', 'content-item');
				endwhile;
				?>
			</div>
	<?php
		endif;
		wp_reset_query();
		echo '</div>';
	}
	?>
</div>
<?php get_footer(); ?>