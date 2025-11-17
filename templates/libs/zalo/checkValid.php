<?php
$iZaloClass = new iZaloClass();

$apikey ='uLmbd41lRmIIVHHCfWWqYwmClcqTZ0Q3';
$url = 'https://api.zalo.ai';

if (!$apikey) {
    return array("error" => "Password not provided. Install here Setup > Domain Registrars.");
}


if (!$url) {
    return array("error" => "The Url Control Name API is incorrect. Install here Setup > Domain Registrars.");
}

$iZaloClass->apikey = $apikey;
$iZaloClass->Url = $url;