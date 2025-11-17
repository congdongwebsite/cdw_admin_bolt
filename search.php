<?php get_header(); ?>

<!-- Page header with logo and tagline-->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <h1 class="fw-bolder">Tìm Kiếm</h1>
			<?php get_search_form(); ?>
            <p class="lead mb-0">

                <?php

                $search_query = new WP_Query('s=' . $s . '&showposts=-1');

                $search_keyword = wp_specialchars($s, 1);

                $search_count = $search_query->post_count;

                printf(__('Kết quả tìm kiếm <strong>%1$s</strong>. Chúng tôi tìm thấy <strong>%2$s</strong> kết quả cho bạn.', 'congdongtheme'), $search_keyword, $search_count);

                ?>
            </p>
			
        </div>
    </div>
</header>

<!-- Page content-->
<div class="container-md">
    <div class="row">
        <!-- Blog entries-->
        <div class="col-lg-12">
            <!-- Nested row for non-featured blog posts-->
          <ul class="timeline">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>            
                            <?php get_template_part('content', 'blog'); ?>
                    <?php endwhile; ?>

                    <?php congdongtheme_post_pagination(); ?>

                <?php else : ?>

                    <?php get_template_part('content', 'none'); ?>

                <?php endif; ?>
			</ul>

        </div>
    </div>
</div>

<?php get_footer(); ?>