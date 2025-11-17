<?php
$iNETClass = new iNETClass();

$token ='1886767C9C12A5D8BF5D5A6C6D5627F1AD28DAAF';
$registarGlobalCode = 'inet-global';
$url = 'https://dms.inet.vn/api';
$ns1_default = 'ns1.inet.vn';
$ns2_default = 'ns2.inet.vn';

if (!$token) {
    return array("error" => "Password not provided. Install here Setup > Domain Registrars.");
}

if (!$registarGlobalCode) {
    return array("error" => "Registrar code has not been provided. Install here Setup > Domain Registrars.");
}

if (!$url) {
    return array("error" => "The Url Control Name API is incorrect. Install here Setup > Domain Registrars.");
}

$iNETClass->Token = $token;
$iNETClass->RegistarGlobalCode = $registarGlobalCode;
$iNETClass->Url = $url;
$iNETClass->Ns1_default = $ns1_default;
$iNETClass->Ns2_default = $ns2_default;