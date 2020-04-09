<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once '{{{ucname}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Login Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_login_controller extends {{{subclass_prefix}}}Controller
{
	protected $_redirect = '/{{{name}}}/dashboard';

    public function __construct()
    {
        parent::__construct();
    }

	public function index ()
	{
        $this->load->model('credential_model');
        $this->load->model('{{{model}}}');
        $this->load->helper('cookie');

        $service = new User_service($this->credential_model);

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            echo $this->load->view('{{{ucname}}}/Login', $this->_data, TRUE);
            exit;
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $redirect = $service->get_redirect($this->input->cookie('redirect', TRUE), $this->_redirect);
        $role = $this->_valid_roles[0];
        $authenticated_user = $service->login_by_role($email, $password, $role);

        if ($authenticated_user)
        {
            delete_cookie('redirect');
            $user_obj = $this->{{{model}}}->get_user_by_credential_id($authenticated_user->id);

            if(empty( $user_obj))
            {
                $this->error('xyzWrong email or password.');
                return $this->redirect('{{{name}}}/login');
            }
            $this->set_session('credential_id', (int) $authenticated_user->id);
            $this->set_session('user_id', (int) $user_obj->id);
            $this->set_session('email', (string) $authenticated_user->email);
            $this->set_session('role', (string) $authenticated_user->role_id);
            return $this->redirect($redirect);
        }

        $this->error('xyzWrong email or password.');
        return $this->redirect('{{{name}}}/login');
    }

    public function logout ()
    {
        $this->destroy_session();
		return $this->redirect('{{{name}}}/login');
    }
}