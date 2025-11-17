<?php get_header(); ?>

<!-- Page header with logo and tagline-->
<div class="background-header-single">
	<div class="container align-center text-center dark">
		<div class="header-single">
			<a href="<?php echo home_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="Cộng Đồng Theme - <?php echo single_term_title(); ?>" title="<?php echo single_term_title(); ?>"></a>
			<h1 class="entry-title">
				Mẫu Giao Diện <?php echo single_term_title() ?>
			</h1>
		</div>
	</div>
	<div class="trick"></div>
</div>
<!-- Page content-->
<div class="container-md">
	<!-- Nested row for non-featured blog posts-->
	<div class="row">
		<div>
			<?php echo do_shortcode('[rank_math_breadcrumb]'); ?>
		</div>
		<div class="col-12 content-archive-site-managers">
			<div class="row content-item">
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

<?php get_footer(); ?>