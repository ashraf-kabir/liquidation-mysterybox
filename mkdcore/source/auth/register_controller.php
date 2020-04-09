<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once '{{{ucname}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Register Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_register_controller extends {{{subclass_prefix}}}Controller
{
	protected $_redirect = '/{{{name}}}/dashboard';

    public $_valid_roles = [{{{valid_roles}}}];

    public function __construct()
    {
        parent::__construct();
        $this->_run_middlewares();
    }

    protected function _middleware()
    {
        return [
            'affilate',
            'maintenance'
        ];
    }

	public function index ()
	{
        $this->load->model('{{{model}}}');
        $this->load->model('refer_log_model');
        $this->load->helper('cookie');
        $this->load->library('google_service');
        $this->load->model('credential_model');
        // $this->load->library('facebook_service');
        $this->google_service->init();
        // $this->facebook_service->init();
        $this->_data['google_auth_url'] = $this->google_service->make_auth_url();
		// $this->_data['facebook_auth_url'] = $this->facebook_service->make_auth_url();

        $service = new User_service($this->credential_model, $this->{{{model}}});
        $service->set_refer_log_model($this->refer_log_model);
        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'xyzPassword', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'xyzConfirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('first_name', 'xyzFirst Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'xyzLast Name', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            echo $this->load->view('{{{ucname}}}/Register', $this->_data, TRUE);
            exit;
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $redirect = $service->get_redirect($this->input->cookie('redirect', TRUE), $this->_redirect);
        $session = $this->get_session();
        $refer = (isset($session['refer']) && strlen($session['refer']) > 0) ? $session['refer'] : '';

        $created_user = $service->create($email, $password, $first_name, $last_name, {{{role}}}, $refer);

        if (!$created_user)
        {
            $this->_data['error'] = 'xyzUser creation failed. Please try again.';
            echo $this->load->view('{{{ucname}}}/Register', $this->_data, TRUE);
            exit;
        }

        delete_cookie('redirect');
        $this->set_session('user_id', (int) $created_user->id);
        $this->set_session('email', (string) $created_user->email);
        $this->set_session('role', (string) $created_user->role_id);
        return $this->redirect($redirect);
    }
}