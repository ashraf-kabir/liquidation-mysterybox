<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once __DIR__ . '/../../services/Token_service.php';
include_once '{{{ucname}}}_api_auth_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Register API Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_register_api_controller extends {{{ucname}}}_api_auth_controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index ()
	{
        $this->load->model('{{{model}}}');
        $this->load->model('token_model');
        $this->load->model('credential_model');

        $service = new User_service($this->credential_model, $this->{{{model}}});
        $token_service = new Token_service();
        $token_service->set_model($this->token_model);

        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'xyzPassword', 'trim|required');
        $this->form_validation->set_rules('first_name', 'xyzFirst Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'xyzLast Name', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->_render_validation_error();
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');

        $created_user = $service->create($email, $password, $first_name, $last_name, {{{role}}});

        if (!$created_user)
        {
            return $this->render([
                'email' => 'xyzUser creation failed. Please try again.'
            ]);
        }

        $key = $this->config->item('jwt_key');
        $base_url = $this->config->item('base_url');
        $jwt_expire_at = $this->config->item('jwt_expire_at');
        $jwt_refresh_expire_at = $this->config->item('jwt_refresh_expire_at');
        $access_token = $token_service->generate_access_token($key, $base_url, $jwt_expire_at, [
            'user_id' => $created_user->id,
            'role_id' => $created_user->role_id
        ]);
        $refresh_token = $token_service->generate_refresh_token($created_user->id, $created_user->role_id, $jwt_refresh_expire_at);

        return $this->success([
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'expire_in' => 14400
        ], 200);
    }
}