<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../services/Token_service.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Token Middleware
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Token_middleware
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
        $token_service = new Token_service();
        $key = $this->_ci->item('jwt_key');

        $jwt_token = $this->_get_bearer_token();

        if (strlen($jwt_token) < 1)
        {
            $this->unauthorize_error_message();
        }

        $result = $token_service->validate_token ($key, $this->_get_bearer_token());

        if ($result)
        {
            $this->_controller->set_user_id($result->user_id);
            $this->_controller->set_role_id($result->role_id);
            return TRUE;
        }

        $this->unauthorize_error_message();
        return FALSE;
    }

    private function _get_bearer_token()
    {
        $bearer_token = '';

        $bearer_token_header = $this->_controller->input->get_request_header('Authorization');
        $bearer_token_get = $this->_controller->input->get('Authorization');

        if (strlen($bearer_token_header) < 1)
        {
            if (strlen($bearer_token_get) < 1)
            {
                $lower_case_bearer_token_header = $this->_controller->input->get_request_header('authorization');
                $lower_case_bearer_token_get = $this->_controller->input->get('authorization');
                if (strlen($lower_case_bearer_token_header) < 1)
                {
                    if (strlen($lower_case_bearer_token_get) > 1)
                    {
                        $bearer_token = $lower_case_bearer_token_get;
                    }
                }
                else
                {
                    $bearer_token = $lower_case_bearer_token_header;
                }
            }
            else
            {
                $bearer_token = $bearer_token_get;
            }
        }
        else
        {
            $bearer_token = $bearer_token_header;
        }

        if (strpos($bearer_token, 'Bearer ') !== 0)
        {
            return '';
        }
        else
        {
            return str_replace('Bearer ', '', $bearer_token);
        }
    }

    private function unauthorize_error_message()
    {
        http_response_code(401);
        echo json_encode([
            'code' => 401,
            'success' => FALSE,
            'message' => 'xyzinvalid credentials'
        ]);
        stop_execution();
    }
}