<?php
defined('ABSPATH') || exit;
/*
Template name: Admin Congdongweb
* Tất cả các module thì phải tạo ở file module.json, và menu.json(menu.json khi muốn hiển thị ngoài menu, chỉ tạo 1 module trong 1 file)
* Module nhiều action: tạo thư mục modules/ -> tạo file index.php
* Module 1 action: tạo file module.php ngoài thư mục template admin
* Add button Breadcrumb: tạo file action-breadcrumb.php
* Add header & footer: nếu module nhiều action: tạo file lib-js.php,lib-css.php trong thư mục modules/
* Add header & footer: nếu module 1 action: tạo file module-js.php,module-css.php
* add trong file function: require_once(get_stylesheet_directory() . '/templates/admin/core/init.php');
*/

require_once(URL_ADMIN . '/index.php');
