<?php
defined('ABSPATH') || exit;
class AjaxClientEmail
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-client-email',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_client_get_email_records_inet', array($this, 'func_get_email_records_inet'));
        add_action('wp_ajax_ajax_client_get_email_detail_inet', array($this, 'func_get_email_detail_inet'));
        add_action('wp_ajax_ajax_client_check_email_domain_available_inet', array($this, 'func_check_email_domain_available_inet'));
        add_action('wp_ajax_ajax_client_create_email_package_inet', array($this, 'func_create_email_package_inet'));
        add_action('wp_ajax_ajax_client_reset_email_password_inet', array($this, 'func_reset_email_password_inet'));

    }

    public function func_reset_email_password_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-email-nonce', 'security');

        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }

        $response = $CDWFunc->inet->reset_email_password($inet_email_id);

        if ($response['success']) {
            wp_send_json_success(['msg' => $response['msg'], 'newPassword' => $response['data']['newPassword']]);
        } else {
            wp_send_json_error($response);
        }
        wp_die();
    }

    public function func_check_email_domain_available_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-email-nonce', 'security');
        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($customer_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID dịch vụ email.']);
        }

        $email_type_id = get_post_meta($customer_email_id, 'email-type', true);
        if (empty($email_type_id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy gói email được liên kết.']);
        }

        $plan_id = get_post_meta($email_type_id, 'inet_plan_id', true);
        if ($plan_id === '') {
            wp_send_json_error(['msg' => 'Không tìm thấy iNET Plan ID cho gói email này.']);
        }

        $response = $CDWFunc->inet->check_email_domain($domain, $plan_id);
        if ($response['success']) {
            if (isset($response['data']['status']) && $response['data']['status'] == 'available') {
                wp_send_json_success($response['data']);
            } else {
                $message = isset($response['data']['message']) ? $response['data']['message'] : 'Tên miền không hợp lệ hoặc đã được sử dụng';
                wp_send_json_error(['msg' => $message]);
            }
        } else {
            wp_send_json_error(['msg' => isset($response['msg']) ? $response['msg'] : 'Lỗi khi tìm kiếm tên miền hoặc không có kết quả.']);
        }
    }

    public function func_create_email_package_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-email-nonce', 'security');
        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($customer_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu ID dịch vụ email.']);
        }

        $customer_id = get_post_meta($customer_email_id, 'customer-id', true);
        if (empty($customer_id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy thông tin khách hàng được liên kết.']);
        }

        $kyc_status = get_post_meta($customer_id, 'status-kyc', true);
        if ($kyc_status != '3') {
            wp_send_json_error(['msg' => 'Tài khoản của bạn chưa hoàn tất xác minh KYC. Vui lòng liên hệ quản trị viên.']);
        }

        $email_type_id = get_post_meta($customer_email_id, 'email-type', true);
        if (empty($email_type_id)) {
            wp_send_json_error(['msg' => 'Không tìm thấy gói email được liên kết.']);
        }

        $plan_id = get_post_meta($email_type_id, 'inet_plan_id', true);
        if ($plan_id === '') {
            wp_send_json_error(['msg' => 'Không tìm thấy iNET Plan ID cho gói email này.']);
        }

        $inet_customer_id = get_post_meta($customer_id, 'inet_customer_id', true);
        if (empty($inet_customer_id)) {
            wp_send_json_error(['msg' => 'Tài khoản của bạn chưa được đồng bộ với nhà cung cấp. Vui lòng liên hệ quản trị viên.']);
        }

        $buy_date = get_post_meta($customer_email_id, 'buy_date', true);
        $expiry_date = get_post_meta($customer_email_id, 'expiry_date', true);
        $period = 1; // Default to 1 month

        if ($buy_date && $expiry_date) {
            $calculated_period = $CDWFunc->date->diffInMonths($buy_date, $expiry_date);
            if ($calculated_period >= 1) {
                $period = $calculated_period;
            }
        }
        error_log('[func_update] customer-email: ' . print_r(['domain' => $domain, 'inet_customer_id' => $inet_customer_id, 'plan_id' => $plan_id, 'period' => $period], true));
        $response = $CDWFunc->inet->create_email($domain, $inet_customer_id, $plan_id, $period);

        if ($response['success'] && isset($response['data']['id'])) {
            $inet_email_id = $response['data']['id'];
            update_post_meta($customer_email_id, 'inet_email_id', $inet_email_id);

            $detail_response = $CDWFunc->inet->get_email_detail($inet_email_id);
            if ($detail_response['success'] && isset($detail_response['data'])) {
                $this->_save_inet_email_details($customer_email_id, $detail_response['data']);
            }

            wp_send_json_success(['id' => $inet_email_id]);
        } else {
            wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không xác định từ nhà cung cấp khi tạo gói email']);
        }
    }

    private function _save_inet_email_details($customer_email_id, $details)
    {
        if (empty($customer_email_id) || empty($details)) {
            return;
        }

        $domain_name = $details['domainName'] ?? '';
        $config = $details['emailConfig'] ?? [];
        $sub_domain = $config['subDomain'] ?? 'mail';

        $client_url = "https://{$sub_domain}.{$domain_name}";
        $admin_url = "{$client_url}/admin";
        $admin_email = "admin@{$domain_name}";

        update_post_meta($customer_email_id, 'inet_status', $details['status'] ?? '');
        update_post_meta($customer_email_id, 'issue_date', $details['issueDate'] ?? '');
        update_post_meta($customer_email_id, 'expiry_date', $details['expireDate'] ?? '');
        update_post_meta($customer_email_id, 'domain', $domain_name);
        update_post_meta($customer_email_id, 'url_admin', $admin_url);
        update_post_meta($customer_email_id, 'url_client', $client_url);
        update_post_meta($customer_email_id, 'user', $admin_email);
        update_post_meta($customer_email_id, 'quota_limit', $config['quotaLimit'] ?? '');
        update_post_meta($customer_email_id, 'account_limit', $config['accountLimit'] ?? '');
        update_post_meta($customer_email_id, 'group_limit', $config['distributionListLimit'] ?? '');
        update_post_meta($customer_email_id, 'quota_used', $config['totalQuotaUsed'] ?? 0);
        update_post_meta($customer_email_id, 'account_used', $config['accountCurent'] ?? 0);
        update_post_meta($customer_email_id, 'group_used', $config['distributionListCurent'] ?? 0);
    }

    public function func_get_email_records_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-email-nonce', 'security');
        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }
        error_log('[iNET Get Email Records] Fetching records for inet_email_id: ' . $inet_email_id);
        $response = $CDWFunc->inet->get_email_detail($inet_email_id);
        error_log('[iNET Get Email Records] Response: '. json_encode($response));

        if (!$response['success'] || empty($response['data'])) {
            wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không lấy được bản ghi DNS từ iNET']);
        }

        $details = $response['data'];
        $domain_name = $details['domainName'];
        $config = $details['emailConfig'];

        $required_records = [];

        if (isset($config['recordA'])) {
            $required_records[] = [
                'type' => 'a',
                'name' => ($config['subDomain'] ?? 'mail') . '.' . $domain_name,
                'value' => $config['recordA']
            ];
        }
        if (isset($config['recordMxReseller'])) {
            $mx_records = explode(',', $config['recordMxReseller']);
            foreach ($mx_records as $mx) {
                $mx_parts = explode(':', $mx);
                $required_records[] = [
                    'type' => 'mx',
                    'name' => $domain_name,
                    'value' => $mx_parts[0],
                    'priority' => $mx_parts[1] ?? 0
                ];
            }
        }
        if (isset($config['recordSPFReseller'])) {
            $required_records[] = [
                'type' => 'txt',
                'name' => $domain_name,
                'value' => $config['recordSPFReseller']
            ];
        }

        // Add saved DKIM record if available
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';
        if (!empty($customer_email_id)) {
            $dkim_record_name = get_post_meta($customer_email_id, 'dkim_record_name', true);
            $dkim_record_value = get_post_meta($customer_email_id, 'dkim_record_value', true);
            $dkim_record_type = get_post_meta($customer_email_id, 'dkim_record_type', true);


            if (!empty($dkim_record_name) && !empty($dkim_record_value) && !empty($dkim_record_type)) {
                $dkim_record = [
                    'type' => strtolower($dkim_record_type),
                    'name' => $dkim_record_name,
                    'value' => $dkim_record_value,
                ];
                error_log('[iNET DKIM Record] dkim_record: '. json_encode($dkim_record));
                $required_records[] = $dkim_record;
            }
        }

        $verified_records = [];
        $all_verified = true;

        foreach ($required_records as $record) {
            $record['verified'] = false;

            $record_name = $record['name'];
            $record_type = $record['type'];
            error_log('[iNET NSLookup] Looking up '. $record_type. ' for '. $record_name);
            $nslookup_response = $CDWFunc->inet->nslookup($record_name, $record_type);
            error_log('[iNET NSLookup] Response: '. json_encode($nslookup_response));

            if ($nslookup_response['success'] && isset($nslookup_response['data'])) {
                $expected_value = $record['value'];
                $lookup_results = $nslookup_response['data'];

                if (!is_array($lookup_results)) {
                    $lookup_results = [['value' => $lookup_results]];
                }

                foreach ($lookup_results as $dns_record) {
                    $actual_value = '';

                    if (is_string($dns_record)) { // A record as string in an array
                        $actual_value = $dns_record;
                    } else if (is_array($dns_record)) {
                        if (isset($dns_record['value'])) {
                            $actual_value = $dns_record['value'];
                        } elseif (isset($dns_record['target'])) {
                            $actual_value = $dns_record['target'];
                        } elseif (isset($dns_record['txt'])) {
                            $actual_value = $dns_record['txt'];
                        } elseif (isset($dns_record['ip'])) {
                            $actual_value = $dns_record['ip'];
                        } elseif (isset($dns_record['exchange'])) {
                            $actual_value = $dns_record['exchange'];
                        } elseif ($record['type'] === 'txt' && isset($dns_record[0])) { // TXT record in nested array
                            $actual_value = $dns_record[0];
                        }
                    }

                    if (empty($actual_value)) {
                        error_log('[iNET NSLookup] Could not extract actual value from dns_record: '. json_encode($dns_record));
                        continue;
                    }

                    $expected_value_trimmed = rtrim($expected_value, '.');
                    $actual_value_trimmed = rtrim($actual_value, '.');
                    
                    if ($actual_value_trimmed == $expected_value_trimmed || strpos($expected_value_trimmed, $actual_value) !== false) {
                        $record['verified'] = true;
                        break;
                    }
                }
            } else {
                error_log('[iNET NSLookup] Failed or empty response for '. $record_name. ': '. json_encode($nslookup_response));
            }

            if (!$record['verified']) {
                $all_verified = false;
            }
            $verified_records[] = $record;
        }

        if ($all_verified) {
            update_post_meta($customer_email_id, '_inet_records_verified', true);
        }

        wp_send_json_success(['records' => $verified_records, 'all_verified' => $all_verified, 'is_verified' => get_post_meta($customer_email_id, '_inet_records_verified', true)]);
    }

    public function func_get_email_detail_inet()
    {
        global $CDWFunc;
        check_ajax_referer('ajax-client-email-nonce', 'security');
        $inet_email_id = isset($_POST['inet_email_id']) ? $_POST['inet_email_id'] : '';
        $customer_email_id = isset($_POST['customer_email_id']) ? $_POST['customer_email_id'] : '';

        if (empty($inet_email_id)) {
            wp_send_json_error(['msg' => 'Thiếu iNET Email ID.']);
        }

        $details = [];
        $config = [];
        $loaded_from_meta = false;

        if (!empty($customer_email_id)) {
            $details_from_meta = [
                'status' => get_post_meta($customer_email_id, 'inet_status', true),
                'issueDate' => get_post_meta($customer_email_id, 'issue_date', true),
                'expireDate' => get_post_meta($customer_email_id, 'expiry_date', true),
                'domainName' => get_post_meta($customer_email_id, 'domain', true),
                'admin_url' => get_post_meta($customer_email_id, 'url_admin', true),
                'client_url' => get_post_meta($customer_email_id, 'url_client', true),
                'admin_email' => get_post_meta($customer_email_id, 'user', true),
                'planName' => get_the_title(get_post_meta($customer_email_id, 'email-type', true)),
            ];

            $config_from_meta = [
                'quotaLimit' => get_post_meta($customer_email_id, 'quota_limit', true),
                'accountLimit' => get_post_meta($customer_email_id, 'account_limit', true),
                'distributionListLimit' => get_post_meta($customer_email_id, 'group_limit', true),
                'totalQuotaUsed' => get_post_meta($customer_email_id, 'quota_used', true),
                'accountCurent' => get_post_meta($customer_email_id, 'account_used', true),
                'distributionListCurent' => get_post_meta($customer_email_id, 'group_used', true),
                'subDomain' => explode('.', $details_from_meta['admin_url'] ?? '')[0] ?? 'mail',
            ];

            // Check if essential meta data is present
            if (!empty($details_from_meta['domainName']) && !empty($details_from_meta['status'])) {
                $details = $details_from_meta;
                $details['emailConfig'] = $config_from_meta;
                $config = $config_from_meta;
                $loaded_from_meta = true;
            }
        }

        if (!$loaded_from_meta) {
            $response = $CDWFunc->inet->get_email_detail($inet_email_id);
            error_log('[iNET Get Email Detail] Response for id ' . $inet_email_id . ': ' . json_encode($response));

            if (!$response['success'] || empty($response['data'])) {
                wp_send_json_error(['msg' => $response['msg'] ?? 'Lỗi không lấy được chi tiết gói email']);
            }

            $details = $response['data'];
            if (!empty($customer_email_id)) {
                $this->_save_inet_email_details($customer_email_id, $details);
            }
            $config = $details['emailConfig'] ?? [];
        }

        $domain_name = $details['domainName'] ?? '';
        $sub_domain = $config['subDomain'] ?? 'mail';

        $quota_used_gb = round(($config['totalQuotaUsed'] ?? 0) / 1024, 2);
        $quota_limit_gb = round(($config['quotaLimit'] ?? 0) / 1024, 2);
        $email_type = get_post_meta($customer_email_id, 'email-type', true);
        $clean_response = [
            'quota' => "{$quota_used_gb} GB / {$quota_limit_gb} GB",
            'accounts' => ($config['accountCurent'] ?? 0) . " / " . ($config['accountLimit'] ?? 0),
            'groups' => ($config['distributionListCurent'] ?? 0) . " / " . ($config['distributionListLimit'] ?? 0),
            'status' => $details['status'] ?? '',
            'expiry_date' => date('d/m/Y', strtotime($details['expireDate'] ?? '')),
            'admin_url' => $details['admin_url'] ?? "https://{$sub_domain}.{$domain_name}/admin",
            'admin_email' => $details['admin_email'] ?? "admin@{$domain_name}",
            'client_url' => $details['client_url'] ?? "https://{$sub_domain}.{$domain_name}",
            'plan' => get_the_title($email_type) ?? ($details['planName'] ?? ''),
            'domain' => $domain_name,
            'created_date' => date('d/m/Y', strtotime($details['issueDate'] ?? '')),
            'is_verified' => get_post_meta($customer_email_id, '_inet_records_verified', true)
        ];

        wp_send_json_success($clean_response);
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-email-nonce', 'security');

        $columns = ['url_admin', 'url_client', 'user', 'pass',  'email-type', 'price', 'buy_date', 'expiry_date', 'inet_email_id'];
        $fieldSearch = ['ip', 'port'];

        $userC = wp_get_current_user();
        $customer_id = get_user_meta($userC->ID, 'customer-id', true);
        $id_customers = $customer_id ? [$customer_id] : [];
        $arr = array(
            'post_type' => 'customer-email',
            'post_status' => 'publish',
            'meta_key' => 'buy_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'posts_per_page' => -1
        );

        $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
        $until_date = isset($_POST['until_date']) ? $_POST['until_date'] : "";
        $from_expiry_date = isset($_POST['from_expiry_date']) ? $_POST['from_expiry_date'] : "";
        $until_expiry_date = isset($_POST['until_expiry_date']) ? $_POST['until_expiry_date'] : "";
        $domain_status = isset($_POST['domain_status']) ? $_POST['domain_status'] : "";
        $search = isset($_POST['search']) ? $_POST['search'] : "";

        if (count($id_customers) == 0) $id_customers = -1;
        // if (!$CDWFunc->isAdministrator($userC->ID))
        $arr['meta_query'][] =
            array(
                'key' => "customer-id",
                'value' => $id_customers,
                'compare' => 'in',
            );

        if ($search != '') {
            $arr['meta_query']['relation'] = 'OR';
            foreach ($fieldSearch as $field) {
                $arr['meta_query'][] =
                    array(
                        'key' => $field,
                        'value' => $search,
                        'compare' => 'like',
                    );
            }
        }

        if ($CDWFunc->date->isValidDateFormat($from_date)) {
            $from_date_d = $CDWFunc->date->convertDateTime($from_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $from_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_date)) {
            $until_date_d = $CDWFunc->date->convertDateTime($until_date);
            $arr['meta_query'][] = array(
                'key'     => 'buy_date',
                'value'   => $until_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($from_expiry_date)) {
            $from_expiry_date_d = $CDWFunc->date->convertDateTime($from_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $from_expiry_date_d,
                'compare' => '>=',
                'type'    => 'DATE'
            );
        }
        if ($CDWFunc->date->isValidDateFormat($until_expiry_date)) {
            $until_expiry_date_d = $CDWFunc->date->convertDateTime($until_expiry_date);
            $arr['meta_query'][] = array(
                'key'     => 'expiry_date',
                'value'   => $until_expiry_date_d,
                'compare' => '<=',
                'type'    => 'DATE'
            );
        }

        switch ($domain_status) {
            case "all":
                break;
            case "runing":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );
                break;
                break;
            case "expired":
                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '<',
                    'type'    => 'DATE'
                );
                break;
            case "closetoexpiration":

                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->getCurrentDateTime(),
                    'compare' => '>=',
                    'type'    => 'DATE'
                );

                $arr['meta_query'][] = array(
                    'key'     => 'expiry_date',
                    'value'   => $CDWFunc->date->addMonths($CDWFunc->date->getCurrentDateTime(), 1, $CDWFunc->date->formatDB),
                    'compare' => '<=',
                    'type'    => 'DATE'
                );
                break;
        }

        $posts = get_posts($arr);
        $data = [];
        foreach ($posts as $post) {
            $item = [];
            $item['id'] = $post->ID;
            foreach ($columns as $column) {
                $item[$column] = get_post_meta($post->ID, $column, true);
            }

            if (!empty($item["port"]))
                $item["ip"] .= ":" . $item["port"];
            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["email-type_label"] = get_the_title($item["email-type"]) . '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $item["account"] = get_post_meta($item["email-type"], "account", true);
            $item["hhd"] = get_post_meta($item["email-type"], "hhd", true);

            $status = 'Chưa xác định';
            $date = get_post_meta($post->ID, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_email =  $CDWFunc->date->create_datetime_from_string($date);
            if ($date_email >= $date_now) {
                $status = '<span class="badge badge-primary">Đang hoạt động</span>';
            }
            if ($date_email < $date_now) {
                $status = '<span class="badge badge-danger">Hết hạn</span>';
            }
            if ($date_email >= $date_now && $date_email <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="badge badge-warning">Sắp hết hạn</span>';
            }

            $item['status'] = $status;
            $customer = get_post_meta(get_post_meta($post->ID, 'customer-id', true), 'name', true);
            $item['customer'] = $customer;
            $item['urlredirect'] = $CDWFunc->getUrl('detail', 'customer', 'id=' . get_post_meta($post->ID, 'customer-id', true));
            $item['urlEmail'] = 'http://' . ($item["url_admin"] ?? $item["url_client"]);
            $item['action'] = '';
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxClientEmail();
