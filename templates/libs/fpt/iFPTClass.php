<?php
require_once('cURL.php');

class iFPTClass
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

    /**
     * Call DMS API
     * @param type $api
     * @param type $params
     * @param type $output
     */
    public function texttoAudioConverter($data)
    {
        $curl = new cURL();
        $apikey = isset($this->apikey) ? $this->apikey : '';
        $url = (isset($this->Url) && strlen($this->Url)) ? $this->Url : '';

        $url .= '/hmi/tts/v5';

        if (is_array($data)) {
            $data['speed'] = (!isset($data['speed'])) ? $this->speed : $data['speed'];
            $data['voice'] = (!isset($data['speaker_id'])) ? $this->voice : $data['speaker_id'];
            $data['input'] = (!isset($data['input'])) ? $this->input : $data['input'];     
            $data['format'] = (!isset($data['format'])) ? $this->format : $data['format'];   
        } elseif (is_object($data)) {
            $data->speed = (!isset($data->speed)) ? $this->speed : $data->speed;
            $data->voice = (!isset($data->speaker_id)) ? $this->voice : $data->speaker_id;
            $data->input = (!isset($data->input)) ? $this->input : $data->input;
            $data->format = (!isset($data->format)) ? $this->format : $data->format;

        }
        // Set token vao header
        $params = array();
        $params['header'] = array(
            'Content-Type: application/x-www-form-urlencoded', 
            'api-key: ' . $apikey,
            'speed: ' . $data['speed'],
            'voice: ' . $data['voice'],
            'format: ' . $data['format'],
            'callback_url: google.com'
        );
        
       $resp = $curl->post(trim($url), $data['input'], $params);

        $resp = json_decode($resp);
        return $resp;
    }
}
