<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class User_module_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_routes = [];
    protected $_models = [];
    protected $_controllers = [];
    protected $_packages = [];
    protected $_migration = [];
    protected $_services = [];
    protected $_factory = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_models = [
        ];
        $this->_services = [
            '../release/application/services/User_service.php'
        ];
        $this->_factory = [
            '../release/application/factories/User_factory.php'
        ];

        $this->_template = '';
        $this->_locale = $locale;
    }

    public function build()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        foreach ($this->_models as $key => $value)
        {
            $template = file_get_contents(str_replace('../release/application/models/', '../mkdcore/source/auth/', $value));
            file_put_contents($value, $template);
        }

        foreach ($this->_services as $key => $value)
        {
            $template = file_get_contents(str_replace('../release/application/services/', '../mkdcore/source/auth/', $value));
            file_put_contents($value, $template);
        }

        foreach ($this->_factory as $key => $value)
        {
            $template = file_get_contents(str_replace('../release/application/factories/', '../mkdcore/source/auth/', $value));
            file_put_contents($value, $template);
        }
    }

    public function destroy ()
    {
        foreach ($this->_models as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }

        foreach ($this->_services as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }

        foreach ($this->_factory as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }
    }
}