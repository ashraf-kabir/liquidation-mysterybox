<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Config_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_lang;
    protected $_file_path = 'src/application/config/config.php';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_dynamic_config = [];
        $this->_template = '';
        $this->_lang = '';
        $this->_locale = $locale;
    }

    public function add_dynamic_config($config)
    {
        foreach ($config as $key => $value)
        {
            $this->_config['dynamic_config'][$key] = $value;
        }
    }

    public function set_language($lang)
    {
        $this->_lang = $lang;
    }

    public function build()
    {
        $this->_template = file_get_contents('templates/source/core/config.php');

        if (strlen($this->_lang) > 0)
        {
            $this->_template = str_replace('english', $this->_lang, $this->_template);
        }

        foreach ($this->_config as $key => $value)
        {
            if (!is_array($value))
            {
                $this->_template = $this->inject_substitute($this->_template, $key, $value);
            }
            else
            {
                if ($key == 'dynamic_config')
                {
                    $dynamic_config_text = '';
                    foreach ($value as $dynamic_config_key => $dynamic_config_value)
                    {
                        $dynamic_config_text .= "\$config['$dynamic_config_key'] = '$dynamic_config_value';\n";
                    }
                    $this->_template = $this->_template . "\n" . $dynamic_config_text;
                }
                else if ($this->is_object_or_array($value))
                {
                    $this->_template = $this->inject_array($this->_template, $key, $value);
                }
                else
                {
                    foreach ($value as $key2 => $value2)
                    {
                        $this->_template = $this->inject_substitute($this->_template, $key2, $value2);
                    }
                }
            }
        }

        return $this->_template;
    }
}