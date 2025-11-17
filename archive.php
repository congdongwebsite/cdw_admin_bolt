<?php
get_header();

$cat = get_category(get_query_var('cat'));
?>

<!-- Page header with logo and tagline-->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <h1 class="fw-bolder">
                <?php

                if (is_tag()) :

                    printf(__('Thẻ: %1$s', 'congdongtheme'), single_tag_title('', false));

                elseif (is_category()) :

                    printf(__('Danh mục: %1$s', 'congdongtheme'), single_cat_title('', false));

                elseif (is_day()) :

                    printf(__('Lưu trữ hàng ngày: %1$s', 'congdongtheme'), the_time('l, F j, Y'));

                elseif (is_month()) :

                    printf(__('Lưu trữ hàng tháng: %1$s', 'congdongtheme'), the_time('F Y'));

                elseif (is_year()) :

                    printf(__('Lưu trữ hàng năm: %1$s', 'congdongtheme'), the_time('Y'));

                endif;

                ?>

            </h1>
            <?php if (is_tag() || is_category()) : ?>
                <p class="lead mb-0">
                    <?php echo term_description(); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</header>
<div class="post-feature my-3">
    <section class="container">
        <h2><?php echo single_cat_title(); ?> Nổi Bật</h2>
        <div class="list-post-feature row">
            <?php
            $args = array(
                'post_type' => array('post'),
                'post_status' => array('publish'),
                'posts_per_page' => 8,
                'ignore_sticky_posts' => true,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'cat' => $cat->term_id,
            );

            // The Query
            $post_feature = new WP_Query($args);

            // The Loop
            if ($post_feature->have_posts()) {
                while ($post_feature->have_posts()) {
                    $post_feature->the_post();
            ?>
                    <div class="col-6 col-md-6 col-lg-3 my-3 bai-viet">
                        <div class="image-cover">
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(get_the_ID()); ?>" title="<?php echo get_the_title(get_the_ID()); ?>" />
                        </div>
                        <a href="<?php echo get_permalink(get_the_ID()); ?>">
                            <h4 class="title-post-feature"><?php echo get_the_title(get_the_ID()); ?></h4>
                        </a>
                    </div>
            <?php
                }
            } else {
                // no posts found
            }
            /* Restore original Post Data */
            wp_reset_postdata();
            ?>

        </div>
    </section>
</div>

<section id="timeline" class="timeline-outer ">
    <div class="container">
        <ul class="title-blog">
            <li><a href="<?php echo get_home_url(); ?>/tin-tuc">Bài Viết Mới</a></li>
            <?php
            foreach (get_categories(['fields' => 'id=>name', 'parent' => $cat->term_id]) as $key => $value) {
            ?>
                <li><a href="<?php echo get_term_link($key); ?>"><?php echo $value; ?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
    <div class="container white-blog">
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
</section>

<?php get_footer(); ?>
<?php get_footer(); ?>