<?php
require_once('cURL.php');

class iZaloClass
{
    var $speed = 1.0;
    var $quality = 0;
    var $encode_type = 1;
    var $speaker_id = 2;
    var $input = "Thiếu Input đọc rồi anh ơi!";
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

        $url .= '/v1/tts/synthesize';

        if (is_array($data)) {
            $data['speed'] = (!isset($data['speed'])) ? $this->speed : $data['speed'];
            $data['quality'] = (!isset($data['quality'])) ? $this->quality : $data['quality'];
            $data['encode_type'] = (!isset($data['encode_type'])) ? $this->encode_type : $data['encode_type'];
            $data['speaker_id'] = (!isset($data['speaker_id'])) ? $this->speaker_id : $data['speaker_id'];
            $data['input'] = (!isset($data['input'])) ? $this->input : $data['input'];     
        } elseif (is_object($data)) {
            $data->speed = (!isset($data->speed)) ? $this->speed : $data->speed;
            $data->quality = (!isset($data->quality)) ? $this->quality : $data->quality;
            $data->encode_type = (!isset($data->encode_type)) ? $this->encode_type : $data->encode_type;
            $data->speaker_id = (!isset($data->speaker_id)) ? $this->speaker_id : $data->speaker_id;
            $data->input = (!isset($data->input)) ? $this->input : $data->input;

        }
        // Set token vao header
        $params = array();
        $params['header'] = array('Content-Type: application/x-www-form-urlencoded', 'apikey: ' . $apikey);
        
       $resp = $curl->post(trim($url), $data, $params);

        $resp = json_decode($resp);

        return $resp;
    }
}
