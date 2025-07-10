<?php
global $CDWRecaptcha;
$CDWRecaptcha->init();
add_action('cdw-header-lock', function () {
    global $CDWFunc;
    $CDWFunc->initCSS();
});

add_action('cdw-footer-lock', function () {
    global $CDWFunc;
    $CDWFunc->initJS();
});
