<?php
defined('ABSPATH') || exit;
global $CDWFunc, $menuAdmin, $userCurrent;
$menuAdmin = new MenuAdmin();
$userCurrent = wp_get_current_user();
$fileModule = $CDWFunc->getModuleFileName(ACTION_ADMIN, MODULE_ADMIN, SUBACTION_ADMIN);

$fileNameModule = $fileModule->fileName;
$module_exists = $fileModule->found && file_exists($fileNameModule);

$fileBreadcrumb = $CDWFunc->getBreadcrumbFileName(ACTION_ADMIN, MODULE_ADMIN, SUBACTION_ADMIN);
$fileNameBreadcrumb = $fileBreadcrumb->fileName;
$breadcrumb_exists = $fileBreadcrumb->found && file_exists($fileNameBreadcrumb);

$assetInit = $CDWFunc->getPathInit(ACTION_ADMIN, MODULE_ADMIN);
if ($assetInit->found && file_exists($assetInit->fileName)) {
    include($assetInit->fileName);
}

$rightBarModules = $CDWFunc->getModules(['inbox', 'chat']);

switch (strtolower(MODULE_ADMIN)) {
    case "lock":
        $url = home_url();
        header('Location: ' .  $url);
        require_once('header-lock.php');
        switch (strtolower(ACTION_ADMIN)) {
            case 'index':
                if (!is_user_logged_in()) {
                    $url = $CDWFunc->getUrl('login', 'lock');
                    if (isset($_GET['urlredirect']) && $_GET['urlredirect'] != '')
                        $url .= '&urlredirect=' . urlencode($_GET['urlredirect']);
                    header('Location: ' .  $url);
                } else {
                    $functionLock->lock();
                }
                break;
            case 'login':
            case 'register':
            case 'forgot-password':
                if (is_user_logged_in())
                    header('Location: ' . $CDWFunc->getUrl('', ''));
                break;
            case 'maintenance':

                break;
            default;
                if (is_user_logged_in())
                    if ($functionLock->getLock())
                        header('Location: ' . $CDWFunc->getUrl('index', 'lock'));
                    else
                        header('Location: ' . $CDWFunc->getUrl('', ''));
        }
        if ($module_exists) {
            require_once($fileNameModule);
        }
        require_once('footer-lock.php');
        break;
    default:
        if (!is_user_logged_in()) {
            $url = home_url(); //$CDWFunc->getUrl('login', 'lock');
            if (isset($_GET['urlredirect']) && $_GET['urlredirect'] != '')
                $url .= '&urlredirect=' . urlencode($_GET['urlredirect']);
            header('Location: ' .  $url);
        } else {
            // if ($functionLock->getLock())
            //     header('Location: ' . $CDWFunc->getUrl('index', 'lock'));
            if (isset($_GET['urlredirect']) && $_GET['urlredirect'] != '')
                header('Location: ' . $_GET['urlredirect']);
        }

        if ($module_exists) {
            if ($CDWFunc->checkPermission(ACTION_ADMIN, MODULE_ADMIN)) {
                require_once('header.php');
                require_once($fileNameModule);
                require_once('footer.php');
            } else {
                require_once('header-lock.php');
                require_once(ADMIN_THEME_URL . '/modules/lock/can-permission.php');
                require_once('footer-lock.php');
            }
        } else {
            if (count($_GET) > 0) {
                require_once('header-lock.php');
                require_once(ADMIN_THEME_URL . '/modules/lock/404.php');
                require_once('footer-lock.php');
            } else {
                require_once('header.php');

                foreach ($userCurrent->roles as $role) {
                    if (file_exists(ADMIN_THEME_URL . "/"  . "page-" . $role . ".php")) {
                        require_once(ADMIN_THEME_URL . "/"  . "page-" . $role . ".php");
                        break;
                    } else
                        require_once('page.php');
                }

                require_once('footer.php');
            }
        }
}
