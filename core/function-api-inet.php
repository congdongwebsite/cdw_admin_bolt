<?php
defined('ABSPATH') || exit;
class APIInetConnectionManager
{
    private $baseURL;
    private $token;

    public function __construct($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    public function authenticate($email, $password)
    {
        $url = $this->baseURL . '/sso/v1/user/signin';
        $params = array(
            'email' => $email,
            'password' => $password
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            $this->token = $data['session.token'];

            return array(
                'success' => true,
                'data' => $data
            );
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
    public function searchDomain($name)
    {
        $url = $this->baseURL . '/rms/v1/domain/search';

        $params = array(
            'name' => $name
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);

            return array(
                'success' => true,
                'data' => $data
            );
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }

    public function getRecord($id)
    {
        $url = $this->baseURL . '/rms/v1/domain/getrecord';

        $params = array(
            'id' => $id
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);

            if ($data['status'] != 'error')
                return array(
                    'success' => true,
                    'data' => $data
                );
            else {
                return array(
                    'success' => false,
                    'data' => $data['message']
                );
            }
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }
    public function updateDNS($id, $nsList)
    {
        $url = $this->baseURL . '/rms/v1/domain/updatedns';

        $params = array(
            'id' => $id,
            'nsList' => $nsList,
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            if ($data['status'] != 'error')
                return array(
                    'success' => true,
                    'data' => $data
                );
            else {
                return array(
                    'success' => false,
                    'data' => $data['message']
                );
            }
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }
    public function updateRecord($id, $recordList)
    {
        $url = $this->baseURL . '/rms/v1/domain/updaterecord';

        $params = array(
            'id' => $id,
            'recordList' => $recordList,
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            if ($data['status'] != 'error')
                return array(
                    'success' => true,
                    'data' => $data
                );
            else {
                return array(
                    'success' => false,
                    'data' => $data['message']
                );
            }
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }

    public function checkDomainAvailability($name, $registrar)
    {
        $url = $this->baseURL . '/rms/v1/domain/checkavailable';
        $params = array(
            'name' => $name
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            return (object) array(
                'success' => true,
                'data' => $data
            );
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return (object) array(
                'success' => false,
                'data' => $error
            );
        }
    }

    public function getDomainDetail($id)
    {
        $url = $this->baseURL . '/rms/v1/domain/detail';
        $params = array(
            'id' => $id
        );

        $response = $this->sendRequest($url, $params);

        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            $message = $data['message'];

            return array(
                'success' => true,
                'data' => $data
            );
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return array(
                'success' => false,
                'data' => $error
            );
        }
    }

    public function getDomainWhois($domain)
    {
        $url = $this->baseURL . '/public/whois/v1/whois/directly';
        $params = array(
            'domainName' => $domain
        );

        $response = $this->sendRequest($url, $params);
        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            $code = $data['code'];

            if ($code == 0) {

                return (object) array(
                    'success' => true,
                    'data' => $data
                );
            } elseif ($code == 1) {
                return (object) array(
                    'success' => false,
                    'data' => 'Không tìm thấy'
                );
            } elseif ($code == 2) {
                return (object) array(
                    'success' => false,
                    'data' => 'Trả về kết quả gần nhất sau 20 phút'
                );
            }
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return (object) array(
                'success' => false,
                'data' => $error
            );
        }
    }

    private function sendRequest($url, $params)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_USERAGENT => "CDW/" . CDW_VERSION,
            CURLOPT_HTTPHEADER => array(
                'token: ' . $this->token,
                'Content-Type: application/json'
            )
        ));

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'data' => $response
        );
    }
}
