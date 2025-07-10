<?php
defined('ABSPATH') || exit;
class AjaxClientDomain
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_get-client-domain',  array($this, 'func_get_list'));
        add_action('wp_ajax_ajax_search-domain-client-cart',  array($this, 'func_search'));
        add_action('wp_ajax_ajax_search-per-domain-client-cart',  array($this, 'func_search_per'));
        add_action('wp_ajax_ajax_info-domain',  array($this, 'func_info_domain'));


        add_action('wp_ajax_ajax_search-domain-frontend-client-cart',  array($this, 'func_domain_check_search'));
        add_action('wp_ajax_ajax_search-per-domain-frontend-client-cart',  array($this, 'func_domain_check_search_per'));
        add_action('wp_ajax_ajax_info-domain-frontend',  array($this, 'func_info_domain_frontend'));

        add_action('wp_ajax_ajax_load-dns-domain-client-cart',  array($this, 'func_load_dns_domain_client_cart'));
        add_action('wp_ajax_ajax_update-dns-domain-client-cart',  array($this, 'func_update_dns_domain_client_cart'));
        add_action('wp_ajax_ajax_update-dns-domain-default-client-cart',  array($this, 'func_update_dns_domain_default_client_cart'));


        add_action('wp_ajax_ajax_load-record-domain-client-cart',  array($this, 'func_load_record_domain_client_cart'));
        add_action('wp_ajax_ajax_update-record-domain-client-cart',  array($this, 'func_update_record_domain_client_cart'));
        add_action('wp_ajax_ajax_update-per-record-domain-client-cart',  array($this, 'func_update_per_record_domain_client_cart'));
        add_action('wp_ajax_ajax_update-record-domain-default-client-cart',  array($this, 'func_update_record_domain_default_client_cart'));

        add_action('wp_ajax_nopriv_ajax_search-domain-frontend-client-cart',  array($this, 'func_domain_check_search'));
        add_action('wp_ajax_nopriv_ajax_search-per-domain-frontend-client-cart',  array($this, 'func_domain_check_search_per'));
        add_action('wp_ajax_nopriv_ajax_info-domain-frontend',  array($this, 'func_info_domain_frontend'));
    }

    public function func_get_list()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-nonce', 'security');

        $columns = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip'];
        $fieldSearch = ['url', 'price', 'buy_date', 'expiry_date', 'url_dns', 'ip'];

        $userC = wp_get_current_user();
        $arr = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key'     => 'user-id',
                    'value'   => $userC->ID,
                    'compare' => '=',
                )
            )
        );
        $id_customers = get_posts($arr);
        $arr = array(
            'post_type' => 'customer-domain',
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
        if (!$CDWFunc->isAdministrator($userC->ID))
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
            $buy_date = $CDWFunc->date->convertDateTimeDisplay($item["buy_date"]);
            $expiry_date = $CDWFunc->date->convertDateTimeDisplay($item["expiry_date"]);
            $item["url"] .= '<br><small class="text-primary">Đăng ký ngày ' . $buy_date . '<br>Hết hạn ngày ' . $expiry_date . '</small>';
            $status = 'Chưa xác định';
            $date = get_post_meta($post->ID, 'expiry_date', true);
            $date_now = $CDWFunc->date->create_datetime_now();
            $date_domain =  $CDWFunc->date->create_datetime_from_string($date);
            if ($date_domain >= $date_now) {
                $status = '<span class="text-primary">Đang hoạt động</span>';
            }
            if ($date_domain < $date_now) {
                $status = '<span class="text-danger">Hết hạn</span>';
            }
            if ($date_domain >= $date_now && $date_domain <=  $CDWFunc->date->create_datetime_from_string($CDWFunc->date->addMonths($date_now, 1, $CDWFunc->date->formatDB))) {
                $status = '<span class="text-warning">Sắp hết hạn</span>';
            }
            $item['urlDomain'] =  trim($item["url_dns"]);

            $item['status'] = $status;
            $item['action'] = '';
            $item['urlUpdateDNS'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-dns&id=' . $post->ID);
            $item['urlUpdateRecord'] = $CDWFunc->getUrl('domain', 'client', 'subaction=update-record&id=' . $post->ID);
            $data[] = $item;
        }

        wp_send_json_success($data);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_search()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-choose-nonce', 'security');

        $search = isset($_POST['search']) ? $_POST['search'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : "";
        $result = [];
        $item_default = [
            "domain" => "",
            "status" => "Chưa Đăng Ký",
            "available" => "available",
            "creationDate" => "",
            "expirationDate" => "",
            "price" => "Liên hệ",
            "renewal_price" => "Liên hệ",
            "id" => -1,
            "template" => "table-row-result-available-template",
        ];
        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $domain_remove = $CDWFunc->get_domain_paths($search);
        $textdomain = $domain_remove->domain;

        if (!empty($domain_remove->suffix)) {
            $item = (object) array_merge([], $item_default);
            $result[$domain_remove->suffix] =  $item;
        }

        $suffix_type = $CDWFunc->get_domain_paths(get_the_title($type));
        if (!empty($suffix_type->suffix) && $domain_remove->suffix != $suffix_type->suffix) {

            $item = (object) array_merge([], $item_default);
            $result[$suffix_type->suffix] =  $item;
        }

        $args = array(
            'post_type' => 'domain',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
        );
        $ids = get_posts($args);

        foreach ($ids as $id) {

            $domain = $CDWFunc->get_domain_paths(get_the_title($id));

            $item = (object) array_merge([], $item_default);

            $item->domain =  $textdomain . "." . $domain->suffix;

            $item->id = $id;
            $item->price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia', true));
            $item->renewal_price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia_han', true));
            $item->template = "table-row-result-" . $item->available . "-template";
            $result[$domain->suffix] =  $item;
        }
        foreach ($result as $key => $item) {
            if ($item->id == -1) {
                $item->domain =  $textdomain . "." . $key;
                $result[$key] =  $item;
            }
        }
        wp_send_json_success(["items" => $result]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_search_per()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-choose-nonce', 'security');

        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";
        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $item = (object) [
            "domain" => "",
            "status" => "Chưa Đăng Ký",
            "available" => "available",
            "creationDate" => "",
            "expirationDate" => "",
            "price" => "Liên hệ",
            "renewal_price" => "Liên hệ",
            "id" => -1,
            "template" => "table-row-result-available-template",
        ];
        $item->domain = $domain;
        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $status_domain = $apiInet->checkDomainAvailability($item->domain, "");
        if ($status_domain->success) {

            if (isset($status_domain->data["code"])) {
                switch ($status_domain->data["code"]) {
                    case "1":
                        $item->status = $status_domain->data["message"];
                        $item->available = 'notfound';
                        break;
                }
            } else {
                if ($status_domain->data["status"] != "available") {
                    $whois = $apiInet->getDomainWhois($item->domain);
                    $item->available = $status_domain->data["status"];
                    if ($whois->success) {
                        switch ($whois->data["code"]) {
                            case "0":
                                $item->status = "Đã Đăng Ký";
                                $item->creationDate = $CDWFunc->date->convert_timestamp_to_date($whois->data["creationDate_L"]);
                                $item->expirationDate = $CDWFunc->date->convert_timestamp_to_date($whois->data["expirationDate_L"]);
                                break;
                            case "1":
                                $item->available = 'available';
                                $item->status = $whois->data["message"];
                                break;
                        }
                    } else {
                        $item->status = "Không thể tìm thấy";
                        $item->available = 'notfound';
                    }
                }
            }
        } else {
            $item->status = "Lỗi trong quá trình tra cứu";
        }
        if ($item->available != 'notavailable') {
            $exists_system = $this->func_check_domain_exists($item->domain);
            if ($exists_system->exists) {
                $item->available = 'exists';
                $item->status = 'Đã được đặt';
                $item->date = $CDWFunc->date->convertDateTimeDisplay($exists_system->date);
            }
        }

        $item->id = (float) $id;
        $item->template = "table-row-result-" . $item->available . "-template";
        if ($item->id != -1) {
            $item->price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia', true));
            $item->renewal_price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia_han', true));
        }
        wp_send_json_success(["item" => $item]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_info_domain()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-choose-nonce', 'security');

        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";

        $domain_remove = $CDWFunc->get_domain_paths($domain);
        $info = (object) [
            "available" => "notAvailable",
            "domainName" => "",
            "suffix" => "",
            "registrar" => "",
            "nameServer" => "",
            "status" => "",
            "creationDate" => "",
            "updatedDate" => "",
            "expirationDate" => "",
            "DNSSEC" => "",
            "rawtext" => "",
        ];
        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $whois = $apiInet->getDomainWhois($domain_remove->full);
        if ($whois->success) {
            switch ($whois->data["code"]) {
                case "0":
                    $info->available = $whois->data["available"];
                    $info->domainName = $whois->data["domainName"];
                    $info->suffix = $whois->data["suffix"];
                    $info->registrar = $whois->data["registrar"];
                    $info->nameServer = implode(',', $whois->data["nameServer"]);
                    $info->status = implode(',', $whois->data["status"]);
                    $info->creationDate = $whois->data["creationDate"];
                    $info->updatedDate = $whois->data["updatedDate"];
                    $info->expirationDate = $whois->data["expirationDate"];
                    $info->DNSSEC = $whois->data["DNSSEC"];
                    $info->registrantName = isset($whois->data["registrantName"]) ? $whois->data["registrantName"] : "";
                    $info->registrantStreet = isset($whois->data["registrantStreet"]) ? $whois->data["registrantStreet"] : "";
                    $info->registrantPhone = isset($whois->data["registrantPhone"]) ? $whois->data["registrantPhone"] : "";
                    $info->registrantEmail = isset($whois->data["registrantEmail"]) ? $whois->data["registrantEmail"] : "";

                    $info->rawtext = $whois->data["rawtext"];
                    break;
            }
        }

        if (empty($info->domainName)) {
            $exists_system = $this->func_check_domain_exists($domain_remove->full);
            if ($exists_system->exists) {
                $info->domainName = $domain_remove->full;
                $info->suffix = $domain_remove->suffix;
                $info->registrar = 'Cộng Đồng Web';
                $info->status = 'Đã được đặt';
                $info->date = $CDWFunc->date->convertDateTimeDisplay($exists_system->date);
                wp_send_json_success(["info" => $info, "template" => "info-domain-exists-template"]);
            }
        }
        wp_send_json_success(["info" => $info, "template" => "info-domain-template"]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_check_domain_exists($domain)
    {
        global $CDWFunc;
        $result = new stdClass();
        $result->exists = false;
        $arr = array(
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => -1
        );

        $ids = get_posts($arr);
        foreach ($ids as $id) {
            $url = get_post_meta($id, 'url', true);
            $url_data = $CDWFunc->get_domain_paths($url);
            $domain_data = $CDWFunc->get_domain_paths($domain);

            if (str_ends_with($url_data->full, $domain_data->full)) {

                $result->exists = true;
                $result->date = get_post_meta($id, 'buy_date', true);
            }
        }
        return $result;
    }


    public function func_domain_check_search()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $search = isset($_POST['search']) ? $_POST['search'] : "";
        $result = [];
        $item_default = [
            "domain" => "",
            "status" => "Chưa Đăng Ký",
            "available" => "available",
            "creationDate" => "",
            "expirationDate" => "",
            "price" => "Liên hệ",
            "renewal_price" => "Liên hệ",
            "id" => -1,
            "template" => "domain-available-template",
        ];

        $domain_remove = $CDWFunc->get_domain_paths($search);
        $textdomain = $domain_remove->domain;

        if (!empty($domain_remove->suffix)) {
            $item = (object) array_merge([], $item_default);
            $result[$domain_remove->suffix] =  $item;
        }

        $suffix_type = $CDWFunc->get_domain_paths(get_the_title($type));
        if (!empty($suffix_type->suffix) && $domain_remove->suffix != $suffix_type->suffix) {

            $item = (object) array_merge([], $item_default);
            $result[$suffix_type->suffix] =  $item;
        }

        $args = array(
            'post_type' => 'domain',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
        );
        $ids = get_posts($args);

        foreach ($ids as $id) {

            $domain = $CDWFunc->get_domain_paths(get_the_title($id));

            $item = (object) array_merge([], $item_default);

            $item->domain =  $textdomain . "." . $domain->suffix;

            $item->id = $id;
            $item->price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia', true));
            $item->renewal_price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia_han', true));
            $item->template = "domain-" . $item->available . "-template";
            $result[$domain->suffix] =  $item;
        }
        foreach ($result as $key => $item) {
            if ($item->id == -1) {
                $item->domain =  $textdomain . "." . $key;
                $result[$key] =  $item;
            }
        }
        wp_send_json_success(["items" => $result]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_domain_check_search_per()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";
        $id = isset($_POST['id']) ? $_POST['id'] : "";

        $item = (object) [
            "domain" => "",
            "status" => "Chưa Đăng Ký",
            "available" => "available",
            "creationDate" => "",
            "expirationDate" => "",
            "price" => "Liên hệ",
            "renewal_price" => "Liên hệ",
            "id" => -1,
            "template" => "domain-available-template",
        ];
        $item->domain = $domain;
        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $status_domain = $apiInet->checkDomainAvailability($item->domain, "");
        // var_dump($status_domain);
        if ($status_domain->success) {

            if (isset($status_domain->data["code"])) {
                switch ($status_domain->data["code"]) {
                    case "1":
                        $item->status = $status_domain->data["message"];
                        $item->available = 'notfound';
                        break;
                }
            } else {
                if ($status_domain->data["status"] != "available") {
                    $whois = $apiInet->getDomainWhois($item->domain);
                    $item->available = $status_domain->data["status"];
                    if ($whois->success) {
                        switch ($whois->data["code"]) {
                            case "0":
                                $item->status = "Đã Đăng Ký";
                                $item->creationDate = $CDWFunc->date->convert_timestamp_to_date($whois->data["creationDate_L"]);
                                $item->expirationDate = $CDWFunc->date->convert_timestamp_to_date($whois->data["expirationDate_L"]);
                                break;
                            case "1":
                                $item->available = 'available';
                                $item->status = $whois->data["message"];
                                break;
                        }
                    } else {
                        $item->status = "Không thể tìm thấy";
                        $item->available = 'notfound';
                    }
                }
            }
        } else {
            $item->status = "Lỗi trong quá trình tra cứu";
        }
        if ($item->available != 'notavailable') {
            $exists_system = $this->func_check_domain_exists($item->domain);
            if ($exists_system->exists) {
                $item->available = 'exists';
                $item->status = 'Đã được đặt';
                $item->date = $CDWFunc->date->convertDateTimeDisplay($exists_system->date);
            }
        }

        $item->id = (float) $id;
        $item->template = "domain-" . $item->available . "-template";
        if ($item->id != -1) {
            $item->price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia', true));
            $item->renewal_price = $CDWFunc->number->amountDisplay(get_post_meta($id, 'gia_han', true));
        }
        wp_send_json_success(["item" => $item]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_info_domain_frontend()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-index-frontend-nonce', 'security');

        $domain = isset($_POST['domain']) ? $_POST['domain'] : "";

        $domain_remove = $CDWFunc->get_domain_paths($domain);
        $info = (object) [
            "available" => "notAvailable",
            "domainName" => "",
            "suffix" => "",
            "registrar" => "",
            "nameServer" => "",
            "status" => "",
            "creationDate" => "",
            "updatedDate" => "",
            "expirationDate" => "",
            "DNSSEC" => "",
            "rawtext" => "",
        ];
        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $whois = $apiInet->getDomainWhois($domain_remove->full);
        if ($whois->success) {
            switch ($whois->data["code"]) {
                case "0":
                    $info->available = $whois->data["available"];
                    $info->domainName = $whois->data["domainName"];
                    $info->suffix = $whois->data["suffix"];
                    $info->registrar = $whois->data["registrar"];
                    $info->nameServer = implode(',', $whois->data["nameServer"]);
                    $info->status = implode(',', $whois->data["status"]);
                    $info->creationDate = $whois->data["creationDate"];
                    $info->updatedDate = $whois->data["updatedDate"];
                    $info->expirationDate = $whois->data["expirationDate"];
                    $info->DNSSEC = $whois->data["DNSSEC"];
                    $info->registrantName = isset($whois->data["registrantName"]) ? $whois->data["registrantName"] : "";
                    $info->registrantStreet = isset($whois->data["registrantStreet"]) ? $whois->data["registrantStreet"] : "";
                    $info->registrantPhone = isset($whois->data["registrantPhone"]) ? $whois->data["registrantPhone"] : "";
                    $info->registrantEmail = isset($whois->data["registrantEmail"]) ? $whois->data["registrantEmail"] : "";
                    $info->rawtext = $whois->data["rawtext"];
                    break;
            }
        }

        if (empty($info->domainName)) {
            $exists_system = $this->func_check_domain_exists($domain_remove->full);
            if ($exists_system->exists) {
                $info->domainName = $domain_remove->full;
                $info->suffix = $domain_remove->suffix;
                $info->registrar = 'Cộng Đồng Web';
                $info->status = 'Đã được đặt';
                $info->date = $CDWFunc->date->convertDateTimeDisplay($exists_system->date);
                wp_send_json_success(["info" => $info, "template" => "info-domain-exists-template"]);
            }
        }
        wp_send_json_success(["info" => $info, "template" => "info-domain-template"]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_load_dns_domain_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-dns-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        if (empty($domain))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $data = $apiInet->searchDomain($domain);
        if (!$data['success'] || count($data['data']['content']) == 0)
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $id = $data['data']['content'][0]['id'];

        $domain = $apiInet->getDomainDetail($id);

        if (!$domain['success'])
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $result = [];
        foreach ($domain['data']['nsList'] as $key => $ns) {
            $item = new stdClass();
            $item->index = $key + 1;
            $item->hostname = $ns['hostname'];
            $result[] = $item;
        }
        $template = [
            'item-list' => 'domain-item-list-update-dns-template'
        ];
        wp_send_json_success(["items" => $result, 'template' => $template, 'id' => $id]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_update_dns_domain_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-dns-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if (empty($id))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $dns1 = isset($_POST['dns1']) ? $_POST['dns1'] : '';
        $dns2 = isset($_POST['dns2']) ? $_POST['dns2'] : '';
        $dns3 = isset($_POST['dns3']) ? $_POST['dns3'] : '';
        $dns4 = isset($_POST['dns4']) ? $_POST['dns4'] : '';
        $dns5 = isset($_POST['dns5']) ? $_POST['dns5'] : '';
        $dns6 = isset($_POST['dns6']) ? $_POST['dns6'] : '';
        $dns7 = isset($_POST['dns7']) ? $_POST['dns7'] : '';
        $dns8 = isset($_POST['dns8']) ? $_POST['dns8'] : '';

        $nsList = [];
        if (!empty($dns1)) {
            $item = new stdClass();
            $item->hostname = trim($dns1);
            $nsList[] = $item;
        }
        if (!empty($dns2)) {
            $item = new stdClass();
            $item->hostname = trim($dns2);
            $nsList[] = $item;
        }
        if (!empty($dns3)) {
            $item = new stdClass();
            $item->hostname = trim($dns3);
            $nsList[] = $item;
        }
        if (!empty($dns4)) {
            $item = new stdClass();
            $item->hostname = trim($dns4);
            $nsList[] = $item;
        }
        if (!empty($dns5)) {
            $item = new stdClass();
            $item->hostname = trim($dns5);
            $nsList[] = $item;
        }
        if (!empty($dns6)) {
            $item = new stdClass();
            $item->hostname = trim($dns6);
            $nsList[] = $item;
        }
        if (!empty($dns7)) {
            $item = new stdClass();
            $item->hostname = trim($dns7);
            $nsList[] = $item;
        }
        if (!empty($dns8)) {
            $item = new stdClass();
            $item->hostname = trim($dns8);
            $nsList[] = $item;
        }
        $domain = $apiInet->updateDNS($id, $nsList);
        if (!$domain['success'])
            wp_send_json_error(['msg' => $domain['data']]);

        wp_send_json_success(['msg' => 'Cập nhật thành công']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_update_dns_domain_default_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-dns-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if (empty($id))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $nsList = [];
        $item = new stdClass();
        $item->hostname = 'ns1.inet.vn';
        $nsList[] = $item;

        $item = new stdClass();
        $item->hostname = 'ns2.inet.vn';
        $nsList[] = $item;
        $domain = $apiInet->updateDNS($id, $nsList);
        if (!$domain['success'])
            wp_send_json_error(['msg' => $domain['data']]);

        wp_send_json_success(['msg' => 'Cập nhật thành công']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_load_record_domain_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-record-nonce', 'security');

        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $domain  = get_post_meta($id, 'url', true);
        if (empty($id) || empty($domain))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $data = $apiInet->searchDomain($domain);
        if (!$data['success'] || count($data['data']['content']) == 0)
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $id = $data['data']['content'][0]['id'];

        $domain = $apiInet->getRecord($id);

        if (!$domain['success'])
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $result = [];
        foreach ($domain['data']['recordList'] as $key => $ns) {
            $item = new stdClass();
            $item->index = $key + 1;
            $item->id = $ns["id"];
            $item->domainId = $ns["domainId"];
            $item->name = $ns["name"];
            $item->ttl = $ns["ttl"];
            $item->dClass = $ns["dClass"];
            $item->type = $ns["type"];
            $item->value = $ns["data"];
            $item->priority = $ns["priority"];
            $result[] = $item;
        }
        $template = [
            'item-list' => 'domain-item-list-update-record-template'
        ];
        wp_send_json_success(["items" => $result, 'template' => $template, 'id' => $id]);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_update_record_domain_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-record-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if (empty($id))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $domain = $apiInet->getRecord($id);

        if (!$domain['success'])
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $data = isset($_POST['data']) ? $_POST['data'] : array();

        $recordList = [];
        foreach ($domain['data']['recordList'] as $ns) {
            $item = new stdClass();
            $item->id = $ns["id"];
            $item->name = $ns["name"];
            $item->ttl = $ns["ttl"];
            $item->type = $ns["type"];
            $item->data = $ns["data"];
            $item->action = "del";
            $recordList[] = $item;
        }
        foreach ($data as $record) {
            if (!empty($record["name"])) {
                $item = new stdClass();
                $item->name = $record["name"];
                $item->ttl = $record["ttl"];
                $item->type = $record["type"];
                $item->data = $record["value"];
                $item->action = "add";
                $recordList[] = $item;
            }
        }

        if (empty($recordList))
            wp_send_json_error(['msg' => 'Vui lòng điền đầy đủ thông tin.']);
        $domain = $apiInet->updaterecord($id, $recordList);
        if (!$domain['success'])
            wp_send_json_error(['msg' => $domain['data']]);

        wp_send_json_success(['msg' => 'Cập nhật thành công']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
    public function func_update_per_record_domain_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-record-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        if (empty($id))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $record = isset($_POST['record']) ? $_POST['record'] : '';

        if (empty($record))
            wp_send_json_error(['msg' => 'Dữ liệu không đúng định dạng, vui lòng cấu hình thủ công.']);

        $recordList = [];

        if (!empty($record["name"])) {
            $item = new stdClass();
            $item->id = $record["id"];
            $item->name = $record["name"];
            $item->ttl = $record["ttl"];
            $item->type = $record["type"];
            $item->data = $record["value"];
            $item->action = "del";
            $recordList[] = $item;

            $item = new stdClass();
            $item->id = $record["id"];
            $item->name = $record["name"];
            $item->ttl = $record["ttl"];
            $item->type = $record["type"];
            $item->data = $record["value"];
            $item->action = "add";
            $recordList[] = $item;
        } else
            wp_send_json_error(['msg' => 'Vui lòng điền đầy đủ thông tin.']);

        $domain = $apiInet->updaterecord($id, $recordList);
        if (!$domain['success'])
            wp_send_json_error(['msg' => $domain['data']]);

        wp_send_json_success(['msg' => 'Cập nhật thành công']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }

    public function func_update_record_domain_default_client_cart()
    {
        global $CDWFunc;
        // First check the nonce, if it fails the function will break
        check_ajax_referer('ajax-client-domain-update-record-nonce', 'security');


        require_once(ADMIN_THEME_URL . '/core/function-api-inet.php');
        $apiInet = new APIInetConnectionManager(APIINETURL);
        $apiInet->setToken(APIINETTOKEN);

        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $cd_id = isset($_POST['cd_id']) ? $_POST['cd_id'] : '';
        if (empty($id))
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $domain = $apiInet->getRecord($id);

        if (!$domain['success'])
            wp_send_json_error(['msg' => 'Domain không được tìm thấy, vui lòng cấu hình thủ công.']);

        $recordList = [];
        foreach ($domain['data']['recordList'] as $ns) {
            $item = new stdClass();
            $item->id = $ns["id"];
            $item->name = $ns["name"];
            $item->ttl = $ns["ttl"];
            $item->type = $ns["type"];
            $item->data = $ns["data"];
            $item->action = "del";
            $recordList[] = $item;
        }
        $ip = get_post_meta($cd_id, 'ip', true);
        if (empty($ip)) $ip = SERVER_IP;
        $item = new stdClass();
        $item->name = 'WWW';
        $item->ttl = '300';
        $item->type = 'A';
        $item->data = $ip;
        $item->action = "add";
        $recordList[] = $item;


        $item = new stdClass();
        $item->name = '@';
        $item->ttl = '300';
        $item->type = 'A';
        $item->data = $ip;
        $item->action = "add";
        $recordList[] = $item;

        $domain = $apiInet->updaterecord($id, $recordList);
        if (!$domain['success'])
            wp_send_json_error(['msg' => $domain['data']]);

        wp_send_json_success(['msg' => 'Cập nhật thành công']);

        wp_send_json_error(['msg' => 'Lỗi']);
        wp_die();
    }
}
new AjaxClientDomain();
