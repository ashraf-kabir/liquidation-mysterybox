<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Pos_user Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_pos_user_controller extends Admin_controller
{
    protected $_model_file = 'pos_user_model';
    public $_page_name = 'POS Users';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('credential_model');
        $this->load->model('store_model');
        $this->load->model('department_model');
        $this->load->library('names_helper_service');
    }

    

    	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Pos_user_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Pos_user_admin_list_paginate_view_model(
            $this->pos_user_model,
            $this->pagination,
            '/admin/pos_user/0');
        $this->_data['view_model']->set_heading('POS Users');
        $this->_data['view_model']->set_first_name(($this->input->get('first_name', TRUE) != NULL) ? $this->input->get('first_name', TRUE) : NULL);
		$this->_data['view_model']->set_last_name(($this->input->get('last_name', TRUE) != NULL) ? $this->input->get('last_name', TRUE) : NULL);
		$this->_data['view_model']->set_email(($this->input->get('email', TRUE) != NULL) ? $this->input->get('email', TRUE) : NULL);
		$this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);
		
        $where = [
            'first_name' => $this->_data['view_model']->get_first_name(),
			'last_name' => $this->_data['view_model']->get_last_name(),
			'email' => $this->_data['view_model']->get_email(),
			'status' => $this->_data['view_model']->get_status(),
			
            
        ];

        $this->_data['view_model']->set_total_rows($this->pos_user_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/pos_user/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->pos_user_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format == 'csv')
        {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }


        if ( !empty( $this->_data['view_model']->get_list() ) ) 
        {
            $this->names_helper_service->set_store_model($this->store_model);
            
            foreach ($this->_data['view_model']->get_list() as $key => &$value) 
            { 
                $value->store_id       = $this->names_helper_service->get_store_name( $value->store_id );  
            }
        }

        return $this->render('Admin/Pos_user', $this->_data);
	}

    public function add()
	{
        include_once __DIR__ . '/../../view_models/Pos_user_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->pos_user_model->set_form_validation(
        $this->form_validation, $this->pos_user_model->get_all_validation_rule());
        $this->_data['view_model'] = new Pos_user_admin_add_view_model($this->pos_user_model);
        $this->_data['view_model']->set_heading('POS Users');
        $this->_data['stores']  =   $this->store_model->get_all();

        $this->_data['department']  = $this->department_model->get_all();
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Pos_userAdd', $this->_data);
        }

        $first_name = $this->input->post('first_name');
		$last_name  = $this->input->post('last_name');
		$email      = $this->input->post('email');
        $status     = $this->input->post('status');
        $password   = $this->input->post('password');
        $store_id   = $this->input->post('store_id');
        $department_id   = $this->input->post('department_id', TRUE);
        $check_data = $this->credential_model->get_all(['email' => $email]);
        
        if(!empty($check_data))
        {
            $this->_data['error'] = 'Error! Email already exist try new email.';
            return $this->render('Admin/Pos_userAdd', $this->_data);
        }
        
        $result = $this->pos_user_model->create([
            'first_name'      => $first_name,
			'last_name'       => $last_name,
			'email'           => $email,
			'status'          => $status,
			'store_id'        => $store_id, 
			'department_id'   => $department_id, 
        ]);

        if ($result)
        {
            
            $add_credential = array(
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'verify'   => 1,
                'role_id'  => 3,
                'type'     => 'n',
                'status'   => $status,
            );
            $credential_id = $this->credential_model->create($add_credential); 

            $update_credential = array(
                'credential_id'    => $credential_id, 
            );
            $this->pos_user_model->edit($update_credential,$result);



            return $this->redirect('/admin/pos_user/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/Pos_userAdd', $this->_data);
	}

    public function edit($id)
	{
        $model = $this->pos_user_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/pos_user/0');
        }

        include_once __DIR__ . '/../../view_models/Pos_user_admin_edit_view_model.php';
        $this->form_validation = $this->pos_user_model->set_form_validation(
        $this->form_validation, $this->pos_user_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Pos_user_admin_edit_view_model($this->pos_user_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('POS Users');
        $this->_data['stores'] =   $this->store_model->get_all();
        

        $this->_data['department']  = $this->department_model->get_all();
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Pos_userEdit', $this->_data);
        }

        $first_name = $this->input->post('first_name');
		$last_name  = $this->input->post('last_name');
		$email      = $this->input->post('email');
        $status     = $this->input->post('status');
        $password   = $this->input->post('password');
        $store_id   = $this->input->post('store_id');
        $department_id   = $this->input->post('department_id', TRUE);
		
        $result = $this->pos_user_model->edit([
            'first_name' => $first_name,
			'last_name'  => $last_name,
			'email'      => $email,
			'status'     => $status,
			'store_id'   => $store_id,
			'department_id'   => $department_id, 
        ], $id);

        if ($result)
        {

            $model = $this->pos_user_model->get($id);
            $update_credential = array(
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'verify'   => 1,
                'role_id'  => 3,
                'type'     => 'n',
                'status'   => $status,
            );

            if( empty($password) )
            {
                unset($update_credential['password']);
            }


            $credential_id = $this->credential_model->edit($update_credential, $model->credential_id );  

            return $this->redirect('/admin/pos_user/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/Pos_userEdit', $this->_data);
	}

    	public function view($id)
	{
        $model = $this->pos_user_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/pos_user/0');
		}

        $this->names_helper_service->set_store_model($this->store_model); 
        $this->names_helper_service->set_department_model($this->department_model); 
            
        $model->store_id       = $this->names_helper_service->get_store_name( $model->store_id );  
        $model->department_id  = $this->names_helper_service->get_department_real_name( $model->department_id );  
            

        include_once __DIR__ . '/../../view_models/Pos_user_admin_view_view_model.php';
		$this->_data['view_model'] = new Pos_user_admin_view_view_model($this->pos_user_model);
		$this->_data['view_model']->set_heading('POS Users');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Pos_userView', $this->_data);
	}

    
    
    
    
    
    
    
    
}