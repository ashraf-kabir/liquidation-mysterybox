<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_payment_orders Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_payment_orders_controller extends Admin_controller
{
    protected $_model_file = 'stripe_payments_model';
    public $_page_name = 'xyzPayment Orders';

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
        include_once __DIR__ . '/../../view_models/Stripe_payment_orders_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_payment_orders_admin_list_paginate_view_model($this->stripe_payments_model,$this->pagination,'/admin/stripe_payment_orders/0');
        $this->_data['view_model']->set_heading('xyzPayment Orders');
        $this->_data['view_model']->set_created_at(($this->input->get('created_at', TRUE) != NULL) ? $this->input->get('created_at', TRUE) : NULL);
		$this->_data['view_model']->set_stripe_id(($this->input->get('stripe_id', TRUE) != NULL) ? $this->input->get('stripe_id', TRUE) : NULL);
		
        $where = [
            'created_at' => $this->_data['view_model']->get_created_at(),
			'stripe_id' => $this->_data['view_model']->get_stripe_id(),
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_payments_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_payment_orders/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_payments_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_payment_orders', $this->_data);
	}

   /**
     * @see https://stripe.com/docs/api/refunds/create
    */
    public function refund($id)
    {
        $model = $this->stripe_payments_model->get($id);
        if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_payment_orders/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_payment_orders_admin_view_view_model.php';
        $this->load->library('form_validation'); 
        $this->_data['view_model'] = new Stripe_payment_orders_admin_view_view_model($this->stripe_payments_model);
		$this->_data['view_model']->set_heading('xyzPayment Order Refund');
        $this->_data['view_model']->set_model($model);
        
        $this->form_validation->set_rules('amount', 'xyzAmount', 'required');
        $this->form_validation->set_rules('stripe_id', 'xyzCharge', 'required');
      
        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('Admin/Stripe_payment_ordersRefund', $this->_data);
        }

        $amount = $this->input->post('amount');
        $reason = $this->input->post('reason');
        $stripe_id = $this->input->post('stripe_id');
        $refund_application_fee = FALSE; // default value used by stripe is false
        $reverse_transfer = FALSE;

        if( (int) $this->input->post('refund_application_fee') === 1)
        {
            $refund_application_fee = TRUE;
        } 

        if((int) $this->input->post('reverse_transfer') === 1)
        {
            $reverse_transfer = TRUE;
        }

        $refund_params = [
            'amount' => $amount,
            'charge' => $stripe_id 
        ];
        
        if(!empty($reason))
        {
            $refund_params['reason'] = $reason;
        }
        
        if($refund_application_fee)
        {
            $refund_params['refund_application_fee'] = $refund_application_fee;
        }

        if($reverse_transfer)
        {
            $refund_params['reverse_transfer'] = $reverse_transfer;
        }

        try
        {
            $refund = $this->payment_service->create_refund($refund_params);
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_payment_ordersRefund', $this->_data);   
        }
        
        if(isset($refund['id']))
        {
            $refund_total  =  $model->refunded +  ($this->payment_service->get_convert_to_cents() ? ($refund['amount'] / 100) : $refund['amount']);
            if($this->stripe_payments_model->edit(['refunded' =>  $refund_total ], $id))
            {
                $this->success('xyzRefund Success');
                return $this->redirect('/admin/stripe_payment_orders/0', 'refresh'); 
            }
        }

        return $this->render('Admin/Stripe_payment_ordersRefund', $this->_data);
    }




	public function view($id)
	{
        $model = $this->stripe_payments_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/admin/stripe_payment_orders/0');
		}


        include_once __DIR__ . '/../../view_models/Stripe_payment_orders_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_payment_orders_admin_view_view_model($this->stripe_payments_model);
		$this->_data['view_model']->set_heading('xyzPayment Order');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_payment_ordersView', $this->_data);
	}









}