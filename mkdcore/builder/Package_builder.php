<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Package_builder extends Builder
{
    protected $_config;
    protected $_render_list = [];
    protected $_routes = [];
    protected $_file_path = '';
    protected $_template = '';
    protected $_locale = null;

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_package ($package)
    {
        $this->_packages = $package;
    }

    public function get_routes ()
    {
        $routes = [];
        foreach ($this->_packages as $package_type => $allow)
        {
            if(is_array($this->_packages[$package_type]) && !empty($this->_packages[$package_type]) && $this->_packages[$package_type]['is_active'] === FALSE)
            {
                continue;
            }

            if ($allow)
            {
                switch ($package_type)
                {
                    case 'voice':
                        $routes['v1/api/voice/gather'] = 'Guest/Voice_controller/gather';
                        $routes['v1/api/voice/call/message/(:num)'] = 'Guest/Voice_controller/get_call_message/$1';
                        $routes['v1/api/voice/call/play/(:num)'] = 'Guest/Voice_controller/get_call_play_message/$1';
                        $routes['v1/api/voice/call/custom/(:num)'] = 'Guest/Voice_controller/get_custom_call_play_message/$1';
                        $routes['v1/api/voice/call/response/(:num)'] = 'Guest/Voice_controller/call_callback/$1';
                        $routes['v1/api/sms/reply'] = 'Guest/Voice_controller/sms_callback';
                        $routes['v1/api/sms/callback/(:num)'] = 'Guest/Voice_controller/sms_reply/$1';
                        $routes['v1/api/sms/replyfail'] = 'Guest/Voice_controller/sms_callback_fail';
                        $routes['v1/api/voice/call/log'] = 'Guest/Voice_controller/get_call_logs';
                    break;
                }
            }
        }
        return $routes;
    }

    public function build()
    {
        foreach ($this->_packages as $package_type => $allow)
        {
            if ($allow)
            {
                if(is_array($this->_packages[$package_type]) && !empty($this->_packages[$package_type]) && $this->_packages[$package_type]['is_active'] === FALSE)
                {
                    continue;
                }

                switch ($package_type)
                {
                    case 'pdf':
                        $template = file_get_contents('../mkdcore/source/pdf/Pdf_service.php');
                        $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                        file_put_contents('../release/application/libraries/Pdf_service.php', $template);
                        break;
                    case 'voice':
                        $template = file_get_contents('../mkdcore/source/voice/Voice_service.php');
                        $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                        file_put_contents('../release/application/libraries/Voice_service.php', $template);

                        $template = file_get_contents('../mkdcore/source/voice/Voice_controller.php');
                        $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                        file_put_contents('../release/application/controllers/Guest/Voice_controller.php', $template);
                    case 'payment':
                        $template = file_get_contents('../mkdcore/source/payment/stripe_client.js.php');
                        $template = $this->inject_substitute($template, 'stripe_publish_key', $this->_config['stripe_publish_key']);
                        file_put_contents('../release/assets/js/stripe_client.js', $template);
                    break;
                    default:
                        # code...
                        break;
                }
            }
        }
    }

    public function destroy()
    {
        $destroy_list = [
            '../release/application/libraries/Pdf_service.php',
            '../release/application/libraries/Voice_service.php',
            '../release/application/controllers/Guest/Voice_controller.php'
        ];

        foreach ($destroy_list as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }
    }
}