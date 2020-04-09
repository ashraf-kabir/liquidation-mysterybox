<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Cronjob_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_cronjob = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_cronjob($cronjobs)
    {
        $this->_cronjob = $cronjobs;
    }

    public function build()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        foreach ($this->_cronjob as $key => $value)
        {
            $template = file_get_contents("../mkdcore/source/cronjob/$key");
            file_put_contents("../release/application/controllers/Cli/$value", $template);
        }
    }

    public function destroy ()
    {
        foreach ($this->_cronjob as $key => $value)
        {
            if (file_exists("../release/application/controllers/Cli/$value"))
            {
                unlink("../release/application/controllers/Cli/$value");
            }
        }
    }
}