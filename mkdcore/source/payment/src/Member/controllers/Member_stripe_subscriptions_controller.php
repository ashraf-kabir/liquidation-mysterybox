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

    private function subscribe($subscription_array, $coupon_id = 0, $plan_id = 0, $user_id = 0, $role_id = 0,  $order_id = 0)
    {
        $this->load->model('stripe_subscriptions_model');
        $subscriptions_array = $subscription_array;
        
        try
        {
            $subscription_result = $this->payment_service->create_subscription( $subscriptions_array);
            $status = $subscription_result['status'] ?? '';
            $stripe_subscription = [
                'stripe_id' =>  $subscription_result['id'] ?? "",
                'plan' =>  json_encode($subscription_result['plan'] ?? ""),
                'cancel_at_period_end' =>  $subscription_result['cancel_at_period_end'] ?? "",
                'current_period_start' =>  date('Y-m-d', $subscription_result['current_period_start']) ?? "",
                'current_period_end' =>  date('Y-m-d', $subscription_result['current_period_end']),
                'user_id' => $user_id ?? 0,
                'role_id' => $role_id,
                'plan_id' => $plan_id,
                'order_id' =>  $order_id ?? 0,
                'coupon_id' =>  $coupon_id ?? 0,
                'stripe_customer_id' => $subscription_result['customer'] ?? "",
                'subscription_interval' =>  $subscription_result['interval'] ?? "",
                'interval_count' =>  $subscription_result['interval_count'] ?? "",      
                'trial_period_days' =>  $subscription_result['trial_period_days'] ?? "",
                'trial_end' =>  $subscription_result['trial_end'] ?? "",
                'trial_start' =>  $subscription_result['trial_start'] ?? "",
                'status' => $subscription_result['status'] ?? ""
            ];

            $this->stripe_subscriptions_model->create($stripe_subscription);

            if($status == 'active')
            {
                return TRUE;
            }
            return FALSE;
        }
        catch(Exception $e)
        {
           throw new Exception($e);
        }
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

         $this->load->model('user_model');
         $this->load->model('stripe_plans_model');
         $session = $this->get_session();
         $user_id = $session['user_id'];
         $role_id = $session['role'];

         $user_obj = $this->user_model->get($user_id);
         $plan_obj = $this->stripe_plans_model->get($plan_id);

         if(empty($plan_obj))
         {
            $this->error('Invalid Plan');
            return $this->redirect('/member/stripe_subscriptions/0');  
         }

         if(empty($user_obj->stripe_id))
         {
            $error_message = "xyzPayment Method not found <a href='/member/stripe_cards/add' class='float-right'>xyzAdd Payment method</a>";
            $this->error( $error_message);
            return $this->redirect('/member/stripe_subscriptions/0');
         }

         $current_subscription = $this->stripe_subscriptions_model->get_by_fields([
             'user_id' => $user_id,
             'role_id' => $role_id
         ]);

         /**
          * User does not have a active subscription
          * status 5 <subscription canceled>
          * @see stripe_subscription mapping stripe_subscription_model
          */
         if(empty($current_subscription) || $current_subscription->status == 5)
         {
            try
            {
                $params = [
                    'customer' => $user_obj->stripe_id,
                    'items' => ['plan' => $plan_obj->stripe_id]
                ];

                $stripe_subscription = $this->subscribe($params, 0, $plan_obj->id , $user_id , $role_id, 0);

                if($stripe_subscription)
                {
                    $this->success('xyzNew subscription created');
                    return $this->redirect('/member/stripe_subscriptions/0');
                }

            }
            catch(Exception $e)
            {
                $this->success('xyzError creating subscription');
                return $this->redirect('/member/stripe_subscriptions/0');
            }
        }

        /**
         * user has a subscription but want to change plan
         *  status 5 <subscription canceled>
          * @see stripe_subscription mapping stripe_subscription_model
         */
        if(!empty($current_subscription) && $current_subscription->status != 5 )
        {
            if($plan_id != $current_subscription->plan_Id)
            {
                try
                {
                    $subscription_result = $this->payment_service->update_subscription_plan($current_subscription->stripe_id, $plan_obj->stripe_id);
                    
                    if(isset($subscription_result['id']))
                    {
                        $update_params = [
                            'current_period_end' => date('Y-m-d', $subscription_result['current_period_start']),
                            'current_period_start' => date('Y-m-d', $subscription_result['current_period_end']),
                            'plan_id' => $plan_obj->id,
                        ];

                        $this->stripe_subscriptions_model->edit($update_params,$current_subscription->id);
                        $this->success('xyzSubscription plan updated');
                        return $this->redirect('/member/stripe_subscriptions/0');
                    }
                }
                catch(Exception $e)
                {
                    $this->error('xyzError updating subscription plan');
                    return $this->redirect('/member/stripe_subscriptions/0');
                }
            }
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
}