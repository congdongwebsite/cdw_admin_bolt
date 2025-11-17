<li class="event"  data-aos="fade-up" data-aos-duration="3000">
    <span class="date d-none d-xl-block"><?php echo get_the_date();?></span>
    <span class="d-xl-none"><?php echo get_the_date();?></span>
    <div class="item1 row align-middle">
        <div class="col-md-8 col-sm-12">
            <?php congdongtheme_post_header(); ?>
            <?php congdongtheme_post_meta() ?>
            <p>
			<?php echo wp_strip_all_tags(substr(get_the_content(get_the_ID()), 0, 300)); ?>...
            </p>
            <span class="view"><?php echo td_get_post_views(get_the_ID());  ?></span>
        </div>
        <div class="col-md-4 col-sm-12">
			<div class="image-cover">
				 <a href="<?php echo get_permalink(get_the_ID()); ?>">
               		 <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'thumbnail'); ?>" alt="<?php echo get_the_title(get_the_ID()); ?>" title="<?php echo get_the_title(get_the_ID()); ?>"/>
            	</a>
			</div>
        </div>
    </div>
</li>