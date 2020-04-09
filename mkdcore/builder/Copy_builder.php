<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Copy_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_copys = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_copy($copys)
    {
        $this->_copys = $copys;
    }

    public function build()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        foreach ($this->_copys as $key => $value)
        {
            $template = file_get_contents($key);

            foreach ($this->_config as $config_key => $config_value)
            {
                if (!is_array($config_value))
                {
                    $template = $this->inject_substitute($template, $config_key, $config_value);
                }
            }

            file_put_contents($value, $template);
        }
    }

    public function destroy ()
    {
        foreach ($this->_copys as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }
    }
}