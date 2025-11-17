<?php
defined('ABSPATH') || exit;
function create_slickpost_shortcode($atts)
{
    wp_enqueue_style('slick-style');
    wp_enqueue_style('slick-theme-style');
    wp_enqueue_script('slick-script');
    // Attributes
    extract(shortcode_atts(
        array(
            'title' => '',
            'id_element' => 'id_' . rand(),
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => '5',
            'offset' => '0',
            'order' => 'ASC',
            'orderby' => 'date',
            'ignore_sticky_posts' => 'true',
            'cat' => '',
            'category_name' => '',
            'show_desktop' => 4,
            'show_tab' => 3,
            'show_mobile' => 1,
            'show_cat' => true,
            'show_date' => true,
            'show_arrows' => true,
            'show_dots' => true,
            'infinite' => true,
        ),
        $atts,
        'slick_post'
    ));

    // Query Arguments
    $args = array(
        'post_type' => $post_type,
        'post_status' => $post_status,
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'ignore_sticky_posts' => $ignore_sticky_posts,
        'order' => $order,
        'cat' => $cat,
        'category_name' => $category_name
    );

    // The Query
    $slick_post = new WP_Query($args);

    // The Loop
    if ($slick_post->have_posts()) { ?>
        <style>
            .slick-post>.row>#<?php echo $id_element; ?>>.slick-list .slick-post-item>a>img {
                width: 100% !important;
                height: 200px !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-list .slick-slide {
                margin-right: 4px;
                margin-left: 4px;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-prev,
            .slick-post>.row>#<?php echo $id_element; ?>>.slick-next {
                border: unset !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-prev:before,
            .slick-post>.row>#<?php echo $id_element; ?>>.slick-next:before {
                color: black;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots {
                bottom: -10 !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots button,
            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots button {
                border: unset !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots li {
                height: 3px !important;
                background-color: #919191 !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots .slick-active {
                background-color: #C3002F !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-dots button:before {
                content: "" !important;
            }

            .slick-post>.row>#<?php echo $id_element; ?>>.slick-list .slick-post-item .slick-post-title {
                font-weight: bold;
            }
        </style>
        <div class="slick-post ">
            <?php if (isset($title)) echo "<h3 class=\"slick-post-title\">" . $title . "</h3>"; ?>

            <div class="row">
                <div id="<?php echo $id_element; ?>" class="">
                    <?php

                    while ($slick_post->have_posts()) {
                        $slick_post->the_post();
                    ?>
                        <div class="slick-post-item">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                the_post_thumbnail('thumbnail', array('title' => get_the_title(), 'alt' => get_the_title(), 'class' => '',));
                                ?>
                            </a>
                            <?php
                            if ($show_cat) {
                            ?>
                                <div class="slick-post-cat my-2">
                                    <?php
                                    $category_detail = get_the_category(get_the_ID()); //$post->ID
                                    foreach ($category_detail as $cd) {
                                    ?>
                                        <a href="<?php echo get_category_link($cd->ID); ?>" class="slick-post-cat-item" title="<?php echo $cd->cat_name; ?>"><span class=""><?php echo $cd->cat_name; ?></span></a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                            <a href="<?php the_permalink(); ?>" class="slick-post-title">
                                <h4><?php the_title(); ?></h4>
                            </a>
                            <?php
                            if ($show_date) {
                            ?>
                                <div class="slick-post-date my-2">
                                    <?php
                                    echo get_the_date('d/m/Y');
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('.slick-post > .row > #<?php echo $id_element; ?>').slick({
                    infinite: <?php echo $infinite == 1 ? "true" : "false"; ?>,
                    slidesToShow: <?php echo $show_desktop; ?>,
                    slidesToScroll: <?php echo $show_desktop; ?>,
                    arrows: <?php echo $show_arrows == 1 ? "true" : "false"; ?>,
                    dots: <?php echo $show_dots == 1 ? "true" : "false"; ?>,
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: <?php echo $show_desktop; ?>,
                                slidesToScroll: <?php echo $show_desktop; ?>,
                            }

                        }, {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: <?php echo $show_tab; ?>,
                                slidesToScroll: <?php echo $show_tab; ?>,
                            }

                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: <?php echo $show_tab; ?>,
                                slidesToScroll: <?php echo $show_tab; ?>,
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: <?php echo $show_mobile; ?>,
                                slidesToScroll: <?php echo $show_mobile; ?>,
                            }
                        }
                    ]
                });
            });
        </script>
    <?php

    } else {
        // no posts found
    }
    /* Restore original Post Data */
    wp_reset_postdata();
}

function create_slickimage_shortcode($atts)
{
    wp_enqueue_style('slick-style');
    wp_enqueue_style('slick-theme-style');
    wp_enqueue_script('slick-script');

    // Attributes
    extract(shortcode_atts(
        array(
            'title' => '',
            'id_element' => 'id_' . rand(),
            'show_desktop' => 4,
            'show_tab' => 3,
            'show_mobile' => 1,
            'show_arrows' => true,
            'show_dots' => true,
            'infinite' => true,
            'listid' => false,
            'url' => '/',
        ),
        $atts,
        'slick_image'
    ));
    // The Loop
    if (!$listid) return;
    ?>
    <style>
        .slick-image>.row>#<?php echo $id_element; ?>>.slick-list .slick-slide {
            margin-right: 4px;
            margin-left: 4px;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-prev,
        .slick-image>.row>#<?php echo $id_element; ?>>.slick-next {
            border: unset !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-prev:before,
        .slick-image>.row>#<?php echo $id_element; ?>>.slick-next:before {
            color: black;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots {
            bottom: -10 !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots button,
        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots button {
            border: unset !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots li {
            height: 3px !important;
            background-color: #919191 !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots .slick-active {
            background-color: #C3002F !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-dots button:before {
            content: "" !important;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-list .slick-slide .slick-image-item {
            text-align: center;
            min-height: 145px;
            border-radius: 10px;
            border: 1px solid #e8e8e8;
            background-color: #fff;
        }

        .slick-image>.row>#<?php echo $id_element; ?>>.slick-list .slick-slide .slick-image-item img {
            padding: 15px;
        }
    </style>
    <div class="slick-image">
        <div class="row">
            <div id="<?php echo $id_element; ?>" class="">
                <?php
                $data = explode(",", $listid);
                foreach ($data as $key => $value) {
                ?>
                    <div class="slick-image-item">
                        <a href="<?php echo $url; ?>" target="_blank">
                            <img src="<?php echo wp_get_attachment_image_src($value, 'thumbnail')[0]; ?>" alt="đơn vị thiết kế website cộng đồng theme">
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('.slick-image > .row > #<?php echo $id_element; ?>').slick({
                infinite: <?php echo $infinite == 1 ? "true" : "false"; ?>,
                slidesToShow: <?php echo $show_desktop; ?>,
                slidesToScroll: <?php echo $show_desktop; ?>,
                arrows: <?php echo $show_arrows == 1 ? "true" : "false"; ?>,
                dots: <?php echo $show_dots == 1 ? "true" : "false"; ?>,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: <?php echo $show_desktop; ?>,
                            slidesToScroll: <?php echo $show_desktop; ?>,
                        }

                    }, {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: <?php echo $show_tab; ?>,
                            slidesToScroll: <?php echo $show_tab; ?>,
                        }

                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: <?php echo $show_tab; ?>,
                            slidesToScroll: <?php echo $show_tab; ?>,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: <?php echo $show_mobile; ?>,
                            slidesToScroll: <?php echo $show_mobile; ?>,
                        }
                    }
                ]
            });
        });
    </script>
<?php

}

function create_lightslider_lightgallery_shortcode($atts)
{
    wp_enqueue_style('lightgallery-style');
    wp_enqueue_script('lightgallery-script');
    wp_enqueue_style('lightslider-style');
    wp_enqueue_script('lightslider-script');

    // Attributes
    extract(shortcode_atts(
        array(
            'id_element' => 'image-gallery_' . rand(),
            'id_post' => false,
            'show_desktop' => 5,
            'show_tab' => 4,
            'show_mobile' => 3,
        ),
        $atts,
        'lightslider_lightgallery'
    ));

    if (!$id_post) return;
?>
    <div class="lightslider_lightgallery">
        <div class="row">
            <?php
            $images = get_field('album_image', $id_post);
            // (thumbnail, medium, large, full or custom size)
            if ($images) : ?>
                <ul id="<?php echo $id_element; ?>" class="gallery list-unstyled">
                    <?php foreach ($images as $image_id) : ?>

                        <li data-src="<?php echo wp_get_attachment_image_url($image_id["id"], 'full'); ?>" data-sub-html="<h4><?php echo  $image_id["title"]; ?></h4>">
                            <a href="#">
                                <img src="<?php echo wp_get_attachment_image_url($image_id["id"], 'thumbnail'); ?>" alt="<?php echo  $image_id["title"]; ?>" />
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            // Gallery Ảnh
            $('.lightslider_lightgallery > .row > #<?php echo $id_element; ?>').lightSlider({
                item: 4.5,
                loop: false,
                slideMargin: 5,
                controls: true,
                speed: 600,
                keyPress: true,
                freeMove: true,
                enableDrag: true,
                enableTouch: true,
                currentPagerPosition: 'middle',
                thumbItem: 4,
                auto: false,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            item: <?php echo $show_desktop; ?>,
                        }

                    }, {
                        breakpoint: 992,
                        settings: {
                            item: <?php echo $show_tab; ?>,
                        }

                    },
                    {
                        breakpoint: 768,
                        settings: {
                            item: <?php echo $show_tab; ?>,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            item: <?php echo $show_mobile; ?>,
                        }
                    }
                ],
                onSliderLoad: function(el) {
                    el.lightGallery({
                        download: false,
                        selector: '.lightslider_lightgallery > .row #<?php echo $id_element; ?> .lslide',
                    });
                }
            });
        });
    </script>
<?php

}
function create_lightgallery_shortcode($atts)
{
    wp_enqueue_style('justifiedGallery-style');
    wp_enqueue_script('justifiedGallery-script');

    wp_enqueue_style('lightgallery-style');
    wp_enqueue_script('lightgallery-script');

    // Attributes
    extract(shortcode_atts(
        array(
            'id_element' => 'image-gallery_' . rand(),
            'id_post' => false,
            'show_desktop' => 5,
            'show_tab' => 4,
            'show_mobile' => 3,
        ),
        $atts,
        'lightgallery'
    ));

    if (!$id_post) return;
?>
    <style>

    </style>
    <div class="lightgallery">
        <div class="row">
            <div id="<?php echo $id_element; ?>">

                <?php
                $images = get_field('album_image', $id_post);
                // (thumbnail, medium, large, full or custom size)
                if ($images) : ?>
                    <?php foreach ($images as $image_id) : ?>
                        <a data-src="<?php echo wp_get_attachment_image_url($image_id, 'full'); ?>" data-sub-html="<h4><?php echo  $image_id["title"]; ?></h4>">
                            <img class="img-thumbnail" src="<?php echo wp_get_attachment_image_url($image_id, 'thumbnail'); ?>" alt="<?php echo  $image_id["title"]; ?>" />
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $(".lightgallery .row #<?php echo $id_element; ?>").justifiedGallery({
                lastRow: 'nojustify',
                margins: 3
            }).on('jg.complete', function() {
                $(this).lightGallery({
                    thumbnail: true,
                    download: false
                });
            });;
        });
    </script>
<?php

}
