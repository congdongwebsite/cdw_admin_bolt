<?php

require_once('iFPTClass.php');
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
        $data = $iFPTClass->texttoAudioConverter($data);
        //var_dump($data);
        $result = new stdClass();
        if (isset($data->error) && $data->error != 0) {
            $result->error = $data->message . ' - ' . $data->message;
        } else
        if (isset($data->async)) {
            $result->url =  $data->async;
            for ($i = 0; $i < (2 * 60 + 10); $i++) {
                //sleep(1);
                if (UR_exists($data->async)) {
                    break;
                }
            }
            if (!UR_exists($data->async))
                $result->error = 'Không tìm thấy file.';
        }
        if (!isset($result->error) && !isset($result->url))
            $result->error = 'Lỗi chưa xác định:' . json_encode($data);

        return  $result;
    } catch (\Exception $e) {
        $result->error = $e->getMessage();
        return  $result;
    }
}

function UR_exists($url)
{
    $headers = get_headers($url);
    return stripos($headers[0], "200 OK") ? true : false;
}
