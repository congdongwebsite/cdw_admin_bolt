<?php

wp_enqueue_style('slick-style');
wp_enqueue_style('slick-theme-style');
wp_enqueue_script('slick-script');
get_header();
td_set_post_views(get_the_ID()); ?>
<div class="background-header-single">
	<div class="container-lg align-center text-center dark">
		<div class="header-single">
			<a href="<?php echo home_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="Cộng Đồng Theme - <?php echo the_title(); ?>" title="<?php echo the_title(); ?>"></a>
			<h1 class="entry-title">
				<?php echo the_title(); ?>
			</h1>
			<small><?php echo do_shortcode('[rank_math_breadcrumb]'); ?></small>
		</div>
	</div>
	<div class="trick"></div>
</div>
<!-- Page content-->
<div class="container-lg mt-5">
	<div class="row">
		<div class="<?php echo is_active_sidebar('single-sidebar') ? "col-lg-8" : "col-lg-12"; ?>">
			<!-- Post content-->
			<?php if (have_posts()) : while (have_posts()) : the_post();

			?>
				<figure class="mb-4 d-flex justify-content-center align-items-center image-avatar-blog">
					<?php congdongtheme_post_thumbnail('full'); ?>
				</figure>
					<?php $avatar = get_field('user_avatar','user_'.get_the_author_meta('ID'));
					?>
					<div class="row align-center single-content-cdt">
						<div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 col-12">
							<div class="social">
								<!-- Share Button -->
								<ul>
									<li class="date-singleblog">
						<strong><?php echo get_the_time('d', get_the_ID()); ?></strong>
						<span><?php echo get_the_time('m', get_the_ID()); ?>/<?php echo get_the_time('Y', get_the_ID()); ?></span></li>
									<li>Share</li>
									<li><a class="button_share share facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>"><i class="fa fa-facebook"></i></a></li>
									<li><a class="button_share share twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo get_permalink(); ?>&via=Congdongtheme"><i class="fa fa-twitter"></i></a></li>
									<li><a class="button_share share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title(); ?>&source=<?php echo get_permalink(); ?>"><i class="fa fa-linkedin"></i></a></li>
								</ul>
							</div>
									
						</div>
						<div class="col-xl-9 col-lg-9 col-md-10 col-sm-10 col-12">	
							<table cellpadding="3" class="header-blog-single">
								<tbody>
								<tr>
									<td class="category-single"><i class="fa fa-user" aria-hidden="true"></i> <?php echo get_the_author_meta('display_name'); ?></td>
									<td class="category-single"><i class="fa fa-folder"></i> <?php echo get_the_category_list(', ') ?></td>
									<td class="category-single"><i class="fa fa-eye" aria-hidden="true"></i><?php echo td_get_post_views(get_the_ID());  ?></td>
								</tr>
								</tbody>
							</table>
							<?php congdongtheme_post_content(); ?>
						</div>
					</div>
					<!--Bài viết liên quan-->
					<?php
					$categories = get_the_category($post->ID);
					if ($categories) {
						$category_ids = array();

						foreach ($categories as $individual_category) $category_ids[] = $individual_category->term_id;
						$args = array(
							'category__in'   => $category_ids,
							'post__not_in'   => array($post->ID),
							'posts_per_page' => 6,
							'ignore_sticky_posts' => 1,
							'no_found_rows'   => true
						);

						$my_query = new wp_query($args);
						if ($my_query->have_posts()) {
							echo '<h3>Tin Tức Liên Quan</h3><div class="row">
							<div id="slick_post_related" class="frame-tintuc-footer">';
							while ($my_query->have_posts()) {
								$my_query->the_post();
					?>
								<div class="image-cover tintuclq px-2">
									<a href="<?php echo get_permalink(get_the_ID()); ?>">
										<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(get_the_ID()); ?>" title="<?php echo get_the_title(get_the_ID()); ?>" />
									</a>
									<h4>
										<a href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo the_title(); ?></a>
									</h4>
									<p>
										<?php echo get_the_excerpt(); ?>
									</p>
								</div>
					<?php
							}
							echo '</div></div>';
						}
					}
					?>

					<script>
						jQuery(document).ready(function($) {
							$('#slick_post_related').slick({
								infinite: false,
								slidesToShow: 5,
								slidesToScroll: 5,
								arrows: false,
								dots: false,
								responsive: [{
										breakpoint: 1200,
										settings: {
											slidesToShow: 4,
											slidesToScroll: 4,
										}

									}, {
										breakpoint: 992,
										settings: {
											slidesToShow: 3,
											slidesToScroll: 3,
										}

									},
									{
										breakpoint: 768,
										settings: {
											slidesToShow: 2,
											slidesToScroll: 2,
										}
									},
									{
										breakpoint: 576,
										settings: {
											slidesToShow: 1,
											slidesToScroll: 1,
										}
									}
								]
							});
						});
					</script>
					<h3>
						Bình Luận Của Bạn
					</h3>
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

				<?php endwhile; ?>

			<?php else : ?>

				<?php get_template_part('content', 'none'); ?>

			<?php endif; ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.share').click(function() {
			var NWin = window.open($(this).prop('href'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
			if (window.focus) {
				NWin.focus();
			}
			return false;
		});
	});
</script>
<?php get_footer(); ?>