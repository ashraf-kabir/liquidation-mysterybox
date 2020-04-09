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
class Acl_middleware
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

        $user_id = isset($session['user_id']) ? $session['user_id'] : 0;
        $email = isset($session['email']) ? $session['email'] : '';
        $role = isset($session['role']) ? $session['role'] : NULL;

        $condition = ($role != NULL) && in_array((int)$role, $this->_controller->_valid_roles) &&
         ($user_id > 0) && (strlen($email) > 0);

        if (!$condition)
        {
            $this->_controller->destroy_session();
            return $this->_controller->redirect('/', 'refresh');
        }

        return TRUE;
    }
}