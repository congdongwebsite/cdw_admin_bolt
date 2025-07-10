<?php
global $CDWFunc, $menuAdmin;
$menus = $CDWFunc->getMenus();
?>
<nav id="left-sidebar-nav" class="sidebar-nav">
    <ul id="main-menu" class="metismenu li_animation_delay">
        <?php
        foreach ($menus as $menuItem) {
            echo  $menuAdmin->getItemMenu($menuItem);
        }
        ?>
    </ul>
</nav>