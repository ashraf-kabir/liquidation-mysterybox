<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * License Middleware
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class License_middleware
{
    protected $_controller;
    protected $_ci;

    private $_license_key = '{{{license_key}}}';

    public function __construct(&$controller, &$ci)
    {
        $this->_controller = $controller;
        $this->_ci = $ci;
    }

    public function run()
    {
        $license_key = $this->_controller->config->item('license_key');

        if (strlen($this->_license_key) < 1 || !$license_key || $license_key != $this->_license_key)
        {
            stop_execution();
            return FALSE;
        }

        return TRUE;
    }
}