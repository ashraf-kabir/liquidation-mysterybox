<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * PaymentCustomPlans Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_payment_custom_plans_controller extends Admin_controller
{
    protected $_model_file = 'stripe_plans_model';
    public $_page_name = 'xyzCustom Plans';

    public function __construct()
    {
        parent::__construct();
    }

    public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/PaymentCustomPlans_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new PaymentCustomPlans_admin_list_paginate_view_model($this->stripe_plans_model,$this->pagination,'/admin/payment_custom_plans/0');
        $this->_data['view_model']->set_heading('xyzCustom Plans');
        $this->_data['view_model']->set_display_name(($this->input->get('display_name', TRUE) != NULL) ? $this->input->get('display_name', TRUE) : NULL);
		$this->_data['view_model']->set_amount(($this->input->get('amount', TRUE) != NULL) ? $this->input->get('amount', TRUE) : NULL);
		
        $where = [
            'display_name' => $this->_data['view_model']->get_display_name(),
            'amount' => $this->_data['view_model']->get_amount(),    
        ];

        $where[] = 'type > 0';

        $this->_data['view_model']->set_total_rows($this->stripe_plans_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/payment_custom_plans/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_plans_model->get_paginated(
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

        return $this->render('Admin/PaymentCustomPlans', $this->_data);
	}

    public function add()
	{
        include_once __DIR__ . '/../../view_models/PaymentCustomPlans_admin_add_view_model.php';
        $session = $this->get_session();
        $this->load->model('stripe_products_model');
        $this->form_validation = $this->stripe_plans_model->set_form_validation(
        $this->form_validation, $this->stripe_plans_model->get_all_validation_rule());
        $this->_data['view_model'] = new PaymentCustomPlans_admin_add_view_model($this->stripe_plans_model);
        $this->_data['view_model']->set_heading('Custom Plans');
        $this->_data['products'] = $this->stripe_products_model->get_all();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/PaymentCustomPlansAdd', $this->_data);
        }

        $subscription_interval = $this->input->post('subscription_interval');
		$amount = $this->input->post('amount');
		$type = $this->input->post('type');
		$status = $this->input->post('status');
		$product_id = $this->input->post('product_id');
		$display_name = $this->input->post('display_name');
		
        $result = $this->stripe_plans_model->create([
            'subscription_interval' => $subscription_interval,
			'amount' => $amount,
			'type' => $type,
			'status' => $status,
			'product_id' => $product_id,
			'display_name' => $display_name,
			
        ]);

        if ($result)
        {
            
            
            return $this->redirect('/admin/payment_custom_plans/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/PaymentCustomPlansAdd', $this->_data);
	}

    public function edit($id)
	{
        $model = $this->stripe_plans_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/payment_custom_plans/0');
        }

        include_once __DIR__ . '/../../view_models/PaymentCustomPlans_admin_edit_view_model.php';
        $this->form_validation = $this->stripe_plans_model->set_form_validation(
        $this->form_validation, $this->stripe_plans_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new PaymentCustomPlans_admin_edit_view_model($this->stripe_plans_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Custom Plans');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/PaymentCustomPlansEdit', $this->_data);
        }

        $display_name = $this->input->post('display_name');
		$type = $this->input->post('type');
		$status = $this->input->post('status');
		
        $result = $this->stripe_plans_model->edit([
            'display_name' => $display_name,
			'type' => $model->type,
			'status' => $status,
			
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/payment_custom_plans/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/PaymentCustomPlansEdit', $this->_data);
	}

    	public function view($id)
	{
        $model = $this->stripe_plans_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/payment_custom_plans/0');
		}


        include_once __DIR__ . '/../../view_models/PaymentCustomPlans_admin_view_view_model.php';
		$this->_data['view_model'] = new PaymentCustomPlans_admin_view_view_model($this->stripe_plans_model);
		$this->_data['view_model']->set_heading('Custom Plans');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/PaymentCustomPlansView', $this->_data);
	}

    
    
    
    
    
    
    
    
	public function search_product_id_add_auto_complete()
	{
		$this->load->model('stripe_products_model');
		$search_text = $this->input->get("search_text");
		$sql =  ' SELECT name, id FROM stripe_products WHERE name LIKE '. " '%" . $search_text . "%'" ;
		$result = $this->stripe_products_model->raw_query($sql)->result(); 
		echo json_encode($result);
		exit();
	}

}