<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Reset Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_reset_controller extends {{{subclass_prefix}}}Controller
{
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
            $this->error('xyzEmail does not exist in our system.');
            return $this->redirect('/{{{name}}}/login');
        }

        $this->_data['reset_token'] = $reset_token;

        $this->form_validation->set_rules('password', 'xyzPassword', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'xyzConfirm Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() === FALSE)
        {
            echo $this->load->view('{{{ucname}}}/Reset', $this->_data, TRUE);
            exit;
        }

        $password = $this->input->post('password');
        $password_reseted = $service->reset_password($valid_user->id, $password);

        if ($password_reseted)
        {
            $service->invalidate_token($reset_token, $valid_user->id);
            $this->success('xyzYour password was reset. Try to login now');
            return $this->redirect('/{{{name}}}/login');
        }

        $this->error('xyzInvalid reset token to reset password.');
        return $this->redirect('/{{{name}}}/login');
	}
}