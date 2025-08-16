<?php
global $CDWFunc;
?>
<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-brand">
            <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-bars"></i></button>
            <button type="button" class="btn-toggle-fullwidth"><i class="fa fa-bars"></i></button>

            <?php
            if ($CDWFunc->isAdministrator()) {
                $customer_id = get_user_meta($userCurrent->ID, 'customer-default-id', true);
            ?>
                <label><select id='customer-id' name='customer-id' class='select2 form-control' data-value="<?php echo $customer_id; ?>"></select></label>
            <?php
            } else {
            ?>
                <a href="<?php echo $CDWFunc->getUrl('', ''); ?>"><?php echo $blogInfo->name; ?></a>
            <?php
            }
            ?>
        </div>
        <div class="navbar-right">

            <!-- Top navbar Search -->
            <?php
            require_once('top-navbar-search.php');
            ?>

            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown top-navbar-notification">
                        <!-- Top navbar Notification -->
                        <?php
                        require_once('top-navbar-notification.php');
                        ?>
                    </li>
                    <li>
                        <a href="<?php echo $CDWFunc->getUrl('index', 'lock'); ?>" class="icon-menu"><i class="fa fa-power-off"></i></a>
                    </li>
                    <li class="ml-2 top-navbar-cart">
                        
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>