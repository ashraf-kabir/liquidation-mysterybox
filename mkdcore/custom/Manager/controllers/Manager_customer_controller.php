<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Manager_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Customer Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Manager_customer_controller extends Manager_controller
{
    protected $_model_file = 'customer_model';
    public $_page_name = 'Customer';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    

    	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Customer_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Customer_admin_list_paginate_view_model(
            $this->customer_model,
            $this->pagination,
            '/manager/customer/0');
        $this->_data['view_model']->set_heading('Customer');
        $this->_data['view_model']->set_name(($this->input->get('name', TRUE) != NULL) ? $this->input->get('name', TRUE) : NULL);
		$this->_data['view_model']->set_email(($this->input->get('email', TRUE) != NULL) ? $this->input->get('email', TRUE) : NULL);
		$this->_data['view_model']->set_phone(($this->input->get('phone', TRUE) != NULL) ? $this->input->get('phone', TRUE) : NULL);
		$this->_data['view_model']->set_customer_since(($this->input->get('customer_since', TRUE) != NULL) ? $this->input->get('customer_since', TRUE) : NULL);
		$this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);
		
        $where = [
            'name' => $this->_data['view_model']->get_name(),
			'email' => $this->_data['view_model']->get_email(),
			'phone' => $this->_data['view_model']->get_phone(),
			'customer_since' => $this->_data['view_model']->get_customer_since(),
			'status' => $this->_data['view_model']->get_status(),
			
            
        ];

        $this->_data['view_model']->set_total_rows($this->customer_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/manager/customer/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->customer_model->get_paginated(
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

        return $this->render('Admin/Customer', $this->_data);
	}

    	public function add()
	{
        include_once __DIR__ . '/../../view_models/Customer_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->customer_model->set_form_validation(
        $this->form_validation, $this->customer_model->get_all_validation_rule());
        $this->_data['view_model'] = new Customer_admin_add_view_model($this->customer_model);
        $this->_data['view_model']->set_heading('Customer');
        

		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/CustomerAdd', $this->_data);
        }

        $name = $this->input->post('name', TRUE);
		$email = $this->input->post('email', TRUE);
		$phone = $this->input->post('phone', TRUE);
		$company_name = $this->input->post('company_name', TRUE);
		$billing_zip = $this->input->post('billing_zip', TRUE);
		$billing_address = $this->input->post('billing_address', TRUE);
		$billing_country = $this->input->post('billing_country', TRUE);
		$billing_state = $this->input->post('billing_state', TRUE);
		$billing_city = $this->input->post('billing_city', TRUE);
		$customer_since = $this->input->post('customer_since', TRUE);
        $status = $this->input->post('status', TRUE);
        $password = $this->input->post('password', TRUE);
		
        $result = $this->customer_model->create([
            'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'company_name' => $company_name,
			'billing_zip' => $billing_zip,
			'billing_address' => $billing_address,
			'billing_country' => $billing_country,
			'billing_state' => $billing_state,
			'billing_city' => $billing_city,
			'customer_since' => $customer_since,
			'status' => $status,
			'password' => $password,
        ]);

        if ($result)
        {
            $this->success('Customer has been added successfully.');
            
            return $this->redirect('/manager/customer/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/CustomerAdd', $this->_data);
	}

    	public function edit($id)
	{
        $model = $this->customer_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/manager/customer/0');
        }

        include_once __DIR__ . '/../../view_models/Customer_admin_edit_view_model.php';
        $this->form_validation = $this->customer_model->set_form_validation(
        $this->form_validation, $this->customer_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Customer_admin_edit_view_model($this->customer_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Customer');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/CustomerEdit', $this->_data);
        }

        $name = $this->input->post('name', TRUE);
		$email = $this->input->post('email', TRUE);
		$phone = $this->input->post('phone', TRUE);
		$company_name = $this->input->post('company_name', TRUE);
		$billing_zip = $this->input->post('billing_zip', TRUE);
		$billing_address = $this->input->post('billing_address', TRUE);
		$billing_country = $this->input->post('billing_country', TRUE);
		$billing_state = $this->input->post('billing_state', TRUE);
		$billing_city = $this->input->post('billing_city', TRUE);
		$customer_since = $this->input->post('customer_since', TRUE);
		$status = $this->input->post('status', TRUE);
		
        $result = $this->customer_model->edit([
            'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'company_name' => $company_name,
			'billing_zip' => $billing_zip,
			'billing_address' => $billing_address,
			'billing_country' => $billing_country,
			'billing_state' => $billing_state,
			'billing_city' => $billing_city,
			'customer_since' => $customer_since,
			'status' => $status,
			
        ], $id);

        if ($result)
        {
            $this->success('Customer has been updated successfully.');
            
            return $this->redirect('/manager/customer/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/CustomerEdit', $this->_data);
	}

    	public function view($id)
	{
        $model = $this->customer_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/manager/customer/0');
		}


        include_once __DIR__ . '/../../view_models/Customer_admin_view_view_model.php';
		$this->_data['view_model'] = new Customer_admin_view_view_model($this->customer_model);
		$this->_data['view_model']->set_heading('Customer');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/CustomerView', $this->_data);
	}

    
    
    
    
    
    
    
    
}