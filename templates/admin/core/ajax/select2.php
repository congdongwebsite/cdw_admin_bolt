<?php
defined('ABSPATH') || exit;
class Select2
{
    public function __construct()
    {
        add_action('wp_ajax_ajax_domain-status',  array($this, 'func_get_domain_status'));
        add_action('wp_ajax_ajax_domains',  array($this, 'func_get_domains'));
        add_action('wp_ajax_ajax_billing-status',  array($this, 'func_get_billing_status'));
        add_action('wp_ajax_ajax_dvhc-tp',  array($this, 'func_get_dvhc_tp'));
        add_action('wp_ajax_ajax_dvhc-qh',  array($this, 'func_get_dvhc_qh'));
        add_action('wp_ajax_ajax_dvhc-px',  array($this, 'func_get_dvhc_px'));
        add_action('wp_ajax_nopriv_ajax_dvhc-tp',  array($this, 'func_get_dvhc_tp'));
        add_action('wp_ajax_nopriv_ajax_dvhc-qh',  array($this, 'func_get_dvhc_qh'));
        add_action('wp_ajax_nopriv_ajax_dvhc-px',  array($this, 'func_get_dvhc_px'));
        add_action('wp_ajax_ajax_finance-types',  array($this, 'func_get_finance_types'));
        add_action('wp_ajax_ajax_site-types',  array($this, 'func_get_site_types'));
        add_action('wp_ajax_ajax_sites',  array($this, 'func_get_sites'));
        add_action('wp_ajax_ajax_ticket-type',  array($this, 'func_get_ticket_type'));
        add_action('wp_ajax_ajax_hosting',  array($this, 'func_get_hostings'));
        add_action('wp_ajax_ajax_hosting-feature',  array($this, 'func_get_hosting_feature'));
        add_action('wp_ajax_ajax_hosting-package',  array($this, 'func_get_hosting_package'));
        add_action('wp_ajax_ajax_customer',  array($this, 'func_get_customers'));
        add_action('wp_ajax_ajax_plugin-types',  array($this, 'func_get_plugin_types'));
        add_action('wp_ajax_ajax_module-versions',  array($this, 'func_get_module_versions'));
        add_action('wp_ajax_ajax_plugins',  array($this, 'func_get_plugins'));
        add_action('wp_ajax_ajax_emails',  array($this, 'func_get_emails'));
        add_action('wp_ajax_ajax_version-type',  array($this, 'func_get_version_type'));
        add_action('wp_ajax_ajax_dvhc-tp-inet',  array($this, 'func_get_dvhc_tp_inet'));
        add_action('wp_ajax_nopriv_ajax_dvhc-tp-inet',  array($this, 'func_get_dvhc_tp_inet'));
        add_action('wp_ajax_ajax_dvhc-ward-inet',  array($this, 'func_get_dvhc_ward_inet'));
        add_action('wp_ajax_nopriv_ajax_dvhc-ward-inet',  array($this, 'func_get_dvhc_ward_inet'));
        add_action('wp_ajax_ajax_inet_email_plans',  array($this, 'func_get_inet_email_plans'));
    }
    public function func_get_domain_status()
    {
        $search = $_GET['search'] ?? '';
        $data = [
            [
                "id" => "all",
                "text" => "Tất cả"
            ],
            [
                "id" => "runing",
                "text" => "Đang hoạt động"
            ],
            [
                "id" => "expired",
                "text" => "Hết hạn"
            ],
            [
                "id" => "closetoexpiration",
                "text" => "Sắp hết hạn"
            ],
        ];
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_domains()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'domain',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'stt',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_the_title($id);

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_dvhc_tp()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $data = $CDWFunc->getListTP();
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_get_dvhc_qh()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $parent = $_GET['parent'];
        $data = [];
        if (isset($parent) && !empty($parent)) {
            $data = $CDWFunc->getListQH($parent);
            $result = [];
            if (!empty($search)) {
                foreach ($data as $item) {
                    if (str_contains(strtolower($item["text"]), strtolower($search))) {
                        $result[] = $item;
                    }
                }
                wp_send_json_success($result);
            }
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_get_dvhc_px()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $parent = $_GET['parent'];
        $data = [];
        if (isset($parent) && !empty($parent)) {
            $data =  $CDWFunc->getListPX($parent);
            $result = [];
            if (!empty($search)) {
                foreach ($data as $item) {
                    if (str_contains(strtolower($item["text"]), strtolower($search))) {
                        $result[] = $item;
                    }
                }
                wp_send_json_success($result);
            }
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_finance_types()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'finance-type',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_post_meta($id, 'name', true);

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_billing_status()
    {
        $search = $_GET['search'] ?? '';
        $data = [
            [
                "id" => "publish",
                "text" => "Tiếp nhận"
            ],
            [
                "id" => "pending",
                "text" => "Đang xử lý"
            ],
            [
                "id" => "cancel",
                "text" => "Hủy"
            ],
            [
                "id" => "success",
                "text" => "Đã thanh toán"
            ]
        ];
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }
    public function func_get_site_types()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $taxonomy = 'site-types';

        $args = array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'number' => 0,
            'fields' => 'id=>name'
        );

        if (!empty($search)) {
            $args['search'] = $search;
        }
        $terms = get_terms($args);

        if (!empty($terms)) {
            foreach ($terms as $id => $term) {
                $item = [];
                $item['id'] = $id;
                $item['text'] = $term;

                $result[] = $item;
            }
        }

        wp_send_json_success($result);

        wp_die();
    }

    public function func_get_sites()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'site-managers',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_post_meta($id, 'name', true);

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_ticket_type()
    {
        global $CDWTicket;
        $result = [];
        foreach ($CDWTicket->getDefaultType() as $key => $value) {
            $item = [];
            $item['id'] = $key;
            $item['text'] = $value['text'];

            $result[] = $item;
        }
        wp_send_json_success($result);


        wp_die();
    }
    public function func_get_hostings()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'hosting',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['s'] = $search;
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;

            $cpu = get_post_meta($id, "cpu", true);
            $ram = get_post_meta($id, "ram", true);
            $hhd = get_post_meta($id, "hhd", true);
            $cpu = $cpu == -1 ? "Tùy chỉnh" : $cpu;
            $ram = $ram == -1 ? "Tùy chỉnh" : $ram;
            $hhd = $hhd == -1 ? "Tùy chỉnh" : $hhd;
            $item['text'] = get_the_title($id) . "/ CPU:" . $cpu . "/ RAM:" . $ram . "/ HHD:" . $hhd;

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_hosting_feature()
    {
        $search = $_GET['search'] ?? '';
        $data = [
            [
                "id" => "goi-pho-bien",
                "text" => "Gói phổ biến"
            ],
            [
                "id" => "goi-cao-cap",
                "text" => "Gói cao cấp"
            ]
        ];
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_hosting_package()
    {
        global $CDWFunc;

        $search = $_GET['search'] ?? '';
        $result = [];

        $response = $CDWFunc->directAdmin->listResellerPackages();
        write_syslog(__METHOD__ . " listResellerPackages " . print_r($response, true));

        if (empty($response) || empty($response['list'])) {
            wp_send_json_success($result);
        }

        $packages = array_map(function ($item) {
            return [
                'id'   => $item,
                'text' => $item
            ];
        }, $response['list']);

        if (! empty($search)) {
            foreach ($packages as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($packages);

        wp_die();
    }

    public function func_get_customers()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'customer',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;

            $name = get_post_meta($id, "name", true);
            $phone = get_post_meta($id, "phone", true);
            $item['text'] = $name . " - " . $phone;

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_plugin_types()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $taxonomy = 'plugin-type';

        $args = array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'number' => 0,
            'fields' => 'id=>name'
        );

        if (!empty($search)) {
            $args['search'] = $search;
        }
        $terms = get_terms($args);

        if (!empty($terms)) {
            foreach ($terms as $id => $term) {
                $item = [];
                $item['id'] = $id;
                $item['text'] = $term;

                $result[] = $item;
            }
        }

        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_module_versions()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type'      => 'version',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        );

        if (!empty($search)) {
            $args['s'] = $search;
        }

        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_the_title($id);
            $result[] = $item;
        }

        wp_send_json_success($result);

        wp_die();
    }

    public function func_get_plugins()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'plugin',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_post_meta($id, 'name', true);

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_emails()
    {
        $result = [];
        $search = $_GET['search'] ?? '';

        $args = array(
            'post_type' => 'email',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'stt',
            'fields' => 'ids',
            'order' => 'ASC',
        );
        if (!empty($search)) {
            $args['meta_query'] = array(
                'relation' => 'and',
                array(
                    'key' =>  'name',
                    'value' => $search,
                    'compare' => 'like',
                )
            );
        }
        $ids = get_posts($args);

        foreach ($ids as $id) {
            $item = [];
            $item['id'] = $id;
            $item['text'] = get_the_title($id);

            $result[] = $item;
        }
        wp_send_json_success($result);

        wp_die();
    }
    public function func_get_version_type()
    {
        $search = $_GET['search'] ?? '';
        $data = [
            [
                "id" => "theme",
                "text" => "Theme"
            ],
            [
                "id" => "plugin",
                "text" => "Plugin"
            ],
        ];
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_dvhc_tp_inet()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $response = $CDWFunc->inet->getProvinceList();
        $data = [];
        if ($response['status'] == 200) {
            $provinces = json_decode($response['data'], true);
            if (is_array($provinces)) {
                foreach ($provinces as $province) {
                    $data[] = [
                        'id' => $province['value'],
                        'text' => $province['name'],
                        'province_id' => $province['id']
                    ];
                }
            }
        }

        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_dvhc_ward_inet()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $parent = $_GET['parent'];
        $data = [];
        if (isset($parent) && !empty($parent)) {
            $response = $CDWFunc->inet->getWardListByParentId($parent);
            if ($response['status'] == 200) {
                $wards = json_decode($response['data'], true);
                if (is_array($wards)) {
                    foreach ($wards as $ward) {
                        $data[] = [
                            'id' => $ward['id'],
                            'text' => $ward['name']
                        ];
                    }
                }
            }
        }
        
        $result = [];
        if (!empty($search)) {
            foreach ($data as $item) {
                if (str_contains(strtolower($item["text"]), strtolower($search))) {
                    $result[] = $item;
                }
            }
            wp_send_json_success($result);
        }
        wp_send_json_success($data);

        wp_die();
    }

    public function func_get_inet_email_plans()
    {
        global $CDWFunc;
        $search = $_GET['search'] ?? '';
        $result = [];
        $serviceType = 'email-062025';

        $response = $CDWFunc->inet->get_service_plans('email', $serviceType,  $search);
        
        if ($response['success'] && !empty($response['data'])) {
            foreach ($response['data'] as $plan) {
                $item = [
                    'id' => $plan['id'],
                    'text' => $plan['name'],
                    'title' => $plan['description'] ?? ''
                ];

                $result[] = $item;
            }
        }

        wp_send_json_success($result);
        wp_die();
    }
}

new Select2();
