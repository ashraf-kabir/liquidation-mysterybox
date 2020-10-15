<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Payments Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 */

class Subscriptions_service{
  
    private $_user_model;
    private $_plan_model;
    private $_stripe_subscription_model;
    private $_payment_service;
    private $_subscription_log_model;
    private $_card_model;
    private $_ci = NULL;
    private $_user_id;
    private $_role_id;
    private $_prorate;
    private $_currency;
    private $_stripe_payments_model;


    public function init($user_id = 0, $role_id = 0)
    {
        $this->_user_id = $user_id;
        $this->_role_id = $role_id;
        $this->_ci = &get_instance();
        $this->_currency = $this->_ci->config->item('stripe_currency');
    }


    public function set_user_model($user_model)
    {
        $this->_user_model = $user_model;
    }

    public function set_stripe_payment_model($payments_model)
    {
        $this->_stripe_payments_model = $payments_model;
    }

    public function set_user_id($user_id)
    {
        $this->_user_id = $user_id;
    }

    public function set_role_id($role_id)
    {
        $this->_role_id = $role_id;
    }

    public function set_plan_model($plan_model)
    {
        $this->_plan_model = $plan_model;
    }

    public function set_stripe_subscription_model($stripe_subscription_model)
    {
        $this->_stripe_subscription_model = $stripe_subscription_model;
    }

    public function set_subscription_log_model($subscription_log_model)
    {
        $this->_subscription_log_model = $subscription_log_model;
    }

    public function set_card_model($card_model)
    {
        $this->_card_model = $card_model;
    }

    public function set_payment_service($payment_service)
    {
        $this->_payment_service = $payment_service;
    }

    /**
     * @param array $subscription_array
     * @param int $coupon_id
     * @param int $plan_id
     * @param int $user_id
     * @param int $role_id
     * @param int $order_id
     */
    private function _stripe_subscription($subscription_array, $coupon_id = 0, $plan_id = 0, $user_id = 0, $role_id = 0,  $order_id = 0)
    {
        $subscriptions_array = $subscription_array; 
        
        try
        {
            $subscription_result = $this->_payment_service->create_subscription( $subscriptions_array);
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

            $this->_stripe_subscription_model->create($stripe_subscription);
           
            $log_status = ( $status == 'active' ? 0 : 1);
            $log = $this->_subscription_log_model->get_last( $this->_user_id, $this->_role_id);

            if(empty($log))
            {
                $log_params = [
                    'user_id' => $this->_user_id,
                    'role_id' => $this->_role_id,
                    'plan_id' => $plan_obj->id,
                    'type' => 0,
                    'status' => $log_status 
                ];

                return $this->_subscription_log_model->create( $log_params);
            }
            else
            {
                $log_params = [
                    'plan_id' => $plan_obj->id,
                    'type' => 2,
                    'status' => $log_status 
                ];

                return $this->_subscription_log_model->edit( $log_params, $log->id);
            }

            return FALSE;
        }
        
        catch(Exception $e)
        {
           throw new Exception($e->getMessage());
        }
    }


    public function _free_subscription($user_id, $role_id, $plan_obj)
    {
        $log_params = [
            'user_id' => $user_id,
            'role_id' => $role_id,
            'plan_id' => $plan_obj->id,
            'type' => 1,
            'status' => 1
        ];

        return $this->_subscription_log_model->create( $log_params);
    }


    public function create_customer($user_obj, $source)
    {
        try
        {
            $stripe_client = $this->_payment_service->create_customer(['email' => $user_obj->email, 'source' => $source]);
            
            if(isset($stripe_client['id']))
            {
                $this->_user_model->edit(['stripe_id' => $stripe_client['id']], $user_obj->id);
                $card = $stripe_client['sources']['data'][0] ?? [];
                if(!empty($card))
                {
                    $card_params = [
                        'card_last' => $card['last4'] ?? " ",
                        'card_brand' =>  $card['brand'] ?? " ",
                        'card_exp_month' =>  $card['exp_month'] ?? " ",
                        'exp_year' => $card['exp_year'] ?? " ",
                        'card_name' =>  $card_name  ?? ( $card['brand'] ?? " "),
                        'stripe_card_customer' =>  $card['customer'],
                        'stripe_card_id' => $card['id'],
                        'is_default' => 1,
                        'user_id' => $user_obj->id,
                        'role_id' => $this->_role_id
                    ];
                        
                    $this->_stripe_cards_model->create($card_params);
                }

                return TRUE;
            }

            return FALSE;
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }


    public function _lifetime_subscription($user_obj, $role_id, $plan_obj, $source = '', $update_existing = FALSE)
    {
        /**
         * create payment amount
         * charge credit card with amount on plan
         * save the transaction
         * update subscription log
        */
        $args = [
            'amount' => $plan_obj->amount,
            'currency' =>  $this->_currency,
            'description' => "{$plan_obj->display_name} subscription"
        ];

        if(!empty($user_obj->stripe_id))
        {
            $args['customer'] = $user_obj->stripe_id;
        }

        if(!empty($source))
        {
            $args['source'] = $source;
        }

        try
        {
            $payment_result = $this->_payment_service->create_charge($args);
           
            if(isset($payment_result['id']))
            {
                $stripe_payment = [
                    'stripe_id' => $payment_result['id'] ?? "",
                    'object' =>  $payment_result['object'] ?? "",
                    'amount' =>  $payment_result['amount'] ?? "",
                    'balance_transaction' =>  $payment_result['balance_transaction'] ?? "",
                    'billing_details' =>  $payment_result['billing_details'] ?? "",
                    'currency' =>  $payment_result['currency'] ?? "",
                    'customer' =>  $payment_result['customer'] ?? "",
                    'description' =>  $payment_result['description'] ?? "",
                    'payment_method' =>  $payment_result['payment_method'] ?? "",
                    'refunded' =>  $payment_result['refunded'] ?? "",
                    'status' => $payment_result['status'] ?? ""
                ];
    
                $this->_stripe_payments_model->create($stripe_payment);

                $log = $this->_subscription_log_model->get_last( $this->_user_id, $this->_role_id);

                if(empty($log))
                {
                    $log_params = [
                        'user_id' => $this->_user_id,
                        'role_id' => $this->_role_id,
                        'plan_id' => $plan_obj->id,
                        'type' => 2,
                        'status' => ($stripe_payment['status'] == 'succeeded' ? 1 : 0 )
                    ];

                    return $this->_subscription_log_model->create( $log_params);
                }
                else
                {
                    $log_params = [
                        'plan_id' => $plan_obj->id,
                        'type' => 2,
                        'status' => ($stripe_payment['status'] == 'succeeded' ? 1 : 0 )
                    ];

                    return $this->_subscription_log_model->edit( $log_params, $log->id);
                }
    
                return TRUE;
            }

            return FALSE;
        }
        catch(Exception $e)
        {
            throw new Exception('exception ' .  $e->getMessage());
        }
    }

    
    public function _change_stripe_plan($current_subscription, $user_obj, $plan_obj, $source = '', $coupon_id=0)
    {
        try
        {
             /**
               * User does not have a active subscription
               * status 5 <subscription canceled>
               * @see stripe_subscription mapping stripe_subscription_model
             */
             if(empty($current_subscription) || $current_subscription->status == 5)
             {
                 $params = [
                     'customer' => $user_obj->stripe_id,
                     'items' => ['plan' => $plan_obj->stripe_id]
                 ];
 
                 return $this->_stripe_subscription($params, 0 , $plan_obj->id, $user_obj->id, $this->_role_id);
             }
 
             /**
               * user has a subscription but want to change plan
               *  status 5 <subscription canceled>
               * @see stripe_subscription mapping stripe_subscription_model
             */
             if(!empty($current_subscription) && $current_subscription->status != 5 )
             {
                if($plan_obj->id != $current_subscription->plan_Id)
                {
                    if(!empty($card_obj) && $card_obj->is_default !== 1)
                    {
                         $subscription_result = $this->_payment_service->update_subscription_plan($current_subscription->stripe_id, $plan_obj->stripe_id, $this->_prorate, $card_obj->stripe_card_id);
                         $source_changed = TRUE;
                    }
                    else
                    {
                        $subscription_result = $this->_payment_service->update_subscription_plan($current_subscription->stripe_id, $plan_obj->stripe_id, $this->_prorate);
                    }
                    if(isset($subscription_result['id']))
                    {
                        $update_params = [
                            'current_period_end' => date('Y-m-d', $subscription_result['current_period_start']),
                            'current_period_start' => date('Y-m-d', $subscription_result['current_period_end']),
                            'plan_id' => $plan_obj->id,
                            'cancel_at_period_end' => 0
                        ];
                        $this->_stripe_subscription_model->edit($update_params,$current_subscription->id);
                        
                        if($source_changed)
                        {
                            $this->_stripe_cards_model->update_default_card($user_id, $role_id, $card_id);
                        }
    
                        return TRUE;
                    }
                }                 
             }

            return FALSE;
        }
        catch(Exception $e)
        {
            return FALSE;
            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     * @param Object $user_obj
     * @param Object $plan_obj
     * @param String $source 
     */
    public function create_subscription($user_obj, $plan_obj, $source = '', $coupon_id=0)
    {   
        /**
         * type 0 = stripe plan source needed / customer stripe ID
        */

        if($plan_obj->type == 0  )
        {
            if($source == '' && $user_obj->stripe_id == '' )
            {
                throw new Exception('xyzNo payment method set');
                return;
            }
        }

        /**
         * type 2 == lifetime we need credit card token to create the charge
        */

        if($plan_obj->type == 2)
        {
            if($source == '' )
            {
                throw new Exception('xyzNo payment method set');
                return;
            }
        }

        $success = FALSE;
        
        $params = [
            'customer' => $user_obj->stripe_id,
            'items' => ['plan' => $plan_obj->stripe_id]
        ];

        try
        {
            switch($plan_obj->type)
            {
                case 0:
                    $success = $this->_stripe_subscription($params, $coupon_id, $plan_obj->id, $user_obj->id, $this->_role_id);
                break;

                case 1:
                    $success = $this->_free_subscription($user_obj->id, $this->_role_id, $plan_obj);
                break;

                case 2:
                    $success = $this-> _lifetime_subscription($user_obj, $this->_role_id , $plan_obj, $source);
                break;

            }

            return $success;
        }
        catch(Exception $e)
        {
            throw new Exception($e);
        }
    }


    /**
     * This assumes the plans are using the same product ID
     */
    public function change_plan( $user_obj,  $plan_obj, $current_plan,  $card_obj )
    {

        $current_subscription = $this->_stripe_subscription_model->get_by_fields([
            'user_id' => $this->_user_id,
            'role_id' => $this->_role_id
        ]);

        $result = FALSE;

        //both plans are stripe plans 
        if($plan_obj->type == 0 && $current_plan->type == 0)
        {
            $result = $this->_change_stripe_plan($user_obj, $plan_obj, $source, 0);
            
        }
        // current plan is stripe changing to non stripe plan cancel it first
    
        if( $current_plan->type == 0 && $plan_obj->type > 0)
        {
            try
            {
                $stripe_subscription = $this->_payment_service->cancel_subscription($current_subscription->stripe_id, TRUE, []);
                
                if(isset($stripe_subscription['id']))
                {
                    //cancel the subscription  force prorate           
                    $params = [
                        'status' => 5
                    ];
                    $this->_stripe_subscription_model->edit($params,$current_subscription->id);
                }

                return FALSE;
            }
            catch(Exception $e)
            {
                throw new Exception($e);
                return FALSE;
            }
        }
        
        if( $plan_obj->type > 0) 
        {
            //free plan 
            if($plan_obj->type == 1)
            {
                $result = $this->_free_subscription($user_obj->id, $this->_role_id, $plan_obj);
            }

            //lifetime plan
            if($plan_obj->type == 2)
            {
                $source = '';
                
                if(!empty($card_obj))
                {
                    $source = $card_obj->stripe_id;
                }

                $result = $this->_lifetime_subscription($user_obj, $this->_role_id , $plan_obj, $source );
            }
        }


        if($result == TRUE)
        {
            //now update the subscription log table

            $subscription_log = $this->_subscription_log_model->get_by_fields([
                'user_id' => $this->_user_id,
                'role_id' => $this->_role_id
            ]);

            if(!empty($subscription_log))
            {
                $params = [
                    'plan_id' => $plan_obj->id,
                    'type' => $plan_obj->type,
                    'status' => 1
                ];

                $this->_subscription_log_model->edit($params, $subscription_log->id);
                return TRUE;
            }
            else
            {
                $params = [
                    'user_id' => $this->user_id,
                    'role_id' => $this->role_id,
                    'plan_id' => $plan_obj->id,
                    'type' =>  $plan_obj->type,
                    'status' => 1
                ];
                $this->_subscription_log_model->create($params);
                return TRUE;
            }           
        }

        return FALSE;
    }


    public function check_subscription()
    {

    }

}