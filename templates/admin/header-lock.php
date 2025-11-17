<!doctype html>
<html lang="en">

<head>
    <title>:: <?php echo $blogInfo->name; ?> :: <?php echo $moduleCurrent->title; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Iconic Bootstrap 4.5.0 Admin Template">
    <meta name="author" content="Cộng đồng Web, Thiết kế website, Logo">

    <link rel="icon" href="<?PHP echo  $blogInfo->icon; ?>" type="image/x-icon">

    <?php
    do_action('cdw-header-lock');
    ?>

</head>

<body data-theme="light" class="font-nunito">
    <!-- WRAPPER -->
    <div id="wrapper" class="theme-cyan">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle auth-main university">
                <div class="auth-box">
                    <div class="top">
                        <img src="<?PHP echo $blogInfo->logo; ?>" alt="Iconic">
                    </div>