<?php

require_once('utility.php');
require_once('iNETClass.php');

/**
 * Config params
 */
function inet_getConfigArray()
{
    $mess_curl = '';
    if (!function_exists('curl_version')) {
        $mess_curl = '<br />Vui lòng cài đặt và bật thư viện cURL trước khi sử dụng';
    }

    return array(
        "FriendlyName" => array("Type" => "System", "Value" => "iNET"),
        "Description" => array(
            "Type" => "System",
            "Value" => "Nếu bạn chưa có tài khoản? Vui lòng truy cập: <a href=\"https://www.inet.vn\" target=\"_blank\">https://www.inet.vn</a>" . $mess_curl
        ),
        "Token" => array(
            "FriendlyName" => "Token",
            "Type" => "text",
            "Size" => "30",
            "Description" => "API Token được cấp bởi iNET."
        ),
        "RegistarGlobalCode" => array(
            "FriendlyName" => "",
            "Type" => "text",
            "Size" => "30",
            "Description" => "Only applicable for International domain names."
        ),
        "Url" => array(
            "FriendlyName" => "API Url",
            "Type" => "text",
            "Size" => "50",
            "Description" => ''
        ),
        "Ns1_default" => array(
            "FriendlyName" => "Nameserver 1 default",
            "Type" => "text",
            "Size" => "50",
            "Description" => 'Giá trị mặc định là ns1.inet.vn'
        ),
        "Ns2_default" => array(
            "FriendlyName" => "Nameserver 2 default",
            "Type" => "text",
            "Size" => "50",
            "Description" => 'Giá trị mặc định là ns2.inet.vn'
        )
    );
}

/**
 * Register domain
 * @param type $params
 * @return type
 */
function inet_RegisterDomain($params)
{
    // Require param
    require('checkValid.php');

    $fullname = trim($params['firstname'] . ' ' . $params['lastname']);
    if (isset($params['original']['firstname']) && isset($params['original']['lastname'])) {
        $fullname = trim($params['original']['firstname'] . ' ' . $params['original']['lastname']);
    }

    $email = $params['email'];
    $phone = (string)$params['phonenumber'];

    // xu ly so dien thoai
    if (substr($phone, 1) != '0') {
        $phone = '0' . $phone;
    }

    $address = trim($params['address1']);
    $state = (isset($params['state']) && strlen($params['state'])) ? $params['state'] : $params['city'];

    $cData = array();
    $cData['fullname'] = $fullname;
    $cData['email'] = $email;
    $cData['phone'] = $phone;
    $cData['fax'] = '';
    $cData['address'] = $address;
    $cData['country'] = $params['countrycode'];
    $cData['province'] = $iNETClass->getProvinceCode($state, $params['domainname'], $params['countrycode']);

    $api = '/customer/getandcreate';
    $customer = $iNETClass->callDMS($api, $cData);

    if (!isset($customer->id)) {
        return array('error' => $iNETClass->getDMSError($customer));
    }

    if ($params['regperiod'] && floatval($params['regperiod']) > $iNETClass->maxPeriod) {
        return array('error' => 'Không đăng ký được quá 10 năm.');
    }

    $data = array();
    $data['name'] = strtolower($params['domainname']);
    $data['period'] = min(max($params['regperiod'], $iNETClass->minPeriod), $iNETClass->maxPeriod);
    $data['customerId'] = $customer->id;
    $data['registrar'] = $iNETClass->getRegistrarCode($params['domainname']);
    $data['suffix'] = $params['tld'];
    $data['nsList'] = array();
    $data['contacts'] = array();

    // Set name server
    $ns1 = (isset($params['ns1']) && strlen($params['ns1'])) ? $params['ns1'] : $params['Ns1_default'];
    $ns2 = (isset($params['ns2']) && strlen($params['ns2'])) ? $params['ns2'] : $params['Ns2_default'];

    $data['nsList'][] = array('domainName' => $params['domainname'], 'hostname' => $ns1);
    $data['nsList'][] = array('domainName' => $params['domainname'], 'hostname' => $ns2);
    if (strlen($params['ns3'])) {
        $data['nsList'][] = array('domainName' => $params['domainname'], 'hostname' => $params['ns3']);
    }
    if (strlen($params['ns4'])) {
        $data['nsList'][] = array('domainName' => $params['domainname'], 'hostname' => $params['ns4']);
    }

    $organization = (isset($params['additionalfields']['Type']) && convert_vi_to_en($params['additionalfields']['Type']) == 'cong-ty') ? true : false;

    $dataExtend = new stdClass();
    $dataExtend->idNumber = (isset($params['additionalfields']['CMND'])) ? $params['additionalfields']['CMND'] : '';
    $dataExtend->birthday = (isset($params['additionalfields']['Birthday'])) ? date('d/m/Y', strtotime($params['additionalfields']['Birthday'])) : '';
    $dataExtend->gender = (isset($params['additionalfields']['Gender'])) ? $params['additionalfields']['Gender'] : '';
    if ($organization) {
        $dataExtend->taxCode = ($organization && isset($params['additionalfields']['Tax'])) ? $params['additionalfields']['Tax'] : '';
    }

    if (strlen($dataExtend->gender)) {
        $dataExtend->gender = (convert_vi_to_en($dataExtend->gender) == 'nam') ? 'male' : 'female';
    }

    // domain name is .vn
    if (strpos($params['domainname'], '.vn')) {
        // type is company
        if ($organization === true && (!strlen($dataExtend->taxCode) || !strlen($params['companyname']))) {
            // Error
            return array('error' => 'Tên công ty và Mã số thuế không được bỏ trống.');
        }

        // type is Personal
        if ($organization === false && (!strlen($dataExtend->idNumber) || !strlen($dataExtend->birthday))) {
            // Error
            return array('error' => 'CMND và Ngày sinh không được bỏ trống.');
        }
    }

    // Fixed tax code
    if ($organization) {
        //$dataExtend = new stdClass();
        //$dataExtend->taxCode = '1234567890';
    } else {
        //$dataExtend = new stdClass();
        //$dataExtend->idNumber = (string) rand(111111111, 999999999);
        //$dataExtend->birthday = '28/03/1989';
        //$dataExtend->gender = 'male';
    }

    // Registrant contact
    $registrant = array();
    $registrant['fullname'] = $params['original']['fullname'];
    $registrant['organization'] = ($organization === true) ? 'true' : 'false';
    if ($organization === true) {
        $registrant['organizationName'] = $params['original']['companyname'];
        $registrant['fullname'] = $params['original']['companyname'];
        $_dataExtend = new stdClass();
        foreach (array('taxCode') as $p) {
            if (!isset($dataExtend->$p)) {
                continue;
            }
            $_dataExtend->$p = $dataExtend->$p;
        }
    }

    $registrant['email'] = $email;
    $registrant['country'] = $params['original']['countrycode'];
    $registrant['province'] = $iNETClass->getProvinceCode($state, $params['domainname'], $params['countrycode']);
    $registrant['address'] = $address;
    $registrant['phone'] = $phone;
    $registrant['fax'] = '';
    $registrant['type'] = 'registrant';
    $registrant['dataExtend'] = count($_dataExtend) ? json_encode($_dataExtend) : json_encode($dataExtend);
    $data['contacts'][] = $registrant;

    if ($organization == true) {
        unset($dataExtend->taxCode);
    }

    // Admin contact
    $admin = array();
    $admin['fullname'] = $fullname;
    $admin['organization'] = 'false';
    $admin['email'] = $email;
    $admin['country'] = $params['original']['countrycode'];
    $admin['province'] = $iNETClass->getProvinceCode($state, $params['domainname'], $params['countrycode']);
    $admin['address'] = $address;
    $admin['phone'] = $phone;
    $admin['fax'] = '';
    $admin['type'] = 'admin';
    $admin['dataExtend'] = json_encode($dataExtend);
    $data['contacts'][] = $admin;

    // technique contact
    $admin['type'] = 'technique';
    $technique = $admin;
    $data['contacts'][] = $admin;

    // billing contact
    $admin['type'] = 'billing';
    $billing = $admin;
    $data['contacts'][] = $admin;

    $resp = $iNETClass->callDMS('/domain/create', $data);

    if (isset($resp->id)) {
        return array('success' => 'Domain registration successful');
    }

    // Error
    return array('error' => $iNETClass->getDMSError($resp));
}

/**
 * @param type $params
 * @return type
 */
function inet_TransferDomain($params)
{
    // Require param
    require('checkValid.php');

    if (!isset($params['eppcode']) && $params['eppcode'] != '') {
        return array('error' => 'EPP Code is required.');
    }

    $data = array();
    $data['name'] = strtolower($params['domainname']);
    $data['authCode'] = isset($params['eppcode']) ? $params['eppcode'] : '';
    $data['registrar'] = $iNETClass->getRegistrarCode($data['name']);

    $resp = $iNETClass->callDMS('/domaintransfer/request', $data);

    // error
    if (!isset($resp->id)) {
        return array('error' => $iNETClass->getDMSError($resp));
    }

    // Success
    return array('success' => 'Your changes have been saved');
}

/**
 *
 * @param type $params
 */
function inet_RenewDomain($params)
{
    // Require param
    require('checkValid.php');

    if ($params['regperiod'] && floatval($params['regperiod']) > $iNETClass->maxPeriod) {
        return array('error' => 'Period is only from 1 -> 10 years.');
    }

    $data = array();
    $data['name'] = strtolower($params['domainname']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
    if (!isset($domain->id)) {
        return array('error' => $iNETClass->getDMSError($domain));
    }
    $params['regperiod'] = (int)$params['regperiod'];

    $data = array();
    $data['id'] = $domain->id;
    $data['period'] = (int)min(max($params['regperiod'], $iNETClass->minPeriod), $iNETClass->maxPeriod);
    $data['expireDate'] = $domain->expireDate;
    $resp = $iNETClass->callDMS('/domain/renew', $data);

    // error
    if (!isset($resp->id)) {
        return array('error' => $iNETClass->getDMSError($resp));
    }

    // Success
    return array('success' => 'Your changes have been saved');
}

/**
 * @param type $params
 * @return type
 */
function inet_GetNameservers($params)
{
    // Require param
    require('checkValid.php');

    $data = array();
    $data['name'] = strtolower($params['domain']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
    if (isset($domain->nsList) && count($domain->nsList)) {
        $list = array();
        foreach ($domain->nsList as $k => $ns) {
            $list['ns' . ($k + 1)] = $ns->hostname;
        }
        return $list;
    }

    $ls = array();
    foreach (array(1, 2, 3, 4) as $v) {
        if (isset($params['Ns' . $v . '_default']) && strlen($params['Ns' . $v . '_default'])) {
            $ls['ns' . $v] = $params['Ns' . $v . '_default'];
            if (!strlen($ls['ns' . $v]) && isset($params['ns' . $v])) {
                $ls['ns' . $v] = $params['ns' . $v];
            }
        }
    }
    return $ls;
    //return array( 'error' => $iNETClass->getDMSError($domain) );
}

/**
 * Thong tin contact domain
 * @param type $params
 * @return type
 */
function inet_GetContactDetails($params)
{
    // Require param
    require('checkValid.php');

    $data = array();
    $data['name'] = strtolower($params['domain']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);

    $contacts = array();
    if (isset($domain->contactList) && isset($domain->contacts)) {
        $contactList = new stdClass();
        foreach ($domain->contactList as $contactItem) {
            $contactList->{$contactItem->contactId} = $contactItem;
        }

        foreach ($domain->contacts as $k => $contact) {
            $contact->_dataExtend = (strlen($contact->dataExtend)) ? json_decode($contact->dataExtend) : new stdClass();
            if (isset($contactList->{$contact->id}->type) && $contactList->{$contact->id}->type == 'registrant') {
                $contacts['Registrar']['Id'] = $contact->id;
                $contacts['Registrar']['FullName'] = $contact->fullname;
                if (isset($contact->organizationName) && strlen($contact->organizationName)) {
                    $contacts['Registrar']['Company'] = $contact->organizationName;
                }
                $contacts['Registrar']['Address1'] = $contact->address;
                $contacts['Registrar']['Address2'] = '';
                $contacts['Registrar']['Address3'] = '';
                $contacts['Registrar']['Province'] = $contact->province;
                $contacts['Registrar']['Country'] = $contact->country;
                $contacts['Registrar']['PhoneNumber'] = $contact->phone;
                $contacts['Registrar']['Fax'] = $contact->phone  ?? '';
                $contacts['Registrar']['Email'] = $contact->email;
                $contacts['Registrar']['Registrar'] = $contact->registrar ?? '';
                foreach ($contact->_dataExtend as $k => $v) {
                    $contacts['Registrar'][$k] = $v;
                }
            }

            if (isset($contactList->{$contact->id}->type) && $contactList->{$contact->id}->type == 'admin') {
                $contacts['Tech']['Id'] = $contact->id;
                $contacts['Tech']['FullName'] = $contact->fullname;
                $contacts['Tech']['Address1'] = $contact->address;
                $contacts['Tech']['Address2'] = '';
                $contacts['Tech']['Address3'] = '';
                $contacts['Tech']['Province'] = $contact->province;
                $contacts['Tech']['Country'] = $contact->country;
                $contacts['Tech']['PhoneNumber'] = $contact->phone;
                $contacts['Tech']['Fax'] = $contact->fax ?? '';
                $contacts['Tech']['Email'] = $contact->email;
                $contacts['Tech']['Registrar'] = $contact->registrar ?? '';
                foreach ($contact->_dataExtend as $k => $v) {
                    $contacts['Tech'][$k] = $v;
                }
            }

            if (isset($contactList->{$contact->id}->type) && $contactList->{$contact->id}->type == 'technique') {
                $contacts['Admin']['Id'] = $contact->id;
                $contacts['Admin']['FullName'] = $contact->fullname;
                $contacts['Admin']['Address1'] = $contact->address;
                $contacts['Admin']['Address2'] = '';
                $contacts['Admin']['Address3'] = '';
                $contacts['Admin']['Province'] = $contact->province;
                $contacts['Admin']['Country'] = $contact->country;
                $contacts['Admin']['PhoneNumber'] = $contact->phone;
                $contacts['Admin']['Fax'] = $contact->fax ?? '';
                $contacts['Admin']['Email'] = $contact->email;
                $contacts['Admin']['Registrar'] = $contact->registrar ?? '';
                foreach ($contact->_dataExtend as $k => $v) {
                    $contacts['Admin'][$k] = $v;
                }
            }

            if (isset($contactList->{$contact->id}->type) && $contactList->{$contact->id}->type == 'billing') {
                $contacts['Billing']['Id'] = $contact->id;
                $contacts['Billing']['FullName'] = $contact->fullname;
                $contacts['Billing']['Address1'] = $contact->address;
                $contacts['Billing']['Address2'] = '';
                $contacts['Billing']['Address3'] = '';
                $contacts['Billing']['Province'] = $contact->province;
                $contacts['Billing']['Country'] = $contact->country;
                $contacts['Billing']['PhoneNumber'] = $contact->phone;
                $contacts['Billing']['Fax'] = $contact->fax ?? '';
                $contacts['Billing']['Email'] = $contact->email;
                $contacts['Billing']['Registrar'] = $contact->registrar ?? '';
                foreach ($contact->_dataExtend as $k => $v) {
                    $contacts['Billing'][$k] = $v;
                }
            }
        }
        return $contacts;
    }
    return array('error' => $iNETClass->getDMSError($domain));
}

/**
 * @param type $params
 */
function inet_SaveContactDetails($params)
{
    // Require param
    require('checkValid.php');

    $data = array();
    $data['name'] = strtolower($params['domainname']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
    if (!isset($domain->id)) {
        return array('error' => $iNETClass->getDMSError($domain));
    }

    $domainContacts = new stdClass();
    foreach ($domain->contacts as $domainContact) {
        $domainContacts->{$domainContact->id} = $domainContact;
    }

    $types = array('Registrar' => 'registrant', 'Tech' => 'technique', 'Admin' => 'admin', 'Billing' => 'billing');
    foreach ($types as $type => $_type) {
        if (!isset($params['contactdetails'][$type]['Id'])) {
            continue;
        }

        $contact = $params['contactdetails'][$type];

        $data = array();
        $data['id'] = $contact['Id'];
        $data['type'] = $_type;
        $data['fullname'] = $contact['FullName'];
        $data['email'] = $contact['Email'];
        if (isset($contact['Company']) && strlen($contact['Company'])) {
            $data['organizationName'] = $contact['Company'];
            $data['organization'] = true;
        }

        $data['phone'] = $contact['PhoneNumber'];
        $data['address'] = trim($contact['Address1']);
        $data['province'] = $contact['Province'];
        $data['country'] = $contact['Country'];
        $data['registrar'] = $contact['Registrar'];
        $data['dataExtend'] = new stdClass();
        foreach (array('gender', 'CMND', 'birthday', 'taxCode') as $ext) {
            if (isset($contact[$ext]) && strlen($contact[$ext])) {
                $data['dataExtend']->$ext = $contact[$ext];
            }
        }
        $data['dataExtend'] = json_encode($data['dataExtend']);
        foreach (array('authCode', 'code', 'renewTrial', 'trialDay') as $p) {
            if (!isset($domainContacts->{$contact['Id']}->{$p})) {
                continue;
            }
            $data[$p] = $domainContacts->{$contact['Id']}->{$p};
        }
        $resp = $iNETClass->callDMS('/contact/update', $data);
        if (!isset($resp->id)) {
            return array('error' => $iNETClass->getDMSError($resp));
        }
    }
    return array('success' => 'Your changes have been saved');
}

/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 */
function inet_CheckAvailabilitys($params)
{
    // Require param
    require('checkValid.php');
    try {
        // domain parameters
        $sld = $params['sld'];

        $results = array();

        foreach ($params['tlds'] as $tld) {

            // Instantiate a new domain search result object
            $searchResult = [
                "domain" => "",
                "status" => "",
                "priceregister" => 0,
                "pricerenew" => 0,

            ];
            $searchResult["domain"] = $sld . "." . $tld;
            $available = null;
            $whois = $iNETClass->whois($sld . "." . $tld);
            if (!isset($whois->code)) {
                continue;
            }

            if ($whois->code == '1') {
                $available = $iNETClass->checkavailable($sld . $tld);
            }

            // Determine the appropriate status to return
            if ($whois->code == '1' && $available === false) {
                $status = "STATUS_NOT_REGISTERED";
            } elseif ($whois->code == '0') {
                $status = "STATUS_REGISTERED";
            } elseif ($whois->code == 'reserved') {
                $status = "STATUS_RESERVED";
            } elseif ($whois->message == 'no match') {
                $status = "STATUS_TLD_NOT_SUPPORTED";
            } else {
                $status = "STATUS_TLD_NOT_SUPPORTED";
            }

            $price = $iNETClass->getPriceFormTld($tld);
            $searchResult["status"] = $status;
            $searchResult["priceregister"] = number_format($price['register']->price, 0, ',', '.');
            $searchResult["pricerenew"] = number_format($price['renew']->price, 0, ',', '.');

            // Append to the search results list
            $results[] = $searchResult;
        }
        return $results;
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}
/**
 * Check Domain Availability.
 *
 * Determine if a domain or group of domains are available for
 * registration or transfer.
 */
function inet_CheckAvailability($params)
{
    // Require param
    require('checkValid.php');
    try {
        // domain parameters
        $domain = $params['domain'];


        // Instantiate a new domain search result object
        $results = [
            "domain" => "",
            "status" => "",

        ];
        $results["domain"] = $domain;
        $available = null;
        $whois = $iNETClass->whois($domain);
        if (!isset($whois->code)) {
            $status = "STATUS_NOT_REGISTERED";
        }

        if ($whois->code == '1') {
            $available = $iNETClass->checkavailable($domain);
        }

        // Determine the appropriate status to return
        if ($whois->code == '1' && $available === false) {
            $status = "STATUS_NOT_REGISTERED";
        } elseif ($whois->code == '0') {
            $status = "STATUS_REGISTERED";
        } elseif ($whois->code == 'reserved') {
            $status = "STATUS_RESERVED";
        } elseif ($whois->message == 'no match') {
            $status = "STATUS_TLD_NOT_SUPPORTED";
        } else {
            $status = "STATUS_TLD_NOT_SUPPORTED";
        }

        $results["status"] = $status;
        // Append to the search results list

        return $results;
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}
/**
 * Get Domain Suggestions.
 *
 * Provide domain suggestions based on the domain lookup term provided.
 *
 */
function inet_GetDomainSuggestions($params)
{
    // Require param
    require('checkValid.php');

    $results = new ResultsList();
    // domain parameters
    $searchTerm = $params['searchTerm'];
    try {
        foreach ($params['tldsToInclude'] as $tld) {

            // Instantiate a new domain search result object
            $searchResult = new SearchResult($searchTerm, $tld);

            $whois = $iNETClass->whois($searchTerm . '.' . $tld);
            if (!isset($whois->code)) {
                continue;
            }

            // Determine the appropriate status to return
            if ($whois->code == '1') {
                $status = SearchResult::STATUS_NOT_REGISTERED;
            } elseif ($whois->code == '0') {
                $status = SearchResult::STATUS_REGISTERED;
            } elseif ($whois->code == 'reserved') {
                $status = SearchResult::STATUS_RESERVED;
            } elseif ($whois->message == 'no match') {
                $status = SearchResult::STATUS_TLD_NOT_SUPPORTED;
            } else {
                $status = SearchResult::STATUS_TLD_NOT_SUPPORTED;
            }

            $searchResult->setStatus($status);

            // Return premium information if applicable
            if (isset($whois->isPremiumName) && $whois->isPremiumName) {
                $searchResult->setPremiumDomain(true);
                $searchResult->setPremiumCostPricing(
                    array(
                        'register' => (isset($price['register']['price'])) ? $price['register']['price'] : 0,
                        'renew' => (isset($price['renew']['price'])) ? $price['renew']['price'] : 0,
                        'CurrencyCode' => 'VND',
                    )
                );
            }
            // Append to the search results list
            $results->append($searchResult);
        }
        return $results;
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Luu nameserver
 * @param type $params
 * @return type
 */
function inet_SaveNameservers($params)
{
    // Require param
    require('checkValid.php');

    $data = array();
    $data['name'] = strtolower($params['domainname']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
    if (!isset($domain->id)) {
        return array('error' => $iNETClass->getDMSError($domain));
    }

    $data = array();
    $data['id'] = $domain->id;
    $data['nsList'] = array();
    foreach (array('ns1', 'ns2', 'ns3', 'ns4') as $ns) {
        if (isset($params[$ns]) && strlen($params[$ns])) {
            $data['nsList'][] = array('hostname' => $params[$ns]);
        }
    }
    $resp = $iNETClass->callDMS('/domain/updatedns', $data);

    if (isset($resp->id)) {
        return $data['nsList'];
    }

    return array('error' => $iNETClass->getDMSError($resp));
}

/**
 * Sync Domain Status & Expiration Date.
 *
 * Domain syncing is intended to ensure domain status and expiry date
 * changes made directly at the domain registrar are synced to WHMCS.
 * It is called periodically for a domain.
 *
 */
function inet_Sync($params)
{
    // Require param
    require('checkValid.php');
    try {
        $data = array();
        $data['name'] = strtolower($params['domain']);
        $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
        if (!isset($domain->id)) {
            return array('error' => $iNETClass->getDMSError($domain));
        }
        // echo '<pre>';
        // var_dump($domain);
        // echo '</pre>';
        return array(
            'issueDate' => date('Y-m-d', strtotime($domain->issueDate)), // Format: YYYY-MM-DD
            'expirydate' => date('Y-m-d', strtotime($domain->expireDate)), // Format: YYYY-MM-DD
            'active' => (isset($domain->status) && $domain->status == 'active') ? true : false, // Return true if the domain is active
            'expired' => (isset($domain->expireDate) && strtotime($domain->expireDate) < time()) ? true : false, // Return true if the domain has expired
            'transferredAway' => false, // Return true if the domain is transferred out
            'domainStatuses' => $domain->domainStatuses[0]->status, // Return true if the domain is transferred out
        );
    } catch (\Exception $e) {
        return array(
            'error' => $e->getMessage(),
        );
    }
}

/**
 * Lay EPP Code
 * @param type $params
 * @return type
 */
function inet_GetEPPCode($params)
{
    // Require param
    require('checkValid.php');

    $data = array();
    $data['name'] = strtolower($params['domain']);
    $domain = $iNETClass->callDMS('/domain/detailbyname', $data);
    if (!isset($domain->id)) {
        return array('error' => $iNETClass->getDMSError($domain));
    }

    if (!isset($domain->authCode) || !strlen($domain->authCode)) {
        return array('error' => "Cannot get EPP Code.");
    }

    return $domain->authCode;
}
