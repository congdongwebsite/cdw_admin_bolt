<?php
defined('ABSPATH') || exit;
class FunctionAdmin // extends ConstantAdmin
{
    private $menus;
    private $module_menus;
    private $modules;
    private $dvhc;
    private $permission;
    public $wpdb;
    public $date;
    public $number;
    public $vn_charset_conversion;
    public $directAdmin;
    public $inet;
    public $momo;
    public $inetCustomer;
    public function __construct()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $module_menu_json = file_get_contents(ADMIN_THEME_URL . '/module-menu.json');
        $userCurrent = wp_get_current_user();
        $menu_json = file_get_contents(ADMIN_THEME_URL . '/menu.json');
        foreach ($userCurrent->roles as $role) {
            if (file_exists(ADMIN_THEME_URL . '/menu-' . $role . '.json')) {
                $menu_json = file_get_contents(ADMIN_THEME_URL . '/menu-' . $role . '.json');
            }
        }

        $module_json                 = file_get_contents(ADMIN_THEME_URL . '/module.json');
        $permission_json             = file_get_contents(ADMIN_THEME_URL . '/permission.json');
        $this->menus                 = json_decode($menu_json);
        $this->module_menus          = json_decode($module_menu_json);
        $this->modules               = json_decode($module_json);
        $this->permission            = json_decode($permission_json);
        $this->wpdb                  = new FunctionWPDB();
        $this->date                  = new DateTimeHandler();
        $this->number                = new FunctionNumber();
        $this->vn_charset_conversion = new vn_charset_conversion();
        $this->directAdmin           = new DirectAdminClient();
        $this->inet         = new INetCustomerManager();
        $this->momo = new APIMomo(APIMOMOURL);
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
        if ($modules == null)  $modules = $this->module_menus;
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
        $url = URL_HOME;
        if ($module == '') $module = $action;
        if ($module != '')
            $url .=  "/" . URL_ADMIN . "/?module=" . $module . ($action != "" ? "&action=" . $action : "");
        else
            $url .=  "/" . URL_ADMIN;
        if ($parameter != '')  $url .= '&' . $parameter;
        return  $url;
    }
    public function getObjectByField($arr, $field, $action)
    {
        $result = null;
        if (!is_array($arr)) return $result;
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
        if (!is_array($arr)) return $result;
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
        // $isAdministrator = array_search("administrator", $userCurrent->roles, true) !== false;
        // // if ($isAdministrator) return $isAdministrator;
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
        if ($userCurrent == null) return false;
        $isAdministrator = array_search("administrator", $userCurrent->roles, true) !== false;
        if ($isAdministrator) return $isAdministrator;
    }
    public function isKYC($userID = '')
    {
        global $userCurrent;
        if (empty($userID)) {
            $userID = wp_get_current_user()->ID;
        };

        $customer_id = get_user_meta($userID, 'customer-id', true);
        $kyc_status_meta = get_post_meta($customer_id, 'status-kyc', true);
        
        switch ($kyc_status_meta) {
            case '3':
                return true;
                break;
            default:
                return false;
        }
    }

    public function getCustomer($userID = '')
    {
        $customer_id = '';
        if (empty($userID)) {
            $userID = wp_get_current_user()->ID;
        };

        $userCurrent = get_user_by('id', $userID);

        if (array_search("administrator", $userCurrent->roles, true) !== false) {
            $customer_id = get_user_meta($userID, 'customer-default-id', true);
        }
        if (empty($customer_id)) {
            $customer_id = get_user_meta($userID, 'customer-id', true);
        }
        return empty($customer_id) ? false : $customer_id;
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

function cdw_create_customer_log($customer_id, $title, $content = '')
{
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_name = $current_user->display_name;

    $log_post = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_author'  => $user_id,
        'post_type'    => 'customer-log',
    );

    $log_id = wp_insert_post($log_post);

    if ($log_id) {
        add_post_meta($log_id, 'customer-id', $customer_id);
        add_post_meta($log_id, 'user-name', $user_name);
    }

    return $log_id;
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
    register_rest_route('cdw/v1', '/email-status', array(
        'methods' => 'GET',
        'callback' => 'get_email_expiry_status',
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

    // Clear data for items expired more than 3 months
    $three_months_ago = date('Y-m-d', strtotime("$date_now -3 months"));
    $args_clear = [
        'post_type' => 'customer-hosting',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'expiry_date',
                'value' => $three_months_ago,
                'compare' => '<=',
                'type' => 'DATE',
            ]
        ]
    ];
    $query_clear = new WP_Query($args_clear);
    if (!empty($query_clear->posts)) {
        do_action('clear_data_3rd_party', $query_clear->posts, 'hosting');
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

    // Clear data for items expired more than 3 months
    $three_months_ago = date('Y-m-d', strtotime("$date_now -3 months"));
    $args_clear = [
        'post_type' => 'customer-domain',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'expiry_date',
                'value' => $three_months_ago,
                'compare' => '<=',
                'type' => 'DATE',
            ]
        ]
    ];
    $query_clear = new WP_Query($args_clear);
    if (!empty($query_clear->posts)) {
        do_action('clear_data_3rd_party', $query_clear->posts, 'domain');
    }

    write_custom_log('Đã gửi thông báo Domain', $results);
    return rest_ensure_response($results);
}

function get_email_expiry_status($request)
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
            'post_type' => 'customer-email',
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

        $CDWEmail->sendEmailNotificationEmail($query->posts);

        $results[$label] = $query->posts;
    }

    // Clear data for items expired more than 3 months
    $three_months_ago = date('Y-m-d', strtotime("$date_now -3 months"));
    $args_clear = [
        'post_type' => 'customer-email',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'expiry_date',
                'value' => $three_months_ago,
                'compare' => '<=',
                'type' => 'DATE',
            ]
        ]
    ];
    $query_clear = new WP_Query($args_clear);
    if (!empty($query_clear->posts)) {
        do_action('clear_data_3rd_party', $query_clear->posts, 'email');
    }

    write_custom_log('Đã gửi thông báo Email', $results);
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

function mask_license($license)
{
    $length = strlen($license);

    if ($length >= 7) {
        $firstThree = substr($license, 0, 3);
        $lastFour = substr($license, -4);
        $middleLength = $length - 7;
        $mask = str_repeat('x', $middleLength);
        return $firstThree . $mask . $lastFour;
    }
    return $license;
}
function is_date_greater_than_30_days($date)
{
    // $date format: Y-m-d H:i:s
    $input_date = date('Y-m-d', strtotime($date));
    $now_date = date('Y-m-d', strtotime(current_time('mysql')));
    $diff = (strtotime($now_date) - strtotime($input_date)) / DAY_IN_SECONDS;
    return ($diff > 30);
}

function cdw_admin_notice($message, $type = 'info', $dismissible = true)
{
    $class = 'alert';
    $icon = '';
    switch ($type) {
        case 'success':
            $class .= ' alert-success';
            $icon = '<i class="fa fa-check-circle"></i> ';
            break;
        case 'warning':
            $class .= ' alert-warning';
            $icon = '<i class="fa fa-warning"></i> ';
            break;
        case 'error':
        case 'danger':
            $class .= ' alert-danger';
            $icon = '<i class="fa fa-times-circle"></i> ';
            break;
        default:
            $class .= ' alert-info';
            $icon = '<i class="fa fa-info-circle"></i> ';
            break;
    }
    if ($dismissible) {
        $class .= ' alert-dismissible';
        $closeBtn = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    } else {
        $closeBtn = '';
    }
    echo '<section class="container-fluid pt-3 mt-3"><div class="' . esc_attr($class) . ' mb-1" role="alert">' . $closeBtn . $icon . $message . '</div></section>';
}

function cdw_push_admin_notice($message, $type = 'info', $dismissible = true)
{
    $notices = get_transient('cdw_admin_notices');
    if (!is_array($notices)) $notices = [];
    $notices[] = [
        'message' => $message,
        'type' => $type,
        'dismissible' => $dismissible
    ];
    set_transient('cdw_admin_notices', $notices, 60 * 5);
}

function cdw_render_admin_notices()
{
    $notices = get_transient('cdw_admin_notices');
    if (!empty($notices) && is_array($notices)) {
        foreach ($notices as $notice) {
            cdw_admin_notice($notice['message'], $notice['type'], $notice['dismissible']);
        }
        delete_transient('cdw_admin_notices');
    }
}
add_action('cdw_admin_notices', 'cdw_render_admin_notices');
add_action('cdw_admin_notices', function () {
    global $CDWUser, $CDWFunc;
    $customer_id = get_user_meta($CDWUser->id, 'customer-id', true);
    $kyc_status_meta = get_post_meta($customer_id, 'status-kyc', true);
    switch ($kyc_status_meta) {
        case '2':
            $kyc_status_text = 'Đang Xác Thực Tài Khoản';
            $kyc_status_class = 'warning';
            cdw_admin_notice($kyc_status_text . ' <a href="' . $CDWFunc->getUrl('index', 'setting') . '">Kiểm tra thông tin </a>', $kyc_status_class);
            break;
        case '3':
            $kyc_status_text = 'Đã Xác Thực Tài Khoản';
            $kyc_status_class = 'success';
            break;
        case '1':
        default:
            $kyc_status_text = 'Chưa Xác Thực Tài Khoản';
            $kyc_status_class = 'danger';
            cdw_admin_notice($kyc_status_text . ' <a href="' . $CDWFunc->getUrl('index', 'setting') . '">Cập nhật thông tin </a>', $kyc_status_class);
            break;
    }
});
function cdw_process_successful_payment($idBilling)
{
    global $CDWFunc, $CDWEmail;
    error_log("cdw_process_successful_payment -> START processing billing ID: " . $idBilling);
    $id = get_post_meta($idBilling, 'customer-id', true);
    $name = get_post_meta($id, 'name', true);
    $email = get_post_meta($id, 'email', true);

    if (!get_post_meta($idBilling, 'is-update', true)) {
        $items = get_post_meta($idBilling, 'items', true);
        if (is_array($items))
            foreach ($items as $key => $item) {
                switch ($item["type"]) {
                    case "customer-domain":
                        $domain_post_id = $item["id"];
                        $expiry_date = get_post_meta($domain_post_id, 'expiry_date', true);
                        $inet_domain_id = get_post_meta($domain_post_id, 'inet_domain_id', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($domain_post_id, 'expiry_date', $expiry_date_new);
                        update_post_meta($domain_post_id, 'quantity', (int) $item["quantity"]);

                        $domain_name = get_post_meta($domain_post_id, 'url', true);
                        $period = (int)$item["quantity"];
                        error_log('[func_update] customer-domain: ' . print_r(['domain_name' => $domain_name, 'period' => $period], true));

                        if (empty($inet_domain_id)) {
                            $inet_domain_info_result = $CDWFunc->inet->get_domain_by_name($domain_name);
                            error_log('[func_update] customer-domain/inet_domain_info_result: ' . print_r($inet_domain_info_result, true));

                            if ($inet_domain_info_result['success'] && !empty($inet_domain_info_result['data']['id'])) {
                                $inet_domain_id = $inet_domain_info_result['data']['id'];
                                update_post_meta($domain_post_id, 'inet_domain_id', $inet_domain_id);
                            }
                        }

                        if (!empty($inet_domain_id)) {
                            $renewal_result = $CDWFunc->inet->renew_domain($inet_domain_id, $period);
                            error_log('[func_update] customer-domain/renewal_result: ' . print_r($renewal_result, true));
                            if ($renewal_result['success']) {
                                cdw_create_customer_log($id, 'Gia hạn tên miền iNET thành công', 'Tên miền: ' . $domain_name . '. Thời gian: ' . $period . ' năm. Chi tiết: ' . json_encode($renewal_result));
                            } else {
                                cdw_create_customer_log($id, 'Gia hạn tên miền iNET thất bại', 'Tên miền: ' . $domain_name . '. Lỗi: ' . $renewal_result['msg']);
                            }
                        } else {
                            cdw_create_customer_log($id, 'Không tìm thấy tên miền trên iNET để gia hạn', 'Tên miền: ' . $domain_name . '. Lỗi: ' . ($inet_domain_info_result['msg'] ?? 'Không rõ'));
                        }
                        break;

                    case "customer-hosting":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                        // $note = "Gia hạn domain từ ngày " . $CDWFunc->date->convertDateTime($expiry_date, $CDWFunc->date->formatDB, $CDWFunc->date->format) . " tới ngày " . $CDWFunc->date->convertDateTime($expiry_date_new, $CDWFunc->date->formatDB, $CDWFunc->date->format);
                        // $CDWFunc->wpdb->save_history($item["id"], $id, 'Gia hạn hosting ', 'Hosting:' . get_post_meta($item["id"], 'ip', true) . ',' . get_post_meta($item["id"], 'port', true), 'success', $CDWFunc->date->getCurrentDateTime($CDWFunc->date->formatDB), $note);

                        break;


                    case "customer-email":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);

                        break;

                    case "customer-email-change":
                        $customer_email_id = $item['id'];
                        $new_plan_wp_id = $item['new_plan_id'];
                        $old_plan_wp_id = $item['old_plan_id'];
                        $customer_id_from_item = get_post_meta($customer_email_id, 'customer-id', true);

                        $inet_email_id = get_post_meta($customer_email_id, 'inet_email_id', true);
                        $new_inet_plan_id = get_post_meta($new_plan_wp_id, 'inet_plan_id', true);

                        if (empty($inet_email_id) || empty($new_inet_plan_id)) {
                            cdw_create_customer_log($customer_id_from_item, 'Đổi gói Email thất bại', 'Lỗi: Không tìm thấy thông tin iNET cần thiết để đổi gói.');
                            break;
                        }

                        // When changing plan after payment, period is always 1 as cost is already calculated.
                        $period = 1;
                        error_log('[cdw_process_successful_payment] customer-email-change payload: ' . print_r(['inet_email_id' => $inet_email_id, 'new_inet_plan_id' => $new_inet_plan_id, 'period' => $period], true));
                        $response = $CDWFunc->inet->change_email_plan($inet_email_id, $new_inet_plan_id, $period);
                        error_log('[cdw_process_successful_payment] customer-email-change response: ' . print_r($response, true));

                        if ($response['success']) {
                            update_post_meta($customer_email_id, 'email-type', $new_plan_wp_id);
                            update_post_meta($customer_email_id, 'inet_plan_id', $new_inet_plan_id);

                            $detail_response = $CDWFunc->inet->get_email_detail($inet_email_id);
                            if ($detail_response['success'] && isset($detail_response['data'])) {
                                do_action('cdw_save_inet_email_details', $customer_email_id, $detail_response['data']);
                            }

                            $old_plan_name = get_the_title($old_plan_wp_id);
                            $new_plan_name = get_the_title($new_plan_wp_id);
                            cdw_create_customer_log($customer_id_from_item, 'Đổi gói Email thành công', "Đã đổi từ gói '{$old_plan_name}' sang gói '{$new_plan_name}'.");
                        } else {
                            cdw_create_customer_log($customer_id_from_item, 'Đổi gói Email thất bại', 'Lỗi từ iNET: ' . ($response['msg'] ?? 'Không xác định'));
                        }
                        break;

                    case "customer-plugin":
                        $expiry_date = get_post_meta($item["id"], 'expiry_date', true);
                        $expiry_date_new = $CDWFunc->date->addYears($expiry_date, $item["quantity"], $CDWFunc->date->formatDB);
                        update_post_meta($item["id"], 'expiry_date', $expiry_date_new);
                        $license_code = get_post_meta($item["id"], 'license', true);
                        $license = get_license_by_code($license_code);
                        if (!empty($license)) {
                            update_post_meta($license->ID, '_expires_at', $expiry_date_new);
                        }
                        break;

                    case "manage-hosting":
                        //Hosting
                        $buy_date    = $CDWFunc->date->getCurrentDateTime();
                        $quantity    = (float) $item["quantity"];
                        $expiry_date = $CDWFunc->date->addYears($buy_date, 1, "Y-m-d H:i:s");

                        write_syslog(__METHOD__ . " manage-hosting => items " . print_r($item, true));

                        $ips     = $CDWFunc->directAdmin->listIPs();
                        $firstIp = $ips['list'][0] ?? null;

                        for ($i = 0; $i < $quantity; $i++) {
                            $password  = wp_generate_password();
                            $generate  = $CDWFunc->directAdmin->generateUniqueUsername($name);
                            $package   = get_post_meta($item["id"], 'package', true);
                            $domain    = $generate['domain'];
                            $user_name = $generate['username'];
                            $response  = $CDWFunc->directAdmin->createReseller($user_name, $email, $password, $domain, $package, ['ip' => $firstIp]);

                            $hostings = [
                                [
                                    'ip'          => $firstIp,
                                    'port'        => DA_DEFAULT_PORT,
                                    'user'        => $user_name,
                                    'pass'        => $password,
                                    'type'        => $item["id"],
                                    'price'       => (float) $item["price"],
                                    'audit'       => json_encode($response),
                                    'buy_date'    => $buy_date,
                                    'expiry_date' => $expiry_date,
                                ]
                            ];

                            write_syslog(__METHOD__ . " data hostings => insert data: $i " . print_r($hostings, true));

                            if (is_array($hostings) && count($hostings) > 0) {
                                $hostingColumns     = [
                                    'ip',
                                    'port',
                                    'user',
                                    'pass',
                                    'type',
                                    'price',
                                    'audit'
                                ];
                                $hostingColumnDates = ['buy_date', 'expiry_date'];
                                $hostings           = $CDWFunc->wpdb->func_new_detail_post('customer-hosting', 'customer-id', $id, $hostings, $hostingColumns);
                                $CDWFunc->wpdb->func_update_detail_post_type_date('customer-hosting', 'customer-id', $id, $hostings, $hostingColumnDates);
                            }
                        }
                        break;

                    case "manage-email":
                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $emails = [
                            [
                                'url_admin' => "",
                                'url_client' => "",
                                'user' => "",
                                'pass' => "",
                                'email-type' => $item["id"],
                                'price' => (float)$item["price"],
                                'buy_date' => $buy_date,
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                            ]
                        ];

                        if (is_array($emails) && count($emails) > 0) {
                            $emailColumns = ['url_admin', 'url_client', 'user', 'pass', 'email-type', 'price'];
                            $emailColumnDates = ['buy_date', 'expiry_date'];
                            $emails = $CDWFunc->wpdb->func_new_detail_post('customer-email', 'customer-id', $id, $emails, $emailColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-email', 'customer-id', $id, $emails, $emailColumnDates);
                        }
                        break;
                    case "manage-domain":
                        $domain_name_to_register = $item["service"];
                        $period_to_register = (int)$item["quantity"];
                        $customer_id_for_inet = $id;
                        $inet_domain_id_registered = null;
                        error_log('[func_update] manage-domain:' . print_r(['domain_name_to_register' => $domain_name_to_register, 'period_to_register' => $period_to_register], true));
                        $availability_check = $CDWFunc->inet->check_domain($domain_name_to_register);
                        error_log('[func_update] manage-domain/availability_check ' . print_r($availability_check, true));

                        if ($availability_check['success'] && $availability_check['data']['status'] == 'available') {
                            $registration_result = $CDWFunc->inet->create_domain($customer_id_for_inet, $domain_name_to_register, $period_to_register);
                            error_log('[func_update] manage-domain/registration_result ' . print_r($registration_result, true));
                            if ($registration_result['success']) {
                                cdw_create_customer_log($customer_id_for_inet, 'Đăng ký tên miền iNET thành công', 'Tên miền: ' . $domain_name_to_register . '. Thời gian: ' . $period_to_register . ' năm. Chi tiết: ' . json_encode($registration_result));
                                $inet_domain_id_registered = $registration_result['data']['id'] ?? null;
                                if ($inet_domain_id_registered) {
                                    $CDWFunc->inet->upload_documents_for_domain($inet_domain_id_registered, $customer_id_for_inet);
                                }
                            } else {
                                cdw_create_customer_log($customer_id_for_inet, 'Đăng ký tên miền iNET thất bại', 'Tên miền: ' . $domain_name_to_register . '. Lỗi: ' . $registration_result['msg']);
                            }
                        } else {
                            $error_msg = $availability_check['data']['status'] == 'error' ? $availability_check['data']['message'] : 'Tên miền ' . $domain_name_to_register . ' không có sẵn hoặc có lỗi khi kiểm tra';
                            cdw_create_customer_log($customer_id_for_inet, 'Đăng ký tên miền iNET thất bại', $error_msg);
                        }

                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $domains = [
                            [
                                'url_dns' => "",
                                'billing_id' => $idBilling,
                                'ip' => "",
                                'user' => "",
                                'pass' => "",
                                'url' => $item["service"],
                                'price' => (float)$item["price"],
                                'buy_date' => $buy_date,
                                'quantity' => $quantity,
                                'domain-type' => empty($item["id"]) ? "" : $item["id"],
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                                'note' => "",
                                'inet_domain_id' => $inet_domain_id_registered,
                            ]
                        ];

                        if (is_array($domains) && count($domains) > 0) {

                            $domainColumns = ['url', 'price', 'url_dns', 'ip', 'user', 'pass', 'note', 'domain-type', 'inet_domain_id', 'billing_id', 'quantity'];
                            $domainColumnDates = ['buy_date', 'expiry_date'];
                            $domains = $CDWFunc->wpdb->func_new_detail_post('customer-domain', 'customer-id', $id, $domains, $domainColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-domain', 'customer-id', $id, $domains, $domainColumnDates);
                        }

                        break;
                    case 'site-managers':

                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $themes = [
                            [
                                'name' =>  $item["service"],
                                'site-type' => empty($item["id"]) ? "" : $item["id"],
                                'price' => (float) $item["price"],
                                'date' => $buy_date
                            ]
                        ];

                        if (is_array($themes) && count($themes) > 0) {
                            $themeColumns = ['name', 'price', 'site-type'];
                            $themeColumnDates = ['date'];
                            $themes =  $CDWFunc->wpdb->func_new_detail_post('customer-theme', 'customer-id', $id, $themes, $themeColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-theme', 'customer-id', $id, $themes, $themeColumnDates);
                        }
                        break;
                    case 'manage-plugin':
                        $buy_date =  $CDWFunc->date->getCurrentDateTime();
                        $quantity = (float)$item["quantity"];
                        $license_id = cdw_create_license($item["id"], 'premium', $quantity, 'active', '', '', '', $id);
                        $plugins = [
                            [
                                'name' =>  $item["service"],
                                'plugin-type' => empty($item["id"]) ? "" : $item["id"],
                                'price' => (float) $item["price"],
                                'date' => $buy_date,
                                'expiry_date' => $CDWFunc->date->addYears($buy_date, $quantity, "Y-m-d H:i:s"),
                                'license' => cdw_get_license_code($license_id)
                            ]
                        ];

                        if (is_array($plugins) && count($plugins) > 0) {
                            $pluginColumns = ['name', 'price', 'plugin-type', 'license'];
                            $pluginColumnDates = ['date', 'expiry_date'];
                            $plugins =  $CDWFunc->wpdb->func_new_detail_post('customer-plugin', 'customer-id', $id, $plugins, $pluginColumns);
                            $CDWFunc->wpdb->func_update_detail_post_type_date('customer-plugin', 'customer-id', $id, $plugins, $pluginColumnDates);

                            $CDWEmail->sendEmailLicense($plugins[0]['id'], true);
                        }
                        break;
                }
            }
        update_post_meta($idBilling, 'is-update', true);
        error_log("cdw_process_successful_payment -> END processing billing ID: " . $idBilling . ". Marked as updated.");
    } else {
        error_log("cdw_process_successful_payment -> SKIPPED processing billing ID: " . $idBilling . ". Already updated.");
    }
}
