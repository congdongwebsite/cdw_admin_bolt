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
            'ticket-item-template',
            'ticket-item-type-template',
            'ticket-status-template',
            'ticket-type-item-template',
            'ticket-pagination-template',
        ]);
        break;
}
