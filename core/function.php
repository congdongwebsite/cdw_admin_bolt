<?php
defined('ABSPATH') || exit;
class FunctionAdmin // extends ConstantAdmin
{
    private $menus;
    private $modules;
    private $dvhc;
    private $permission;
    public $wpdb;
    public $date;
    public $number;
    public $vn_charset_conversion;
    public function __construct()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $menu_json = file_get_contents(ADMIN_THEME_URL . '/menu.json');
        $module_json = file_get_contents(ADMIN_THEME_URL . '/module.json');
        $permission_json = file_get_contents(ADMIN_THEME_URL . '/permission.json');
        $this->menus = json_decode($menu_json);
        $this->modules = json_decode($module_json);
        $this->permission = json_decode($permission_json);
        $this->wpdb = new FunctionWPDB();
        $this->date = new DateTimeHandler();
        $this->number = new FunctionNumber();
        $this->vn_charset_conversion = new vn_charset_conversion();
    }
    public function updateOption($optionName, $value)
    {
        return  update_option($optionName,  $value);
    }
    public function getOption($optionName)
    {
        return  get_option($optionName);
    }

    public function updateUserOption($userId, $optionName, $value)
    {
        return update_user_meta($userId, $optionName,  $value);
    }
    public function getUserOption($userId, $optionName)
    {
        return  get_user_meta($userId, $optionName, true);
    }
    public function getDataDVHC()
    {
        if ($this->dvhc == null) {
            $dvhc_json = file_get_contents(ADMIN_THEME_URL . '/data_dvhc.json');
            $this->dvhc = json_decode($dvhc_json);
        }
    }
    public function getListTP()
    {
        if ($this->dvhc == null) {
            $dvhc_json = file_get_contents(ADMIN_THEME_URL . '/data_dvhc.json');
            $this->dvhc = json_decode($dvhc_json);
        }
        return $this->unique_multidim_array(array_column($this->dvhc, 'tp'), 'code');
    }
    public function getListQH($parent)
    {
        return $this->unique_multidim_array($this->array_column_by_parent($this->dvhc, 'qh', 'tp', $parent), 'code');
    }
    public function getListPX($parent)
    {
        return $this->unique_multidim_array($this->array_column_by_parent($this->dvhc, 'px', 'qh', $parent), 'code');
    }

    public function getTP($id)
    {
        return $this->search_array_column_by_key($this->dvhc, 'tp', $id);
    }
    public function getQH($id)
    {
        return $this->search_array_column_by_key($this->dvhc, 'qh', $id);
    }
    public function getPX($id)
    {
        return $this->search_array_column_by_key($this->dvhc, 'px', $id);
    }

    public function search_array_column_by_key($array, $column_name, $value)
    {
        $data = $this->unique_multidim_array($this->array_column_by_parent($array, $column_name, $column_name, $value), 'code');
        if (count($data) > 0) {
            return $data[0]['text'];
        } else
            return "";
    }
    public function array_column_by_parent($array, $column_name, $column_name_parent, $value_parent)
    {
        return array_filter(array_map(function ($element) use ($column_name, $column_name_parent, $value_parent) {
            if ($element->$column_name_parent->code == $value_parent) {
                return $element->$column_name;
            }
        }, $array));
    }
    public function unique_multidim_array($array, $key)
    {

        $temp_array = array();

        $i = 0;

        $key_array = array();

        foreach ($array as $val) {

            if (!in_array($val->$key, $key_array)) {

                $key_array[$i] = $val->$key;

                $temp_array[$i] = [
                    "id" => $val->$key,
                    "text" => $val->name
                ];
                $i++;
            }
        }

        return $temp_array;
    }
    public function getPathFileModule($action, $module, $isPath = false, $modules = null)
    {
        if ($modules == null)  $modules = $this->modules;
        $found = false;
        $fileName = '';
        if (sanitize_title($module) != '' && sanitize_title($action) != '') {
            foreach ($modules as $moduleItem) {
                $fileName = '';
                if (sanitize_title($module) == sanitize_title($moduleItem->module) && sanitize_title($action) == sanitize_title($moduleItem->action)) {
                    $fileName = sanitize_title($moduleItem->module) . '/';
                    if (!$isPath) $fileName .=  sanitize_title($moduleItem->action);
                    $found = true;
                    break;
                }
                if ($found) {
                    break;
                }
            }
        }
        return (object) [
            'found' => $found,
            'fileName' => $fileName
        ];
    }
    public function getPathFileMenu($action, $module, $isPath = false, $modules = null)
    {
        if ($modules == null)  $modules = $this->menus;
        $found = false;
        $fileName = '';
        if (sanitize_title($module) != '' && sanitize_title($action) != '') {
            foreach ($modules as $moduleItem) {
                $fileName = '';
                if (sanitize_title($module) == sanitize_title($moduleItem->module) && sanitize_title($action) == sanitize_title($moduleItem->action)) {
                    if (!$isPath) $fileName = sanitize_title($moduleItem->action);
                    $found = true;
                    break;
                } else {
                    $fileName .= sanitize_title($moduleItem->action) . '/';
                    if (isset($moduleItem->items) && count($moduleItem->items) > 0) {
                        $tmp = $this->getPathFileMenu($action, $module, $isPath, $moduleItem->items);
                        $fileName   .= $tmp->fileName;
                        $found = $tmp->found;
                    }
                }
                if ($found) {
                    break;
                }
            }
        }
        return (object) [
            'found' => $found,
            'fileName' => $fileName
        ];
    }
    public function getModuleFileName($action, $module, $subaction = '')
    {
        $found = false;
        $fileName = ADMIN_THEME_URL  . '/modules/';

        if ((sanitize_title($module) != '' && sanitize_title($action) == '') || sanitize_title($module) == sanitize_title($action)) {
            $fileName .= sanitize_title($module);
        } else {
            if (file_exists($fileName . "/" . sanitize_title($module) . "/" . sanitize_title($action) . ".php")) {
                $fileName .=   sanitize_title($module) . "/" . sanitize_title($action);
                $found = true;
            } else {
                $tmp = $this->getPathFileMenu($action, $module);
                $found =  $tmp->found;
                if ($found)
                    $fileName .=  $tmp->fileName;
                else {
                    $tmp = $this->getPathFileModule($action, $module);
                    $found =  $tmp->found;
                    $fileName .=  $tmp->fileName;
                    if (!$found) $fileName = '';
                }
            }
        }
        return (object) [
            'found' => $found,
            'fileName' => $fileName . (empty($subaction) ? "" : '-' . $subaction) . '.php'
        ];
    }
    public function getBreadcrumbFileName($action, $module, $subaction = '')
    {
        $found = false;
        $fileName = ADMIN_THEME_URL  . '/modules/';

        if ((sanitize_title($module) != '' && sanitize_title($action) == '') || sanitize_title($module) == sanitize_title($action)) {
            $fileName .= sanitize_title($module);
        } else {
            if (file_exists($fileName . "/" . sanitize_title($module) . "/" . sanitize_title($action) . "-breadcrumb.php")) {
                $fileName .=   sanitize_title($module) . "/" . sanitize_title($action);
                $found = true;
            } else {
                $tmp = $this->getPathFileMenu($action, $module);
                $found =  $tmp->found;
                if ($found)
                    $fileName .=  $tmp->fileName;
                else {
                    $tmp = $this->getPathFileModule($action, $module);
                    $found =  $tmp->found;
                    $fileName .=  $tmp->fileName;
                    if (!$found) $fileName = '';
                }
            }
        }
        return (object) [
            'found' => $found,
            'fileName' => $fileName . (empty($subaction) ? "" : '-' . $subaction) . '-breadcrumb.php'
        ];
    }
    public function getPathInit($action, $module)
    {
        $found = false;
        $fileName = ADMIN_THEME_URL  . '/modules/';

        if (file_exists($fileName .  sanitize_title($module) . "/init.php")) {
            $fileName .=   sanitize_title($module) . "/init.php";
            $found = true;
        } else {
            $tmp = $this->getPathFileMenu($action, $module, true);
            $found =  $tmp->found;
            if ($found) {
                $fileName .=  $tmp->fileName . 'init.php';
            } else {
                $tmp = $this->getPathFileModule($action, $module, true);
                $found =  $tmp->found;
                $fileName .=  $tmp->fileName . 'init.php';
                if (!$found) $fileName = '';
            }
        }

        return (object) [
            'found' => $found,
            'fileName' => $fileName
        ];
    }

    public function getMenu($action, $module)
    {
        $obj = $this->getObjectByTwoField($this->menus, 'action', $action, 'module', $module);
        return  $obj;
    }
    public function getModule($action, $module)
    {
        $obj = $this->getObjectByTwoField($this->modules, 'action', $action, 'module', $module);
        if ($obj == null) $obj = $this->getMenu($action, $module);
        return  $obj;
    }

    public function getModules($arr)
    {
        $result = [];
        foreach ($arr as $module) {
            $obj = $this->getObjectByTwoField($this->modules, 'action', 'index', 'module', $module);
            if ($obj == null) $obj = $this->getMenu('index', $module);
            if ($obj != null) $result[] = $obj;
        }
        return  $result;
    }
    public function getMenuByAction($action)
    {
        $obj = $this->getObjectByField($this->menus, 'action', $action);
        return  $obj;
    }
    public function getModuleByAction($action)
    {
        $obj = $this->getObjectByField($this->modules, 'action', $action);
        if ($obj == null) $obj = $this->getMenuByAction($action);
        return  $obj;
    }
    public function getMenus()
    {
        return  $this->menus;
    }
    public function getComponent($name)
    {
        $name .= '.php';
        if (file_exists(ADMIN_THEME_URL . '/component/' . $name)) {
            require(ADMIN_THEME_URL . '/component/' . $name);
        }
    }
    public function getUrl($action, $module  = '', $parameter  = '')
    {
        $url = '';
        if ($module == '') $module = $action;
        if ($module != '')
            $url =  "/" . URL_ADMIN . "/?module=" . $module . ($action != "" ? "&action=" . $action : "");
        else
            $url =  "/" . URL_ADMIN;
        if ($parameter != '')  $url .= '&' . $parameter;
        return  $url;
    }
    public function getObjectByField($arr, $field, $action)
    {
        $result = null;
        foreach ($arr as $object) {
            if (strtolower($object->$field) == strtolower($action)) {
                $result = $object;
                break;
            }
            if ($result == null && count($object->items) > 0)
                $result = $this->getObjectByField($object->items, $field, $action);
        }
        return $result;
    }
    public function getObjectByTwoField($arr, $fieldAction, $action, $fieldModule, $module)
    {
        $result = null;
        foreach ($arr as $object) {

            $checkAction = strtolower($object->$fieldAction) == strtolower($action);
            $checkModule = strtolower($object->$fieldModule) == strtolower($module);

            if ($checkAction && $checkModule) {
                $result = $object;
                break;
            }
            if ($result == null && isset($object->items) && is_array($object->items) && count($object->items) > 0)
                $result = $this->getObjectByTwoField($object->items,  $fieldAction, $action, $fieldModule, $module);
        }
        return $result;
    }

    public function number_format($number, $decimal = 0, $groupSymboy = ".", $decimalSymboy = ",")
    {
        return number_format($number, $decimal, $decimalSymboy, $groupSymboy);
    }

    public function is_valid_file_type($type)
    {
        $type = strtolower(trim($type));
        return  $type == 'png' || $type == 'gif' || $type == 'jpg' || $type == 'jpeg';
    }
    public function checkPermission($action, $module)
    {
        global $userCurrent;
        $isAdministrator = array_search("administrator", $userCurrent->roles, true) !== false;
        if ($isAdministrator) return $isAdministrator;
        foreach ($userCurrent->roles as $role) {
            $data = array_column($this->permission, $role);
            if (count($data) > 0) {
                $check = array_filter(array_map(function ($element) use ($action, $module) {
                    if ($element->module == $module && $element->action == $action) {
                        return $element;
                    }
                }, $data[0]));
                if (count($check) > 0) return true;
            }
        }
        return false;
    }
    public function isAdministrator($userID = '')
    {
        global $userCurrent;
        if (empty($userID)) {
            $userID = wp_get_current_user()->ID;
        };
        $userCurrent = get_user_by('id', $userID);
        $isAdministrator = array_search("administrator", $userCurrent->roles, true) !== false;
        if ($isAdministrator) return $isAdministrator;
    }

    public function getCustomer($userID = '')
    {
        $customer_id = '';
        if (empty($userID)) {
            $userID = wp_get_current_user()->ID;
        };
        $customer_id = get_user_meta($userID, 'customer-id', true);

        return $customer_id;
    }

    public function redirectCustomerCheck($customerID = '', $link, $userID = '')
    {
        if (!$this->isAdministrator()) {
            if (empty($userID)) {
                $userC = wp_get_current_user();
                $userID = $userC->ID;
            }
            $arr = array(
                'post_type' => 'customer',
                'post_status' => 'publish',
                'fields' => 'ids',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key'     => 'user-id',
                        'value'   => $userID,
                        'compare' => '=',
                    )
                )
            );
            $id_customers = get_posts($arr);
            if (!in_array($customerID, $id_customers)) {
                header('Location: ' .  $link);
            }
        }
    }
    public function generatePassword($_len)
    {
        global $CDWConst;
        $_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';            // small letters
        $_alphaCaps  = strtoupper($_alphaSmall);                // CAPITAL LETTERS
        $_numerics   = '1234567890';                            // numerics
        $_specialChars = '!@#$&*';   // Special Characters

        $password = '';         // will contain the desired pass
        for ($i = 0; $i < $_len; $i++) {
            $char = '';                              // Loop till the length mentioned
            $r = rand(0, 3);
            switch ($r) {
                case 0:
                    $_rand = rand(0, strlen($_alphaSmall) - 1);
                    $char = substr($_alphaSmall, $_rand, 1);
                    break;
                case 1:
                    $_rand = rand(0, strlen($_alphaCaps) - 1);
                    $char = substr($_alphaCaps, $_rand, 1);
                    break;
                case 2:
                    $_rand = rand(0, strlen($_numerics) - 1);
                    $char = substr($_numerics, $_rand, 1);
                    break;
                case 3:
                    $_rand = rand(0, strlen($_specialChars) - 1);
                    $char = substr($_specialChars, $_rand, 1);
                    break;
            }              // Get Randomized Length
            $password .= $char;                // returns part of the string [ high tensile strength ;) ] 
        }

        if (!preg_match($CDWConst->preg_match_password, $password))
            $password = $this->generatePassword($_len);
        return $password;       // Returns the generated Pass
    }
    public function get_lable_status($code)
    {
        $lable = '';
        switch ($code) {
            case "all":
                $lable = 'Tất cả';
                break;
            case "publish":
                $lable = '<span class="text-warning">Tiếp nhận</span>';
                break;
                break;
            case "pending":
                $lable = 'Đang xử lý';
                break;
            case "cancel":
                $lable = '<span class="text-danger">Hủy</span>';
                break;
            case "success":
                $lable = '<span class="text-success">Đã thanh toán</span>';
                break;
        }
        return $lable;       // Returns the generated Pass
    }
    public function stripVN($str)
    {
        return $this->vn_charset_conversion->convert($str);
    }
    public function get_domain_paths($strdomain)
    {
        $suffix = '';

        if (filter_var($strdomain, FILTER_VALIDATE_URL)) {
            $strdomain = parse_url($strdomain, PHP_URL_HOST);
        }
        $domain = preg_replace("/^www\./i", '', $strdomain);

        $domainParts = explode('.', $domain);
        switch (count($domainParts)) {
            case 0:
                break;
            case 1:
                $domain = $domainParts[0];
                break;
            case 2:
                $domain = $domainParts[0];
                $suffix = $domainParts[1];
                break;
            case 3:
                $domain = $domainParts[0];
                $suffix = $domainParts[1] . "." . $domainParts[2];
                break;
            case 4:
                $domain = $domainParts[1];
                $suffix = $domainParts[2] . "." . $domainParts[3];
                break;
        }


        return (object)[
            'domain' => $domain,
            'suffix' => $suffix,
            'full' => $domain . "." . $suffix,
        ];
    }


    public function get_device_type_by_user_agent($user_agent)
    {
        // Kiểm tra xem User Agent có phải là Mobile hay không
        $is_mobile = preg_match('/(Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone)/i', $user_agent);

        // Nếu là Mobile, in ra thông tin về loại thiết bị
        if ($is_mobile) {
            return 'mobile';
        }
        // Nếu không phải Mobile, kiểm tra xem User Agent có phải là Desktop hay không
        else {
            $is_desktop = preg_match('/(Windows|Macintosh|Linux|Ubuntu)/i', $user_agent);
            // Nếu là Desktop, kiểm tra xem User Agent có phải là Laptop hay không
            if ($is_desktop) {
                $is_laptop = preg_match('/Laptop|Notebook|Netbook|Ultrabook/i', $user_agent);
                // Nếu là Laptop, in ra thông tin về loại thiết bị
                if ($is_laptop) {
                    return 'laptop';
                }
                // Nếu không phải Laptop, in ra thông tin về loại thiết bị là Desktop
                else {
                    return 'desktop';
                }
            }
            // Nếu không phải Desktop, in ra thông tin không xác định
            else {
                return 'desktop';
            }
        }
    }
    public function destroy_session($user_id, $session_id)
    {

        // Lấy thông tin phiên đăng nhập cần hủy
        $session = wp_get_session_tokens($user_id, $session_id);

        // Kiểm tra xem phiên đăng nhập cần hủy có tồn tại hay không
        if ($session) {
            // Hủy phiên đăng nhập cụ thể
            wp_destroy_session($session_id);
        }
    }

    public function trim_space_html($stringHtml)
    {
        return trim(preg_replace('/\s+/', ' ', $stringHtml));
    }
    public function initCSS()
    {
        $path_module = '';
        $tmp = $this->getPathFileMenu(ACTION_ADMIN, MODULE_ADMIN, true);
        $found =  $tmp->found;
        if ($found)
            $path_module .=  $tmp->fileName;
        else {
            $tmp = $this->getPathFileModule(ACTION_ADMIN, MODULE_ADMIN, true);
            $found =  $tmp->found;
            $path_module .=  $tmp->fileName;
        }
        if (empty($path_module)) $path_module = MODULE_ADMIN . '/';
        if (file_exists(ADMIN_THEME_URL . '/assets/css/' . $path_module . ACTION_ADMIN . '.css')) {
            wp_register_style(MODULE_ADMIN . '-' . ACTION_ADMIN, ADMIN_CHILD_THEME_URL_F . '/assets/css/' . $path_module . ACTION_ADMIN . '.css', array(), CDW_VERSION, 'all');
            wp_print_styles(MODULE_ADMIN . '-' . ACTION_ADMIN);
        }

        if (!empty(SUBACTION_ADMIN) && file_exists(ADMIN_THEME_URL . '/assets/css/' . $path_module . ACTION_ADMIN . '-' . SUBACTION_ADMIN . '.css')) {
            wp_register_style(MODULE_ADMIN . '-' . ACTION_ADMIN . '-' . SUBACTION_ADMIN, ADMIN_CHILD_THEME_URL_F . '/assets/css/' . $path_module . ACTION_ADMIN . '-' . SUBACTION_ADMIN . '.css', array(), CDW_VERSION, 'all');
            wp_print_styles(MODULE_ADMIN . '-' . ACTION_ADMIN . '-' . SUBACTION_ADMIN);
        }
    }

    public function initJS()
    {
        $path_module = '';
        $tmp = $this->getPathFileMenu(ACTION_ADMIN, MODULE_ADMIN, true);
        $found =  $tmp->found;
        if ($found)
            $path_module .=  $tmp->fileName;
        else {
            $tmp = $this->getPathFileModule(ACTION_ADMIN, MODULE_ADMIN, true);
            $found =  $tmp->found;
            $path_module .=  $tmp->fileName;
        }

        if (empty($path_module)) $path_module = MODULE_ADMIN . '/';
        if (file_exists(ADMIN_THEME_URL . '/assets/js/' . $path_module  . ACTION_ADMIN . '.js')) {
            wp_register_script(MODULE_ADMIN . '-' . ACTION_ADMIN, ADMIN_CHILD_THEME_URL_F . '/assets/js/' . $path_module .  ACTION_ADMIN . '.js', ['jquery'], CDW_VERSION);
            wp_print_scripts(MODULE_ADMIN . '-' . ACTION_ADMIN);
        }


        if (!empty(SUBACTION_ADMIN) && file_exists(ADMIN_THEME_URL . '/assets/js/' . $path_module . ACTION_ADMIN . '-' . SUBACTION_ADMIN . '.js')) {
            wp_register_script(MODULE_ADMIN . '-' . ACTION_ADMIN . '-' . SUBACTION_ADMIN, ADMIN_CHILD_THEME_URL_F . '/assets/js/' . $path_module . ACTION_ADMIN . '-' . SUBACTION_ADMIN . '.js', ['jquery'], CDW_VERSION);
            wp_print_scripts(MODULE_ADMIN . '-' . ACTION_ADMIN . '-' . SUBACTION_ADMIN);
        }
    }
    public function trimWords($text, $num_words = 20, $more = "")
    {
        return  wp_trim_words($text, $num_words, $more);
    }
}
add_action('rest_api_init', function () {
    register_rest_route('cdw/v1', '/hosting-status', array(
        'methods' => 'GET',
        'callback' => 'get_hosting_expiry_status',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('cdw/v1', '/domain-status', array(
        'methods' => 'GET',
        'callback' => 'get_domain_expiry_status',
        'permission_callback' => '__return_true',
    ));
});
function get_hosting_expiry_status($request)
{
    global $CDWEmail;
    $results = [];
    $date_now = current_time('Y-m-d');

    $day_ranges = [
        'expire_in_30_days' => 30,
        'expire_in_7_days'  => 7,
        'expire_in_1_day'   => 1,
        'expired_1_day_ago' => -1,
    ];

    foreach ($day_ranges as $label => $diff_days) {
        $target_date = date('Y-m-d', strtotime("$date_now $diff_days days"));

        $args = [
            'post_type' => 'customer-hosting',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'expiry_date',
                    'value' => $target_date,
                    'compare' => '=',
                    'type' => 'DATE',
                ]
            ]
        ];

        $query = new WP_Query($args);

        $CDWEmail->sendEmailNotificationHosting($query->posts);
        $results[$label] = $query->posts;
    }

    write_custom_log('Đã gửi thông báo Hosting ', $results);
    return rest_ensure_response($results);
}
function get_domain_expiry_status($request)
{
    global $CDWEmail;
    $results = [];
    $date_now = current_time('Y-m-d');

    $day_ranges = [
        'expire_in_30_days' => 30,
        'expire_in_7_days'  => 7,
        'expire_in_1_day'   => 1,
        'expired_1_day_ago' => -1,
    ];

    foreach ($day_ranges as $label => $diff_days) {
        $target_date = date('Y-m-d', strtotime("$date_now $diff_days days"));

        $args = [
            'post_type' => 'customer-domain',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'expiry_date',
                    'value' => $target_date,
                    'compare' => '=',
                    'type' => 'DATE',
                ]
            ]
        ];

        $query = new WP_Query($args);

        $CDWEmail->sendEmailNotificationDomain($query->posts);
        $results[$label] = $query->posts;
    }
    write_custom_log('Đã gửi thông báo Domain', $results);
    return rest_ensure_response($results);
}
function write_custom_log($label, $data = null, $filename = 'custom-log.log')
{
    if (is_array($data) || is_object($data)) {
        $data = print_r($data, true);
    }

    $upload_dir = wp_upload_dir();
    $log_path = trailingslashit($upload_dir['basedir']) . $filename;

    $log_entry = "[" . current_time('mysql') . "] ";
    $log_entry .= strtoupper($label) . ': ';
    $log_entry .= $data ? $data : 'NO DATA';
    $log_entry .= PHP_EOL;
    file_put_contents($log_path, $log_entry, FILE_APPEND);
}
function custom_upload_private_image($file)
{
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'Bạn phải đăng nhập');
    }

    $private_dir = WP_CONTENT_DIR . '/wp-private-uploads/';

    if (!file_exists($private_dir)) {
        mkdir($private_dir, 0755, true);
    }

    $file_tmp = $file['tmp_name'];
    $filename = wp_unique_filename($private_dir, sanitize_file_name($file['name']));
    $destination = $private_dir . $filename;

    if (!move_uploaded_file($file_tmp, $destination)) {
        return new WP_Error('upload_error', 'Không thể lưu file');
    }

    $attachment_id = wp_insert_attachment([
        'post_mime_type' => $file['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_author'    => get_current_user_id()
    ]);

    update_post_meta($attachment_id, '_private_path', $destination);
    $attach_data = wp_generate_attachment_metadata($attachment_id, $destination);
    wp_update_attachment_metadata($attachment_id, $attach_data);

    return $attachment_id;
}

function move_existing_attachment_to_private($attachment_id)
{
    $_private_path = get_post_meta($attachment_id, '_private_path', true);
    if ($_private_path) return false;
    $id_parrent = get_post_meta($attachment_id, 'id-parent', true);

    $original_file = get_attached_file($attachment_id);
    if (!$original_file || !file_exists($original_file)) {
        return 'File không tồn tại';
    }

    $filename = basename($original_file);

    $private_dir = WP_CONTENT_DIR . '/wp-private-uploads/';

    if (!file_exists($private_dir)) {
        mkdir($private_dir, 0755, true);
    }

    $filename = wp_unique_filename($private_dir, sanitize_file_name($filename));
    $destination = $private_dir . $filename;
    if (!copy($original_file, $destination)) {
        return new WP_Error('copy_failed', '❌ Không thể sao chép sang thư mục private');
    }

    unlink($original_file);
    update_post_meta($attachment_id, '_private_path', $destination);
    return $attachment_id;
}
function get_list_abc()
{
    $args = array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'id-parent',
                'value'   => '',
                'compare' => '!=',
            )
        )
    );
    $attachments = get_posts($args);
    foreach ($attachments as $attachment) {
        $_private_path = get_post_meta($attachment, '_private_path', true);
        $id_parrent = get_post_meta($attachment, 'id-parent', true);

        $original_file = get_attached_file($attachment);
        unlink($original_file);
        // if (3909 != $id_parrent) continue;
        var_dump($original_file);
        if ($_private_path) continue;

        // $original_file = get_attached_file($attachment);
        // $aaa = move_existing_attachment_to_private($attachment);
        // var_dump($aaa);
        // var_dump($id_parrent, $attachment, $original_file);
    }
    return     [];
}
add_action('init', function () {
    add_rewrite_rule('^admin-image/([0-9]+)/?', 'index.php?admin_image=1&id=$matches[1]', 'top');
});
add_filter('query_vars', function ($vars) {
    $vars[] = 'admin_image';
    $vars[] = 'id';
    return $vars;
});
add_action('template_redirect', function () {
    if (get_query_var('admin_image') != 1) return;

    if (!is_user_logged_in())
        wp_die('Vui lòng đăng nhập');

    $id = intval(get_query_var('id'));
    $attachment = get_post($id);
    $user_id = get_current_user_id();

    if (!$attachment || ($attachment->post_author !== $user_id && !current_user_can('administrator'))) {
        wp_die('Bạn không có quyền truy cập');
    }

    $file_path = get_post_meta($id, '_private_path', true);

    if (!$file_path || !file_exists($file_path)) {
        wp_die('Không tìm thấy file');
    }

    $mime = mime_content_type($file_path);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
});
function get_private_image_link($attachment_id)
{
    return home_url('/admin-image/' . intval($attachment_id));
}
