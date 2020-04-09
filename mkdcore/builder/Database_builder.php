<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Database_builder extends Builder
{
    protected $_config;
    protected $_template;
    protected $_lang;
    protected $_file_path = 'src/application/config/database.php';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_lang = '';
        $this->_locale = $locale;
    }

    public function build()
    {
        $this->_template = file_get_contents('templates/source/core/database.php');
        foreach ($this->_config as $key => $value)
        {
            if (!is_array($value))
            {
                $this->_template = $this->inject_substitute($this->_template, $key, $value);
            }
        }

        return $this->_template;
    }
}