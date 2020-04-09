<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once __DIR__ . '/../../services/Token_service.php';
include_once '{{{ucname}}}_api_auth_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Login API Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_login_api_controller extends {{{ucname}}}_api_auth_controller
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
        $service = new User_service($this->credential_model);
        $token_service = new Token_service();
        $token_service->set_model($this->token_model);

        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'xyzPassword', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->_render_validation_error();
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role = $this->_valid_roles[0];
        $authenticated_user = $service->login_by_role($email, $password, $role);

        if ($authenticated_user)
        {
            $user_obj = $this->{{{model}}}->get_user_by_credential_id($authenticated_user->id);
            $key = $this->config->item('jwt_key');
            $base_url = $this->config->item('base_url');
            $jwt_expire_at = $this->config->item('jwt_expire_at');
            $jwt_refresh_expire_at = $this->config->item('jwt_refresh_expire_at');
            $access_token = $token_service->generate_access_token($key, $base_url, $jwt_expire_at, [
                'user_id' =>$user_obj->id ?? 0,
                'role_id' => $authenticated_user->role_id ?? 0
            ]);
            $refresh_token = $token_service->generate_refresh_token($authenticated_user->id, $authenticated_user->role_id, $jwt_refresh_expire_at);

            return $this->success([
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'expire_in' => 14400
            ], 200);
            exit();
        }

        return $this->_render_custom_error([
            'email' => 'xyzWrong email or password.'
        ]);
        exit();
    }

    public function token ()
	{
        $this->load->model('{{{model}}}');
        $this->load->model('token_model');

        $service = new User_service($this->{{{model}}});
        $token_service = new Token_service();
        $token_service->set_model($this->token_model);

        $this->form_validation->set_rules('token', 'xyzToken', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->_render_validation_error();
        }

        $token = $this->input->post('token');

        $exist = $this->token_model->get_by_fields([
            'token' => $token,
            'type' => 2
        ]);

        if ($exist)
        {
            $expired_time = strtotime($exist->expire_at);
            $now = time();
            if ($now < $expired_time)
            {
                $key = $this->config->item('jwt_key');
                $base_url = $this->config->item('base_url');
                $jwt_expire_at = $this->config->item('jwt_expire_at');
                $data = json_decode($exist->data, TRUE);

                $this->token_model->real_delete_by_fields([
                    'user_id' => $data['user_id'],
                    'type' => 1
                ]);

                $access_token = $token_service->generate_access_token($key, $base_url, $jwt_expire_at, [
                    'user_id' => $data['user_id'],
                    'role_id' => $data['role_id']
                ]);

                return $this->success([
                    'access_token' => $access_token['token'],
                    'expire_in' => $jwt_expire_at
                ], 200);
                exit();
            }
            else
            {
                $this->token_model->real_delete_by_fields([
                    'user_id' => $exist->user_id,
                    'type' => 1
                ]);
                $this->token_model->real_delete_by_fields([
                    'user_id' => $exist->user_id,
                    'type' => 2
                ]);
                return $this->expire_token_error_message();
                exit();
            }
        }
        else
        {
            return $this->unauthorize_error_message();
            exit();
        }
    }
}