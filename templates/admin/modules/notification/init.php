<?php
global $CDWTMPL;
add_action('cdw-header', function () {
    global $CDWFunc;
    $CDWFunc->initCSS();
});

add_action('cdw-footer', function () {
    global $CDWFunc;
    $CDWFunc->initJS();
});

switch (ACTION_ADMIN) {
    case 'index':
        $CDWTMPL->initTemplate([
            'notification-item-template',
            'notification-pagination-item-template',
        ]);
        break;
}
