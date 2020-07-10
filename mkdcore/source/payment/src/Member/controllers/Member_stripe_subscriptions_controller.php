<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Member_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_subscriptions Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Member_stripe_subscriptions_controller extends Member_controller
{
    protected $_model_file = 'stripe_subscriptions_model';
    public $_page_name = 'xyzSubscriptions';

    public function __construct()
    {
        parent::__construct();
    }

	public function index($page)
	{
        $this->load->library('pagination');
        $this->load->model('stripe_plans_model');
        include_once __DIR__ . '/../../view_models/Stripe_subscriptions_member_list_paginate_view_model.php';
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';
        $session = $this->get_session();
        $where = [
            'user_id' => $session['user_id'],
            'role_id' => $session['role']
        ];
        $this->_data['view_model'] = new Stripe_subscriptions_member_list_paginate_view_model($this->stripe_subscriptions_model,$this->pagination,'/member/stripe_subscriptions/0');
        $this->_data['view_model']->set_heading('Subscriptions');
        $this->_data['view_model']->set_total_rows($this->stripe_subscriptions_model->count($where));

        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/member/stripe_subscriptions/0');
        $this->_data['view_data']['interval_mapping'] = $this->stripe_plans_model->subscription_interval_mapping();
        $this->_data['view_data']['plans'] = $this->stripe_plans_model->get_all();

        $results = $this->stripe_subscriptions_model->get_paginated( $this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction);
        $this->_data['view_data']['user_plans'] = array_column($results, 'plan_id');

        foreach($results as $result)
        {
            $plan = $this->stripe_plans_model->get($result->plan_id ?? 0);
            $result->{"plan_name"} = $plan->display_name ?? '';
            $result->{"plan_interval"} = $this->stripe_plans_model->subscription_interval_mapping()[$plan->interval] ?? '';
        }

		$this->_data['view_model']->set_list($results);

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Member/Stripe_subscriptions', $this->_data);
	}















}