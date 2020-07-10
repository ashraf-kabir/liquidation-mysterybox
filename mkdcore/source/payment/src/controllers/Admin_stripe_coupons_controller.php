<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_coupons Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_coupons_controller extends Admin_controller
{
    protected $_model_file = 'stripe_coupons_model';
    public $_page_name = 'Coupons';

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
    
    private function _generate_slug($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        
        for ($i = 0; $i < $length; $i++)
        {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        
        return  $random_string;
    }

	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Stripe_coupons_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_coupons_admin_list_paginate_view_model($this->stripe_coupons_model,$this->pagination,'/admin/stripe_coupons/0');
        $this->_data['view_model']->set_heading('Coupons');
        $this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);
		$this->_data['view_model']->set_slug(($this->input->get('slug', TRUE) != NULL) ? $this->input->get('slug', TRUE) : NULL);
		$this->_data['view_model']->set_amount(($this->input->get('amount', TRUE) != NULL) ? $this->input->get('amount', TRUE) : NULL);
        $where = [
            'status' => $this->_data['view_model']->get_status(),
			'slug' => $this->_data['view_model']->get_slug(),
            'amount' => $this->_data['view_model']->get_amount(),
            'coupon_type' => 1
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_coupons_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_coupons/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_coupons_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_coupons', $this->_data);
	}

	public function add()
	{
        include_once __DIR__ . '/../../view_models/Stripe_coupons_admin_add_view_model.php';
        $this->form_validation = $this->stripe_coupons_model->set_form_validation(
        $this->form_validation, $this->stripe_coupons_model->get_all_validation_rule());
        $this->_data['view_model'] = new Stripe_coupons_admin_add_view_model($this->stripe_coupons_model);
        $this->_data['view_model']->set_heading('Coupons');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_couponsAdd', $this->_data);
        }

        $slug = $this->input->post('name');
        $duration = $this->input->post('duration');
        $name = $this->input->post('name');
		$status = $this->input->post('status');
		$usage_limit = $this->input->post('usage_limit');
		$current_usage_limit = $this->input->post('current_usage_limit');
		$amount = $this->input->post('amount');
        $amount_type = $this->input->post('amount_type');
        $duration_in_months = $this->input->post('duration_in_months');
        $duration = $this->input->post('duration');
        $coupon_type = 1;
        
        $coupon_params = [
            'duration' =>  $this->_data['view_model']->duration_mapping()[$this->input->post('duration')],
            'currency' => $this->config->item('stripe_currency') ?? 'CAD',
            'name' => $name,
            'max_redemptions' => $usage_limit
        ];
        
        if((int)$amount_type === 0)
        {
            $coupon_params['percent_off'] = $amount;
        }

        if((int)$amount_type === 1)
        {
            $coupon_params['amount_off'] = $amount;
        }

        if(!empty($duration_in_months))
        {
            $coupon_params['duration_in_months'] = $duration_in_months;
        }

        // create stripe token
        if(((int) $coupon_type ) == 1)
        {
            try
            {
                $coupon = $this->payment_service->create_coupon($coupon_params); 
            }
            catch(Exception $e)
            {
                $this->_data['error'] = $e->getMessage();
                return $this->render('Admin/Stripe_couponsAdd', $this->_data);
            }

            if(isset($coupon['id']))
            {
                
                $result = $this->stripe_coupons_model->create([
                    'slug' => $slug,
                    'status' => 0,
                    'usage_limit' => $usage_limit,
                    'amount' => $amount,
                    'amount_type' => $amount_type,
                    'stripe_id' => 	$coupon['id'] ?? "xyzCustom_coupon",
                    'name' => $name,
                    'current_usage' => 0,
                    'coupon_type' => 1,
                    'duration' =>  $duration,
                    'currency' => $this->config->item('stripe_currency') ?? 'CAD',
                    'name' => $name,
                    'max_redemptions' => $usage_limit	
                ]);
    
                if($result)
                {
                    return $this->redirect('/admin/stripe_coupons/0?order_by=id&direction=DESC&coupon_type=1', 'refresh');
                }
                
                $this->_data['error'] = 'Error';
                return $this->render('Admin/Stripe_couponsAdd', $this->_data);
            }
        }
         
        $this->_data['error'] = 'Error';
        return $this->render('Admin/Stripe_couponsAdd', $this->_data);
	}



	public function view($id)
	{
        $model = $this->stripe_coupons_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_coupons/0');
		}

        include_once __DIR__ . '/../../view_models/Stripe_coupons_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_coupons_admin_view_view_model($this->stripe_coupons_model);
		$this->_data['view_model']->set_heading('Coupons');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_couponsView', $this->_data);
    }
    
    public function edit($id)
	{
        $model = $this->stripe_coupons_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_coupons/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_coupons_admin_edit_view_model.php';
        $this->form_validation = $this->stripe_coupons_model->set_form_validation(
        $this->form_validation, $this->stripe_coupons_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Stripe_coupons_admin_edit_view_model($this->stripe_coupons_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Coupons');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_couponsEdit', $this->_data);
        }

        $status = $this->input->post('status');
		
        $result = $this->stripe_coupons_model->edit([
            'status' => $status,
			
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/stripe_coupons/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/Stripe_couponsEdit', $this->_data);
	}


    public function delete($id)
	{
        $model = $this->stripe_coupons_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_coupons/0?order_by=id&direction=DESC&coupon_type=1');
        }

        $result = $this->stripe_coupons_model->real_delete($id);

        if ($result)
        {
            
            return $this->redirect('/admin/stripe_coupons/0?order_by=id&direction=DESC&coupon_type=1', 'refresh');
        }

        $this->error('Error');
        return redirect('/admin/stripe_coupons/0?order_by=id&direction=DESC&coupon_type=1');
	}







}