<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Affilate Middleware
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Affilate_middleware
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
        $refer_code = $this->_controller->input->get('affilate', TRUE);

        if ($refer_code && strlen($refer_code) > 0)
        {
            $this->_controller->set_session('refer', $refer_code);
        }

        return TRUE;
    }
}