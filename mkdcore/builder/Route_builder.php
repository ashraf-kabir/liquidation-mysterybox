<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Route_builder extends Builder
{
    protected $_config;
    protected $_render_list = [];
    protected $_routes = [];
    protected $_file_path = 'src/application/config/routes.php';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_route($routes)
    {
        $this->_routes = $routes;
    }

    public function build()
    {
        $this->_template = file_get_contents('templates/source/core/routes.php');
        $result = '';

        foreach ($this->_routes as $key => $value)
        {
            $result .= "\$route['{$key}'] = '{$value}';\n";
        }

        $this->_template = $this->inject_substitute($this->_template, 'routes', $result);
        return $this->_template;
    }
}