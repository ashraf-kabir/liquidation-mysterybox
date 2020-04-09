<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once '{{{ucname}}}_api_auth_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Reset API Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_reset_api_controller extends {{{ucname}}}_api_auth_controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index ($reset_token)
	{
        $this->load->model('{{{model}}}');
        $this->load->model('token_model');
        $this->load->model('credential_model');

        $service = new User_service($this->credential_model);
        $service->set_token_model($this->token_model);

        $valid_user = $service->valid_reset_token($reset_token);

        if (!$valid_user)
        {
            return $this->_render_custom_error([
                'token' => 'xyzInvalid reset token to reset password.'
            ]);
        }

        $this->_data['reset_token'] = $reset_token;

        $this->form_validation->set_rules('password', 'xyzPassword', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->_render_validation_error();
        }

        $password = $this->input->post('password');
        $password_reseted = $service->reset_password($valid_user->id, $password);

        if ($password_reseted)
        {
            $service->invalidate_token($reset_token, $valid_user->id);
            return $this->success([
                'message' => 'xyzYour password was reset. Try to login now'
            ]);
        }

        return $this->_render_custom_error([
            'token' => 'xyzInvalid reset token to reset password.'
        ]);
    }
}