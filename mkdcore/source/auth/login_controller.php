<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once __DIR__ . '/../../libraries/Twillo_service.php';
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

    public $_valid_roles = [{{{valid_roles}}}];

    public function __construct()
    {
        parent::__construct();
    }

	public function index ()
	{
        $this->load->model('{{{model}}}');
        $this->load->helper('cookie');
        $this->load->library('google_service');
        $this->load->model('credential_model');
        // $this->load->library('facebook_service');
        $this->google_service->init();
        // $this->facebook_service->init();
        $this->_data['google_auth_url'] = $this->google_service->make_auth_url();
		// $this->_data['facebook_auth_url'] = $this->facebook_service->make_auth_url();
        $this->_data['portal'] = '{{{name}}}';
        $service = new User_service($this->credential_model, $this->{{{model}}});

        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'xyzPassword', 'required');

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

	public function sms_login ()
	{
        $this->load->model('{{{model}}}');
        $this->load->model('credential_model');
        $this->load->helper('cookie');

        $service = new User_service($this->credential_model, $this->{{{model}}});

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            echo $this->load->view('{{{ucname}}}/Login', $this->_data, TRUE);
            exit;
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $role = $this->_valid_roles[0];

        $authenticated_user = $service->login_by_role($email, $password, $role);

        if ($authenticated_user)
        {
            delete_cookie('redirect');
            $this->set_session('user', $authenticated_user);
            return $this->redirect('/{{{name}}}/sms_auth');
        }

        $this->error('Wrong email or password.');
        return $this->redirect('{{{name}}}/login');
    }

    public function sms_auth ()
    {
        $this->load->library('twillo_service');
        $this->load->library('form_validation');
        $this->load->model('{{{model}}}');

        if (!isset($_SESSION['user']))
        {
            return $this->redirect('{{{name}}}/login');
        }

        $this->_data['phone'] = $_SESSION['user']->phone;

        $this->_data['phone'] = '+' . $_SESSION['user']->phone;

        if (!isset($_POST['btn-login']) || $_POST['btn-login'] !== 'Login')
        {
            try
            {
                $res = $this->twillo_service->verify_number($this->_data['phone']);
            }
            catch(Exception $e)
            {
                $this->_data['error'] = 'xyzSorry we cannot verify your phone right right. Please contact support';
            }
        }

        $this->form_validation->set_rules('code', 'xyzCode', 'required');

        $this->_data['error'] = '';

        if ($this->form_validation->run() === FALSE)
        {
             return $this->load->view('{{{ucname}}}/SmsAuth', $this->_data);
        }

        $code = $this->input->post('code');

        try
        {
            $res = $this->twillo_service->check_verification_code($code, $this->_data['phone']);

            if($res->status == 'approved')
            {
                $user = $_SESSION['user'];
                $this->set_session('user_id', (int) $user->id);
                $this->set_session('email', (string) $user->email);
                $this->set_session('role', (string) $user->role_id);
                return $this->redirect('{{{name}}}/dashboard');
            }

        }
        catch(Exception $e)
        {
            $this->_data['error'] = 'xyzInvalid Code';
            return $this->load->view('{{{ucname}}}/SmsAuth', $this->_data);
        }

        $this->_data['error'] = 'xyzInvalid Code';
        return $this->load->view('{{{ucname}}}/SmsAuth', $this->_data);
    }

    public function send_verification ()
    {
        $this->load->library('twillo_service');

        $number = $this->input->post('phone');

        try
        {
            $result = $this->twillo_service->verify_number($number);
        }
        catch(Exception $e)
        {
            echo json_encode([
                'error' => TRUE,
                'message' =>  $e->getMessage()
            ]);
            exit();
        }

        echo json_encode([
            'error' => FALSE,
            'message' =>  'xyzVerification Sent'
        ]);
        exit();
    }
}