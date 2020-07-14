<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * ACL Middleware
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Subscription_middleware
{

    private $_controller;
    private $_ci;
    
    public function __construct(&$controller, &$ci)
    {
        $this->_controller = $controller;
        $this->_ci = $ci;
        $this->_controller->load->database();
    }

    public function run()
    {
        $session = $this->_controller->get_session();
        die('middleware running');

        if(!empty($session))
        {
            $user_id = $session['user_id'];
            $role_id = $session['role'];
        }

        return FALSE;
    }

}