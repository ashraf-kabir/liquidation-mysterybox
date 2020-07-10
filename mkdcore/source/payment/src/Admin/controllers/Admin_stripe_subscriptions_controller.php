<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_subscriptions Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_subscriptions_controller extends Admin_controller
{
    protected $_model_file = 'stripe_subscriptions_model';
    public $_page_name = 'Subscriptions';

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
        include_once __DIR__ . '/../../view_models/Stripe_subscriptions_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_subscriptions_admin_list_paginate_view_model(
            $this->stripe_subscriptions_model,
            $this->pagination,
            '/admin/stripe_subscriptions/0');
        $this->_data['view_model']->set_heading('Subscriptions');
        $this->_data['view_model']->set_created_at(($this->input->get('created_at', TRUE) != NULL) ? $this->input->get('created_at', TRUE) : NULL);
		$this->_data['view_model']->set_id(($this->input->get('id', TRUE) != NULL) ? $this->input->get('id', TRUE) : NULL);
		
        $where = [
            'created_at' => $this->_data['view_model']->get_created_at(),
			'id' => $this->_data['view_model']->get_id(),
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_subscriptions_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_subscriptions/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_subscriptions_model->get_user_paginated(
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

        return $this->render('Admin/Stripe_subscriptions', $this->_data);
	}





	public function view($id)
	{
        $model = $this->stripe_subscriptions_model->get($id);
        
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_subscriptions/0');
		}


        include_once __DIR__ . '/../../view_models/Stripe_subscriptions_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_subscriptions_admin_view_view_model($this->stripe_subscriptions_model);
		$this->_data['view_model']->set_heading('Subscriptions');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_subscriptionsView', $this->_data);
	}


    public function cancel($id)
    {
        $model = $this->stripe_subscriptions_model->get($id);
      
        if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_subscriptions/0');
        }
        
        include_once __DIR__ . '/../../view_models/Stripe_subscriptions_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_subscriptions_admin_view_view_model($this->stripe_subscriptions_model);
		$this->_data['view_model']->set_heading('Subscriptions');
        $this->_data['view_model']->set_model($model);

        $this->load->library('form_validation');
    
        $this->form_validation->set_rules('cancellation', 'Cancellation Type', 'required');
        
        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('Admin/StripeSubscriptionCancel', $this->_data);
        }

        $type = $this->input->post('cancellation');
        $cancellation_params = [];
        
        if($type === 'invoice_now')
        {
            $cancellation_params['invoice_now'] = TRUE;
        }

        if($type === 'prorate')
        {
            $cancellation_params['prorate'] = TRUE;
        }

        try
        {
            $stripe_subscription = $this->payment_service->cancel_subscription($model->stripe_id, TRUE, $cancellation_params);
            
            if(isset($stripe_subscription['id']))
            {
                $update_params = [
                    'status' =>  $this->stripe_subscriptions_model->get_mappings_key($stripe_subscription['status'], 'status')  
                ];
                
                $this->stripe_subscriptions_model->edit($update_params, $id);
                $this->success('xyzSubscription Canceled');
                return $this->redirect('/admin/stripe_subscriptions/0', 'refresh');
                
            }
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/StripeSubscriptionCancel', $this->_data);
        }
        
		return $this->render('Admin/StripeSubscriptionCancel', $this->_data);
    }






}