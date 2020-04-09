<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Lang_builder extends Builder
{
    protected $_config;
    protected $_template;
    protected $_translations;
    protected $_lang;
    protected $_file_path = '../release/application/language/';

    public function __construct($config)
    {
        $this->_config = $config;
        $this->_translations = [];
        $this->_lang = '';
        $this->_template = '';
    }

    public function set_translation($translations)
    {
        $this->_translations = $translations;
    }

    public function set_language($lang)
    {
        $this->_lang = $lang;
    }

    public function build()
    {
        $this->_template = file_get_contents('../mkdcore/source/custom_lang.php');

        foreach ($this->_translations as $key => $value)
        {
            $this->_template .= "\n\$lang[$key]		= '$value';";
        }

        return $this->_template;
    }

    public function inject_template ()
    {
        file_put_contents($this->_file_path . $this->_lang . '/custom_lang.php', $this->_template);
    }

    public function destroy ()
    {
        if (file_exists($this->_file_path . $this->_lang . '/custom_lang.php'))
        {
            unlink($this->_file_path . $this->_lang . '/custom_lang.php');
        }
    }
}