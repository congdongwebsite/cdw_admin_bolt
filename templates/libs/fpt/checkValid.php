<?php
$iFPTClass = new iFPTClass();

$apikey ='XcpfcQzd0As78zvk5e6LAhBNZC3FiQwx';
$url = 'https://api.fpt.ai';

if (!$apikey) {
    return array("error" => "Password not provided. Install here Setup > Domain Registrars.");
}


if (!$url) {
    return array("error" => "The Url Control Name API is incorrect. Install here Setup > Domain Registrars.");
}

$iFPTClass->apikey = $apikey;
$iFPTClass->Url = $url;