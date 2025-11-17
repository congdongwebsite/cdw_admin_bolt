<?php

require_once('iZaloClass.php');
/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 */
function texttoAudioConverter($data)
{
    // Require param
    require('checkValid.php');
    try {
        $data = $iZaloClass->texttoAudioConverter($data);
        $result = new stdClass();
        if (isset($data->error_code)) {
            $result->error = $data->error_code . ' - ' . $data->error_message;
        }
        if (isset($data->data) && isset($data->data->url)) {
            $result->url =  $data->data->url;
        }
        if (!isset($result->error) && !isset($result->url))
            $result->error = 'Lỗi chưa xác định:' . json_encode($data);
        return  $result;
    } catch (\Exception $e) {
        $result->error = $e->getMessage();
        return  $result;
    }
}
