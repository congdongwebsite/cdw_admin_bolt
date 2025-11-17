<div class="col-md-6 col-lg-3">
    <div class="frame-thiet-ke1">
        <div class="img-thiet-ke">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail("full", array("title" => get_the_title(), "alt" => get_the_title())); ?></a>
        </div>
        <div class="frame-bottom-web">
            <a href="<?php the_permalink(); ?>">
                <h3><?php the_title(); ?></h3>
            </a>
            <?php
            $valuePrice = get_field("price");
            $price = "";
            if (is_numeric($valuePrice))
                $price = number_format($valuePrice, 0, ',', '.');
            else
                $price = $valuePrice;
            ?>
            <span class="price-thietke"><?php echo $price; ?></span>
        </div>
        <div class="category-thietke">
            <?php
            $terms = get_the_terms($post->ID, 'site-types');
            if ($terms && !is_wp_error($terms)) :
                $draught_links = array();
                foreach ($terms as $term) {
                    $url = get_term_link($term);
            ?>
                    <span><a href="<?php echo $url; ?>"><?php echo  $term->name; ?></a></span>
                <?php
                }
                ?>

            <?php endif; ?>
        </div>
    </div>

</div>