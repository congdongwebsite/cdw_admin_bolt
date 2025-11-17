<?php
add_action('cdw-header', function () {
    global $CDWFunc;
    $CDWFunc->initCSS();
});

add_action('cdw-footer', function () {
    global $CDWFunc;
    $CDWFunc->initJS();
});
