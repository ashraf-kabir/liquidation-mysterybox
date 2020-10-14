<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_plans Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_plans_controller extends Admin_controller
{
    protected $_model_file = 'stripe_plans_model';
    public $_page_name = 'xyzPlans';

    public function __construct()
    {
        parent::__construct();
        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config);
    }

	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Stripe_plans_admin_list_paginate_view_model.php';
     
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_plans_admin_list_paginate_view_model($this->stripe_plans_model,$this->pagination,'/admin/stripe_plans/0');
        $this->_data['view_model']->set_heading('xyzPlans');
        $this->_data['view_model']->set_amount(($this->input->get('amount', TRUE) != NULL) ? $this->input->get('amount', TRUE) : NULL);
		$this->_data['view_model']->set_display_name(($this->input->get('display_name', TRUE) != NULL) ? $this->input->get('display_name', TRUE) : NULL);
		
        $where = [
            'amount' => $this->_data['view_model']->get_amount(),
            'display_name' => $this->_data['view_model']->get_display_name(),
            'type' => 0
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_plans_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_plans/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_plans_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_plans', $this->_data);
	}

	public function add()
	{
        include_once __DIR__ . '/../../view_models/Stripe_plans_admin_add_view_model.php';
        $this->load->model('stripe_products_model');
        $this->form_validation = $this->stripe_plans_model->set_form_validation(
        $this->form_validation, $this->stripe_plans_model->get_all_validation_rule());
        $this->_data['view_model'] = new Stripe_plans_admin_add_view_model($this->stripe_plans_model);
        $this->_data['view_model']->set_heading('xyzPlans');
        $this->_data['products'] = $this->stripe_products_model->get_all();
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_plansAdd', $this->_data);
        }

        $subscription_interval = $this->input->post('subscription_interval');
		$amount = $this->input->post('amount');
		$product_id = $this->input->post('product_id');
        $display_name = $this->input->post('display_name');
        $product_obj = $this->stripe_products_model->get($product_id);
        $plan_params = [
            'currency' =>  $this->config->item('stripe_default_currency') ?? 'CAD',
            'interval' =>  $this->_data['view_model']->subscription_interval_mapping()[$subscription_interval], 
            'product' =>  $product_obj->stripe_id,
            'nickname' => $display_name,
            'amount' => $amount 
        ];
        try
        {
            $stripe_plan = $this->payment_service->create_plan($plan_params);
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_plansAdd', $this->_data);
        }
        
        if(isset($stripe_plan['id']))
        {
            $result = $this->stripe_plans_model->create([
                'subscription_interval' => $subscription_interval,
                'amount' => $amount,
                'stripe_id' =>$stripe_plan['id'],
                'stripe_product_id' =>  $product_obj->stripe_id,
                'display_name' => $display_name,
                'product_id' => $product_id,
                'type' => 0       
            ]);
            if ($result)
            {
                $this->success('xyzPlan Created');
                return $this->redirect('/admin/stripe_plans/0', 'refresh');
            }
    
            $this->_data['error'] = 'xyzError';
            return $this->render('Admin/Stripe_plansAdd', $this->_data);
        }
        $this->_data['error'] = 'xyzError creating Stripe plan';
        return $this->render('Admin/Stripe_plansAdd', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->stripe_plans_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/admin/stripe_plans/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_plans_admin_edit_view_model.php';
        $this->form_validation = $this->stripe_plans_model->set_form_validation(
        $this->form_validation, $this->stripe_plans_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Stripe_plans_admin_edit_view_model($this->stripe_plans_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Plans');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_plansEdit', $this->_data);
        }

        $subscription_interval = $this->input->post('subscription_interval');
		$amount = $this->input->post('amount');
		$stripe_id = $this->input->post('stripe_id');
		$display_name = $this->input->post('display_name');
        
        $plan_params = [
            'nickname' => $display_name,
        ];

        try
        {
            $stripe_plan = $this->payment_service->update_plan($model->stripe_id, $plan_params);
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_plansEdit', $this->_data);
        }


        if(isset($stripe_plan['id']))
        {
            $result = $this->stripe_plans_model->edit([
                'subscription_interval' => $subscription_interval,
                'amount' => $amount,
                'stripe_id' => $stripe_id,
                'display_name' => $display_name,
                
            ], $id);
    
            if ($result)
            { 
                $this->success('xyzPlan Activated');
                return $this->redirect('/admin/stripe_plans/0', 'refresh');
            }
        }
       
        $this->_data['error'] = 'xyzError';
        return $this->render('Admin/Stripe_plansEdit', $this->_data);
	}

	public function view($id)
	{
        $model = $this->stripe_plans_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/admin/stripe_plans/0');
		}


        include_once __DIR__ . '/../../view_models/Stripe_plans_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_plans_admin_view_view_model($this->stripe_plans_model);
		$this->_data['view_model']->set_heading('Plans');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_plansView', $this->_data);
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