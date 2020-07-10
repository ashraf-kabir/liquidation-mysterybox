<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Member_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_invoices Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Member_stripe_invoices_controller extends Member_controller
{
    protected $_model_file = 'stripe_subscriptions_invoices_model';
    public $_page_name = 'xyzInvoices';

    public function __construct()
    {
        parent::__construct();
    }

	public function index($page)
	{
        $this->load->library('pagination');
        $this->load->model('stripe_plans_model');
        include_once __DIR__ . '/../../view_models/Stripe_invoices_member_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_invoices_member_list_paginate_view_model($this->stripe_subscriptions_invoices_model,$this->pagination,'/member/stripe_invoices/0');
        $this->_data['view_model']->set_heading('xyzInvoices');
        $this->_data['view_model']->set_created_at(($this->input->get('created_at', TRUE) != NULL) ? $this->input->get('created_at', TRUE) : NULL);
		$this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);
        $where = [
            'created_at' => $this->_data['view_model']->get_created_at(),
            'user_id' => $session['user_id'],
            'role_id' => $session['role']
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_subscriptions_invoices_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/member/stripe_invoices/0');
        $this->_data['view_model']->set_page($page);
        
        $results = $this->stripe_subscriptions_invoices_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction);


		$this->_data['view_model']->set_list($results);

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Member/Stripe_invoices', $this->_data);
	}

	public function view($id)
	{
        $model = $this->stripe_subscriptions_invoices_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/member/stripe_invoices/0');
		}

		$session = $this->get_session();
		if ($model->user_id != $session['user_id'])
		{
			$this->error('Error');
			return redirect('/member/stripe_invoices/0');
		}
		
        include_once __DIR__ . '/../../view_models/Stripe_invoices_member_view_view_model.php';
		$this->_data['view_model'] = new Stripe_invoices_member_view_view_model($this->stripe_subscriptions_invoices_model);
		$this->_data['view_model']->set_heading('xyzInvoices');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Member/Stripe_invoicesView', $this->_data);
	}









}