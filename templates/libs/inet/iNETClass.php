<?php
require_once('cURL.php');
require_once('utility.php');

class iNETClass
{
    var $minPeriod = 1;
    var $maxPeriod = 10;
    var $params = array();

    function __construct($params = array())
    {
        $this->params = $params;
    }

    /**
     *
     * @param type $domainName
     * @return string
     */
    function getRegistrarCode($domainName)
    {
        $registrarGlobalCode = strtolower(trim($this->RegistarGlobalCode));
        if (strpos($domainName, '.vn')) {
            return 'inet';
        }
        return $registrarGlobalCode;
    }

    /**
     * Domain is VN
     * @return boolean
     */
    function isVnDomain($domainName)
    {
        if (strpos($domainName, '.vn')) {
            return true;
        }
        return false;
    }

    /**
     * Call DMS API
     * @param type $api
     * @param type $params
     * @param type $output
     */
    public function callDMS($api, $data)
    {
        $curl = new cURL();
        $token = isset($this->Token) ? $this->Token : '';

        // set default
        $url = (isset($this->Url) && strlen($this->Url)) ? $this->Url : '';
        if (strpos($url, '/api') === false) {
            $url .= '/api';
        }

        $url .= '/rms/v1';
        $url .= $api;

        // Set token vao header
        $params = array();
        $params['header'] = array('Content-Type: application/json', 'token: ' . $token);
        $resp = $curl->post(trim($url), json_encode($data), $params);

        $resp = json_decode($resp);

        return $resp;
    }

    /**
     * Get province code
     */
    function getProvinceCode($state = '', $domainName, $countrycode = '')
    {
        if (!$this->isVnDomain($domainName)) {
            return $state;
        }

        // Not is Vietnam code
        if (strtolower($countrycode) != 'vn') {
            return $state;
        }

        // get provinces
        $api = '/category/provincelist';
        $provinces = $this->callDMS($api, array());
        foreach ($provinces as $province) {
            if (
                convert_vi_to_en($state) == convert_vi_to_en($province->value)
                || strpos(strtolower($province->value), strtolower($state)) !== false
            ) {
                return $province->name;
            }

            // Cho Truong hop HCM
            $hcm_string_maps = array('hcm', 'tp-hcm', 'tp--hcm', 'ho-chi-minh', 'tp--ho-chi-minh', 'tp-ho-chi-minh', 'thanh-pho-ho-chi-minh', 'ho-chi-minh-city');
            if (
                in_array(convert_vi_to_en($state), $hcm_string_maps)
            ) {
                return 'HCM';
            }

            // Cho Truong hop HN
            $hn_string_maps = array('hanoi', 'ha-noi', 'tp--ha-noi', 'tp-ha-noi', 'thanh-pho-ha-noi', 'ha-noi-city');
            if (
                in_array(convert_vi_to_en($state), $hn_string_maps)
            ) {
                return 'HNI';
            }

            // Cho Truong hop Dak lak
            $dl_string_maps = array('dak-lak', 'dac-lac');
            if (
                in_array(convert_vi_to_en($state), $dl_string_maps)
            ) {
                return 'DLK';
            }

            // Cho Truong hop Dak Nong
            $dn_string_maps = array('dak-nong', 'dac-nong');
            if (
                in_array(convert_vi_to_en($state), $dn_string_maps)
            ) {
                return 'DAG';
            }

            // Cho Truong hop Bac Kan
            $bc_string_maps = array('bac-kan', 'bac-can');
            if (
                in_array(convert_vi_to_en($state), $bc_string_maps)
            ) {
                return 'BKN';
            }
        }
        return null;
    }

    /**
     * Get province code
     */
    function getCountryCode($country = '')
    {
        // get provinces
        $api = '/category/countrylist';
        $countries = $this->callDMS($api, array());
        foreach ($countries as $country) {
            if (convert_vi_to_en($country) == convert_vi_to_en($country->value)) {
                return $country->name;
            }
        }
        return null;
    }

    /**
     * Get customer field
     */
    function getCustomfieldValue($customfields = array(), $field)
    {
        foreach ($customfields as $customfield) {
            if (isset($customfield['value']) && strpos($customfield['value'], $field . ':') !== false) {
                return explode(':', $customfield['value'])[1];
            }
        }
        return null;
    }

    /**
     * Parse error message
     * @param type $resp
     * @param type $instate
     * @return string
     */
    function getDMSError($resp, $instate = '')
    {
        if (!is_object($resp) || !$resp) {
            return 'An unknown error';
        }

        if (isset($resp->status) && $resp->status == 'error') {
            return $resp->message;
        }

        return $instate;
    }

    /**
     * Get session
     * @param type $key
     * @return type
     */
    private function session($key = null)
    {
        if (!$key) {
            return null;
        }

        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    private function set_session($key = null, $val)
    {
        if (!$key || !$val) {
            return null;
        }

        $_SESSION[$key] = $val;

        return true;
    }

    function whois($domain)
    {
        if (!isset($domain)) {
            return false;
        }    

        // get provinces
        $api = '/public/whois/v1/whois/directly';
        $curl = new cURL();
        $token = isset($this->Token) ? $this->Token : '';

        // set default
        $url = (isset($this->Url) && strlen($this->Url)) ? $this->Url : '';
        $url .= $api;

        // Set token vao header
        $params = array();
        $params['header'] = array('Content-Type: application/json', 'token: ' . $token);
        $resp = $curl->post(trim($url), json_encode(array('domainName' => $domain)), $params);

        $resp = json_decode($resp);

        
        if (isset($resp->status) && $resp->status == 'error') {
            return false;
        }

        if (isset($resp->status) && $resp->status == '') {
            return true;
        }
        
        return $resp;

        //return (strlen($resp)) ? json_decode($resp) : false;
    }

    function checkavailable($domain)
    {
        if (!isset($domain)) {
            return false;
        }

        // get provinces
        $api = '/domain/checkavailable';
        $resp = $this->callDMS($api, array('name' => $domain));

        if (isset($resp->status) && $resp->status == 'error') {
            return false;
        }

        if (isset($resp->status) && $resp->status == '') {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $tld
     */
    function getPriceFormTld($tld = null)
    {
        $tld = ltrim($tld, ".");

        // get provinces
        $api = '/feeselling/getbysuffixid';
        $feeselling = $this->callDMS($api, array());
        
        if (isset($output->status) && $output->status == 'error') {
            return false;
        }

        $_feeselling = array();

        if ($tld) {
            foreach ($feeselling as $r) {
                if ($r->suffix == $tld && $r->serviceType == 'domain') {
                    $_feeselling[$r->type] = $r;
                }
            }
        }

        return $_feeselling;
    }

    /**
     * Kiem tra ten mien
     * @param type $domain
     * @return boolean
     */
    function whois_available($whois)
    {
        if (isset($whois->code) && $whois->code == 1) {
            return true;
        }
        return false;
    }
}
