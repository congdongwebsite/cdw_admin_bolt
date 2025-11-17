<?php
defined('ABSPATH') || exit;

require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');

class INetCustomerManager
{
    private $inet_api;

    public function __construct()
    {
        $this->inet_api = new APIInetConnectionManager(APIINETURL);
        $this->inet_api->setToken(APIINETTOKEN);
    }

    private function get_customer_data($customer_id)
    {
        $email = get_post_meta($customer_id, 'email', true);
        $fullname = get_post_meta($customer_id, 'name', true);
        $phone = get_post_meta($customer_id, 'phone', true);
        $address = get_post_meta($customer_id, 'address', true);
        $company_name = get_post_meta($customer_id, 'company_name', true);
        $mst = get_post_meta($customer_id, 'mst', true);
        $company_phone = get_post_meta($customer_id, 'company_phone', true);
        $company_address = get_post_meta($customer_id, 'company_address', true);
        $cmnd = get_post_meta($customer_id, 'cmnd', true);
        $gender = get_post_meta($customer_id, 'gender', true);
        $birthday = get_post_meta($customer_id, 'birthdate', true);
        $province = get_post_meta($customer_id, 'dvhc_tp', true);
        $district = get_post_meta($customer_id, 'dvhc_qh', true);
        $ward = get_post_meta($customer_id, 'dvhc_px', true);

        $organizationName = $company_name;
        if (!empty($mst)) {
            $organizationName .= ' - ' . $mst;
        }

        $final_phone = !empty($company_phone) ? $company_phone : $phone;
        $final_address = !empty($company_address) ? $company_address : $address;

        return [
            'email' => $email,
            'fullname' => $fullname,
            'phone' => $final_phone,
            'address' => $final_address,
            'organizationName' => $organizationName,
            'idNumber' => $cmnd,
            'taxCode' => $mst,
            'country' => 'VN',
            'gender' => $gender,
            'birthday' => $birthday,
            'province' => $province,
            'district' => $district,
            'ward' => $ward,
        ];
    }

    public function upload_documents_for_domain($inet_domain_id, $customer_id)
    {
        $id_card_front_id = get_post_meta($customer_id, 'id_card_front', true);
        $id_card_back_id = get_post_meta($customer_id, 'id_card_back', true);

        if (empty($id_card_front_id) && empty($id_card_back_id)) {
            return; // No documents to upload
        }

        cdw_create_customer_log($customer_id, 'Bắt đầu upload CCCD cho domain lên iNET', 'Domain ID: ' . $inet_domain_id);

        if (!empty($id_card_front_id)) {
            $front_url = wp_get_attachment_url($id_card_front_id);
            if ($front_url) {
                $params = [
                    'id' => $inet_domain_id,
                    'documentType' => 'frontEnd',
                    'url' => $front_url
                ];
                $response = $this->inet_api->uploadIdNumber($params);
                $response_data = json_decode($response['data'], true);
                if ($response['status'] == 200 && $response_data['status'] !== 'error') {
                    cdw_create_customer_log($customer_id, 'Upload CCCD mặt trước lên iNET thành công', 'URL: ' . $front_url);
                } else {
                    cdw_create_customer_log($customer_id, 'Lỗi upload CCCD mặt trước lên iNET', 'Lỗi: ' . ($response_data['message'] ?? 'Unknown error') . ' | URL: ' . $front_url);
                }
            }
        }

        if (!empty($id_card_back_id)) {
            $back_url = wp_get_attachment_url($id_card_back_id);
            if ($back_url) {
                $params = [
                    'id' => $inet_domain_id,
                    'documentType' => 'backEnd',
                    'url' => $back_url
                ];
                $response = $this->inet_api->uploadIdNumber($params);
                $response_data = json_decode($response['data'], true);
                if ($response['status'] == 200 && $response_data['status'] !== 'error') {
                    cdw_create_customer_log($customer_id, 'Upload CCCD mặt sau lên iNET thành công', 'URL: ' . $back_url);
                } else {
                    cdw_create_customer_log($customer_id, 'Lỗi upload CCCD mặt sau lên iNET', 'Lỗi: ' . ($response_data['message'] ?? 'Unknown error') . ' | URL: ' . $back_url);
                }
            }
        }
    }

    public function sync_customer($customer_id)
    {
        if (empty($customer_id)) {
            return ['success' => false, 'msg' => 'Thiếu ID khách hàng'];
        }

        $customer_data = $this->get_customer_data($customer_id);
        $email = $customer_data['email'];

        $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);

        if (!empty($inet_customer_id)) {
            $response = $this->inet_api->getCustomerById($inet_customer_id);
        } else {
            $response = $this->inet_api->getCustomerByEmail($email);
        }

        $response_data = json_decode($response['data'], true);
        $params = $customer_data;

        if ($response['status'] == 200 && !empty($response_data['id'])) {
            // Customer exists, update
            $inet_customer_id = $response_data['id'];
            $params['id'] = $inet_customer_id;
            $update_response = $this->inet_api->updateCustomerInfo($params);
            $update_response_data = json_decode($update_response['data'], true);

            if ($update_response['status'] == 200 && $update_response_data['status'] !== 'error') {
                update_post_meta($customer_id, 'inet_customer_id', $inet_customer_id);
                return ['success' => true, 'msg' => 'Đồng bộ khách hàng thành công'];
            } else {
                return ['success' => false, 'msg' => 'Lỗi khi cập nhật khách hàng trên iNET: ' . ($update_response_data['message'] ?? 'Lỗi không xác định'), 'data' => $update_response_data];
            }
        } else {
            // Customer does not exist, create
            $params['password'] = wp_generate_password();
            $create_response = $this->inet_api->createCustomer($params);
            $create_response_data = json_decode($create_response['data'], true);

            if ($create_response['status'] == 200 && !empty($create_response_data['id'])) {
                $inet_customer_id = $create_response_data['id'];
                update_post_meta($customer_id, 'inet_customer_id', $inet_customer_id);
                return ['success' => true, 'msg' => 'Tạo và đồng bộ khách hàng thành công'];
            } else {
                return ['success' => false, 'msg' => 'Lỗi khi tạo khách hàng trên iNET: ' . ($create_response_data['message'] ?? 'Lỗi không xác định'), 'data' => $create_response_data];
            }
        }
    }

    public function check_email_domain($domain_name, $plan_id = 0)
    {
        if (empty($domain_name)) {
            return ['success' => false, 'msg' => 'Vui lòng nhập tên miền'];
        }
        return $this->inet_api->checkEmailDomainAvailability($domain_name, $plan_id);
    }

    public function check_domain($domain_name)
    {
        if (empty($domain_name)) {
            return ['success' => false, 'msg' => 'Vui lòng nhập tên miền'];
        }
        return $this->inet_api->checkDomainAvailability($domain_name);
    }

    public function create_domain($customer_id, $domain_name, $period = 1)
    {
        if (empty($customer_id) || empty($domain_name)) {
            return ['success' => false, 'msg' => 'Thiếu thông tin khách hàng hoặc tên miền'];
        }

        // Check domain availability
        $availability = $this->check_domain($domain_name);
        if (!$availability['success'] || $availability['data']['status'] != 'available') {
            return ['success' => false, 'msg' => 'Tên miền đã được đăng ký hoặc có lỗi khi kiểm tra.'];
        }

        $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);
        if (empty($inet_customer_id)) {
            // Try to sync the customer
            $sync_result = $this->sync_customer($customer_id);
            if (!$sync_result['success']) {
                return ['success' => false, 'msg' => 'Khách hàng chưa được đồng bộ với iNET. Vui lòng đồng bộ trước.'];
            }
            $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);
        }

        $customer_data = $this->get_customer_data($customer_id);

        $contacts = [];
        $contact_types = ['registrant', 'admin', 'technique', 'billing'];
        foreach ($contact_types as $type) {
            $contacts[] = [
                'type' => $type,
                'fullname' => $customer_data['fullname'],
                'email' => $customer_data['email'],
                'phone' => $customer_data['phone'],
                'address' => $customer_data['address'],
                'country' => $customer_data['country'],
                'province' => $customer_data['province'],
                'taxCode' => $customer_data['taxCode'],
                'birthday' => $customer_data['birthday'],
                'gender' => $customer_data['gender'],
                'idNumber' => $customer_data['idNumber'],
                'organization' => !empty($customer_data['organizationName']),
                'organizationName' => $customer_data['organizationName'],
                'dataExtend' => json_encode(['idNumber' => $customer_data['idNumber']])
            ];
        }

        $params = [
            'name' => $domain_name,
            'customerId' => $inet_customer_id,
            'period' => $period,
            'nsList' => [
                ['hostname' => 'ns1.inet.vn'],
                ['hostname' => 'ns2.inet.vn'],
            ],
            'contacts' => $contacts
        ];
        error_log('[create_domain] params: ' . print_r($params, true));
        return $this->inet_api->createDomain($params);
    }

    public function create_email($domain_name, $inet_customer_id, $plan_id, $period = 1)
    {
        if (empty($inet_customer_id) || empty($domain_name) || empty($plan_id)) {
            return ['success' => false, 'msg' => 'Thiếu thông tin cần thiết (khách hàng, tên miền, hoặc gói dịch vụ).'];
        }

        $params = [
            'domainName' => $domain_name,
            'customerId' => $inet_customer_id,
            'planId' => $plan_id,
            'period' => $period,
        ];

        error_log('[create_email] request: ' . print_r($params, true));
        $response = $this->inet_api->createEmail($params);
        error_log('[create_email] response: ' . print_r($response, true));
        return $response;
    }

    public function create_hosting($customer_id, $domain_name, $plan_id, $period = 1)
    {
        if (empty($customer_id) || empty($domain_name) || empty($plan_id)) {
            return ['success' => false, 'msg' => 'Thiếu thông tin cần thiết (khách hàng, tên miền, hoặc gói dịch vụ).'];
        }

        $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);
        if (empty($inet_customer_id)) {
            return ['success' => false, 'msg' => 'Khách hàng chưa được đồng bộ với iNET. Vui lòng đồng bộ trước.'];
        }

        $params = [
            'domainName' => $domain_name,
            'ownerId' => $inet_customer_id,
            'planId' => $plan_id,
            'period' => $period,
        ];

        $response = $this->inet_api->createHosting($params);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $response_data['status'] == 'success') {
            return ['success' => true, 'msg' => 'Đăng ký hosting thành công.', 'data' => $response_data];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi đăng ký hosting: ' . $response_data['message'], 'data' => $response_data];
        }
    }

    public function get_service_plans($serviceType = '', $type = '', $name = '')
    {
        $response = $this->inet_api->getPlanList($serviceType, $type, $name);
        $response_data = json_decode($response['data'], true);


        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['content']) ? $response_data['content'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy danh sách gói dịch vụ.', 'data' => $response_data];
        }
    }

    public function renew_domain($domain_inet_id, $period = 1)
    {
        $domain_details = $this->inet_api->getDomainDetail($domain_inet_id);

        if (!$domain_details['success']) {
            return ['success' => false, 'msg' => 'Không thể lấy thông tin tên miền: ' . ($domain_details['msg'] ?? 'Lỗi không xác định')];
        }
        error_log('[renew_domain] domain_details: ' . print_r($domain_details, true));

        $params = [
            'id' => $domain_inet_id,
            'period' => $period,
            'expireDate' => $domain_details['data']['expireDate']
        ];
        
        return $this->inet_api->renewDomain($params);
    }

    public function renew_email($email_service_inet_id, $period = 1)
    {
        $params = [
            'emailId' => $email_service_inet_id,
            'period' => $period,
        ];
        $response = $this->inet_api->renewEmail($params);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $response_data['status'] == 'success') {
            return ['success' => true, 'msg' => 'Gia hạn email thành công.', 'data' => $response_data];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi gia hạn email: ' . $response_data['message'], 'data' => $response_data];
        }
    }

    public function renew_hosting($hosting_service_inet_id, $period = 1)
    {
        $params = [
            'hostingId' => $hosting_service_inet_id,
            'period' => $period,
        ];
        $response = $this->inet_api->renewHosting($params);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && $response_data['status'] == 'success') {
            return ['success' => true, 'msg' => 'Gia hạn hosting thành công.', 'data' => $response_data];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi gia hạn hosting: ' . $response_data['message'], 'data' => $response_data];
        }
    }

    public function get_domain_by_name($domain_name)
    {
        if (empty($domain_name)) {
            return ['success' => false, 'msg' => 'Vui lòng nhập tên miền'];
        }
        $response = $this->inet_api->searchDomain($domain_name);

        if ($response['success'] && isset($response['data']['content'])) {
            foreach ($response['data']['content'] as $domain_data) {
                if (isset($domain_data['name']) && strtolower($domain_data['name']) === strtolower($domain_name)) {
                    return ['success' => true, 'data' => $domain_data];
                }
            }
            return ['success' => false, 'msg' => 'Không tìm thấy tên miền chính xác trong kết quả tìm kiếm.'];
        }
        return ['success' => false, 'msg' => 'Lỗi khi tìm kiếm tên miền hoặc không có kết quả.', 'data' => $response['data'] ?? 'Không có dữ liệu trả về'];
    }

    public function get_customer_by_id($inet_customer_id)
    {
        if (empty($inet_customer_id)) {
            return ['success' => false, 'msg' => 'Thiếu iNET customer ID'];
        }
        $response = $this->inet_api->getCustomerById($inet_customer_id);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && !empty($response_data['id'])) {
            return ['success' => true, 'data' => $response_data];
        } else {
            $message = isset($response_data['message']) ? $response_data['message'] : 'Không tìm thấy khách hàng hoặc có lỗi xảy ra.';
            return ['success' => false, 'msg' => $message, 'data' => $response_data];
        }
    }

    public function getProvinceList()
    {
        return $this->inet_api->getProvinceList();
    }

    public function getWardListByParentId($parentId)
    {
        return $this->inet_api->getWardListByParentId($parentId);
    }

    public function get_email_record_verify($service_id)
    {
        $response = $this->inet_api->getRecordVerify($service_id);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['content']) ? $response_data['content'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy danh sách bản ghi', 'data' => $response_data];
        }
    }

    public function nslookup($domain, $type)
    {
        $response = $this->inet_api->nslookup($domain, $type);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['data']) ? $response_data['data'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy nslookup', 'data' => $response_data];
        }
    }

    public function get_email_detail($inet_email_id)
    {
        $response = $this->inet_api->getEmailDetail($inet_email_id);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['content']) ? $response_data['content'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy chi tiết email', 'data' => $response_data];
        }
    }

    public function privacyProtection($inet_domain_id)
    {
        if (empty($inet_domain_id)) {
            return ['success' => false, 'msg' => 'Thiếu ID tên miền iNET.'];
        }
        $response = $this->inet_api->privacyProtection($inet_domain_id);
        $response_data = json_decode($response['data'], true);
        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['content']) ? $response_data['content'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy chi tiết email', 'data' => $response_data];
        }
    }

    public function unprivacyProtection($inet_domain_id)
    {
        if (empty($inet_domain_id)) {
            return ['success' => false, 'msg' => 'Thiếu ID tên miền iNET.'];
        }
        $response = $this->inet_api->unprivacyProtection($inet_domain_id);
        $response_data = json_decode($response['data'], true);
        if ($response['status'] == 200 && is_array($response_data)) {
            $plans = isset($response_data['content']) ? $response_data['content'] : $response_data;

            return ['success' => true, 'data' => $plans];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi lấy chi tiết email', 'data' => $response_data];
        }
    }

    public function gen_dkim($inet_email_id)
    {
        $response = $this->inet_api->genDkim($inet_email_id);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && is_array($response_data)) {
            return ['success' => true, 'msg' => 'Tạo DKIM thành công', 'data' => $response_data];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi tạo DKIM: ' . ($response_data['message'] ?? 'Unknown error'), 'data' => $response_data];
        }
    }

    public function change_email_plan($inet_email_id, $new_plan_id, $period) // Keep period for local logic, but not for API call
    {
        if (empty($inet_email_id) || empty($new_plan_id)) { // Period is not required for API call
            return ['success' => false, 'msg' => 'Thiếu thông tin cần thiết để đổi gói email.'];
        }

        $params = [
            'id' => $inet_email_id,
            'planId' => $new_plan_id,
        ];

        $response = $this->inet_api->changeEmailPlan($params);

        if ($response['success']  && isset($response['data'])) {
            return ['success' => true, 'msg' => 'Đổi gói thành công', 'data' => $response['data']];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi đổi gói: ' . ($response['msg'] ?? 'Unknown error'), 'data' => $response['data']];
        }
    }

    public function reset_email_password($inet_email_id)
    {
        $params = [
            'id' => $inet_email_id,
        ];

        $response = $this->inet_api->resetWebmailPassword($params);
        $response_data = json_decode($response['data'], true);
        
        if ($response['status'] == 200 && $response_data['message'] == 'success') {
            return ['success' => true, 'msg' => 'Tạo lại mật khẩu thành công', 'data' => ['newPassword' => $response_data['newPassword'] ?? '', 'values' => $response_data['values'] ?? []]];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi tạo lại mật khẩu: ' . ($response_data['message'] ?? 'Unknown error'), 'data' => $response_data];
        }
    }

    public function search_email($params = [])
    {
        $response = $this->inet_api->searchEmail($params);
        $response_data = json_decode($response['data'], true);

        if ($response['status'] == 200 && is_array($response_data)) {
            $emails = isset($response_data['content']) ? $response_data['content'] : $response_data;
            return ['success' => true, 'data' => $emails];
        } else {
            return ['success' => false, 'msg' => 'Lỗi khi tìm kiếm email.', 'data' => $response_data];
        }
    }
}
