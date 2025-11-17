<?php
require_once('cURL.php');

class iSHOPEEClass
{
    var $speed = 1.0;
    var $voice = 'banmai';
    var $input = "Thiếu Input đọc rồi anh ơi!";
    var $format = 'mp3';
    var $params = array();

    function __construct($params = array())
    {
        $this->params = $params;
    }

    public function getComment($data)
    {
        $curl = new cURL();
        $url = (isset($this->Url) && strlen($this->Url)) ? $this->Url : '';

        $url .= '/v2/item/get_ratings';

        // Set token vao header
        $params = array();
        $params['header'] = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $url .= "?" . http_build_query($data);
        //var_dump($url);
        $resp = $curl->get(trim($url), $params);

        $resp = json_decode($resp);
        return $resp;
    }
    public function getProduct($data)
    {
        $curl = new cURL();
        $url = (isset($this->Url) && strlen($this->Url)) ? $this->Url : '';

        $url .= '/v4/shop/search_items';

        // Set token vao header
        $params = array();
        $params['header'] = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $url .= "?" . http_build_query($data);
       // var_dump($url);
        $resp = $curl->get(trim($url), $params);

        //echo '<pre>';
        //var_dump( $resp);
        //echo '</pre>';
        $resp = json_decode($resp);
        return $resp;
    }
}
