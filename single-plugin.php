<?php

wp_enqueue_style('sweetalert2-style');
wp_enqueue_script('sweetalert2-script');
wp_enqueue_script('plugin');
get_header(); ?>
<style>
.background-header-single {
    background: linear-gradient(90deg, #f8fafc 0%, #e3e8ee 100%);
    padding: 40px 0 20px 0;
}
.header-single img {
    max-width: 80px;
    margin-bottom: 10px;
}
.header-single h1.entry-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 10px 0 5px 0;
    color: #1a202c;
}
.header-single .rank-math-breadcrumb {
    margin-top: 10px;
}
.section.single-plugin {
    padding: 40px 0 20px 0;
}
.single-plugin .title-website {
    font-size: 1.7rem;
    font-weight: 600;
    color: #2563eb;
    margin-bottom: 10px;
}
.category-plugin span {
    display: inline-block;
    margin-right: 8px;
    margin-bottom: 5px;
}
.category-plugin a {
    background: #e0e7ff;
    color: #3730a3;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.95rem;
    text-decoration: none;
    transition: background 0.2s;
}
.category-plugin a:hover {
    background: #6366f1;
    color: #fff;
}
.frame-plugin .img-plugin img {
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    max-width: 100%;
    height: auto;
}
.background-plugin {
    background: #f1f5f9;
    border-radius: 12px;
    padding: 24px 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.frame-price-plugin h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.total-price {
    font-size: 1.1rem;
    margin-bottom: 8px;
}
.total-price .lb {
    color: #64748b;
    margin-right: 6px;
}
.totalprice {
    color: #ef4444;
    font-weight: bold;
}
.add-to-cart-plugin, .btn-modal-order {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 18px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s;
}
.add-to-cart-plugin:hover, .btn-modal-order:hover {
    background: #1e40af;
    color: #fff;
}
.order-plugin {
    margin-top: 18px;
}
.description-single {
    padding: 40px 0 20px 0;
}
.description-single h3.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 12px;
    color: #2563eb;
}
.description-single .row > div {
    margin-bottom: 20px;
}
.description-single .fb-comments {
    margin-top: 24px;
}
@media (max-width: 991px) {
    .single-plugin .row.align-center {
        flex-direction: column;
    }
    .background-plugin {
        margin-top: 24px;
    }
}
</style>
<!-- Page content-->
<div class="background-header-single">
    <div class="container align-center text-center dark">
        <div class="header-single">
            <a href="<?php echo home_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/2022/03/icon.png" alt="Cộng Đồng Plugin - <?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>"></a>
            <h1 class="entry-title">
                Plugin <?php echo get_the_title(); ?>
            </h1>
            <?php echo do_shortcode('[rank_math_breadcrumb]'); ?>
        </div>
    </div>
    <div class="trick"></div>
</div>
<!--Single plugin-->
<div class="section single-plugin">
    <div class="container">
        <div class="row align-center">
            <div class="col-lg-7">
                <h2 class="title-website"><?php echo get_post_meta(get_the_ID(), "name", true); ?></h2>
                <div class="category-plugin">
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'plugin-type');
                    if ($terms && !is_wp_error($terms)) :
                        foreach ($terms as $term) {
                            $url = get_term_link($term);
                            echo '<span><a href="' . esc_url($url) . '">' . esc_html($term->name) . '</a></span> ';
                        }
                    endif;
                    ?>
                </div>
                <div class="frame-plugin">
                    <div class="img-plugin">
                        <?php the_post_thumbnail("full", array("title" => get_the_title(), "alt" => get_the_title())); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 background-plugin">
                <div class="frame-price-plugin">
                    <div class="frame-bottom-plugin align-middle">
                        <h4><?php echo get_the_title(); ?></h4>
                    </div>
                    <?php
                    $valuePrice = get_post_meta(get_the_ID(), "price", true);
                    $price = "";
                    if (is_numeric($valuePrice) && $valuePrice > 0)
                        $price = number_format($valuePrice, 0, ',', '.') . ' VNĐ';
                    else
                        $price = 'Miễn phí';
                    ?>
                    <div class="total-price">
                        <span class="lb">Giá plugin:</span>
                        <span class="totalprice"><?php echo $price; ?></span>
                    </div>
                    <small>(Giá chưa bao gồm VAT)</small>
                    <a href="javascript:void(0)" class="add-to-cart-plugin" data-id="<?php echo get_the_ID(); ?>">
                        <b>Thêm vào giỏ hàng</b> <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    </a>
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
                    Hình ảnh chức năng <?php echo get_the_title(); ?>
                </h3>
                <div>
                    <?php echo do_shortcode('[lightgallery id_element="image-gallery" id_post="' . get_the_ID() . '"]'); ?>
                </div>
            </div>
            <div class="col-lg-6">
                <h3>
                    Mô tả <?php echo get_the_title(); ?>
                </h3>
                <div>
                    <?php the_content(); ?>
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
<?php get_footer(); ?>