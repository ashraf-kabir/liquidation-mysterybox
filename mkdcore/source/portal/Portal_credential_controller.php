<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{uc_portal}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Credential Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{uc_portal}}}_me_controller extends {{{uc_portal}}}_controller
{
    protected $_model_file = 'credential_model';
    public $_page_name = 'xyzChange Password';

    public function __construct()
    {
        parent::__construct();
    }

    public function me()
    {
        $session = $this->get_session();
        $model = $this->credential_model->get($session['credential_id']);
        $this->_data['email'] = $model->email;
        $this->_data['password'] = '';

        $this->form_validation->set_rules('email', 'xyzEmail', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'xyzPassword', '');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('{{{uc_portal}}}/Mes', $this->_data);
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $payload = [
            'email' => $email,
        ];

        if (strlen($password) > 1)
        {
            $payload['password'] = str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT));
        }

        $result = $this->credential_model->edit($payload, $session['credential_id']);

        if ($result)
        {
            $this->success('xyzSaved');
            return $this->redirect('/{{{portal}}}/me', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('{{{uc_portal}}}/Mes', $this->_data);
    }
}