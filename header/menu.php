<?php
global $CDWFunc, $menuAdmin;
$menus = $CDWFunc->getMenus();
?>
<nav id="left-sidebar-nav" class="sidebar-nav">
    <ul id="main-menu" class="metismenu li_animation_delay">
        <?php
        $active = false;
        foreach ($menus as $menuItem) {
            $menu = $menuAdmin->getItemMenu($menuItem, $active);
            $active = $active || $menu['hasActiveMenu'];
            echo $menu['html'];
        }
        ?>
    </ul>
</nav>