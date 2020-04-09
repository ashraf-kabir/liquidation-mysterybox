<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once '{{{ucname}}}_api_auth_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Forgot API Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_forgot_api_controller extends {{{ucname}}}_api_auth_controller
{
    public function __construct()
    {
        parent::__construct();
    }

	public function index ()
	{
        $this->load->model('{{{model}}}');
        $this->load->model('email_model');
        $this->load->model('token_model');
        $this->load->model('credential_model');
        $this->load->library('mail_service');

        $service = new User_service($this->credential_model);

        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->_render_validation_error();
        }

        $email = $this->input->post('email');
        $from_email = $this->config->item('from_email');
        $base_url = $this->config->item('base_url');

        $this->mail_service->set_adapter('smtp');
        $service->set_email_model($this->email_model);
        $service->set_token_model($this->token_model);
        $service->set_email_service($this->mail_service);

        $reset = $service->forgot_password($email, $from_email, $base_url . '/{{{name}}}/reset', {{{role}}});

        if ($reset)
        {
            return $this->success([
                'message' => 'xyzYour Reset email was sent. Check your email.'
            ]);
        }

        return $this->_render_custom_error([
            'email' => 'xyzEmail does not exist in our system.'
        ]);
	}
}