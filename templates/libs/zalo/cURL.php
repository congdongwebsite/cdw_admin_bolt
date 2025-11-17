<?php

/**
 * cURL Library
 *
 * @author        tieudv89@gmail.com
 * @version        2015-06-24
 */
class cURL
{

    // Bien luu doi tuong cua curl
    var $curl;

    // Bien luu option cua curl
    var $curl_opt = array();
    var $https = FALSE; // Trang thai su dung HTTPS

    function __construct()
    {
        // Khoi tao curl
        $this->curl = curl_init();
    }

    function __destruct()
    {
        // Close curl
        if ($this->curl) {
            curl_close($this->curl);
        }
    }

    /**
     * cURL POST method
     * @param string $url Dia chi url
     * @param mixed $data Du lieu post len url
     * @param array $params cURL params
     * @return    mixed    FALSE (404) || html cua url
     */
    function post($url, $data, array $params = array(), &$result = '')
    {
        // Get curl result
        $params['url'] = $url;
        $params['method'] = 'POST';
        $params['post_fields'] = $data;
        $result = $this->curl($params);

        // // Check file not found
        // if ($this->_is_file_not_found($result['http_code'])) {
        //     return FALSE;
        // }
        return $result['body'];
    }

    /**
     * Chay cURL
     * @param array $params = array(
     *        'url' => '',
     *    'host' => '',
     *    'header' => '',
     *        'method' => '',
     *        'referer' => '',
     *    'cookie' => '',
     *        'post_fields' => '',
     *        'user_pass' => '',
     *    'timeout' => 0,
     *    'curl_opt' => array(),
     *    );
     * @return array 'header', 'body', 'http_code'
     */
    function curl(array $params)
    {
        // Khoi tao curl
        $this->_init($params);

        // Thuc hien curl
        return $this->_exec();
    }

    /**
     * Khoi tao cURL
     */
    private function _init($params)
    {
        // Tao config mac dinh
        $params = $this->set_default_value($params,
            array(
                'url', 'host', 'header', 'method', 'referer',
                'cookie', 'post_fields', 'user_pass', 'timeout', 'curl_opt')
        );

        $header = array(
            'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg',
            'Connection: Keep-Alive',
            'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
        );

        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36 Edg/103.0.1264.77';
        $encoding = 'gzip';


        // Reset cac option cua curl tu phien truoc
        $this->curl = curl_init();
        $this->curl_opt = array();
        //$this->_reset();

        // Tao option cho curl
        $this->curl_opt[CURLOPT_HEADER] = TRUE;
        $this->curl_opt[CURLOPT_VERBOSE] = TRUE;
        $this->curl_opt[CURLOPT_RETURNTRANSFER] = TRUE;
        $this->curl_opt[CURLOPT_URL] = $params['url'];
        $this->curl_opt[CURLOPT_USERAGENT] = $user_agent;
        $this->curl_opt[CURLOPT_ENCODING] = $encoding;
        $this->curl_opt[CURLOPT_FOLLOWLOCATION] = TRUE;

        // Header
        if ($params['host']) {
            $header[] = 'Host: ' . $params['host'];
        }

        if ($params['header']) {
            $header = $params['header'];
        }

        $this->curl_opt[CURLOPT_HTTPHEADER] = $header;

        // Method
        $params['method'] = strtoupper($params['method']);
        if ($params['method'] == 'POST') {
            if (is_array($params['post_fields'])) {
                $params['post_fields'] = http_build_query($params['post_fields']);
            }
            $this->curl_opt[CURLOPT_POST] = TRUE;
            $this->curl_opt[CURLOPT_POSTFIELDS] = $params['post_fields'];
        } elseif ($params['method'] == 'HEAD') {
            $this->curl_opt[CURLOPT_NOBODY] = TRUE;
        }

        // Referer
        if ($params['referer']) {
            $this->curl_opt[CURLOPT_REFERER] = $params['referer'];
        }

        // Cookie
        $_cookie_file = $this->_create_cookie_file($params['cookie']);
        $this->curl_opt[CURLOPT_COOKIEFILE] = $_cookie_file;
        $this->curl_opt[CURLOPT_COOKIEJAR] = $_cookie_file;

        // Login
        if ($params['user_pass']) {
            if (is_array($params['user_pass'])) {
                $params['user_pass'] = implode(':', $params['user_pass']);
            }

            $this->curl_opt[CURLOPT_USERPWD] = $params['user_pass'];
        }

        // Timeout
        if ($params['timeout']) {
            $this->curl_opt[CURLOPT_TIMEOUT] = $params['timeout'];
        }

        // SSL
        if (preg_match('#^https:#i', $params['url']) || $this->https == TRUE) {
            $this->curl_opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
            $this->curl_opt[CURLOPT_SSL_VERIFYHOST] = 2;
        }

        // Cap nhat curl_opt
        if (is_array($params['curl_opt'])) {
            foreach ($params['curl_opt'] as $p => $v) {
                $this->curl_opt[$p] = $v;
            }
        }
        // Gan option cho curl
        foreach ($this->curl_opt as $p => $v) {
            @curl_setopt($this->curl, $p, $v);
        }
    }

    /**
     * Thuc hien cURL
     */
    private function _exec()
    {
        // Thuc hien curl va lay error
        $res = curl_exec($this->curl);
        $error = curl_error($this->curl);
        // Khoi tao bien tra ve
        $result = array(
            'header' => '',
            'body' => '',
            'http_code' => '',
            'error' => '',
        );

        // Xu ly gia tri tra ve
        if ($error) {
            $result['error'] = $error;
        } else {
            $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
            $result['header'] = substr($res, 0, $header_size);
            $result['body'] = substr($res, $header_size);
            $result['http_code'] = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        }

        return $result;
    }

    /**
     * Reset option cua cURL
     */
    private function _reset()
    {
        // Reset curl option
        foreach ($this->curl_opt as $p => $v) {
            if (is_array($v)) $v = array();
            elseif (is_string($v)) $v = '';
            else $v = FALSE;

            @curl_setopt($this->curl, $p, $v);
        }

        // Reset option
        $this->curl_opt = array();
    }

    /**
     * Tao file cookie
     */
    private function _create_cookie_file($filename)
    {
        $filename = (!$filename) ? 'cookie' : $filename;
        /*
        $cookie_file = APPPATH.'cookie/'.$filename;

        if (!file_exists($cookie_file))
        {
                $CI =& get_instance();
                $CI->load->helper('file');

                write_file($cookie_file, '');
        }

        return $cookie_file;
         */
    }

    /**
     * Kiem tra http_code co phai la file not found hay khong
     */
    private function _is_file_not_found($http_code)
    {
        return ($http_code < 200 || $http_code >= 300) ? TRUE : FALSE;
    }

    /**
     * @param type $var
     * @param type $key
     * @param type $value
     * @return type
     */
    private function set_default_value($var, $key, $value = '')
    {
        // Neu $var khong phai la array hoac object
        if (!is_array($var) && !is_object($var)) {
            return $var;
        }

        // Chuyen key thanh array
        $key = (!is_array($key)) ? array($key) : $key;

        // Gan gia tri
        foreach ($key as $k) {
            if (is_array($var)) {
                $var[$k] = (!isset($var[$k])) ? $value : $var[$k];
            } elseif (is_object($var)) {
                $var->$k = (!isset($var->$k)) ? $value : $var->$k;
            }
        }

        return $var;
    }
}