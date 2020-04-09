<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Auth Middleware
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Auth_middleware
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
        $session = $this->_controller->get_session();

        $logged_in = empty($session) || ! isset($session['user_id']) || ! isset($session['email']);

        if ($logged_in)
        {
            $this->_controller->destroy_session();
            $this->_controller->load->helper('cookie');
            $cookie = [
                'name'   => 'redirect',
                'value'  => '/' . uri_string(),
                'expire' => '60',
                'secure' => FALSE
            ];
            set_cookie($cookie);
            return $this->_controller->redirect('/', 'refresh');
        }

        return TRUE;
    }
}