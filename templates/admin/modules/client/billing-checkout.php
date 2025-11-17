<?php
global $CDWFunc, $CDWConst, $CDWCart;

$step = isset($_GET['step']) ? $_GET['step'] : '';

switch ($step) {
    case "1":
        require_once('billing-checkout/billing-checkout-step-1.php');
        break;
    case "2":
        require_once('billing-checkout/billing-checkout-step-2.php');
        break;
    default:
        require_once('billing-checkout/billing-checkout-done.php');
}
