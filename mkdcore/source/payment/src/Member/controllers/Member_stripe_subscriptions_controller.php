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
        $this->load->model('stripe_plans_model');
        $this->load->model('stripe_cards_model');
        $this->load->model('payment_subscription_log_model');
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
        $this->_data['view_model']->set_heading('xyzSubscriptions');
        $this->_data['view_model']->set_total_rows($this->stripe_subscriptions_model->count($where));

        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/member/stripe_subscriptions/0');
        $this->_data['view_data']['interval_mapping'] = $this->stripe_plans_model->subscription_interval_mapping();
        $this->_data['view_data']['plans'] = $this->stripe_plans_model->get_all();
        $this->_data['view_data']['current_subscription'] =  $this->payment_subscription_log_model->get_last(  $session['user_id'] , $session['role']);
        $this->_data['view_data']['cards'] = $this->stripe_cards_model->get_all([
            'user_id' => $session['user_id'],
            'role_id' => $session['role']
        ]);
        
        $results = $this->stripe_subscriptions_model->get_paginated( $this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction);
        $this->_data['view_data']['current_plan'] = $this->stripe_plans_model->get($this->_data['view_data']['current_subscription']->plan_id ?? 0);


        foreach($results as $result)
        {
            $plan = $this->stripe_plans_model->get($result->plan_id ?? 0);
            $result->{"plan_name"} = $plan->display_name ?? '';
            $result->{"plan_interval"} = $this->stripe_plans_model->subscription_interval_mapping()[$plan->subscription_interval] ?? '';
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

    public function change_plan($plan_id)
    {

        /**
         * 1. check user stripe ID
         * 2 check user current subscription
         * 3 if no plan exists create a new subscription
         * 4. if plan exist and is equal to current plan do nothing
         * 5. if plan exist and different to current update the subscription
         */

         $this->load->library('subscriptions_service');
         $this->load->model('user_model');
         $this->load->model('stripe_plans_model');
         $this->load->model('stripe_cards_model');
         $this->load->model('stripe_subscriptions_model');
         $this->load->model('payment_subscription_log_model');
         $session = $this->get_session();
         $user_id = $session['user_id'];
         $role_id = $session['role'];
         $prorate = $this->config->item('prorate_stripe_subscription');
         $card_id = $this->input->get('card') ?? 0;

         $user_obj = $this->user_model->get($user_id);
         $plan_obj = $this->stripe_plans_model->get($plan_id);
         $card_obj = $this->stripe_cards_model->get($card_id);
         $source_changed = FALSE;

         if(empty($plan_obj))
         {
            $this->error('Invalid Plan');
            return $this->redirect('/member/stripe_subscriptions/0');  
         }

         if(empty($user_obj->stripe_id) && $plan_obj->type == 0)
         {
            $error_message = "xyzPayment Method not found <a href='/member/stripe_cards/add' class='float-right'>xyzAdd Payment method</a>";
            $this->error( $error_message);
            return $this->redirect('/member/stripe_subscriptions/0');
         }

         $this->subscriptions_service->set_user_model($this->user_model);
         $this->subscriptions_service->set_plan_model($this->stripe_plans_model);
         $this->subscriptions_service->set_stripe_subscription_model($this->stripe_subscriptions_model);
         $this->subscriptions_service->set_subscription_log_model($this->payment_subscription_log_model);
         $this->subscriptions_service->set_card_model($this->stripe_cards_model);
         $this->subscriptions_service->set_payment_service($this->payment_service);

         $this->subscriptions_service->init($user_id, $role_id);

         $user_subscription_log = $this->payment_subscription_log_model->get_last($user_id, $role_id);

        try
        {
            if(empty($user_log))
            {
                /**
                 * need to create complety new subscription
                 * we have already check stripeod on line 156 
                 * customer stripe id or card stripe can be used as a source
                */

                $source = $user_obj->stripe_id;
                
                if(!empty($card_obj))
                {
                    //overwrite default source if user select card
                    $source = $card_obj->stripe_id;
                }
                
                $result = $this->subscriptions_service->create_subscription($user_obj, $plan_obj, $source, 0);

                if($result)
                {
                    $this->success('xyzSubscription created');
                    return $this->redirect('/member/stripe_subscriptions/0');
                }

            }
   
            $current_plan = $this->stripe_plan_model->get($user_log->plan_id);

            if(!empty($current_plan))
            {
                $result =  $this->subscriptions_service->change_plan($user_obj, $plan_obj, $current_plan,  $card_obj);
                
                if($result)
                {
                    $this->success('xyzSubscription created');
                    return $this->redirect('/member/stripe_subscriptions/0');
                }
            }

            $this->success('xyzError creating subscription');
            return $this->redirect('/member/stripe_subscriptions/0');
        }
        catch(Exception $e)
        {
            $this->success('xyzError creating subscription');
            return $this->redirect('/member/stripe_subscriptions/0');
        }    
    }

    public function cancel_subscription()
    {
        $this->load->model('user_model');
        $session = $this->get_session();
        $user_id = $session['user_id'];
        $role_id = $session['role'];   
        $user_obj = $this->user_model->get($user_id);
       
        $subscription = $this->stripe_subscriptions_model->get_by_fields([
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
        
        if(empty($subscription))
        {
            $this->error('xyzSubscription not found');
            return $this->redirect('/member/stripe_subscriptions/0');
        }

        if($subscription->status == 5)
        {   
           $this->error('xyzSubscription already canceled');
           return $this->redirect('/member/stripe_subscriptions/0');
        }

        try
        {
            $stripe_subscription = $this->payment_service->cancel_subscription($subscription->stripe_id, FALSE, []);
            
            if(isset($stripe_subscription['id']))
            {
                $params = [
                    'cancel_at_period_end' => 1
                ];
                $this->stripe_subscriptions_model->edit($params,$subscription->id);
                $this->success('xyzSubscription canceled');
                return $this->redirect('/member/stripe_subscriptions/0');
            }
        }
        catch(Exception $e)
        {
            $this->error('xyzError canceling subscription');
            return $this->redirect('/member/stripe_subscriptions/0');
        }
    }

    public function reactivate_subscription()
    {
        $this->load->model('user_model');
        $session = $this->get_session();
        $user_id = $session['user_id'];
        $role_id = $session['role'];   
        $user_obj = $this->user_model->get($user_id);
       
        $subscription = $this->stripe_subscriptions_model->get_by_fields([
            'user_id' => $user_id,
            'role_id' => $role_id
        ]);
        
        if(empty($subscription))
        {
            $this->error('xyzSubscription not found');
            return $this->redirect('/member/stripe_subscriptions/0');
        }

        if($subscription->status == 5)
        {   
           $this->error('xyzSubscription already canceled');
           return $this->redirect('/member/stripe_subscriptions/0');
        }

        try
        {
            $stripe_subscription = $this->payment_service->reactivate_subscription($subscription->stripe_id, FALSE, []);
            
            if(isset($stripe_subscription['id']))
            {
                $params = [
                    'cancel_at_period_end' => 0
                ];
                $this->stripe_subscriptions_model->edit($params,$subscription->id);
                $this->success('xyzSubscription reactivated');
                return $this->redirect('/member/stripe_subscriptions/0');
            }
        }
        catch(Exception $e)
        {
            $this->error('xyzError canceling subscription');
            return $this->redirect('/member/stripe_subscriptions/0');
        }
    }
}