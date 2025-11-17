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

    // Customer Management
    public function searchCustomer($params)
    {
        $url = $this->baseURL . '/rms/v1/customer/search';
        return $this->sendRequest($url, $params);
    }

    public function createCustomer($params)
    {
        $url = $this->baseURL . '/rms/v1/customer/create';
        return $this->sendRequest($url, $params);
    }

    public function updateCustomerInfo($params)
    {
        $url = $this->baseURL . '/rms/v1/customer/updateinfo';
        return $this->sendRequest($url, $params);
    }

    public function forgotPassword($email)
    {
        $url = $this->baseURL . '/rms/v1/customer/forgotpassword';
        $params = array('email' => $email);
        return $this->sendRequest($url, $params);
    }

    public function changePassword($id, $password, $token)
    {
        $url = $this->baseURL . '/rms/v1/customer/changepassword';
        $params = array(
            'id' => $id,
            'password' => $password,
            'passwordForgotToken' => $token
        );
        return $this->sendRequest($url, $params);
    }

    public function getCustomerById($id)
    {
        $url = $this->baseURL . '/rms/v1/customer/get';
        $params = array('id' => $id);
        return $this->sendRequest($url, $params);
    }

    public function getCustomerByEmail($email)
    {
        $url = $this->baseURL . '/rms/v1/customer/getbyemail';
        $params = array('email' => $email);
        return $this->sendRequest($url, $params);
    }

    public function suspendCustomer($id)
    {
        $url = $this->baseURL . '/rms/v1/customer/suspend';
        $params = array('id' => $id);
        return $this->sendRequest($url, $params);
    }

    public function activateCustomer($id)
    {
        $url = $this->baseURL . '/rms/v1/customer/active';
        $params = array('id' => $id);
        return $this->sendRequest($url, $params);
    }

    public function getCustomerSignInLink($email)
    {
        $url = $this->baseURL . '/rms/v1/customer/geturlsignin';
        $params = array('email' => $email);
        return $this->sendRequest($url, $params);
    }

    public function searchEmail($params)
    {
        $url = $this->baseURL . '/rms/v1/email/search';
        return $this->sendRequest($url, $params);
    }

    // Domain Management
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

    public function checkEmailDomainAvailability($name, $planId = 0)
    {
        $url = $this->baseURL . '/rms/v1/email/checkdomainavailable';
        $params = array(
            'domainName' => $name,
            'planId' => $planId
        );

        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);
        if ($response['status'] == 200 && isset($data['status'])) {
            return array(
                'success' => true,
                'data' => $data
            );
        } else {
            return array(
                'success' => false,
                'msg' => isset($data['message']) ? $data['message'] : 'An unknown error occurred'
            );
        }
    }

    public function checkDomainAvailability($name)
    {
        $url = $this->baseURL . '/rms/v1/domain/checkavailable';
        $params = array(
            'name' => $name
        );

        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);
        error_log('[checkDomainAvailability] Response: ' . print_r($data, true));
        if ($response['status'] == 200 && isset($data['status'])) {
            return array(
                'success' => true,
                'data' => $data
            );
        } else {
            return array(
                'success' => false,
                'msg' => isset($data['message']) ? $data['message'] : 'An unknown error occurred'
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

    // Service Purchase
    public function createDomain($params)
    {
        $url = $this->baseURL . '/rms/v1/domain/create';
        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);

        error_log('[create_domain] data: ' . print_r($data, true));
        if ($response['status'] == 200 &&  isset($data['id'])) {
            return array(
                'success' => true,
                'data' => $data
            );
        } else {
            return array(
                'success' => false,
                'msg' => isset($data['message']) ? $data['message'] : 'An unknown error occurred'
            );
        }
    }

    public function renewDomain($params)
    {
        $url = $this->baseURL . '/rms/v1/domain/renew';
        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);

        if ($response['status'] == 200 && isset($data['id'])) {
            return array(
                'success' => true,
                'data' => $data
            );
        } else {
            return array(
                'success' => false,
                'msg' => isset($data['message']) ? $data['message'] : 'An unknown error occurred'
            );
        }
    }

    public function getEmailDetail($id)
    {
        $url = $this->baseURL . '/rms/v1/email/gettotalquota';
        $params = array(
            'id' => $id
        );
        return $this->sendRequest($url, $params);
    }

    public function genDkim($id, $format = true)
    {
        $url = $this->baseURL . '/rms/v1/email/gendkim';
        $params = array(
            'id' => $id,
            'format' => $format
        );
        return $this->sendRequest($url, $params);
    }

    public function privacyProtection($id)
    {
        $url = $this->baseURL . '/rms/v1/domain/privacyprotection';
        $params = array(
            'id' => $id
        );
        return $this->sendRequest($url, $params);
    }

    public function unprivacyProtection($id)
    {
        $url = $this->baseURL . '/rms/v1/domain/unprivacyprotection';
        $params = array(
            'id' => $id
        );
        return $this->sendRequest($url, $params);
    }


    public function createEmail($params)
    {
        $url = $this->baseURL . '/rms/v1/email/create';
        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $data['status'] !== 'error') {
            return ['success' => true, 'data' => $data];
        } else {
            return ['success' => false, 'msg' => $data['message'] ?? 'An unknown error occurred', 'data' => $data];
        }
    }

    public function renewEmail($params)
    {
        $url = $this->baseURL . '/rms/v1/email/renew';
        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $data['status'] !== 'error') {
            return ['success' => true, 'data' => $data];
        } else {
            return ['success' => false, 'msg' => $data['message'] ?? 'An unknown error occurred', 'data' => $data];
        }
    }

    public function changeEmailPlan($params)
    {
        $url = $this->baseURL . '/rms/v1/email/changeplan';
        $response = $this->sendRequest($url, $params);
        $data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $data['status'] !== 'error') {
            return ['success' => true, 'data' => $data];
        } else {
            return ['success' => false, 'msg' => $data['message'] ?? 'An unknown error occurred', 'data' => $data];
        }
    }

    public function createHosting($params)
    {
        $url = $this->baseURL . '/rms/v1/hosting/create';
        return $this->sendRequest($url, $params);
    }

    public function renewHosting($params)
    {
        $url = $this->baseURL . '/rms/v1/hosting/renew';
        return $this->sendRequest($url, $params);
    }

    public function changeHostingPlan($params)
    {
        $url = $this->baseURL . '/rms/v1/hosting/changeplan';
        return $this->sendRequest($url, $params);
    }

    public function createVps($params)
    {
        $url = $this->baseURL . '/rms/v1/vps/create';
        return $this->sendRequest($url, $params);
    }

    public function renewVps($params)
    {
        $url = $this->baseURL . '/rms/v1/vps/renew';
        return $this->sendRequest($url, $params);
    }

    public function getRecordVerify($serviceId)
    {
        $url = $this->baseURL . '/rms/v1/email/getrecordverify';
        $params = ['id' => $serviceId];
        return $this->sendRequest($url, $params);
    }

    public function nslookup($domain, $type)
    {
        $url = $this->baseURL . '/public/nslookup/v1/nslookup/lookup';
        $params = ['domain' => $domain, 'type' => $type];
        return $this->sendRequest($url, $params);
    }

    public function changeVpsPlan($params)
    {
        $url = $this->baseURL . '/rms/v1/vps/changeplan';
        return $this->sendRequest($url, $params);
    }

    // Category Management
    public function getProvinceList()
    {
        $url = $this->baseURL . '/rms/v1/category/provincelist';
        return $this->sendRequest($url, array());
    }

    public function getCountryList()
    {
        $url = $this->baseURL . '/rms/v1/category/countrylist';
        return $this->sendRequest($url, array());
    }

    public function getSuffixList()
    {
        $url = $this->baseURL . '/rms/v1/suffix/list';
        return $this->sendRequest($url, array());
    }

    public function getPlanList($serviceType = '', $type = '', $name = '')
    {
        $url = $this->baseURL . '/rms/v1/plan/list';
        return $this->sendRequest($url, array('serviceType' => $serviceType, 'name' => $name, 'type' => $type));
    }

    public function getWardListByParentId($parentId)
    {
        $url = $this->baseURL . '/rms/v1/category/wardlistbyparentid';
        $params = array(
            'parentId' => $parentId
        );
        return $this->sendRequest($url, $params);
    }

    public function uploadIdNumber($params)
    {
        $url = $this->baseURL . '/rms/v1/contact/uploadidnumber';
        return $this->sendRequest($url, $params);
    }

    public function resetWebmailPassword($params)
    {
        $url = $this->baseURL . '/v1/webmail/resetpassword';
        return $this->sendRequest($url, $params);
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
