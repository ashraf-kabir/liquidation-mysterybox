<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Maintenance Middleware
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Maintenance_middleware
{
    protected $_controller;
    protected $_ci;

    public $roles = array();

    public function __construct(&$controller, &$ci)
    {
        $this->_controller = $controller;
        $this->_ci = $ci;
    }

    public function run()
    {
        $setting = $this->_controller->get_setting();
        $condition = (isset($setting) && isset($setting['maintenance']) && $setting['maintenance'] == 1);

        if ($condition)
        {
            header( '503 Service Unavailable', TRUE, 503 );
            stop_execution();
            return FALSE;
        }

        return TRUE;
    }
}