<?php
defined('ABSPATH') || exit;
class FunctionRecaptcha
{
    private $url_api = 'https://www.google.com/recaptcha/api/siteverify';
    private $url_onload = 'https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit';
    public function __construct()
    {
    }

    public function init()
    {
        add_action('cdw-footer',  array($this, 'func_init_script'));
        add_action('cdw-footer-lock',  array($this, 'func_init_script'));
    }
    public function func_init_script()
    {
        wp_register_script('google-recaptcha', $this->url_onload, ['jquery'], CDW_VERSION, true);
        wp_register_script('cdw-recaptcha', ADMIN_CHILD_THEME_URL_F . '/assets/js/g-recaptcha.js', ['jquery'], CDW_VERSION);

        wp_print_scripts('cdw-recaptcha');
        wp_print_scripts('google-recaptcha');
    }
    public function printItem($id)
    {
?>
        <div id="g-<?php echo $id; ?>" data-name="g-<?php echo $id; ?>" class="g-recaptcha"></div>
<?php
    }

    public function check($recaptchaResponse)
    {
        $data = array(
            'secret' => RECAPTCHA_SECRET,
            'response' => $recaptchaResponse
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($this->url_api, false, $context);

        if ($result !== false) {
            $response = json_decode($result, true);
            if ($response['success'] == true) {
                // reCAPTCHA hợp lệ
                return true;
            } else {
                // reCAPTCHA không hợp lệ
                return false;
            }
        } else {
            // Lỗi khi gửi yêu cầu kiểm tra reCAPTCHA
            return false;
        }
    }
    public function checkPost($key = 'g-recaptcha')
    {
        $recaptcha = $_POST['g-' . $key];
        if (!empty($recaptcha)) {
            if (!$this->check($recaptcha)) {
                wp_send_json_error(['msg' => 'reCAPTCHA không chính xác']);
                wp_die();
            }
        } else {
            wp_send_json_error(['msg' => 'Vui lòng hoàn thành reCAPTCHA']);
            wp_die();
        }
    }
}
