<?php
global $CDWFunc;
if (isset($moduleCurrent->actionName) && $moduleCurrent->actionName != '') {
?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h2><?php echo $moduleCurrent->actionName; ?></h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $CDWFunc->getUrl('', ''); ?>"><i class="<?php echo $moduleCurrent->icon; ?>"></i></a>
                </li>
                <li class="breadcrumb-item"><?php echo $moduleCurrent->moduleName; ?></li>
                <li class="breadcrumb-item active"><?php echo $moduleCurrent->actionName; ?></li>
            </ul>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <?php
            if ($breadcrumb_exists) {
                require_once($fileNameBreadcrumb);
            }
            ?>
        </div>
    </div>
<?php
}
?>