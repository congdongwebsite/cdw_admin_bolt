<?php
defined('ABSPATH') || exit;
class ConstantAdmin
{
    public $vatPercent = 10;
    public $limit_file_size = 5000000;
    public $preg_match_password = '/(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}/';
    public $timePing = 5;
    public function __construct()
    {
        add_action('cdw-header',  array($this, 'func_init_style'));
        add_action('cdw-footer',  array($this, 'func_init_script'));

        add_action('cdw-header-lock',  array($this, 'func_init_lock_style'));
        add_action('cdw-footer-lock',  array($this, 'func_init_lock_script'));
    }

    public function func_init_style()
    {
        wp_register_style('bootstrap', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap/css/bootstrap.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('font-awesome', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/font-awesome/css/font-awesome.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('datatables', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/datatables/datatables.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('sweetalert2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/sweetalert2/css/sweetalert2.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('bootstrap-datepicker', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.css', array(), CDW_VERSION, 'all');
        wp_register_style('bootstrap-datetimepicker', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css', array(), CDW_VERSION, 'all');
        wp_register_style('select2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/css/select2.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('select2-bootstrap', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/css/select2-bootstrap.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('summernote', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/summernote/dist/summernote.css', array(), CDW_VERSION, 'all');
        wp_register_style('parsley', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/css/parsley.css', array(), CDW_VERSION, 'all');
        wp_register_style('dropify', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/dropify/css/dropify.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('lightgallery', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/light-gallery/css/lightgallery.css', array(), CDW_VERSION, 'all');
        wp_register_style('main', ADMIN_CHILD_THEME_URL_F . '/assets/css/main.css', array(), CDW_VERSION, 'all');
        wp_register_style('site', ADMIN_CHILD_THEME_URL_F . '/assets/css/site.css', array(), CDW_VERSION, 'all');

        wp_print_styles('bootstrap');
        wp_print_styles('font-awesome');
        wp_print_styles('datatables');
        wp_print_styles('sweetalert2');
        wp_print_styles('bootstrap-datepicker');
        wp_print_styles('bootstrap-datetimepicker');
        wp_print_styles('select2');
        wp_print_styles('select2-bootstrap');
        wp_print_styles('summernote');
        wp_print_styles('parsley');
        wp_print_styles('dropify');
        wp_print_styles('lightgallery');
        wp_print_styles('main');
        wp_print_styles('site');
    }
    public function func_init_script()
    {
        global $userCurrent;
        wp_register_script('libscripts', ADMIN_CHILD_THEME_URL_F . '/assets/bundles/libscripts.bundle.js', ['jquery'], CDW_VERSION);
        wp_register_script('vendorscripts', ADMIN_CHILD_THEME_URL_F . '/assets/bundles/vendorscripts.bundle.js', ['jquery'], CDW_VERSION);
        wp_register_script('lodash', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/lodash/lodash.core.js', ['jquery'], CDW_VERSION);
        wp_register_script('accounting', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/accountingjs/accounting.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('moment', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/moment/moment.js', ['jquery'], CDW_VERSION);
        wp_register_script('datatables', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/datatables/datatables.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('sweetalert2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/sweetalert2/js/sweetalert2.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('bootstrap-datepicker', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('bootstrap-datepicker-locales-vi', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.vi.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('bootstrap-datetimepicker', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('select2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/js/select2.js', ['jquery'], CDW_VERSION);
        wp_register_script('select2-i18n-vi', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/select2/js/i18n/vi.js', ['jquery'], CDW_VERSION);
        wp_register_script('summernote', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/summernote/dist/summernote.js', ['jquery'], CDW_VERSION);
        wp_register_script('autoNumeric', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/autoNumeric/autoNumeric.js', ['jquery'], CDW_VERSION);
        wp_register_script('parsley', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/js/parsley.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('parsley-i18n-vi', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/js/i18n/vi.js', ['jquery'], CDW_VERSION);
        wp_register_script('dropify', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/dropify/js/dropify.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('light-gallery', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/light-gallery/js/lightgallery-all.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('light-gallery-fullscreem', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/light-gallery/js/lg-fullscreen.js', ['jquery'], CDW_VERSION);
        wp_register_script('printThis', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/print-this/printThis.js', ['jquery'], CDW_VERSION);
        wp_register_script('crypto', ADMIN_CHILD_THEME_URL_F . '/assets/js/crypto-js.js', ['jquery'], CDW_VERSION);
        wp_register_script('encryption', ADMIN_CHILD_THEME_URL_F . '/assets/js/encryption.js', ['jquery'], CDW_VERSION);
        wp_register_script('mainscripts', ADMIN_CHILD_THEME_URL_F . '/assets/bundles/mainscripts.bundle.js', ['jquery'], CDW_VERSION);
        wp_register_script('base-init-detail', ADMIN_CHILD_THEME_URL_F . '/assets/js/base-init-detail.js', ['jquery'], CDW_VERSION);
        wp_register_script('base-index-post-type', ADMIN_CHILD_THEME_URL_F . '/assets/js/base-index-post-type.js', ['jquery'], CDW_VERSION);
        wp_register_script('base-new-post-type', ADMIN_CHILD_THEME_URL_F . '/assets/js/base-new-post-type.js', ['jquery'], CDW_VERSION);
        wp_register_script('base-detail-post-type', ADMIN_CHILD_THEME_URL_F . '/assets/js/base-detail-post-type.js', ['jquery'], CDW_VERSION);
        wp_register_script('base-report', ADMIN_CHILD_THEME_URL_F . '/assets/js/base-report.js', ['jquery'], CDW_VERSION);
        wp_register_script('initSelect2', ADMIN_CHILD_THEME_URL_F . '/assets/js/initSelect2.js', ['jquery'], CDW_VERSION);
        wp_register_script('site', ADMIN_CHILD_THEME_URL_F . '/assets/js/site.js', ['jquery'], CDW_VERSION);
        wp_register_script('index-default', ADMIN_CHILD_THEME_URL_F . '/assets/js/index-default.js', ['jquery'], CDW_VERSION);

        foreach ($userCurrent->roles as $role) {
            if (file_exists(ADMIN_THEME_URL . "/assets/js/index-" . $role . ".js")) {
                wp_register_script('index-' . $role, ADMIN_CHILD_THEME_URL_F . '/assets/js/index-' . $role . '.js', ['jquery'], CDW_VERSION);
                break;
            } else {
                wp_register_script('index', ADMIN_CHILD_THEME_URL_F . '/assets/js/index.js', ['jquery'], CDW_VERSION);
            }
        }

        if (count($_GET) == 0) {
            foreach ($userCurrent->roles as $role) {
                if (file_exists(ADMIN_THEME_URL . "/assets/js/page-" . $role . ".js")) {
                    wp_register_script('page-' . $role, ADMIN_CHILD_THEME_URL_F . '/assets/js/page-' . $role . '.js', ['jquery'], CDW_VERSION);
                    break;
                } else {
                    wp_register_script('page', ADMIN_CHILD_THEME_URL_F . '/assets/js/page.js', ['jquery'], CDW_VERSION);
                }
            }
        }
        $array = array(
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-index-nonce'),
            'sitekey'       => RECAPTCHA_SITE,
            'ping' => $this->timePing
        );

        $decimalSymbol = ".";
        $decimalCharacterAlternative = ".";
        $thousandsSymbol = ",";
        $roundPacking = 0;
        $roundQuantity = 2;
        $roundPercent = 2;
        $roundAmount = 2;
        $roundAmountVND = 0;
        $formatDate = "DD/MM/YYYY";
        $formatDateBilling = "yyyy-MM-DD HH:mm:ss";
        $formatDateDatepicker = "dd/mm/yyyy";
        $swalOptions = (object) [
            "timer" => 1000,
            "closeOnClickOutside" => true,
            "closeOnEsc" => true,
        ];

        $dateFormat = ["YYYY-MM-DD", "YYYY-MM-DDThh:mm:ss", $formatDate];
        $siteSettings = [
            "decimalSymbol" => $decimalSymbol,
            "thousandsSymbol" => $thousandsSymbol,
            "roundPacking" => $roundPacking,
            "roundPercent" => $roundPercent,
            "roundQuantity" => $roundQuantity,
            "roundAmount" => $roundAmount,
            "roundAmountVND" => $roundAmountVND,
            "formatDate" => $formatDate,
            "formatDateBilling" => $formatDateBilling,
            "formatDateDatepicker" => $formatDateDatepicker,
            "OptionAutoNumericPacking" => [
                "decimalCharacter" => $decimalSymbol,
                "decimalCharacterAlternative" => $decimalCharacterAlternative,
                "digitGroupSeparator" => $thousandsSymbol,
                "decimalPlaces" => $roundPacking,
                "decimalPlacesRawValue" => $roundPacking,
                "decimalPlacesShownOnBlur" => $roundPacking,
                "decimalPlacesShownOnFocus" => $roundPacking,
                "emptyInputBehavior" => "zero",
            ],
            "OptionAutoNumericQuantity" => [
                "decimalCharacter" => $decimalSymbol,
                "decimalCharacterAlternative" => $decimalCharacterAlternative,
                "digitGroupSeparator" => $thousandsSymbol,
                "decimalPlaces" => $roundQuantity,
                "decimalPlacesRawValue" => $roundQuantity,
                "decimalPlacesShownOnBlur" => $roundQuantity,
                "decimalPlacesShownOnFocus" => $roundQuantity,
                "emptyInputBehavior" => "zero",
            ],
            "OptionAutoNumericPercent" => [
                "decimalCharacter" => $decimalSymbol,
                "decimalCharacterAlternative" => $decimalCharacterAlternative,
                "digitGroupSeparator" => $thousandsSymbol,
                "decimalPlaces" => $roundQuantity,
                "decimalPlacesRawValue" => $roundPercent,
                "decimalPlacesShownOnBlur" => $roundPercent,
                "decimalPlacesShownOnFocus" => $roundPercent,
                "emptyInputBehavior" => "zero",
            ],
            "OptionAutoNumericAmount" => [
                "decimalCharacter" => $decimalSymbol,
                "decimalCharacterAlternative" => $decimalCharacterAlternative,
                "digitGroupSeparator" => $thousandsSymbol,
                "decimalPlaces" => $roundAmount,
                "decimalPlacesRawValue" => $roundAmount,
                "decimalPlacesShownOnBlur" => $roundAmount,
                "decimalPlacesShownOnFocus" => $roundAmount,
                "emptyInputBehavior" => "zero",
            ],
            "OptionAutoNumericAmountVND" => [
                "decimalCharacter" => $decimalSymbol,
                "decimalCharacterAlternative" => $decimalCharacterAlternative,
                "digitGroupSeparator" => $thousandsSymbol,
                "decimalPlaces" => $roundAmountVND,
                "decimalPlacesRawValue" => $roundAmountVND,
                "decimalPlacesShownOnBlur" => $roundAmountVND,
                "decimalPlacesShownOnFocus" => $roundAmountVND,
                "emptyInputBehavior" => "zero",
            ],
            "swal" => [
                "timer" => $swalOptions->timer,
                "closeOnClickOutside" => $swalOptions->closeOnClickOutside,
                "closeOnEsc" => $swalOptions->closeOnEsc,
            ],
        ];
        wp_localize_script('libscripts', 'objAdmin', $array);
        wp_localize_script('libscripts', 'dateFormat', $dateFormat);
        wp_localize_script('libscripts', 'siteSettings', $siteSettings);
        wp_print_scripts("user-profile");
        wp_print_scripts('libscripts');
        wp_print_scripts('vendorscripts');
        wp_print_scripts('lodash');
        wp_print_scripts('accounting');
        wp_print_scripts('moment');
        wp_print_scripts('datatables');
        wp_print_scripts('sweetalert2');
        wp_print_scripts('bootstrap-datepicker');
        wp_print_scripts('bootstrap-datepicker-locales-vi');
        wp_print_scripts('bootstrap-datetimepicker');
        wp_print_scripts('select2');
        wp_print_scripts('select2-i18n-vi');
        wp_print_scripts('summernote');
        wp_print_scripts('autoNumeric');
        wp_print_scripts('parsley');
        wp_print_scripts('parsley-i18n-vi');
        wp_print_scripts('dropify');
        wp_print_scripts('light-gallery');
        wp_print_scripts('light-gallery-fullscreem');
        wp_print_scripts('printThis');
        wp_print_scripts('crypto');
        wp_print_scripts('encryption');
        wp_print_scripts('mainscripts');
        wp_print_scripts('base-init-detail');
        wp_print_scripts('base-index-post-type');
        wp_print_scripts('base-new-post-type');
        wp_print_scripts('base-detail-post-type');
        wp_print_scripts('base-report');
        wp_print_scripts('initSelect2');
        wp_print_scripts('site');
        wp_print_scripts('index-default');

        foreach ($userCurrent->roles as $role) {
            if (file_exists(ADMIN_THEME_URL . "/assets/js/index-" . $role . ".js")) {
                wp_print_scripts('index-' . $role);
                break;
            } else {
                wp_print_scripts('index');
            }
        }

        if (count($_GET) == 0) {
            foreach ($userCurrent->roles as $role) {
                if (file_exists(ADMIN_THEME_URL . "/assets/js/page-" . $role . ".js")) {
                    wp_print_scripts('page-' . $role);
                    break;
                } else {
                    wp_print_scripts('page');
                }
            }
        }
    }

    public function func_init_lock_style()
    {
        wp_register_style('bootstrap', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/bootstrap/css/bootstrap.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('font-awesome', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/font-awesome/css/font-awesome.min.css', array(), CDW_VERSION, 'all');
        wp_register_style('sweetalert2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/sweetalert2/css/sweetalert2.bundle.css', array(), CDW_VERSION, 'all');
        wp_register_style('parsley', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/css/parsley.css', array(), CDW_VERSION, 'all');
        wp_register_style('main', ADMIN_CHILD_THEME_URL_F . '/assets/css/main.css', array(), CDW_VERSION, 'all');

        wp_print_styles('bootstrap');
        wp_print_styles('font-awesome');
        wp_print_styles('sweetalert2');
        wp_print_styles('parsley');
        wp_print_styles('main');
    }
    public function func_init_lock_script()
    {
        global $userCurrent;
        wp_register_script('libscripts', ADMIN_CHILD_THEME_URL_F . '/assets/bundles/libscripts.bundle.js', ['jquery'], CDW_VERSION);
        wp_register_script('moment', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/moment/moment.js', ['jquery'], CDW_VERSION);
        wp_register_script('sweetalert2', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/sweetalert2/js/sweetalert2.bundle.js', ['jquery'], CDW_VERSION);
        wp_register_script('parsley', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/js/parsley.min.js', ['jquery'], CDW_VERSION);
        wp_register_script('parsley-i18n-vi', ADMIN_CHILD_THEME_URL_F . '/assets/vendor/parsleyjs/js/i18n/vi.js', ['jquery'], CDW_VERSION);
        wp_register_script('crypto', ADMIN_CHILD_THEME_URL_F . '/assets/js/crypto-js.js', ['jquery'], CDW_VERSION);
        wp_register_script('encryption', ADMIN_CHILD_THEME_URL_F . '/assets/js/encryption.js', ['jquery'], CDW_VERSION);
        wp_register_script('site-lock', ADMIN_CHILD_THEME_URL_F . '/assets/js/site-lock.js', ['jquery'], CDW_VERSION);

        foreach ($userCurrent->roles as $role) {
            if (file_exists(ADMIN_THEME_URL . "/assets/js/index-" . $role . ".js")) {
                wp_register_script('index-' . $role, ADMIN_CHILD_THEME_URL_F . '/assets/js/index-' . $role . '.js', ['jquery'], CDW_VERSION);
                break;
            } else {
                wp_register_script('index', ADMIN_CHILD_THEME_URL_F . '/assets/js/index.js', ['jquery'], CDW_VERSION);
            }
        }

        if (count($_GET) == 0) {
            foreach ($userCurrent->roles as $role) {
                if (file_exists(ADMIN_THEME_URL . "/assets/js/page-" . $role . ".js")) {
                    wp_register_script('page-' . $role, ADMIN_CHILD_THEME_URL_F . '/assets/js/page-' . $role . '.js', ['jquery'], CDW_VERSION);
                    break;
                } else {
                    wp_register_script('page', ADMIN_CHILD_THEME_URL_F . '/assets/js/page.js', ['jquery'], CDW_VERSION);
                }
            }
        }
        $array = array(
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-index-nonce'),
            'sitekey'       => RECAPTCHA_SITE,
        );
        wp_localize_script('libscripts', 'objAdmin', $array);
        wp_print_scripts('libscripts');
        wp_print_scripts('moment');
        wp_print_scripts('sweetalert2');
        wp_print_scripts('parsley');
        wp_print_scripts('parsley-i18n-vi');
        wp_print_scripts('crypto');
        wp_print_scripts('encryption');
        wp_print_scripts('site-lock');
    }
}
