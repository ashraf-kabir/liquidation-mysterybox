<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_invoices Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_invoices_controller extends Admin_controller
{
    protected $_model_file = 'stripe_subscriptions_invoices_model';
    public $_page_name = 'xyzInvoices';

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
        include_once __DIR__ . '/../../view_models/Stripe_invoices_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_invoices_admin_list_paginate_view_model($this->stripe_subscriptions_invoices_model,$this->pagination,'/admin/stripe_invoices/0');
        $this->_data['view_model']->set_heading('xyzInvoices');
        $this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);
        $this->_data['view_model']->set_payment_attempted(($this->input->get('payment_attempted', TRUE) != NULL) ? $this->input->get('payment_attempted', TRUE) : NULL);
        $this->_data['view_model']->set_refunded(($this->input->get('refunded', TRUE) != NULL) ? $this->input->get('refunded', TRUE) : NULL);
		
        $where = [
            'status' => $this->_data['view_model']->get_status(),
            'payment_attempted' => $this->_data['view_model']->get_payment_attempted(),
            'refunded ' => $this->_data['view_model']->get_refunded()
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_subscriptions_invoices_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_invoices/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_subscriptions_invoices_model->get_user_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_invoices', $this->_data);
	}


    public function refund_invoice($id)
    {
        $model = $this->stripe_subscriptions_invoices_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_invoices/0');
		}

        include_once __DIR__ . '/../../view_models/Stripe_invoices_admin_view_view_model.php';
        $this->load->library('form_validation'); 
		$this->_data['view_model'] = new Stripe_invoices_admin_view_view_model($this->stripe_subscriptions_invoices_model);
		$this->_data['view_model']->set_heading('xyzInvoices');
        $this->_data['view_model']->set_model($model);

        $this->form_validation->set_rules('amount', 'xyzAmount', 'required');
    
        if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_invoiceRefund', $this->_data);
        }
  
        $amount = $this->input->post('amount');
        $reason = $this->input->post('reason');
        $charge_id = $model->stripe_charge_id ?? "";
        $refund_application_fee = FALSE; // default value used by stripe is false
        $reverse_transfer = FALSE;

        $refund_params = [
            'amount' => $amount,
            'charge' => $charge_id
        ];

        if(!empty($reason))
        {
            $refund_params['reason'] = $reason;
        }
    
        try
        {
            $refund = $this->payment_service->create_refund($refund_params);
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_invoiceRefund', $this->_data);   
        }
  
        if(isset($refund['id']))
        {
            $this->success('xyzRefund Success');
            return $this->redirect('/admin/stripe_invoices/0', 'refresh'); 
        }

        return $this->render('Admin/Stripe_invoiceRefund', $this->_data);
        
    }

	public function view($id)
	{
        $this->load->model('stripe_refunds_model');
        $this->load->model('user_model');
        $model = $this->stripe_subscriptions_invoices_model->get($id);
    
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_invoices/0');
		}

        include_once __DIR__ . '/../../view_models/Stripe_invoices_admin_view_view_model.php';
        $refunds = $this->stripe_refunds_model->filter(['invoice_id' => $id]);
        $user_obj = $this->user_model->get($model->user_id);
        $this->_data['view_model'] = new Stripe_invoices_admin_view_view_model($this->stripe_subscriptions_invoices_model);
        $this->_data['view_model']->set_heading('xyzInvoices');
        $this->_data['view_data']['refunds'] =  $refunds;
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_invoicesView', $this->_data);
	}
}