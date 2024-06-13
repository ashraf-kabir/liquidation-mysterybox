<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{uc_portal}}}_controller.php';

/**
 * {{{uc_portal}}} Profile Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{uc_portal}}}_profile_controller extends {{{uc_portal}}}_controller
{
    protected $_model_file = '{{{model}}}';
    public $_page_name = 'xyzProfile';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $session = $this->get_session();
        $model = $this->{{{model}}}->get($session['user_id']);
        $this->load->model('credential_model');
        $id = $session['user_id'];

        if (!$model)
        {
            $this->error('xyzError');
            return redirect('/{{{portal}}}/dashboard');
        }
        include_once __DIR__ . '/../../view_models/{{{uc_portal}}}_profile_view_model.php';

        $this->form_validation->set_rules('first_name', 'xyzFirst Name', 'required');
        $this->form_validation->set_rules('last_name', 'xyzLast Name', 'required');
        $credential_obj = $this->credential_model->get($model->credential_id);
        $model->{"email"} = $credential_obj->email;

        $this->_data['view_model'] = new {{{uc_portal}}}_profile_view_model($this->{{{model}}});
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('{{{uc_portal}}}');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('{{{uc_portal}}}/Profile', $this->_data);
        }

        $email = $this->input->post('email');
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');

        $payload = [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name
        ];

        $result = $this->{{{model}}}->edit_raw($payload, $id);

        if ($result)
        {
            $this->success('xyzSaved');
            return $this->redirect('/{{{portal}}}/dashboard', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('{{{uc_portal}}}/Profile', $this->_data);
    }
    
    public function update_credentials()
    {
        $this->load->model('credential_model');
        $session = $this->get_session();
        $user_obj = $this->{{{model}}}->get($session['user_id']);
        $model = $this->credential_model->get($user_obj->credential_id ?? 0);

        if (!$model)
        {
            die();
        }

        $id = $user_obj->credential_id ?? 0;

        $email_validation_rules = 'required|valid_email';        
        
        if ($this->input->post('email') !=  $model->email)
        {
            $email_validation_rules .= '|is_unique[credential.email]';
        }

        $this->form_validation->set_rules('email', 'Email', $email_validation_rules);

        if ($this->form_validation->run() === FALSE)
        {
            $this->error(validation_errors());
            return $this->redirect('/{{{portal}}}/profile?credential=true', 'refresh');
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $payload = [
            'email' => $email,
        ];

        if (strlen($password) > 0)
        {
            $payload['password'] = str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT));
        }

        $result = $this->credential_model->edit_raw($payload, $id);

        if ($result)
        {
            $this->success('xyzSaved');
            return $this->redirect('/{{{portal}}}/dashboard?credential=true', 'refresh');
        }

        $this->success('xyzError');
        return $this->redirect('/{{{portal}}}/profile?credential=true', 'refresh');
    }
}