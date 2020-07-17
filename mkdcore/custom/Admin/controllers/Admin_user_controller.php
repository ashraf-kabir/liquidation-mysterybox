<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
include_once __DIR__ . '/../../services/User_service.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * User Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_user_controller extends Admin_controller
{
    protected $_model_file = 'user_model';
    public $_page_name = 'Users';

    public function __construct()
    {
        parent::__construct();
		$this->load->model('admin_operation_model');
		$this->load->model('credential_model');        
    }

    public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/User_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new User_admin_list_paginate_view_model(
            $this->user_model,
            $this->pagination,
            '/admin/users/0');
        $this->_data['view_model']->set_heading('Users');
        $this->_data['view_model']->set_id(($this->input->get('id', TRUE) != NULL) ? $this->input->get('id', TRUE) : NULL);
		$this->_data['view_model']->set_email(($this->input->get('email', TRUE) != NULL) ? $this->input->get('email', TRUE) : NULL);
		$this->_data['view_model']->set_first_name(($this->input->get('first_name', TRUE) != NULL) ? $this->input->get('first_name', TRUE) : NULL);
		$this->_data['view_model']->set_last_name(($this->input->get('last_name', TRUE) != NULL) ? $this->input->get('last_name', TRUE) : NULL);
		
        $where = [
            'id' => $this->_data['view_model']->get_id(),
			'email' => $this->_data['view_model']->get_email(),
			'first_name' => $this->_data['view_model']->get_first_name(),
			'last_name' => $this->_data['view_model']->get_last_name(),
			
        ];

        $this->_data['view_model']->set_total_rows($this->user_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/users/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->user_model->get_credential_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/User', $this->_data);
	}

    public function add()
	{
        include_once __DIR__ . '/../../view_models/User_admin_add_view_model.php';
        $this->load->model('refer_log_model');
        $custom_validation = [
            ['email', 'xyzEmail', 'trim|required|valid_email|is_unique[credential.email]'],
            ['password', 'xyzPassword', 'required']
        ];

        $custom_validation = array_merge($custom_validation, $this->user_model->get_all_validation_rule());

        $this->form_validation = $this->user_model->set_form_validation($this->form_validation, $custom_validation);
        $this->_data['view_model'] = new User_admin_add_view_model($this->user_model);
        $this->_data['view_model']->set_heading('Users');
        $this->_data['view_data']['roles'] = $this->credential_model->role_id_mapping();
        
        $service = new User_service($this->credential_model, $this->user_model);
        $service->set_refer_log_model($this->refer_log_model);

		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/UserAdd', $this->_data);
        }

        $email = $this->input->post('email');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$phone = $this->input->post('phone');
		$image = $this->input->post('image');
        $image_id = $this->input->post('image_id');
        $password = $this->input->post('password');
        $role_id = $this->input->post('role_id');

		
        $session = $this->get_session();
        $refer = (isset($session['refer']) && strlen($session['refer']) > 0) ? $session['refer'] : '';

        $created_user = $service->create($email, $password, $first_name, $last_name, $role_id, $refer);

        if ($created_user)
        {
            return $this->redirect('/admin/users/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/UserAdd', $this->_data);
	}

    public function edit($id)
	{
        $model = $this->user_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/users/0');
        }

        $custom_validation = [
            ['role_id', 'xyzRole', 'required']
        ];

        $custom_validation = array_merge($custom_validation, $this->user_model->get_all_validation_rule());
        $credential_obj = $this->credential_model->get($model->credential_id);

        $model->{'email'} = $credential_obj->email;
        $model->{'role_id'} = $credential_obj->role_id;
        $model->{'status'} =  $credential_obj->status;
        
        if($this->input->post('email') != $model->email)
        {
            $custom_validation[] = ['email', 'xyzEmail', 'trim|required|valid_email|is_unique[credential.email]'];
        }

        include_once __DIR__ . '/../../view_models/User_admin_edit_view_model.php';
        $this->form_validation = $this->user_model->set_form_validation(
        $this->form_validation,  $custom_validation);
        $this->_data['view_model'] = new User_admin_edit_view_model($this->user_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Users');
        $this->_data['view_data']['roles'] = $this->credential_model->role_id_mapping();
        $this->_data['view_data']['status'] = $this->credential_model->status_mapping();
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/UserEdit', $this->_data);
        }

        $email = $this->input->post('email');
        $status = $this->input->post('status');
        $role_id = $this->input->post('role_id');
        $password = $this->input->post('password');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$phone = $this->input->post('phone');
		$image = $this->input->post('image');
        $image_id = $this->input->post('image_id');
        
        $credential_params = [
            'email' => $email,
            'role_id' => $role_id,
            'status' => $status
        ];

        if(strlen($password) > 2)
        {
            $credential_params['password'] = str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT));
        }
		
        $params = [
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone' => $phone,
			'image' => $image,
			'image_id' => $image_id,
        ];

        $credential_result = $this->credential_model->edit($credential_params,  $credential_obj->id);
        $result = $this->user_model->edit($params, $model->id);
        
        if ($result && $credential_result)
        {
            $this->success('xyzSaved');
            return $this->redirect('/admin/users/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/UserEdit', $this->_data);
	}

    	public function view($id)
	{
        $model = $this->user_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/users/0');
		}


        include_once __DIR__ . '/../../view_models/User_admin_view_view_model.php';
		$this->_data['view_model'] = new User_admin_view_view_model($this->user_model);
		$this->_data['view_model']->set_heading('Users');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/UserView', $this->_data);
	}

    
    
    
    
    
    
    
    
}