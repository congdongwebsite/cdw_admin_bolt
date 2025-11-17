<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <!-- Post header-->
    <header class="mb-4">
        <!-- Post title-->
        <h1 class="fw-bolder mb-1"><?php congdongtheme_post_header(); ?></h1>
        <!-- Post meta content-->
        <div class="text-muted fst-italic mb-2"><?php congdongtheme_post_meta() ?></div>
        <!-- Post categories-->
        <?php (is_single() ? congdongtheme_post_tag() : ''); ?>
    </header>
    <!-- Preview image figure-->
    <figure class="mb-4">
    <?php congdongtheme_post_thumbnail('full'); ?></figure>
    <!-- Post content-->
    <section class="mb-5">
        <?php congdongtheme_post_content(); ?>
    </section>
</article>