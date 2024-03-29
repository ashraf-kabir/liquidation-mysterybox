<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/../../services/User_service.php';
include_once 'Pos_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Login Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Pos_login_controller extends Manaknight_Controller
{
	protected $_redirect = '/pos/dashboard';

    public $_valid_roles = [3];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pos_user_model');
    }

	public function index ()
	{
        $this->load->model('credential_model');
        
        $this->load->helper('cookie');

        $service = new User_service($this->credential_model);

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->_data['portal'] = 'pos';
        if ($this->form_validation->run() === FALSE)
        {
            echo $this->load->view('Pos/Login', $this->_data, TRUE);
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
            $user_obj = $this->pos_user_model->get_user_by_credential_id($authenticated_user->id);

            if(empty( $user_obj))
            {
                $this->error('Wrong email or password.');
                return $this->redirect('pos/login');
            }

            if($user_obj->status == 0)
            {
                $this->error('Please activate your account.');
                return $this->redirect('pos/login');
            }
            $this->set_session('credential_id', (int) $authenticated_user->id);
            $this->set_session('user_id', (int) $user_obj->id);
            $this->set_session('store_id', (int) $user_obj->store_id);
            $this->set_session('email', (string) $authenticated_user->email);
            $this->set_session('role', (string) $authenticated_user->role_id);


            $this->load->library('helpers_service');
            $this->helpers_service->set_pos_user_model($this->pos_user_model); 
            $this->helpers_service->pos_logged_in($user_obj->id);


            return $this->redirect($redirect);
        }

        $this->error('Wrong email or password.');
        return $this->redirect('pos/login');
    }

    public function logout ()
    {
        $this->load->library('helpers_service');
        $this->helpers_service->set_pos_user_model($this->pos_user_model); 
        $this->helpers_service->pos_logged_out($this->session->userdata('user_id'));

        $this->destroy_session();
		return $this->redirect('pos/login');
    }
}