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
    case 'theme':
        $CDWTMPL->initTemplate('theme-item-template');
        break;
    case 'plugin':
        $CDWTMPL->initTemplate('plugin-item-template');
        break;
    case 'domain':
        $CDWTMPL->initTemplate([
            'table-row-result-exists-template',
            'table-row-result-available-template',
            'table-row-result-notavailable-template',
            'table-row-result-notfound-template',
            'info-domain-template',
            'info-domain-exists-template',
            'domain-item-list-update-dns-template',
            'domain-item-list-update-record-template',
        ]);
        switch (SUBACTION_ADMIN) {
            case "update-record":
            case "update-dns":
                $id = $_GET['id'];
                $customer_id = get_post_meta($id, "customer-id", true);
                $CDWFunc->redirectCustomerCheck($customer_id,  $CDWFunc->getUrl('domain', 'client'));
                break;
        }

        break;
    case 'ticket':
        $CDWTMPL->initTemplate([
            'ticket-item-user-template',
            'ticket-item-type-template',
            'ticket-status-user-template',
            'ticket-type-item-template',
            'ticket-pagination-template',
        ]);
        break;
    case 'notification':
        $CDWTMPL->initTemplate([
            'notification-item-user-template',
            'notification-pagination-item-template',
        ]);
        break;
    case 'cart':
        $CDWTMPL->initTemplate([
            'cart-item-template',
            'cart-action-template',
            'cart-checkout-action-template',
        ]);
        break;
    case 'billing':
        switch (SUBACTION_ADMIN) {
            case "checkout":
                // $id = $_GET['id'];
                // $customer_id = get_post_meta($id, "customer-id", true);
                // $CDWFunc->redirectCustomerCheck($customer_id,  $CDWFunc->getUrl('billing', 'client'));

                $CDWTMPL->initTemplate([
                    'payment-momo-time-expire-text-template',
                ]);
                break;
        }
        break;
}
