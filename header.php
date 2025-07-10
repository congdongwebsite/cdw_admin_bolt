<?php
if (!current_user_can('read')) {
    wp_die('Bạn không có quyền truy cập chức năng này');
}
?>
<!doctype html>
<html lang="en">

<head>
    <title><?php echo isset($moduleCurrent) ? $moduleCurrent->title : ""; ?> :: <?php echo $blogInfo->name; ?> ::</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Iconic Bootstrap 4.5.0 Admin Template">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">
    <link rel="icon" href="<?PHP echo  $blogInfo->icon; ?>" type="image/x-icon">
    <?php
    do_action('cdw-header');
    ?>
</head>

<body data-theme="light" class="font-nunito right_icon_toggle <?php echo wp_is_mobile() ? "layout-fullwidth sidebar_toggle" : ''; ?>">
    <?php wp_nonce_field('ajax-index-nonce', 'index-nonce'); ?>
    <div id="wrapper" class="theme-cyan">

        <!-- Page Loader -->
        <?php
        require_once('header/page-loader.php');
        ?>

        <!-- Top navbar div start -->
        <?php
        require_once('header/top-navbar.php');
        ?>

        <!-- main left menu -->
        <?php
        require_once('header/main-menu.php');
        ?>

        <!-- rightbar icon div -->
        <?php
        require_once('header/right-bar.php');
        ?>


        <!-- main page content body part -->
        <div id="main-content">
            <div class="container-fluid">
                <div class="block-header">
                    <!-- breadcrumb -->
                    <?php
                    require_once('header/breadcrumb.php');
                    ?>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12">