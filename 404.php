<?php get_header(); ?>
<header>
	<div class="container-md">
		<div class="text-center my-3">
			<?php
			echo '<div class="header-breadcrumb dark">';
			echo '<img src="' . get_home_url() . '/wp-content/themes/CongDongTheme/images/impression-header.png">';
			echo '<div class="breadcrumb-title text-center"><h1>404</h1>';
			_e('<p>Xin lỗi, trang bạn đang tìm kiếm không tồn tại!</p>', 'congdongtheme');
			get_search_form();
			echo '<a class="link-cdt1" href="'. get_home_url() .'" data-title="Về Trang Chủ">Về Trang Chủ</a>';
			echo '</div></div>';
			?>
		</div>
	</div>
</header>

<?php get_footer(); ?>