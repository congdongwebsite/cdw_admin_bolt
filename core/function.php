<?php
defined('ABSPATH') || exit;
//Init

if (!function_exists('theme_global_setup')) {
    function theme_global_setup()
    {
        /*
        * Thiết lập theme có thể dịch được
        */
        $language_folder = THEME_URL . '/languages';
        load_theme_textdomain('congdongtheme', $language_folder);

        /*
        * Thêm chức năng post thumbnail
        */
        add_theme_support('post-thumbnails');

        /*
        * Thêm chức năng title-tag để tự thêm <title>
        */
        add_theme_support('title-tag');

        /*
        * Thêm chức năng custom background
        */
        $default_background = array(
            'default-color' => '#e8e8e8',
        );
        add_theme_support('custom-background', $default_background);

        /*
        * Thêm chức năng logo
        */
        add_theme_support('custom-logo', array(
            'height'               => 100,
            'width'                => 400,
            'flex-height'          => true,
            'flex-width'           => true,
            'header-text'          => array('site-title', 'site-description'),
            'unlink-homepage-logo' => true,
        ));

        /*
        * Tạo menu cho theme
        */
        register_nav_menu('primary-menu', __('Menu chính', 'condongtheme'));

        /*
        * Tạo sidebar cho theme
        */
        $sidebar = array(
            'name' => __('Sidebar chính', 'condongtheme'),
            'id' => 'main-sidebar',
            'description' => 'Sidebar mặc định cho website',
            'class' => 'main-sidebar',
            'before_title' => '<h3 class="widgettitle">',
            'after_sidebar' => '</h3>'
        );
        register_sidebar($sidebar);
        /*
        * Tạo sidebar Blog
        */
        $sidebarBlog = array(
            'name' => __('Sidebar Blog', 'condongtheme'),
            'id' => 'blog-sidebar',
            'description' => 'Sidebar Blog',
            'class' => 'main-sidebar',
            'before_widget'  => '<div class="card mb-4">',
            'before_title' => '<div class="card-header">',
            'after_title' => '</div><div class="card-body">',
            'after_widget'   => '</div></div>'
        );
        register_sidebar($sidebarBlog);
        /*
        * Tạo sidebar single
        */
        $sidebarBlog = array(
            'name' => __('Sidebar Single', 'condongtheme'),
            'id' => 'single-sidebar',
            'description' => 'Sidebar Single',
            'class' => 'main-sidebar',
            'before_widget'  => '<div class="card mb-4">',
            'before_title' => '<div class="card-header">',
            'after_title' => '</div><div class="card-body">',
            'after_widget'   => '</div></div>'
        );
        register_sidebar($sidebarBlog);
        // Read more
        add_filter('excerpt_more', 'congdongtheme_post_readmore');

        //Header Text
        add_filter('admin_title', 'congdongtheme_admin_title', 10, 2);

        //Footer Text
        add_filter('admin_footer_text', 'congdongtheme_footer');

        //Footer version        
        add_filter('update_footer', 'congdongtheme_footer_version', 9999);

        //Footer Bottom Contact
        add_action('wp_footer', 'congdongtheme_footer_bottom_contact');

        //Footer Bottom snowflakes
        //add_action('wp_footer', 'congdongtheme_footer_bottom_snowflakes');

        //Hide Language Switcher
        add_filter('login_display_language_dropdown', '__return_false');

        //Thay đổi logo cho trang đăng nhập
        add_action('login_enqueue_scripts', 'congdongtheme_login_enqueue_scripts');

        //Tùy chỉnh CSS cho trang đăng nhập Wordpress
        add_action('login_enqueue_scripts', 'tp_custom_logo');

        //Thay đổi url ảnh logo
        add_filter('login_headerurl', 'congdongtheme_login_headerurl');

        //AddClass to next_posts_link  vs previous_posts_link
        add_filter('next_posts_link_attributes', 'posts_link_attributes');
        add_filter('previous_posts_link_attributes', 'posts_link_attributes');

        //AddClass menu
        add_filter('nav_menu_link_attributes', 'add_menu_link_class', 10, 4);
        add_filter('nav_menu_css_class', 'add_additional_class_on_li', 10, 4);
        add_filter('nav_menu_submenu_css_class', 'my_nav_menu_submenu_css_class', 10, 4);

        //Remove field website comment post
        add_filter('comment_form_default_fields', 'remove_comment_fields');

        //Custom form search
        add_filter('get_search_form', 'congdongtheme_search_form');
        add_filter('body_class', 'custom_class');
    }
    function custom_class($classes)
    {
        if (wp_is_mobile()) {
            $classes[] = 'is-mobile';
        }
        return $classes;
    }
    function remove_comment_fields($fields)
    {
        unset($fields['url']);
        return $fields;
    }
    function add_additional_class_on_li($classes, $item, $args, $depth)
    {
        if ($args->menu == 'primary-menu') {
            $cdt_class[] = '';
            if (!(!$item->has_children && $item->menu_item_parent > 0) && isset($args->add_li_class)) {
                $cdt_class[] = $args->add_li_class;
            }

            if (in_array('menu-item-has-children', $classes) && 0 != $depth) {
                $cdt_class[] = 'dropdown-submenu';
            }
            if (in_array('current-menu-item', $classes)) {
                $cdt_class[] = 'active';
            }
            if (in_array('current-menu-ancestor', $classes)) {
                $cdt_class[] = 'active-grandparent';
            }
            
            return $cdt_class;
        }
        return $classes;
    }

    function add_menu_link_class($atts, $item, $args, $depth)
    {
        if ($args->menu == 'primary-menu') {
            $cdt_class = 'nav-link';
            if (0 == $depth) {
                if (in_array('menu-item-has-children', $item->classes)) {
                    $cdt_class .= ' dropdown-toggle';
                    $atts['data-bs-toggle'] = 'dropdown';
                }
            } else {
                if (in_array('menu-item-has-children', $item->classes)) {
                    $cdt_class = 'dropdown-item';
                    $atts['data-bs-toggle'] = 'dropdown';
                }
            }
            $atts['class'] = $cdt_class;
        }
        return $atts;
    }

    function my_nav_menu_submenu_css_class($classes, $args, $depth)
    {
        if ($args->menu == 'primary-menu') {
            $cdt_class[] = '';
            // if (0 != $depth) {
            //     $cdt_class[] = 'submenu';
            // }
            $cdt_class[] = 'dropdown-menu';
            return $cdt_class;
        }
        return $classes;
    }

    function congdongtheme_login_enqueue_scripts()
    {
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
?>
            <style type="text/css" media="screen">
                #login h1 a {
                    background-image: url(<?php echo esc_url($logo[0]); ?>) !important;
                }
            </style>
        <?php
        }
    }
    //Thay đổi url ảnh logo
    function congdongtheme_login_headerurl()
    {
        return "https://congdongtheme.com";
    }
    //Tùy chỉnh CSS cho trang đăng nhập Wordpress
    function tp_custom_logo()
    {
        ?>
        <style type="text/css">
            .login #login_error {
                box-shadow: 30px 30px 80px rgba(37, 37, 37, 0.1) !important;
                border-radius: 15px !important;
            }

            .login .message {
                border-left: 4px solid #ec1f27 !important;
                border-radius: 15px;
                box-shadow: 0 30px 80px rgba(37, 37, 37, 0.1) !important;
            }

            .login #nav a:hover,
            .login #backtoblog a:hover {
                color: #eb1f27 !important;
            }

            .login .button-primary {
                box-shadow: 0 1px 0 #2271b1 !important;
                border-radius: 20px !important;
                text-shadow: none !important;
            }

            #user_login,
            #user_pass {
                background-color: #EEEEEE;
                padding-top: 10px;
            }

            input[type=checkbox]:checked:before {
                color: #eb1f27;
            }

            .login input[type=text]:focus,
            .login input[type=password]:focus,
            input[type=email]:focus {
                box-shadow: 0 0 2px rgb(255, 255, 255) !important;
            }

            .login h1 a {
                background-size: 115px !important;
                height: 115px !important;
                width: 209px !important;
            }

            #login {
                width: 450px !important;
            }

            .login form {
                box-shadow: 0 30px 80px rgba(37, 37, 37, 0.1) !important;
                border-radius: 15px;
                padding: 40px !important;
            }

            .login form .input,
            .login form input[type=checkbox],
            .login input[type=text] {
                background: #f5f5f5;
                border-radius: 30px;
            }

            .login .button.wp-hide-pw .dashicons {
                top: 0.45rem !important;
                right: 0.15rem !important;
            }

            @media only screen and (max-width: 480px) {
                #login {
                    width: 320px !important;
                }
            }
        </style>
        <?php
    }
    //Header Text
    function congdongtheme_admin_title($admin_title, $title)
    {
        return get_bloginfo('name') . ' &bull; ' . $title . ' &bull; ' . ' Cộng Đồng Theme - Global Theme';
    }
    //Footer version
    function congdongtheme_footer_version()
    {
        return __('Version', 'condongtheme') . ' 1.0.0';
    }
    //Footer Text
    function congdongtheme_footer()
    {
        printf(
            '%1$s <a href="%2$s" target="blank">%3$s</a>',
            __('Thiết kế bởi', 'condongweb'),
            "https://congdongweb.com",
            "Cộng Đồng web - Global Theme"
        );
    }
    //Footer Bottom Contact
    function congdongtheme_footer_bottom_contact()
    {
        $rows = get_field('bottom_contact', 'option');
        if ($rows) {
        ?>
            <div class="congdongtheme__main-cta">
                <ul class="congdongtheme__item congdongtheme__flex">
                    <?php
                    $icon = 'call.png';

                    foreach ($rows as $row) {
                        $type = $row['type'];
                        $alt = $row['alt'];
                        $info = $row['info'];
                        if (!$style || $style == 'blue') $style = 'red';
                        else $style = 'blue';
                        switch ($type) {
                            case "call":
                                break;
                            case "sms":
                                $icon = 'mail.png';
                                break;
                            case "zalo":
                                $icon = 'zalo.png';
                                break;
                            case "messenger":
                                $icon = 'messenger.png';
                                break;
                            case "map":
                                $icon = 'map2.png';
                                break;
                        }
                    ?>
                        <li class="congdongtheme__<?php echo $style; ?>"><a href="<?php echo $info; ?>" class="congdongtheme__icon"><img src="<?php echo THEME_URL_F . '/assets/icons/' . $icon; ?>" alt="<?php echo $alt; ?>"></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        <?php
        }
    }

    //Footer Bottom snowflakes
    function congdongtheme_footer_bottom_snowflakes()
    {
        ?>
        <div class="snowflakes" aria-hidden="true">
            <div class="snowflake" style="font-size: 30px;">❅</div>
            <div class="snowflake">❅</div>
            <div class="snowflake" style="font-size: 40px;">❆ </div>
            <div class="snowflake">❅</div>
            <div class="snowflake" style="font-size: 30px;">❆</div>
            <div class="snowflake" style="font-size: 22px;">❅</div>
            <div class="snowflake" style="font-size: 50px;">❆</div>
            <div class="snowflake" style="font-size: 20px;">❅</div>
            <div class="snowflake" style="font-size: 70px;">❆</div>
            <div class="snowflake" style="font-size: 20px;">❆</div>
        </div>

        <style>
            .snowflake {
                color: green;
            }

            @-webkit-keyframes snowflakes-fall {
                0% {
                    top: -10%
                }

                100% {
                    top: 100%
                }
            }

            @-webkit-keyframes snowflakes-shake {
                0% {
                    -webkit-transform: translateX(0px);
                    transform: translateX(0px)
                }

                50% {
                    -webkit-transform: translateX(80px);
                    transform: translateX(80px)
                }

                100% {
                    -webkit-transform: translateX(0px);
                    transform: translateX(0px)
                }
            }

            @keyframes snowflakes-fall {
                0% {
                    top: -10%
                }

                100% {
                    top: 100%
                }
            }

            @keyframes snowflakes-shake {
                0% {
                    transform: translateX(0px)
                }

                50% {
                    transform: translateX(80px)
                }

                100% {
                    transform: translateX(0px)
                }
            }

            .snowflake {
                position: fixed;
                top: -10%;
                z-index: 9999;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                cursor: default;
                -webkit-animation-name: snowflakes-fall, snowflakes-shake;
                -webkit-animation-duration: 10s, 3s;
                -webkit-animation-timing-function: linear, ease-in-out;
                -webkit-animation-iteration-count: infinite, infinite;
                -webkit-animation-play-state: running, running;
                animation-name: snowflakes-fall, snowflakes-shake;
                animation-duration: 10s, 3s;
                animation-timing-function: linear, ease-in-out;
                animation-iteration-count: infinite, infinite;
                animation-play-state: running, running
            }

            .snowflake:nth-of-type(0) {
                left: 1%;
                -webkit-animation-delay: 0s, 0s;
                animation-delay: 0s, 0s
            }

            .snowflake:nth-of-type(1) {
                left: 10%;
                -webkit-animation-delay: 1s, 1s;
                animation-delay: 1s, 1s
            }

            .snowflake:nth-of-type(2) {
                left: 20%;
                -webkit-animation-delay: 6s, .5s;
                animation-delay: 6s, .5s
            }

            .snowflake:nth-of-type(3) {
                left: 30%;
                -webkit-animation-delay: 4s, 2s;
                animation-delay: 4s, 2s
            }

            .snowflake:nth-of-type(4) {
                left: 40%;
                -webkit-animation-delay: 2s, 2s;
                animation-delay: 2s, 2s
            }

            .snowflake:nth-of-type(5) {
                left: 50%;
                -webkit-animation-delay: 8s, 3s;
                animation-delay: 8s, 3s
            }

            .snowflake:nth-of-type(6) {
                left: 60%;
                -webkit-animation-delay: 6s, 2s;
                animation-delay: 6s, 2s
            }

            .snowflake:nth-of-type(7) {
                left: 70%;
                -webkit-animation-delay: 2.5s, 1s;
                animation-delay: 2.5s, 1s
            }

            .snowflake:nth-of-type(8) {
                left: 80%;
                -webkit-animation-delay: 1s, 0s;
                animation-delay: 1s, 0s
            }

            .snowflake:nth-of-type(9) {
                left: 90%;
                -webkit-animation-delay: 3s, 1.5s;
                animation-delay: 3s, 1.5s
            }
        </style>
    <?php
    }

    // Read more
    function congdongtheme_post_readmore()
    {
        return '...'; //…<a class="read-more" href="' . get_permalink(get_the_ID()) . '">' . __('Read More', 'congdongtheme') . '</a>';
    }

    function posts_link_attributes()
    {
        return 'class="page-link"';
    }
}

// Logo
if (!function_exists('congdongtheme_logo')) {
    function congdongtheme_logo()
    {
    ?>
        <div class="logo">
            <div class="site-name">

                <?php
                if (has_custom_logo()) {
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                ?>
                    <a href="<?php echo home_url(); ?>">
                        <img width="512" height="512" src="<?php echo esc_url($logo[0]); ?>" class="congdongtheme-logo" alt="<?php echo get_bloginfo('sitename'); ?>">
                    </a>
                <?php
                }

                if (is_home()) {
                    printf(
                        '<h1><a href="%1$s" title="%2$s">%3$s</a></h1>',
                        get_bloginfo('url'),
                        get_bloginfo('description'),
                        get_bloginfo('sitename')
                    );
                } else {
                    printf(
                        '<h2><a href="%1$s" title="%2$s">%3$s</a></h2>',
                        get_bloginfo('url'),
                        get_bloginfo('description'),
                        get_bloginfo('sitename')
                    );
                }
                ?>

            </div>
            <div class="site-description">
                <?php bloginfo('description'); ?>
            </div>
        </div>
    <?php
    }
}
//  Menu
if (!function_exists('congdongtheme_menu')) {
    function congdongtheme_menu($slug = 'primary-menu')
    {
        $menu_meta = array(
            'menu' => $slug,
            'container'      => false,
            'menu_class' => 'navbar-nav mb-2 mb-lg-0',
            'add_li_class'  => 'nav-item',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        );
        wp_nav_menu($menu_meta);
    }
}
function add_description_to_menu($item_output, $item, $depth, $args)
{

    if (strlen($item->description) > 0) {
        //$item_output .= sprintf('<span class="description">%s</span>', esc_html($item->description));
        $item_output = substr($item_output, 0, -strlen("</a>{$args->after}")) . sprintf('<span class="description">%s</span >', esc_html($item->description)) . "</a>{$args->after}";
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
// Phân trang
if (!function_exists('congdongtheme_post_pagination')) {
    function congdongtheme_post_pagination($pages = '', $range = 4)
    {
        if (is_singular()) return;
        global $wp_query, $paged;
        if ($pages == '') $pages = $wp_query->max_num_pages;
        /** Ngừng thực thi nếu có ít hơn hoặc chỉ có 1 bài viết */
        if ($pages <= 1) return;
        if (empty($paged)) $paged = 1;
        $showitems = ($range * 2) + 1;
    ?>
        <!-- Pagination-->
        <nav aria-label="Pagination">
            <hr class="my-0" />
            <ul class="pagination justify-content-center my-4">


                <?php
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                ?>
                    <li class="page-item "><a class="page-link" href="<?php echo get_pagenum_link(1); ?>"><?php echo __('&laquo; đầu tiên', 'congdongtheme'); ?></a></li>
                <?php
                }
                ?>
                <li class="page-item <?php if (!get_previous_posts_link()) echo "disabled"; ?>">
                    <?php echo (get_previous_posts_link()) ? get_previous_posts_link(__('Trước', 'congdongtheme')) : "<a class=\"page-link\" href=\"#!\">" . __('Trước', 'congdongtheme') . "</a>" ?>
                </li>

                <?php
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                ?>
                        <li class="page-item <?php echo ($paged == $i) ? " active" : ""; ?>" <?php echo ($paged == $i) ? " aria-current=\"page\"" : ""; ?>><a class="page-link" href="<?php echo ($paged == $i) ? "#!" : get_pagenum_link($i); ?>"><?php echo $i; ?></a></li>
                <?php
                    }
                }
                ?>

                <li class="page-item <?php if (!get_next_posts_link()) echo "disabled"; ?>">
                    <?php echo (get_next_posts_link()) ? get_next_posts_link(__('Sau', 'congdongtheme')) : "<a class=\"page-link\" href=\"#!\">" . __('Sau', 'congdongtheme') . "</a>" ?>
                </li>
                <?php
                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                ?>
                    <li class="page-item "><a class="page-link" href="<?php echo get_pagenum_link($pages); ?>"><?php echo __('&Uacute;Cuối cùng &raquo;', 'congdongtheme'); ?></a></li>
                <?php
                }
                ?>
            </ul>
        </nav>
    <?php
    }
}
// Phân trang
if (!function_exists('congdongtheme_post_pagination_ajax')) {
    function congdongtheme_post_pagination_ajax($pages = '', $range = 4)
    {
        if (is_singular()) return;
        global $wp_query, $paged;
        if ($pages == '') $pages = $wp_query->max_num_pages;
        /** Ngừng thực thi nếu có ít hơn hoặc chỉ có 1 bài viết */
        if ($pages <= 1) return;
        if (empty($paged)) $paged = 1;
        $showitems = ($range * 2) + 1;
    ?>
        <!-- Pagination-->
        <nav aria-label="Pagination" <?php if (is_tax('site-types')) echo "data-idcat=\"" . get_queried_object_id() . "\""; ?>>
            <hr class="my-0" />
            <ul class="pagination justify-content-center my-4">

                <?php
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                ?>
                    <li class="page-item cursor-pointer" data-pagi="1"><?php echo __('<i class="fa fa-angle-double-left" aria-hidden="true"></i>', 'congdongtheme'); ?></li>
                <?php
                }
                ?>

                <li class="page-item <?php if (get_previous_posts_link()) echo "cursor-pointer"; ?>" <?php if (!get_previous_posts_link()) echo "disabled"; ?> data-pagi=" <?php if (!get_previous_posts_link()) echo "-1";
                                                                                                                                                                            else echo $paged - 1; ?>"><?php echo __('<i class="fa fa-angle-left" aria-hidden="true"></i>', 'congdongtheme'); ?></li>

                <?php
                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                ?>
                        <li class="page-item <?php echo ($paged == $i) ? " active" : "cursor-pointer"; ?>" <?php echo ($paged == $i) ? " aria-current=\"page\"" : ""; ?> data-pagi="<?php echo $i; ?>"><?php echo $i; ?></li>

                <?php
                    }
                }
                ?>

                <li class="page-item <?php if (get_next_posts_link()) echo "cursor-pointer"; ?>" <?php if (!get_next_posts_link()) echo "disabled"; ?> data-pagi="<?php if (!get_next_posts_link()) echo "-1";
                                                                                                                                                                    else echo $paged + 1; ?>"><?php echo __('<i class="fa fa-angle-right" aria-hidden="true"></i>', 'congdongtheme'); ?></li>

                <?php
                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                ?>
                    <li class="page-item cursor-pointer" data-pagi="<?php echo $pages; ?>"><?php echo __('<i class="fa fa-angle-double-right" aria-hidden="true"></i>', 'congdongtheme'); ?></li>
                <?php
                }
                ?>
            </ul>
        </nav>
        <?php
    }
}

// Post Thumbnail 
if (!function_exists('congdongtheme_post_thumbnail')) {
    function congdongtheme_post_thumbnail()
    {
        // Chỉ hiển thumbnail với post không có mật khẩu
        if (has_post_thumbnail()  && !post_password_required() || has_post_format('image')) {
            if (!is_single())

                the_post_thumbnail('full');
            else
                the_post_thumbnail('full');
        }
    }
}

// Post Title
if (!function_exists('congdongtheme_post_header')) {
    function congdongtheme_post_header()
    {
        if (is_single()) {
        ?>
            <h1 class="fw-bolder mb-1">
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h1>
        <?php
        } else {
        ?>
            <h3 class="my-0 title">
                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                    <?php the_title(); ?>
                </a>
            </h3>
    <?php
        }
    }
}
//Form Search
function congdongtheme_search_form($form)
{
    $form = '<section class="search"><form role="search" method="get" id="search-form" action="' . home_url('/') . '" >
     <label class="screen-reader-text" for="s">' . __('',  'congdongtheme') . '</label>
     <input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __('Tìm bất cứ thứ gì bạn muốn',  'congdongtheme') . '" />
     <input type="submit" id="searchsubmit" value="' . esc_attr__('Tìm', 'congdongtheme') . '" />
     </form></section>';

    return $form;
}

//  Post meta
if (!function_exists('congdongtheme_post_meta')) {
    function congdongtheme_post_meta()
    {
        if (!is_page()) {
            echo '<div class="post-meta my-2">';
            // Hiển thị tên tác giả, tên category và ngày tháng đăng bài
            printf(
                __('<span class="author">Chia sẻ bởi %1$s -</span>', 'congdongtheme'),
                get_the_author()

            );
            printf(
                __('<span class="category"> Danh mục %1$s -</span>', 'congdongtheme'),
                get_the_category_list(', ')
            );

            // Hiển thị số đếm lượt bình luận
            if (comments_open()) {
                echo ' <span class="meta-reply">';
                comments_popup_link(
                    __('Để lại bình luận', 'congdongtheme'),
                    __('1 bình luận', 'congdongtheme'),
                    __('% Bình luận', 'congdongtheme'),
                    __('Đọc tất cả bình luận', 'congdongtheme')
                );
                echo '</span>';
            }
            echo '</div>';
        }
    }
}


//  Post content
if (!function_exists('congdongtheme_post_content')) {
    function congdongtheme_post_content()
    {
        if (!is_single()) {
            the_excerpt();
        } else {
            the_content();
            /*
           * Code hiển thị phân trang trong post type
           */
            $link_pages = array(
                'before' => __('<p>Trang:', 'congdongtheme'),
                'after' => '</p>',
                'nextpagelink'     => __('Trang tiếp theo', 'congdongtheme'),
                'previouspagelink' => __('Trang trước', 'congdongtheme')
            );
            wp_link_pages($link_pages);
        }
    }
}
add_filter("term_links-post_tag", 'add_tag_class');

function add_tag_class($links)
{
    if (is_single()) {
        return str_replace('<a href="', '<a class="badge bg-secondary text-decoration-none link-light" href="', $links);
    }
}
//  Post tag
if (!function_exists('congdongtheme_post_tag')) {
    function congdongtheme_post_tag()
    {
        if (has_tag()) {
            echo '<div class="post-tag">';
            printf(__('Thẻ %1$s', 'congdongtheme'), get_the_tag_list('', ' '));
            echo '</div>';
        }
    }
}

/*insert to functions.php*/
function congdongtheme_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?> id="li-comment-<?= get_comment_ID(); ?>">
        <div id="comment-<?= get_comment_ID(); ?>" class="clearfix">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, $size = '70', ''); ?>
            </div><!-- end comment autho vcard-->

            <div class="commentBody">
                <div class="comment-meta commentmetadata">
                    <?php printf(__('<p class="fn">%s</p>', 'congdongtheme'), get_comment_author_link()); ?>
                </div>
                <!--end .comment-meta-->
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php echo __('Bình luận của bạn đang chờ kiểm duyệt.', 'congdongtheme'); ?></em>
                <?php endif; ?>
                <div class="noidungcomment">
                    <?php comment_text(); ?>
                </div>
                <div class="tools_comment">
                    <?php comment_reply_link(array_merge($args, array('respond_id' => 'formcmmaxweb', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    <?php edit_comment_link(__('Sửa'), ' ', ''); ?>
                    <?php printf(__('<a href="#comment-%d" class="ngaythang">%s</a>'), get_comment_ID(), get_comment_date('d/m/Y')); ?>
                </div>

            </div>
            <!--end #commentBody-->
        </div>
        <!--end #comment-author-vcard-->
    </li>
    <?php }

// Register Style
function congdongtheme_styles_scripts()
{
    global $wp_query;
    wp_register_style('bootstrap-style', THEME_URL_F . '/assets/lib/bootstrap/bootstrap.min.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('bootstrap-script', THEME_URL_F . '/assets/lib/bootstrap/bootstrap.min.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_style('aos-style', THEME_URL_F . '/assets/lib/aos/aos.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('aos-script', THEME_URL_F . '/assets/lib/aos/aos.js', array(), CDW_MAIN_VERSION, true);

    wp_register_style('fontawesome-style', THEME_URL_F . '/assets/lib/fontawesome/font-awesome.min.css', array(), CDW_MAIN_VERSION, 'all');

    wp_register_style('slick-style', THEME_URL_F . '/assets/lib/slick/slick.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_style('slick-theme-style', THEME_URL_F . '/assets/lib/slick/slick-theme.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('slick-script', THEME_URL_F . '/assets/lib/slick/slick.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_style('lightgallery-style', THEME_URL_F . '/assets/lib/lightgallery/lightgallery.min.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('lightgallery-script', THEME_URL_F . '/assets/lib/lightgallery/lightgallery-all.min.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_style('lightslider-style', THEME_URL_F . '/assets/lib/lightslider/lightslider.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('lightslider-script', THEME_URL_F . '/assets/lib/lightslider/lightslider.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_style('justifiedGallery-style', THEME_URL_F . '/assets/lib/justifiedGallery/justifiedGallery.min.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('justifiedGallery-script', THEME_URL_F . '/assets/lib/justifiedGallery/jquery.justifiedGallery.min.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_style('sweetalert2-style', THEME_URL_F . '/assets/lib/sweetalert2/sweetalert2.min.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('sweetalert2-script', THEME_URL_F . '/assets/lib/sweetalert2/sweetalert2.min.js', array('jquery'), CDW_MAIN_VERSION, true);

    wp_register_script('captcha-script', 'https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit', array(), CDW_MAIN_VERSION, true);

    wp_register_style('select2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/css/select2.min.css', array(), CDW_VERSION, 'all');
    wp_register_style('select2-bootstrap', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/css/select2-bootstrap.min.css', array(), CDW_VERSION, 'all');
    wp_register_script('select2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/js/select2.js', ['jquery'], CDW_VERSION);
    wp_register_script('select2-i18n-vi', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/js/i18n/vi.js', ['jquery'], CDW_VERSION);

    wp_register_script('initSelect2', ADMIN_CHILD_THEME_URL_F . '/assets/js/initSelect2.js', ['jquery'], CDW_VERSION);
    
    wp_register_style('main-style', THEME_URL_F . '/style.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_style('congdongtheme-style', THEME_URL_F . '/assets/css/congdongtheme.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('main-script', THEME_URL_F . '/script.js', array(), CDW_MAIN_VERSION, true);
    wp_register_script('order', THEME_URL_F . '/assets/js/shw-order.js', array(), CDW_MAIN_VERSION, true);
    wp_register_style('order', THEME_URL_F . '/assets/css/shw-order.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_style('checkout', THEME_URL_F . '/assets/css/cdw-checkout.css', array(), CDW_MAIN_VERSION, 'all');
    wp_register_script('check-domain', THEME_URL_F . '/assets/js/check-domain.js', array(), CDW_MAIN_VERSION, true);
    wp_register_script('whois-domain', THEME_URL_F . '/assets/js/whois-domain.js', array(), CDW_MAIN_VERSION, true);
    wp_register_script('server', THEME_URL_F . '/assets/js/server.js', array(), CDW_MAIN_VERSION, true);
    wp_register_script('plugin', THEME_URL_F . '/assets/js/plugin.js', ['jquery'], CDW_MAIN_VERSION, true);
    wp_register_script('email-server', THEME_URL_F . '/assets/js/email-server.js', array(), CDW_MAIN_VERSION, true);
    $sitekey = get_field('site_key', 'option');
    $array = array(
        'ajax_url'       => admin_url('admin-ajax.php'),
        'captcha'       =>  $sitekey,
        'siteurl'       => home_url(),
        'query_vars' => json_encode($wp_query->query),
        'nonce' => wp_create_nonce('ajax-index-frontend-nonce'),
        'is_login' => is_user_logged_in(),
    );

    wp_enqueue_style('bootstrap-style');
    wp_enqueue_script('bootstrap-script');
    wp_enqueue_style('aos-style');
    wp_enqueue_script('aos-script');
    wp_enqueue_style('sweetalert2-style');
    wp_enqueue_script('sweetalert2-script');
    wp_enqueue_style('fontawesome-style');

    wp_enqueue_style('select2');
    wp_enqueue_style('select2-bootstrap');
    wp_enqueue_script('select2');
    wp_enqueue_script('select2-i18n-vi');
    wp_enqueue_script('initSelect2');

    wp_enqueue_style('main-style');
    wp_enqueue_style('congdongtheme-style');

    wp_localize_script('main-script', 'cdwObjects', $array);
    wp_enqueue_script('main-script');

    $page_template = get_page_template_slug(get_queried_object_id());
    switch ($page_template) {
        case "templates/thanh-toan.php";
            wp_enqueue_style('checkout');
            wp_enqueue_script('user-profile');
            wp_enqueue_script('order');
            initTemplateTMPL([
                'cart-item-template',
                'cart-action-template',
                'cart-summary-template',
                'cart-vat-template',
                'cart-summary-total-template',
                'cart-action-checkout-template',
            ]);
            break;
        case "templates/gio-hang.php";
            wp_enqueue_script('order');
            wp_enqueue_script('user-profile');
            wp_enqueue_style('order');
            initTemplateTMPL([
                'cart-item-template',
                'cart-action-template',
                'cart-summary-template',
                'cart-vat-template',
                'cart-summary-total-template',
                'cart-action-checkout-template',
            ]);
            break;
        case "templates/buy-email-server.php";
            wp_enqueue_script('email-server');
            wp_enqueue_script('user-profile');
            wp_enqueue_style('email-server');
            initTemplateTMPL([
                'cart-item-template',
                'cart-action-template',
                'cart-summary-template',
                'cart-summary-total-template',
                'cart-action-checkout-template',
            ]);
            break;
        case "templates/buy-server.php";
            wp_enqueue_script('server');
            wp_enqueue_script('user-profile');
            wp_enqueue_style('server');
            initTemplateTMPL([
                'cart-item-template',
                'cart-action-template',
                'cart-summary-template',
                'cart-summary-total-template',
                'cart-action-checkout-template',
            ]);
            break;
        case "templates/buy-plugin.php";
            wp_enqueue_script('plugin');
            wp_enqueue_script('user-profile');
            wp_enqueue_style('plugin');
            break;
        case "templates/check-domain.php";
            wp_enqueue_script('user-profile');
            wp_enqueue_script('check-domain');
            initTemplateTMPL([
                'domain-available-template',
                'domain-exists-template',
                'domain-info-exists-template',
                'domain-info-template',
                'domain-notavailable-template',
                'domain-notfound-template'
            ]);
            break;
    }
}
// Register Style
function congdongtheme_admin_styles_scripts()
{
    global $wp_query;
    wp_register_style('fontawesome-style', THEME_URL_F . '/assets/lib/fontawesome/font-awesome.min.css', array(), CDW_MAIN_VERSION, 'all');

    wp_enqueue_style('fontawesome-style');
}

//Show ACF Admin Column_site_managers
function congdongtheme_edit_site_managers_columns_sortable($columns)
{
    if (!is_array($columns)) return $columns;

    if (!isset($columns['featured_preview'])) $columns['featured_preview'] = __('Featured Preview', 'congdongtheme');
    if (!isset($columns['sub_domain'])) $columns['sub_domain'] = __('Sub Domain', 'congdongtheme');
    if (!isset($columns['name'])) $columns['name'] = __('Name', 'congdongtheme');
    if (!isset($columns['price'])) $columns['price'] = __('Price', 'congdongtheme');
    if (!isset($columns['login_user'])) $columns['login_user'] = __('User', 'congdongtheme');
    return $columns;
}

function congdongtheme_edit_site_managers_columns_sortable_num($query)
{
    if (!is_admin()) return;

    $orderby = $query->get('orderby');
    switch ($orderby) {
        case 'sub_domain':
        case 'name':
        case 'login_user':
        case 'price':
            $query->set('meta_key', 'duration');
            $query->set('orderby', 'meta_value_num');
            break;
    }
}
function congdongtheme_add_site_managers_custom_posts_columns($columns)
{
    if (!is_array($columns)) return $columns;

    $date_out = $columns['date'];
    unset($columns['date']);

    if (!isset($columns['featured_preview'])) $columns['featured_preview'] = __('Featured Preview', 'congdongtheme');
    if (!isset($columns['sub_domain'])) $columns['sub_domain'] = __('Sub Domain', 'congdongtheme');
    if (!isset($columns['name'])) $columns['name'] = __('Name', 'congdongtheme');
    if (!isset($columns['price'])) $columns['price'] = __('Price', 'congdongtheme');
    if (!isset($columns['login_user'])) $columns['login_user'] = __('User', 'congdongtheme');

    $columns['date'] = $date_out;
    return $columns;
}
function congdongtheme_get_featured_image($post_ID)
{
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}
function congdongtheme_site_managers_columns_content($column_name, $post_ID)
{
    switch ($column_name) {
        case 'featured_preview':
            $post_featured_image = congdongtheme_get_featured_image($post_ID);
            if ($post_featured_image) {
                echo '<img width="auto" height="55" src="' . $post_featured_image . '" />';
            } else {
                echo __('No Featured Preview', 'congdongtheme');
            }
            break;
        case 'sub_domain':
            $url = get_field("sub_domain", $post_ID);
            if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
                echo __('Sub Domain is not a valid URL', 'congdongtheme');
            } else {
                echo "<a href=" . $url . " target=\"_blank\">Visit " . __('sub Domain', 'congdongtheme') . "</a>";
            }
            break;
        case 'name':
            echo get_field("name", $post_ID);
            break;
        case 'price':
            $value = get_field("price", $post_ID);
            if (is_numeric($value))
                echo number_format($value, 0, '.', ',');
            else
                echo "-";
            break;
        case 'login_user':
            echo get_field("login_user", $post_ID);
            break;
    }
}

//Show ACF Admin Column_site_orders
function congdongtheme_edit_site_orders_columns_sortable($columns)
{
    if (!is_array($columns)) return $columns;

    if (!isset($columns['code_order'])) $columns['code_order'] = __('Mã đơn hàng', 'congdongtheme');
    if (!isset($columns['user'])) $columns['user'] = __('Tài khoản', 'congdongtheme');
    if (!isset($columns['name'])) $columns['name'] = __('Họ và tên', 'congdongtheme');
    if (!isset($columns['email'])) $columns['email'] = __('Email', 'congdongtheme');
    if (!isset($columns['phone'])) $columns['phone'] = __('Số điện thoại', 'congdongtheme');
    if (!isset($columns['address'])) $columns['address'] = __('Địa chỉ', 'congdongtheme');
    if (!isset($columns['note'])) $columns['note'] = __('Ghi chú', 'congdongtheme');
    if (!isset($columns['total'])) $columns['total'] = __('Tổng tiền', 'congdongtheme');
    return $columns;
}

function congdongtheme_edit_site_orders_columns_sortable_num($query)
{
    if (!is_admin()) return;

    $orderby = $query->get('orderby');
    switch ($orderby) {
        case 'code_order':
        case 'user':
        case 'name':
        case 'email':
        case 'phone':
        case 'address':
        case 'note':
        case 'total':
            $query->set('meta_key', 'duration');
            $query->set('orderby', 'meta_value_num');
            break;
    }
}
function congdongtheme_add_site_orders_custom_posts_columns($columns)
{
    if (!is_array($columns)) return $columns;

    $date_out = $columns['date'];
    unset($columns['date']);

    if (!isset($columns['code_order'])) $columns['code_order'] = __('Mã đơn hàng', 'congdongtheme');
    if (!isset($columns['user'])) $columns['user'] = __('Tài khoản', 'congdongtheme');
    if (!isset($columns['name'])) $columns['name'] = __('Họ và tên', 'congdongtheme');
    if (!isset($columns['email'])) $columns['email'] = __('Email', 'congdongtheme');
    if (!isset($columns['phone'])) $columns['phone'] = __('Số điện thoại', 'congdongtheme');
    if (!isset($columns['address'])) $columns['address'] = __('Địa chỉ', 'congdongtheme');
    if (!isset($columns['note'])) $columns['note'] = __('Ghi chú', 'congdongtheme');
    if (!isset($columns['total'])) $columns['total'] = __('Tổng tiền', 'congdongtheme');

    $columns['date'] = $date_out;
    return $columns;
}
function congdongtheme_site_orders_columns_content($column_name, $post_ID)
{
    $user = get_info_customer_order($post_ID);
    $bank = get_note_order($post_ID);
    $ma_don_hang = get_code_order($post_ID);

    switch ($column_name) {
        case 'code_order':
            echo  $ma_don_hang;
            break;
        case 'user':
            echo  $user["username"];
            break;
        case 'name':
            echo $user["name"];
            break;
        case 'email':
            echo $user["email"];
            break;
        case 'phone':
            echo $user["phone"];
            break;
        case 'address':
            echo  $user["address"];
            break;
        case 'note':
            echo $bank;
            break;
        case 'total':
            echo number_format(get_total_order($post_ID), 0, '.', ',');
            break;
    }
}

//Show ACF Admin Column_domain
function congdongtheme_edit_domain_columns_sortable($columns)
{
    if (!is_array($columns)) return $columns;

    if (!isset($columns['gia'])) $columns['gia'] = __('Giá', 'congdongtheme');
    if (!isset($columns['gia_han'])) $columns['gia_han'] = __('Gia hạn', 'congdongtheme');
    return $columns;
}

function congdongtheme_edit_domain_columns_sortable_num($query)
{
    if (!is_admin()) return;

    $orderby = $query->get('orderby');
    switch ($orderby) {
        case 'gia':
        case 'gia_han':
            $query->set('meta_key', 'duration');
            $query->set('orderby', 'meta_value_num');
            break;
    }
}
function congdongtheme_add_domain_custom_posts_columns($columns)
{
    if (!is_array($columns)) return $columns;

    $date_out = $columns['date'];
    unset($columns['date']);

    if (!isset($columns['gia'])) $columns['gia'] = __('Giá', 'congdongtheme');
    if (!isset($columns['gia_han'])) $columns['gia_han'] = __('Gia hạn', 'congdongtheme');

    $columns['date'] = $date_out;
    return $columns;
}
function congdongtheme_domain_columns_content($column_name, $post_ID)
{
    switch ($column_name) {
        case 'gia':
            $value = get_field("gia", $post_ID);
            if (is_numeric($value))
                echo number_format($value, 0, '.', ',');
            else
                echo "-";
            break;
        case 'gia_han':
            $value = get_field("gia_han", $post_ID);
            if (is_numeric($value))
                echo number_format($value, 0, '.', ',');
            else
                echo "-";
            break;
    }
}

//Register Post Type Site Order
function create_siteorder_posttype()
{
    $labels = array(
        'name'                  => __('Đơn hàng',  'congdongtheme'),
        'singular_name'         => __('Đơn hàng',  'congdongtheme'),
        'menu_name'             => __('Đơn hàng', 'congdongtheme'),
        'name_admin_bar'        => __('Đơn hàng', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Đơn hàng', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Đơn hàng', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Đơn hàng', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Đơn hàng', 'congdongtheme'),
        'description'           => __('Đơn hàng', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'         => 'dashicons-cart',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false, //xem Site Order
        'capability_type'       => 'post',
        'rewrite' => array(
            'slug' => 'site-order', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('site-orders', $args);
}
//Register Post Type Site Order Item
function create_siteorderitem_posttype()
{
    $labels = array(
        'name'                  => __('Site Order Items',  'congdongtheme'),
        'singular_name'         => __('Site Order Item',  'congdongtheme'),
        'menu_name'             => __('Site Order Item', 'congdongtheme'),
        'name_admin_bar'        => __('Site Order Item', 'congdongtheme'),
        'archives'              => __('Item Archives', 'congdongtheme'),
        'attributes'            => __('Item Attributes', 'congdongtheme'),
        'parent_item_colon'     => __('Parent Item:', 'congdongtheme'),
        'all_items'             => __('Tất cả', 'congdongtheme'),
        'add_new_item'          => __('Thêm mới Site Order Item', 'congdongtheme'),
        'add_new'               => __('Tạo mới', 'congdongtheme'),
        'new_item'              => __('Thêm', 'congdongtheme'),
        'edit_item'             => __('Sửa', 'congdongtheme'),
        'update_item'           => __('Cập nhật', 'congdongtheme'),
        'view_item'             => __('Xem', 'congdongtheme'),
        'view_items'            => __('Xem', 'congdongtheme'),
        'search_items'          => __('Tìm kiếm', 'congdongtheme'),
        'not_found'             => __('Không tìm thấy', 'congdongtheme'),
        'not_found_in_trash'    => __('Không tìm thấy trong thùng rác', 'congdongtheme'),
        'featured_image'        => __('Ảnh', 'congdongtheme'),
        'set_featured_image'    => __('Chọn ảnh', 'congdongtheme'),
        'remove_featured_image' => __('Xóa ảnh', 'congdongtheme'),
        'use_featured_image'    => __('Sử dụng ảnh', 'congdongtheme'),
        'insert_into_item'      => __('Thêm vào Site Order Item', 'congdongtheme'),
        'uploaded_to_this_item' => __('Cập nhật Site Order Item', 'congdongtheme'),
        'items_list'            => __('Danh sách', 'congdongtheme'),
        'items_list_navigation' => __('Items list navigation', 'congdongtheme'),
        'filter_items_list'     => __('Lọc lại', 'congdongtheme'),
    );
    $args = array(
        'label'                 => __('Site Order Item', 'congdongtheme'),
        'description'           => __('Site Order Item', 'congdongtheme'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'         => 'dashicons-cart',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false, //xem Site Order Item
        'capability_type'       => 'post',
        'rewrite' => array(
            'slug' => 'site-order-item', // use this slug instead of post type name
            'with_front' => FALSE // if you have a permalink base such as /blog/ then setting this to false ensures your custom post type permalink structure will be /products/ instead of /blog/products/
        ),
    );
    register_post_type('site-order-items', $args);
}
//Display a custom taxonomy dropdown in admin
function congdongtheme_filter_post_type_by_taxonomy($typenow)
{

    $post_type = 'site-managers'; // change to your post type
    $taxonomy  = 'site-types'; // change to your taxonomy
    if ($typenow == $post_type) {
        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => sprintf(__('Show all %s', 'congdongtheme'), $info_taxonomy->label),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => false,
        ));
    };
}
//Filter posts by taxonomy in admin
function congdongtheme_convert_id_to_term_in_query($query)
{
    global $pagenow;
    $post_type = 'site-managers'; // change to your post type
    $taxonomy  = 'site-types'; // change to your taxonomy
    $q_vars    = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
        $q_vars[$taxonomy] = $term->slug;
    }
}

// Count View
function td_set_post_views($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

//show view count
function td_get_post_views($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return 0;
    }
    return $count . ' Lượt xem';
}
//căn giữa hình ảnh
function custom_image_size()
{
    update_option('image_default_align', 'center');
    update_option('image_default_size', 'full');
}

//thêm alt hình ảnh
function congdongblog_set_image_meta_image_upload($post_ID)
{
    if (wp_attachment_is_image($post_ID)) {
        $my_image_title = get_post($post_ID)->post_title;
        $my_image_title = ucwords(strtolower($my_image_title));
        $my_image_meta = array(
            'ID'        => $post_ID,            // Specify the image (ID) to be updated
            'post_title'    => $my_image_title,        // Set image Title to sanitized title
            'post_excerpt'    => $my_image_title,        // Set image Caption (Excerpt)
            'post_content'    => $my_image_title,        // Set image Description (Content)
        );
        // Set the image Alt-Text
        update_post_meta($post_ID, '_wp_attachment_image_alt', $my_image_title);
        // Set the image meta (e.g. Title, Excerpt, Content)
        wp_update_post($my_image_meta);
    }
}

function schema_local_business()
{
    $result = "";
    $result = "<script type=\"application/ld+json\">
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"Organization\",
      \"image\": [
        \"https://www.congdongweb.com/wp-content/uploads/2022/11/congdongweb.png\",
        \"https://www.congdongweb.com/wp-content/uploads/2022/09/banner-cong-dong-theme-1.jpg\"
       ],
      \"name\": \"Cộng Đồng Web Đơn Vị Thiết Kế Website Uy Tín Tại Hồ Chí Minh\",
      \"address\": {
        \"@type\": \"PostalAddress\",
        \"streetAddress\": \"168 Nguyễn Gia Trí,Phường 25\",
        \"addressLocality\": \"Bình Thạnh\",
        \"addressRegion\": \"Hồ chí minh\",
        \"postalCode\": \"700000\",
        \"addressCountry\": \"VN\"
      },
      \"geo\": {
        \"@type\": \"GeoCoordinates\",
        \"latitude\": 10.8028222,
        \"longitude\": 106.7169823
      },
      \"url\": \"https://www.congdongweb.com/\",
      \"logo\": \"https://www.congdongweb.com/wp-content/uploads/2022/11/congdongweb.png\",
      \"telephone\": [\"+84353814306\",\"+84386270225\"],
      \"servesCuisine\": \"VietNam\",
      \"priceRange\": \"đ\",
      \"openingHoursSpecification\": [
        {
          \"@type\": \"OpeningHoursSpecification\",
          \"dayOfWeek\": [
            \"Monday\",
            \"Tuesday\",
            \"Wednesday\",
            \"Thursday\",
            \"Friday\",
            \"Saturday\"
          ],
          \"opens\": \"08:00\",
          \"closes\": \"17:00\"
        },
        {
          \"@type\": \"OpeningHoursSpecification\",
          \"dayOfWeek\": \"Sunday\",
          \"opens\": \"09:00\",
          \"closes\": \"17:00\"
        }
      ],
      \"menu\": \"https://www.congdongweb.com/kho-giao-dien/\",
      \"acceptsReservations\": \"True\"
    }
    </script>";
    echo $result;
}

function congdongtheme_smtp($phpmailer)
{
    $host = get_field('smtp_host', 'option');
    $auth = get_field('smtp_auth', 'option');
    $port = get_field('smtp_port', 'option');
    $username = get_field('smtp_username', 'option');
    $password = get_field('smtp_password', 'option');
    $secure = get_field('smtp_secure', 'option');
    $from = get_field('smtp_from', 'option');
    $fromName = get_field('smtp_fromName', 'option');

    $phpmailer->isSMTP();
    $phpmailer->Host = $host;
    $phpmailer->SMTPAuth =  $auth;
    $phpmailer->Port = $port;
    $phpmailer->Username = $username;
    $phpmailer->Password = $password;
    $phpmailer->SMTPSecure = $secure;
    $phpmailer->From = $from;
    $phpmailer->FromName = $fromName;
}

function shw_schema_site_manager()
{
    global $post;
    if (is_singular('site-managers')) {
        $imagelist = array();
        $imagelist[] = get_the_post_thumbnail_url($post);

        // $images = get_field('album_image', $post->ID);
        // foreach ($images as $image_id) {
        //     $imagelist[] = wp_get_attachment_image_url($image_id["id"], 'full');
        // }

        $valuePrice = get_field("price");

    ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Product",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "5",
                    "reviewCount": "99"
                },
                "description": "<?php echo $post->post_excerpt; ?>",
                "name": "<?php echo $post->post_title; ?>",
                "image": "<?php echo implode('","', $imagelist); ?>",
                "offers": {
                    "@type": "Offer",
                    "price": "<?php echo $valuePrice; ?>",
                    "availability": "https://schema.org/InStock",
                    "url": "<?php echo get_permalink(); ?>",
                    "priceCurrency": "VND",
                    "priceValidUntil": "<?php echo get_the_date(); ?>"
                },
                "sku": "<?php echo $post->ID; ?>",
                "url": "<?php echo get_permalink(); ?>"
            }
        </script>
        <?php
        $items = array();
        $postType = 'site-managers';
        $taxonomyName = 'site-types';
        $taxonomy = get_the_terms(get_the_ID(), $taxonomyName);
        if ($taxonomy) {
            $category_ids = array();
            $category_names = array();
            foreach ($taxonomy as $individual_category) {
                $category_ids[] = $individual_category->term_id;
                $category_names[] = "{\"@type\": \"PhysicalActivityCategory\",\"name\": \"" . $individual_category->name . "\"}";
            };
            $args = array(
                'post_type' =>  $postType,
                'post__not_in' => array(get_the_ID()),
                'posts_per_page' => 4,
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomyName,
                        'field'    => 'term_id',
                        'terms'    => $category_ids,
                    ),
                )
            );
            $my_query = new wp_query($args);
            if ($my_query->have_posts()) :
                $i = 1;
                while ($my_query->have_posts()) : $my_query->the_post();
                    $imagelist = array();
                    $imagelist[] = get_the_post_thumbnail_url($my_query);

                    // $images = get_field('album_image', $my_query->ID);
                    // foreach ($images as $image_id) {
                    //     $imagelist[] = wp_get_attachment_image_url($image_id["id"], 'full');
                    // }
                    $valuePrice = get_field("price");
                    $items[] = "{ \"@type\": \"ListItem\",\"position\": \"" . $i++ . "\",
                        \"item\": {\"@type\": \"Product\",\"aggregateRating\": {\"@type\": \"AggregateRating\",\"ratingValue\": \"5\",\"reviewCount\": \"99\"},
                        \"description\": \"" . get_the_excerpt() . "\",\"name\": \"" . get_the_title() . "\",\"image\": \"" . implode('\",\"', $imagelist) . "\",
                        \"offers\": {\"@type\": \"Offer\",\"price\": \"" . $valuePrice . "\",\"availability\": \"https://schema.org/InStock\",\"url\": \"" . get_permalink() . "\",\"priceCurrency\": \"VND\",\"priceValidUntil\": \"" . get_the_date() . "\"},
                        \"sku\": \"" . get_the_ID() . "\",\"url\": \"" . get_permalink() . "\",
                        \"category\": [" . implode(',', $category_names) . "]
                    }
                }";
                endwhile;

            endif;
            wp_reset_query();
        }
        ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "ItemList",
                "numberOfItems": <?php echo ($my_query->found_posts + 1) ?>,
                "itemListOrder": "https://schema.org/ItemListOrderAscending",
                "url": "<?php echo get_permalink(); ?>",
                "itemListElement": [<?php echo implode(',', $items); ?>]
            }
        </script>
    <?php

    }
}
add_action('add_meta_boxes', 'add_thong_tin_box');
function add_thong_tin_box()
{
    $screens = ['site-orders'];
    foreach ($screens as $screen) {
        add_meta_box(
            'box_thong_tin',                 // Unique ID
            'Thông tin đơn hàng',      // Box title
            'thong_tin_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}
function get_note_order($id)
{
    $bank = "";
    switch (get_post_meta($id, 'bank', true)) {
        case 'mo-mo':
            $bank = "Thanh toán bằng Momo";
            break;
        case 'bank-online':
            $bank = "Thanh toán bằng Ngân hàng";
            break;
        default:
            $bank = "Liên hệ lại";
            break;
    }
    return $bank;
}

function get_bank_order($id)
{
    $bank = "";
    switch (get_post_meta($id, 'bank', true)) {
        case 'mo-mo':
            $bank = "mo-mo";
            break;
        case 'bank-online':
            $bank = "bank-online";
            break;
        default:
            $bank = "call";
            break;
    }
    return $bank;
}

function get_code_order($id)
{
    return "#DH" . substr('00000000' . $id, -8, 8);;
}

function get_total_order($id)
{
    $total = 0;

    $items = get_item_order($id);
    if (is_array($items))
        foreach ($items as $item) {
            if (is_numeric($item['total'])) {
                $total += $item['total'];
            }
        }
    return $total;
}

function get_info_customer_order($id)
{

    $customer = get_post_meta($id, 'customer', true);
    $user = get_userdata($customer);
    $user_phone = get_field('user_phone', 'user_' .  $customer);
    $user_address = get_field('user_address', 'user_' .  $customer);

    return [
        'id' => $user->ID,
        'name' => $user->first_name . " " . $user->last_name,
        'username' => $user->user_login,
        'email' => $user->user_email,
        'phone' => $user_phone,
        'address' => $user_address,
    ];
}
function get_item_order($id)
{
    try {
        return unserialize(get_post_meta($id, 'item', true));
    } catch (Exception $e) {
        return [];
    }
}
function get_date_diff_order($id)
{
    $date = get_the_date("d/m/yy", $id);
    $datetime = new DateTime();
    $datetime1 = $datetime->createFromFormat('d/m/yy', $date);
    $datetime2 = new DateTime(); // current date    
    date_modify($datetime2, '+1 years');
    $interval = $datetime2->diff($datetime1);
    $timeDiff = 'Còn ';
    if ($interval->y > 0)
        $timeDiff .= $interval->y . ' năm ';
    if ($interval->m > 0)
        $timeDiff .= $interval->m . ' tháng ';
    if ($interval->d > 0)
        $timeDiff .= $interval->d . ' ngày';
    return $timeDiff;
}
function thong_tin_box_html($post)
{
    $user = get_info_customer_order($post->ID);
    $bank = get_note_order($post->ID);
    $ma_don_hang = get_code_order($post->ID);
    ?>
    <div>
        <div><span>Mã đơn hàng: </span><span><strong><?php echo $ma_don_hang; ?></strong></span></div>
        <div>
            <div><span>Khách hàng: </span><span><strong><?php echo  $user["name"]; ?></strong></span></div>
            <div><span>Email: </span><span><strong><?php echo $user["email"]; ?></strong></span></div>
            <div><span>Địa chỉ: </span><span><strong><?php echo $user["address"]; ?></strong></span></div>
            <div><span>Số điện thoại: </span><span><strong><?php echo $user["phone"]; ?></strong></span></div>
            <div><span>Ghi chú:</span> <strong><?php echo $bank; ?></strong></div>
        </div>
    </div>
<?php
}

add_action('add_meta_boxes', 'add_chi_tiet_box');
function add_chi_tiet_box()
{
    $screens = ['site-orders'];
    foreach ($screens as $screen) {
        add_meta_box(
            'box_chi_tiet',                 // Unique ID
            'Chi tiết đơn hàng',      // Box title
            'chi_tiet_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}

function chi_tiet_box_html($post)
{
    $items = get_item_order($post->ID);
    $total = get_total_order($post->ID);

    $timeDiff = get_date_diff_order($post->ID);
    $date = get_the_date("d/m/Y", $post->ID);

?>
    <div class="basket thanh-toan-template">
        <div class="basket-labels">
            <ul>
                <li class="item item-heading">Mã Đơn</li>
                <li class="price">Giá</li>
                <li class="quantity">Số Lượng</li>
                <li class="subtotal">Tổng Tiền</li>
            </ul>
        </div>
        <div id="cartItems">
            <?php
            if (is_array($items) && count($items) > 0) {
                $i = 1;
                foreach ($items as $item) {
                    $name = $item["type"] != 2 ? $item["type"] : "Domain";
                    $vps = "";
                    $domain = "";
                    if ($item['hosting'] != 0) {
                        $vps = ' <p><strong>' .
                            $item["noteHosting"] . ' x ' . (is_numeric($item['hosting']) ? number_format($item['hosting'], 0, ',', '.') : $item["hosting"]) . ' VND</strong></p>
                                <p>Đăng Ký:  ' . $date . ' (' . $timeDiff . ')</p>';
                        $name .= '<br> Hosting';
                    }
                    if ($item["domain"] != 0) {
                        $domain = '<p><strong class="jframe-domain">' .
                            $item["noteDomain"] . ' x ' . (is_numeric($item['domain']) ? number_format($item['domain'], 0, ',', '.') : $item["domain"]) . ' VND</strong> 
                        <span>Domain: ' . (isset($item["yourDomain"]) ? $item["yourDomain"] : "") . '</span></p>                       
                        <p>Đăng Ký: ' . $date . ' (' . $timeDiff . ')</p>';
                        $name .= `<br> Domain`;
                    }
                    // var_dump($item)
            ?>
                    <div class="basket-product" id="item-<?php echo $item['id']; ?>">
                        <div class="remove">
                            <?php echo $i++; ?>
                        </div>
                        <div class="item">
                            <div class="product-sku">
                                <?php echo $name; ?>
                            </div>
                            <div class="product-details">
                                <h3><strong><span class="item-quantity">1 </span> x <a href="<?php echo $item['url']; ?>">
                                            <?php echo $item['name']; ?></a></strong> </h3>
                                <?php echo $vps; ?>
                                <?php echo $domain; ?>

                            </div>
                        </div>
                        <div class="price">
                            <?php
                            if (is_numeric($item['price']))
                                echo number_format($item['price'], 0, ',', '.');
                            else
                                echo $item['price'];
                            ?>
                            VND</div>
                        <div class="quantity">
                            <?php echo $item['count']; ?>
                        </div>
                        <div class="subtotal">
                            <?php
                            if (is_numeric($item['total'])) {
                                echo number_format($item['total'], 0, ',', '.');
                            } else
                                echo $item['total'];
                            ?>
                            VND</div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="basket-product">
                    <div class="item">
                        Bạn chưa mua sản phẩm nào
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="summary-subtotal">
            <div class="subtotal-title">Tổng cộng</div>
            <div class="subtotal-value final-value" id="basket-subtotal" style="display: block;"><?php echo  number_format($total, 0, ',', '.'); ?> VND</div>
        </div>
    </div>
    <style>
        <Style>div#basket-promo {
            text-align: right;
        }

        table.table-infomation {
            width: 100%;
            background: #fff;
        }

        table.table-infomation tr td:nth-child(1) {
            width: 70px;
        }

        table.table-infomation tr,
        table.table-infomation td {
            padding: 3px;
        }

        .summary-subtotal,
        .summary-total {
            border-top: 1px dashed #ccc;
            clear: both;
            margin: 0;
            overflow: hidden;
            padding: 0.5rem 0;
        }

        strong.jframe-domain {
            display: flex;
            align-items: center;
        }

        .product-details input {
            padding: 5px;
            font-size: 11px;
            line-height: 0 !important;
        }

        .subtotal-title,
        .subtotal-value,
        .total-title,
        .total-value,
        .promo-title,
        .promo-value {
            color: #111;
            float: left;
            width: 50%;
        }

        select.summary-bank-option {
            width: 100%;
            padding: 7px;
            border: none;
            box-shadow: 0 0 2px 0 #cccc;
            color: #797979;
        }

        .subtotal-value,
        .total-value {
            text-align: right;
        }

        .summary h3 {
            color: #4991c7;
            margin: 0;
            padding: 0;
            font-size: 1.5em;
            text-align: center;
        }

        .summary {
            background-color: #F8F8F9;
            padding: 1rem;
            position: sticky;
            top: 100px;
            box-shadow: 0 0 4px 0 #cccc;
            border-radius: 5px;
        }

        .summary-total-items {
            color: #666;
            font-size: 0.875rem;
            text-align: center;
            border-bottom: 1px dashed #ccc;
        }

        .basket-labels {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            margin-top: 1.625rem;
        }

        .price,
        .quantity,
        .subtotal {
            width: 15%;
            text-align: right;
        }

        h3.title-cart {
            color: #000;
            text-align: left;
            font-size: 14px;
            padding: 15px 0px 5px 0px;
        }

        .item {
            width: 55%;
        }

        .basket-product .remove {
            width: 5%;
            float: left;
        }

        .basket-product .item {
            width: 50%;
        }

        .basket ul li {
            float: left;
            list-style: none;
            margin: 0;
            color: #111;
            display: inline-block;
            padding: 0.625rem 0;
        }

        .basket ul {
            padding: 0;
            display: flex;
            margin: 0;
        }

        .basket-product {
            border-bottom: 1px solid #ccc;
            padding: 1rem 0;
            position: relative;
            width: 100%;
        }

        .product-sku {
            width: 35%;
        }

        .product-details {
            width: 65%;
        }

        .product-details h3 {
            font-size: 0.75rem;
            font-weight: normal;
            margin: 0;
            padding: 0;
        }

        .product-details p {
            color: #666;
            font-size: 10px;
            margin: 5px 0;
        }

        .item,
        .price,
        .quantity,
        .subtotal,
        .basket-product,
        .product-sku,
        .product-details {
            float: left;
        }

        .summary-promo.hide {
            display: none;
        }

        .quantity-field {
            background-color: #f0f0f0;
            border: 1px solid #aaa;
            border-radius: 4px;
            width: 3.75rem;
        }

        .basket-module {
            margin-bottom: 30px;
        }
    </style>
<?php
}
function wporg_custom_box_html($post)
{
    $value = get_post_meta($post->ID, '_wporg_meta_key', true);
?>
    <label for="wporg_field">Description for this field</label>
    <select name="wporg_field" id="wporg_field" class="postbox">
        <option value="">Select something...</option>
        <option value="something" <?php selected($value, 'something'); ?>>Something</option>
        <option value="else" <?php selected($value, 'else'); ?>>Else</option>
    </select>
<?php
}
function wporg_save_postdata($post_id)
{
    if (array_key_exists('wporg_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_wporg_meta_key',
            $_POST['wporg_field']
        );
    }
}
//add_action( 'save_post', 'wporg_save_postdata' );

//Status Post
// Register Custom Post Status

function register_custom_post_status()
{

    $listStatus = array(
        'receive' => array(
            'name' => 'Tiếp nhận',
            'status' => '',
        ),
        'processing' => array(
            'name' => 'Đang thực hiện',
            'status' => '',
        ),
        'cancel' => array(
            'name' => 'Hủy',
            'status' => '',
        ),
        'completed' => array(
            'name' => 'Hoàn thành',
            'status' => '',
        ),
    );
    foreach ($listStatus as $key => $value) {
        register_post_status($key, array(
            'label'                     => _x($value['name'], 'post'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop($value['name'] . ' <span class="count">(%s)</span>',  $value['name'] . ' <span class="count">(%s)</span>'),
        ));
    }
}
add_action('init', 'register_custom_post_status');

// Display Custom Post Status Option in Post Edit
function display_custom_post_status_option()
{

    $listStatus = array(
        'receive' => array(
            'name' => 'Tiếp nhận',
            'status' => '',
        ),
        'processing' => array(
            'name' => 'Đang thực hiện',
            'status' => '',
        ),
        'cancel' => array(
            'name' => 'Hủy',
            'status' => '',
        ),
        'completed' => array(
            'name' => 'Hoàn thành',
            'status' => '',
        ),
    );
    global $post;
    if ($post->post_type == 'site-orders') {

        echo '<script>
               jQuery(document).ready(function($){';
        foreach ($listStatus as $key => $value) {
            echo '';
            if ($post->post_status == $key) {
                echo '    
                $(".misc-pub-section.misc-pub-post-status #post-status-display").text("' . $value['name'] . '");
                $("select#post_status").val("' . $key . '");
                    $("select#post_status").append("<option value=\"' . $key . '\" selected>' . $value['name'] . '</option>");
                ';
            } else
                echo '    
                $("select#post_status").append("<option value=\"' . $key . '\" >' .  $value['name']  . '</option>");
            ';
        }

        echo '});</script>';
    }
}
//add_action('admin_footer', 'display_custom_post_status_option');

function add_to_post_status_dropdown()
{
    $listStatus = array(
        'receive' => array(
            'name' => 'Tiếp nhận',
            'status' => '',
        ),
        'processing' => array(
            'name' => 'Đang thực hiện',
            'status' => '',
        ),
        'cancel' => array(
            'name' => 'Hủy',
            'status' => '',
        ),
        'completed' => array(
            'name' => 'Hoàn thành',
            'status' => '',
        ),
    );
    global $post;
    if ($post->post_type == 'site-orders') {

        echo '<script>
        jQuery(document).ready(function($){';
        foreach ($listStatus as $key => $value) {
            echo '';
            echo '$( "select[name=\"post_status\"]" ).append( "<option value=\"' . $key . '\">' . $value['name'] . '</option>" );';
            if ($post->post_status == $key) {
                echo '    
                $("#post-status-display" ).text( "' . $value['name'] . '" ); 
                $("select[name=\"post_status\"]" ).val("' . $key . '");
         ';
            }
        }

        echo '});</script>';
    }
}
add_action('post_submitbox_misc_actions', 'add_to_post_status_dropdown');

function custom_status_add_in_quick_edit()
{
    $listStatus = array(
        'receive' => array(
            'name' => 'Tiếp nhận',
            'status' => '',
        ),
        'processing' => array(
            'name' => 'Đang thực hiện',
            'status' => '',
        ),
        'cancel' => array(
            'name' => 'Hủy',
            'status' => '',
        ),
        'completed' => array(
            'name' => 'Hoàn thành',
            'status' => '',
        ),
    );
    global $post;
    if (isset($post->post_type) && $post->post_type == 'site-orders') {

        echo '<script>
        jQuery(document).ready(function($){';
        foreach ($listStatus as $key => $value) {
            echo '';
            echo '$( "select[name=\"_status\"]" ).append( "<option value=\"' . $key . '\">' . $value['name'] . '</option>" );';
            //     if ($post->post_status == $key) {
            //         echo '    
            //         $("#post-status-display" ).text( "' . $value['name'] . '" ); 
            //         $("select[name=\"post_status\"]" ).val("' . $key . '");
            //  ';
            //}
        }

        echo '});</script>';
    }
}
add_action('admin_footer-edit.php', 'custom_status_add_in_quick_edit');

add_filter('display_post_states', function ($statuses) {
    global $post;
    $listStatus = array(
        'receive' => array(
            'name' => 'Tiếp nhận',
            'status' => '',
        ),
        'processing' => array(
            'name' => 'Đang thực hiện',
            'status' => '',
        ),
        'cancel' => array(
            'name' => 'Hủy',
            'status' => '',
        ),
        'completed' => array(
            'name' => 'Hoàn thành',
            'status' => '',
        ),
    );
    if ($post && $post->post_type == 'site-orders') {
        if (get_query_var('post_status') != 'receive' && get_query_var('post_status') != 'receive' && get_query_var('post_status') != 'processing'  && get_query_var('post_status')  != 'cancel'  && get_query_var('post_status')  != 'completed') { // not for pages with all posts of this status
            foreach ($listStatus as $key => $value) {
                if ($post->post_status == $key) {
                    return array($value['name']);
                }
            }
        }
    }
    return $statuses;
});

add_action('transition_post_status', 'wpse118970_post_status_new', 10, 3);
function wpse118970_post_status_new($new_status, $old_status, $post)
{
    if ($post->post_type == 'site-orders' && $new_status != 'receive' && $new_status != 'processing' && $new_status != 'cancel' && $new_status != 'completed' && $new_status != 'trash' && $old_status  != $new_status) {
        $post->post_status = 'receive';
        wp_update_post($post);
    }
}
function wp_118970_force_type_private($post)
{
    if ($post['post_type'] == 'site-orders'  && $post['post_status'] != 'receive' && $post['post_status'] != 'processing' && $post['post_status'] != 'cancel' && $post['post_status'] != 'completed') {
        $post['post_status'] = 'receive';
    }
    return $post;
}
//add_filter('wp_insert_post_data', 'wp_118970_force_type_private');
// Shortcode mã hoá liên kết
function shortcode_mahoalienket($args)
{
    extract($args);
    ob_start();
    if (filter_var($u, FILTER_VALIDATE_URL))
        $mahoa = bin2hex($u);
    else
        $mahoa = $u;

?>
    <button style="border:none;background:#333;padding:10px 20px 10px 20px;border-radius:40px;color:#fff;font-size:15px;margin-top:0px;margin-bottom:0px;" onclick="window.open('/getlink?url=<?php echo $mahoa; ?>')"><?php echo $t; ?></button>
<?php
    return ob_get_clean();
}
add_shortcode('getlink', 'shortcode_mahoalienket');

function IsResourceLocal($url)
{
    if (empty($url)) {
        return false;
    }
    $urlParsed = parse_url($url);
    if (!isset($urlParsed['host'])) {
        /* maybe we have a relative link like: /wp-content/uploads/image.jpg */
        /* add absolute path to begin and check if file exists */
        // $doc_root = $_SERVER['DOCUMENT_ROOT'];
        // $maybefile = $doc_root . $url;
        // /* Check if file exists */
        // $fileexists = file_exists($maybefile);
        // if ($fileexists) {
        /* maybe you want to convert to full url? */
        return true;
        // }
    } else {
        $host = $urlParsed['host'];
        /* strip www. if exists */
        $host = str_replace('www.', '', $host);
        $thishost = $_SERVER['HTTP_HOST'];
        /* strip www. if exists */
        $thishost = str_replace('www.', '', $thishost);
        if ($host == $thishost) {
            return true;
        }
    }
    return false;
}

add_action('init', 'my_init');

function my_init()
{
    add_filter('the_content', 'func_the_content', 12);
    add_filter('the_excerpt', 'func_the_content', 12);
    add_filter('the_post', 'my_post');
}

function my_post($post)
{
    $post->post_excerpt = get_the_excerpt();
    $post->post_content = get_the_content();
    return $post;
}

function func_the_content($content)
{
    $rextaga = '/<\s*a([^>]*)>.*?<\s*\/\s*a>/'; //tag a
    $rexhref = '/href="\s*(.*?)\s*"/'; //href
    $search = array();
    $replace = array();
    if (preg_match_all($rextaga, $content, $match)) {
        if (count($match[0]) > 0 && count($match[1]) > 0) {
            foreach ($match[1] as $key => $value) {
                if (preg_match_all($rexhref, $value, $matchhref)) {
                    if (count($matchhref[0]) > 0 && count($matchhref[1]) > 0) {
                        for ($i = 0; $i < count($matchhref[0]); $i++) {
                            $value2 = $matchhref[1][$i];
                            if (!IsResourceLocal($value2) && !(strpos($value2, 'mail:') !== false || strpos($value2, 'tel:') !== false || strpos($value2, 'sms:') !== false)) {
                                $search[] =  $matchhref[0][$i];
                                $ma_hoa = bin2hex($value2);
                                $replace[] = @"href=\"/getlink?url={$ma_hoa}\"";
                            }
                        }
                    }
                }
            }
        }
        $content = str_replace($search, $replace,  $content);
    }
    return $content;
}

//add_action('wp_head', 'intw_list_hooked_functions');
function intw_list_hooked_functions($tag = false)
{
    global $wp_filter;
    if ($tag) {
        $hook[$tag] = $wp_filter[$tag];
        if (!is_array($hook[$tag])) {
            trigger_error("Nothing found for '$tag' hook", E_USER_WARNING);
            return;
        }
    } else {
        $hook = $wp_filter;
        ksort($hook);
    }

    echo '<pre>';
    foreach ($hook as $tag => $priority) {
        echo "<br />&gt;&gt;&gt;&gt;&gt;\t<strong>$tag</strong><br />";
        ksort($priority);
        foreach ($priority as $priority => $function) {
            echo $priority;
            foreach ($function as $name => $properties) {
                echo "\t$name<br />";
            }
        }
    }
    echo '</pre>';
    return;
}
function get_list_hooked_functions_by_name($hook_name)
{
    global $wp_filter;
    $hook = $wp_filter;
    ksort($hook);
    echo '<pre>';
    echo '<h2>  Hook name: ' .  $hook_name . '</h2> ';
    if (isset($hook[$hook_name])) {
        $priority = $hook[$hook_name];
        ksort($priority);
        foreach ($priority as $priority => $function) {
            echo $priority;
            foreach ($function as $name => $properties) {
                echo "\t$name<br />";
            }
        }
    }
    echo '</pre>';
}
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

function initTemplateTMPL($names)
{
    if (!is_array($names)) $names = [$names];
    foreach ($names as $name) {
        $html =  get_templete_content($name);
        add_action('wp_footer',  function () use ($html) {
            echo $html;
        });
    }
}


function get_templete_content($filename)
{
    ob_start();
    require_once THEME_URL . '/templates/tmpl/' . $filename . '.php';
    $data = ob_get_clean();
    return  $data;
}
