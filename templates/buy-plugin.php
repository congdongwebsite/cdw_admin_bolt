<?php
/*
 Template Name: Kho plugin
 */
get_header();
$search_query = get_query_var('search') ? sanitize_text_field(get_query_var('search')) : (isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '');
?>
<!-- Page content-->
<div class="background-header-single">
	<div class="container-lg align-center text-center dark">
		<div class="header-single">
			<a href="<?php echo home_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="Cộng Đồng Web - <?php echo the_title(); ?>" title="<?php echo the_title(); ?>"></a>
			<h1 class="entry-title">
				<?php echo the_title(); ?> Về Cộng Đồng Web
			</h1>
			<?php echo do_shortcode('[rank_math_breadcrumb]'); ?>
		</div>
	</div>
	<div class="trick"></div>
</div>
<!--Single website desinger-->
<div class="section">
	<div class="container-lg">
		<div class="form-search-baner col-inner">
			<div class="note-title mt-3">Tìm Thương Hiệu Riêng Mình</div>
			<div class="services-name">
				<h3 class="section-title">Đừng bỏ lỡ ý tưởng kinh doanh của mình!</h3>
			</div>
			<div class="input-group md-form form-sm form-2 pl-0">
				<form id="form-check-domain-home" action="<?php echo home_url('/kho-plugin/'); ?>" method="GET" class="form-search-baner form-check-domain form-check-domain-home">
					<input class="my-0 py-1 red-border" id="plugin" name="search" type="text" placeholder="Tìm kiếm modul, plugin cho riêng bạn" aria-label="Search" value="<?php echo esc_attr($search_query); ?>">
					<div class="input-group-append">
						<button class="input-group-text red lighten-3 submit" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
								<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
							</svg> Tìm Kiếm</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="container py-4 ">
	<div class="row align-center align-equal list-plugin">
		<?php
		if (get_query_var('paged')) {
			$paged = get_query_var('paged');
		} elseif (get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		$args = array(
			'post_type' => 'plugin',
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => 20,
			'paged' => $paged,
		);
		if (!empty($search_query)) {
			$args['s'] = $search_query;
		}
		$plugins = new WP_Query($args);
		if ($plugins->have_posts()) :
			while ($plugins->have_posts()) : $plugins->the_post();
				$id = get_the_ID();
				$name = get_post_meta($id, 'name', true);
				$price = get_post_meta($id, 'price', true);
				$module_version = get_post_meta($id, 'module_id', true);
				$module_version_name = get_post_meta($module_version, 'name', true);
				$type = wp_get_post_terms($id, 'plugin-type', array('fields' => 'names'));
				$thumbnail_id = get_post_thumbnail_id($id);
				$image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : home_url() . '/wp-content/uploads/2025/07/learndash-woocommerce-integration-Cong-Dong-Web.png';
				$date = get_the_date('d/m/Y', $id);
		?>
				<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
					<div class="services-name text-center shadow-cdw border-radius-cdw hover-top">
						<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($name ? $name : get_the_title()); ?>" width="100%">
						<div class="col-inner">
							<div class="d-flex justify-content-center gap-3 mb-2">
								<div class="cdw-date bg-warning text-dark px-2 py-1 rounded small">
									<i class="fa-solid fa-calendar-days"></i><?php echo esc_html($date); ?>
								</div>
								<div class="cdw-version bg-primary text-white px-2 py-1 rounded small">
									<?php echo esc_html($module_version_name); ?>
								</div>
							</div>
							<div class="cdw-type small text-muted"><?php echo esc_html(implode(', ', $type)); ?></div>
							<a href="<?php the_permalink(); ?>">
								<h3 class="section-title m-1"><?php echo esc_html($name ? $name : get_the_title()); ?></h3>
							</a>
							<div class="cdw-price mt-2">
								<?php if ($price && $price != '0') : ?>
									<span><?php echo esc_html(number_format($price, 0, ',', '.')); ?> VNĐ</span>
									<!-- <span class="text-danger fw-bold">0 VNĐ</span> -->
								<?php else : ?>
									<span class="text-danger fw-bold">Miễn phí</span>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
		<?php
			endwhile;
			if (function_exists('shw_pagination')) {
				shw_pagination($plugins);
			}
			wp_reset_postdata();
		else :
			echo '<div class="col-12 text-center">Không có plugin nào được tìm thấy.</div>';
		endif;
		?>
	</div>
</div>

<div class="section single-template-web">
	<div class="container-lg">
		<div class="row align-center align-middle">
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12  about-left">
				<h2 class="title-small">Giới Thiệu</h2>
				<h3 class="section-title">CỘNG ĐỒNG <span class="title-color">WEB</span></h3>
				<p>
					Cộng Đồng Web là đơn vị thuộc Công ty cổ phần Young Plus Chuyên nhận thiết kế website chuyên nghiệp, chuẩn SEO, Load nhanh, cung cấp server vps, mail marketing server, Quản lý toàn bộ hệ thống website, giúp duy trì và phát triển mảng website càng ngày càng đi lên và đứng vững trong thị trường online 4.0.
				</p>
				<a class="link-cdt2" href="<?php echo get_home_url(); ?>/kho-giao-dien/" class="link-cdt">Dự Án Đã Làm</a>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
				<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/04/gioi-thieu-cong-dong-theme.jpeg" alt="<?php echo the_title(); ?>" title="<?php echo the_title(); ?>" />
			</div>
		</div>
	</div>
</div>
<!--Lợi Thế Công Ty-->
<div class="section">
	<div class="container-lg">
		<div class="row align-center align-middle">
			<div class="col-md-7 text-center">
				<h2 class="section-title">Tại Sao Chọn <span class="title-color"> Chúng Tôi</span></h2>
				<p class="section-title-description">Với hơn 6 năm nghiên cứu và phát triển, Cộng Đồng Web giúp quý khách hàng một hệ thống Website bán hàng tối ưu, thông minh, chuyên nghiệp và hoạt động hiệu quả</p>
			</div>
		</div>
		<div class="row align-center align-equal">
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services text-center shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/04/layout_1.png" alt="Lợi Ích Cộng Đồng Web" title="Thiết Kế Website">
					</div>
					<div class="services-name">
						<h3 class="section-title">
							Thiết Kế Web Chuẩn SEO
						</h3>
						<p>Kho giao diện thiết kế tỉ mỉ, phong phú, Source code do Cộng Đồng Web phát triển chuẩn SEO.</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services text-center shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/04/ssl.png" alt="Lợi Ích Cộng Đồng Web" title="Thiết Kế Website">
					</div>
					<div class="services-name">
						<h3 class="section-title">
							Chứng Chỉ SSL Bảo Mật
						</h3>
						<p>Các website sử dụng bên Cộng Đồng Web đều được hỗ trợ cung cấp miễn phí chứng chỉ bảo mật SSL</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services text-center shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/04/shipped.png" alt="Lợi Ích Cộng Đồng Web" title="Thiết Kế Website">
					</div>
					<div class="services-name">
						<h3 class="section-title">
							Kết Nối Đơn Vị Vận Chuyển
						</h3>
						<p>Hỗ trợ đầy đủ các đơn vị vận chuyển và có thể tính phí vận chuyển chính xác</p>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 col-cdw">
				<div class="col-inner single-services text-center shadow-cdw border-radius-cdw hover-top">
					<div class="img-services">
						<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2022/04/credit-card_1.png" alt="Lợi Ích Cộng Đồng Web" title="Thiết Kế Website">
					</div>
					<div class="services-name">
						<h3 class="section-title">
							Kết Nối Cổng Thanh Toán
						</h3>
						<p>Tích hợp cổng thanh toán online phổ biến nhất Việt Nam, đặt hàng thanh toán dễ dàng</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>