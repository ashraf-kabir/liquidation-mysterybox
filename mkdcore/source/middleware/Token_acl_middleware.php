<?php
use function GuzzleHttp\json_encode;
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Token ACL Middleware
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Token_acl_middleware
{
    protected $_controller;
    protected $_ci;

    public $roles = array();

    public function __construct(&$controller, &$ci)
    {
        $this->_controller = $controller;
        $this->_ci = $ci;
    }

    /**
     * Steps:
     * 1.Get authorization header
     * 2.Validate it
     * 3.Check if it matches role
     * 4.Return error if not match
     *
     * @return void
     */
    public function run()
    {
        $condition = in_array($this->_controller->get_role_id(), $this->_controller->get_valid_role());

        if (!$condition)
        {
            $this->unauthorize_resource_error_message();
            return FALSE;
        }

        return TRUE;
    }

    private function unauthorize_resource_error_message()
    {
        http_response_code(406);
        echo json_encode([
            'code' => 406,
            'success' => FALSE,
            'message' => 'cannot access resource'
        ]);
        stop_execution();
    }
}