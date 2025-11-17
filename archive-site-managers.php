<?php get_header();
$taxonomy = 'site-types';
$orderby = 'menu_order';
$order = 'ASC';
$show_count = 0;
$pad_counts = 0;
$hierarchical = 0;
$title = '';
$empty = 0;
$args = array(
	'taxonomy' => $taxonomy,
	'orderby' => $orderby,
	'order'  => $order,
	'show_count' => $show_count,
	'pad_counts' => $pad_counts,
	'hierarchical' => $hierarchical,
	'title_li' => $title,
	'hide_empty' => $empty
);
$all_categories = get_categories($args);
?>

<!-- Page header with logo and tagline-->
<header>
	<div class="container-md">
		<div class="text-center my-3">
			<?php
			echo '<div class="header-breadcrumb dark">';
			echo '<img src="' . get_home_url() . '/wp-content/themes/CongDongTheme/images/impression-header.png">';
			echo '<div class="breadcrumb-title text-center"><h1>Kho Giao Diện Website</h1>';
			echo do_shortcode('[rank_math_breadcrumb]');
			echo '</div></div>';
			?>
		</div>
	</div>
</header>

<!-- Page content-->
<div class="container-md">
	<div class="row">
		<div class="col-lg-12">
			<div class="row danh-muc-website">
				<ul class="col-11 col-sm-12 col-md-12 col-lg-11 d-flex flex-wrap">
					<?php
					echo "<li><a href=\"!?\" class=\"active\" data-id =\"-1\" >Tất cả</a></li>";
					foreach ($all_categories as $cat) {
						if ($cat->category_parent == 0) {
							$category_id = $cat->term_id;
							echo "<li>" . '<a class="px-4" href="!?" data-id ="'.$category_id.'" >' . $cat->name . "</a></li>";
						} else {
							echo "";
						}
					}
					?>
				</ul>
			</div>
		</div>
		<!-- Blog entries-->
		<div class="col-lg-12">
			<!-- Nested row for non-featured blog posts-->
			<div class="col-12 content-archive-site-managers">
				<div class="row  ontent-item">
					<?php if (have_posts()) : while (have_posts()) : the_post();
							get_template_part('template-parts/site-managers/archive', 'content-item');
						endwhile; ?>

					<?php else : ?>

						<?php get_template_part('content', 'none'); ?>

					<?php endif; ?>
				</div>
				<?php congdongtheme_post_pagination_ajax(); ?>

			</div>

		</div>
	</div>
</div>

<?php get_footer(); ?>