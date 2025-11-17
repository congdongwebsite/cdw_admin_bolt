<?php
$iSHOPEEClass = new iSHOPEEClass();

$apikey ='XcpfcQzd0As78zvk5e6LAhBNZC3FiQwx';
$url = 'https://shopee.vn/api';

if (!$apikey) {
    return array("error" => "Password not provided. Install here Setup > Domain Registrars.");
}


if (!$url) {
    return array("error" => "The Url Control Name API is incorrect. Install here Setup > Domain Registrars.");
}

$iSHOPEEClass->apikey = $apikey;
$iSHOPEEClass->Url = $url;