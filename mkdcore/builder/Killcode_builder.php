<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Killcode_builder extends Builder
{
    protected $_config;
    protected $_template;
    protected $_lang;
    protected $_file_path = 'src/system/core/IocContainer.php';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_lang = '';
        $this->_locale = $locale;
    }

    public function build()
    {
        if ($this->_config['has_license_key'])
        {
            $this->_template = file_get_contents('templates/source/license/Kill_code.php');
        }
        else
        {
            $this->_file_path = '';
            $this->_template = '';
        }
        return $this->_template;
    }
}