<?php
global $CDWFunc;
?>
<div class="right_icon_bar">
    <ul>
        <?php
        foreach ($rightBarModules as $module) {
        ?>
            <li><a href="<?php echo $CDWFunc->getUrl($module->action, $module->module); ?>"><i class="<?php echo $module->icon; ?>"></i></a></li>
        <?php
        }
        ?>
        <li><a href="javascript:void(0);"><i class="fa fa-plus"></i></a></li>
        <li><a href="javascript:void(0);" class="right_icon_btn"><i class="fa fa-angle-right"></i></a></li>
    </ul>
</div>