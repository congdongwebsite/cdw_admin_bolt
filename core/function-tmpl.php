<?php
defined('ABSPATH') || exit;
class FunctionTMPL
{
    public function __construct()
    {
    }

    public function initTemplate($names)
    {
        if (!is_array($names)) $names = [$names];
        foreach ($names as $name) {
            $html =  $this->get_templete_content($name);
            add_action('cdw-footer-template',  function () use ($html) {
                echo $html;
            });
        }
    }


    public function get_templete_content($filename)
    {
        ob_start();
        require_once ADMIN_THEME_URL . '/templates/tmpl/' . $filename . '.php';
        $data = ob_get_clean();
        return  $data;
    }
}
