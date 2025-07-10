<?php
global $CDWFunc, $CDWUser;
$company = $CDWUser->company;
$avatar = $CDWUser->avatar;
?>
<div class="user-account">
    <img src="<?php echo $avatar; ?>" class="rounded-circle user-photo" alt="<?php echo $company; ?>">
    <div class="dropdown">
        <span>Xin chào,</span>
        <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong><?php echo $company; ?></strong></a>
        <ul class="dropdown-menu dropdown-menu-right account">
            <?php
            $moduleProfile = $CDWFunc->getModule('index', 'profile');
            $moduleSetting = $CDWFunc->getModule('index', 'setting');
            ?>
            <li><a href="<?php echo $CDWFunc->getUrl('index', 'profile'); ?>"><i class="<?php echo $moduleProfile->icon; ?>"></i><?php echo $moduleProfile->moduleName; ?></a></li>
            <li><a href="<?php echo $CDWFunc->getUrl('index', 'setting'); ?>"><i class="<?php echo $moduleSetting->icon; ?>"></i><?php echo $moduleSetting->moduleName; ?></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo wp_logout_url($CDWFunc->getUrl('login', 'lock')); ?>"><i class="icon-power"></i>Đăng xuất</a></li>
        </ul>
    </div>
    <hr>
    <ul class="row list-unstyled user-feature-info  text-center"></ul>
</div>