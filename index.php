<?php
// wp_enqueue_style('slick-style');
// wp_enqueue_style('slick-theme-style');
// wp_enqueue_script('slick-script');
get_header();
?>

<!-- <div class="slider-post">
    <section class="container">
        <?php
        // Query Arguments
        $post_slider_ids = get_field('post_slider', 'option');
        $args = array(
            'post_type' => array('post'),
            'post_status' => array('publish'),
            'posts_per_page' => 5,
            'ignore_sticky_posts' => true,
            'order' => 'DESC',
            'orderby' => 'date',
            'cat' => $post_slider_ids,
        );

        // The Query
        $post_slider = new WP_Query($args);

        // The Loop
        if ($post_slider->have_posts()) {
            while ($post_slider->have_posts()) {
                $post_slider->the_post();
        ?>
                <div>
                    <div class="row header-blog align-middle">
                        <div class="col-12 col-md-5 col-lg-5">
                            <img class="slider-image" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php echo get_the_title(get_the_ID()); ?>" title="<?php echo get_the_title(get_the_ID()); ?>" />
                        </div>
                        <div class="col-12 col-md-7 col-lg-7">
                            <div class="col-inner">
                                <h3 class="title-post-slider"><?php echo get_the_title(get_the_ID()); ?></h3>
                                <small class="date-post-slider">Ngày: <?php echo get_the_date(); ?> - Được đăng bởi <strong><?php echo get_the_author(); ?></strong></small>
                                <p class="description-post-slider">
                                    <?php echo wp_strip_all_tags(substr(get_the_content(get_the_ID()), 0, 500)); ?>...
                                </p>
                                <a href="<?php echo get_permalink(get_the_ID()); ?>" class="read-more-post-slider">Xem Thêm <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
        ?>

    </section>
</div>
<script>
    jQuery(document).ready(function($) {
        $('.slider-post>.container').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            dots: true,
        });
    });
</script> -->
<div class="post-feature my-3">
    <section class="container">
        <h2>Tin Tức Nổi Bật</h2>
        <div class="list-post-feature row">
            <?php
            // Query Arguments
            $post_feature_ids = get_field('post_feature', 'option');
            $args = array(
                'post_type' => array('post'),
                'post_status' => array('publish'),
                'posts_per_page' => 8,
                'ignore_sticky_posts' => true,
                'order' => 'DESC',
                'orderby' => 'date',
                'cat' => $post_feature_ids,
            );

            // The Query
            $post_feature = new WP_Query($args);

            // The Loop
            if ($post_feature->have_posts()) {
                while ($post_feature->have_posts()) {
                    $post_feature->the_post();
            ?>
                    <div class="col-6 col-md-6 col-lg-3 my-3 bai-viet" data-aos="fade-up" data-aos-duration="3000">
                        <a href="<?php echo get_permalink(get_the_ID()); ?>">
                            <div class="image-cover">
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>" alt="<?php echo get_the_title(get_the_ID()); ?>" title="<?php echo get_the_title(get_the_ID()); ?>" />
                            </div>
                            <div class="box-bottom-single">
                                <div class="date-singleblog">
                                    <strong><?php echo get_the_time('d', get_the_ID()); ?></strong>
                                    <span><?php echo get_the_time('m', get_the_ID()); ?>/<?php echo get_the_time('Y', get_the_ID()); ?></span>
                                </div>
                                <h4 class="title-post-feature"><?php echo get_the_title(get_the_ID()); ?></h4>
                            </div>
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
            <li><a href="/tin-tuc">Bài Viết Mới</a></li>
            <?php
            foreach (get_categories(['fields' => 'id=>name']) as $key => $cat) {
            ?>
                <li><a href="<?php echo get_term_link($key); ?>"><?php echo $cat; ?></a></li>
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