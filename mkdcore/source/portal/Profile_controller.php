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

        $this->_data['view_model'] = new {{{uc_portal}}}_profile_view_model($this->{{{model}}});
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('{{{uc_portal}}}');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('{{{uc_portal}}}/Profile', $this->_data);
        }

        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');

        $payload = [
            'first_name' => $first_name,
            'last_name' => $last_name
        ];

        $result = $this->{{{model}}}->edit_raw($payload, $id);

        if ($result)
        {
            $this->success('xyzSaved');
            return $this->redirect('/{{{portal}}}/profile', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('{{{uc_portal}}}/Profile', $this->_data);
    }
}