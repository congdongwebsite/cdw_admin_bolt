<?php
if (!function_exists('pr')) {
    /**
     * prin data
     * @param type $arr
     * @param type $die
     */
    function pr($arr, $die = true)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($die) die;
    }
}

if (!function_exists('convert_vi_to_en')) {
    /**
     * Chuyen tieng viet co dau sang khong dau
     */
    function convert_vi_to_en($str, $parse_spial_str = TRUE)
    {
        $characters = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/' => 'a',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
            '/ì|í|ị|ỉ|ĩ/' => 'i',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
            '/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
            '/đ/' => 'd',
            '/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
            '/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
            '/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
            '/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/' => 'O',
            '/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
            '/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
            '/Đ/' => 'D'
        );

        $replacement = '';
        if ($parse_spial_str) {
            $replacement = '-';
        }

        $characters['/[^A-Za-z0-9\-]/'] = $replacement;
        $characters['/ /'] = $replacement;

        $str = preg_replace(array_keys($characters), array_values($characters), $str);
        return strtolower($str);
    }
}