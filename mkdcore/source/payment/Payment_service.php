<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Payments Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @see https://stripe.com/docs
 */

use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\Charge;
use \Stripe\Refund;
use \Stripe\Plan;
use \Stripe\Coupon;
use \Stripe\Product;
use \Stripe\Subscription;
use \Stripe\Invoice;
use \Stripe\Error;
use \Stripe\Webhook;
use \Stripe\Source;
use \Stripe\Dispute;
use \Stripe\File;
use \Stripe\Exception;
use \Stripe\Event;
use \Stripe\PaymentMethod;

class Payment_service{
    /**
     * @var boolean
     * 
    */
    public $_api_error = false;
    /**
     * Store stripe error_message
     * 
     * @var string
    */
    public $_error_msg = '';
    /**
     * STRIPE SECRET API KEY 
     *
     * @var string
     */
    public $_api_secret_key = '';

     /**
     * STRIPE PUBLISH API KEY 
     *
     * @var string
     */
    public $_api_publish_key = '';

    /**
     * CONVERT CENTS
     * @var boolean
    */
    private $_convert_to_cents = TRUE;

   
    public function __construct($params)
    {
        if(!empty($params['stripe_api_version']))
        {
            Stripe::setApiVersion($params['stripe_api_version']);
        }
        Stripe::setApiKey($params['stripe_secret_key']);
        $this->_api_secret_key = $params['stripe_secret_key'] ?? "";
        $this->_api_publish_key =  $params['stripe_publish_key'] ?? "";   
    }
    
    /**
     * Set sum time cents / dollars
     * 
     * @param boolean $set_convert_to_cents
     * @return void
     */
    public function set_convert_to_cents($should_convert)
    {
        $this->_convert_to_cents = $should_convert;
    }

    /**
     * Get covert to cents setting
     * @return boolean
    */
    public function get_convert_to_cents()
    {
        return $this->_convert_to_cents;
    }

    /**
     * Create stripe customer by email and stripe token.
     * 
     * @param array $customer_params
     * @return \Stripe\Customer|boolean
     */
    public function create_customer($customer_params)
    {
        //address
        $stripe_type = 'customer_create';
        $args['email'] = $customer_params['email'];
        
        if(isset( $customer_params['source']) &&  $customer_params['source'] !== NULL)
        {
            $args['source'] = $customer_params['source'];
        }
        
        if(isset( $customer_params['customer']) &&  $customer_params['customer'] !== NULL)
        {
            $args['customer'] = $customer_params['customer'];
        }
        
        if(isset($customer_params['description']) &&  $customer_params['description'] !== NULL)
        {
            $args['description'] = $customer_params['description'];
        }

        if(isset($customer_params['metadata']) && $customer_params['metadata'] !== NULL)
        {
            $args['metadata'] = $customer_params['metadata'];
        }

        if(isset($customer_params['name']) && $customer_params['name'] !== NULL)
        {
            $args['name'] = $customer_params['name'];
        }

        if(isset($customer_params['payment_method']) && $customer_params['payment_method'] !== NULL)
        {
            $args['payment_method'] = $customer_params['payment_method'];
        }

        if(isset($customer_params['phone']) && $customer_params['phone'] !== NULL)
        {
            $args['phone'] = $customer_params['phone'];
        }

        if(isset($customer_params['shipping']) && $customer_params['shipping'] !== NULL)
        {
            $args['shipping'] = $customer_params['shipping'];
        }
        $result = $this->stripe_master($stripe_type, $args);
        return $result;

    }


    /**
     * Retrieve the Customer from Stripe.
     * 
     * @param string $customer_id
     * @return \Stripe\Customer
     */

     public function update_customer_payment_method($customer_id, $source)
     {
        return Customer::update($customer_id, [
            'source' => $source,
        ]);
     }
 

    /**
     * Retrieve the Customer from Stripe.
     * 
     * @param string $customer_id
     * @return \Stripe\Customer
     */
    public function retrieve_customer($customer_id)
    {
        $stripe_type = 'customer_retrieve';
        $result = $this->stripe_master($stripe_type, $customer_id);
        return $result;
    }

    /**
     * Create create charge by customer id.
     * @param array $$charge_params
     * @return \Stripe\Charge|boolean
     */

    public function create_charge($charge_params)
    {
        $stripe_type = 'charge_create';
        $args = [
            'amount' => ($this->_convert_to_cents ? ($charge_params['amount'] * 100) : $charge_params['amount']),
            'currency' => $charge_params['currency'],
            'description' => $charge_params['description']
        ];

        if(isset($charge_params['customer']) && $charge_params['customer'] !== NULL)
        {
            $args['customer'] =  $charge_params['customer'];
        }

        if(isset($charge_params['payment_intent']) && $charge_params['payment_intent'] !== NULL)
        {
            $args['payment_intent'] =  $charge_params['payment_intent'];
        }

        if (isset($charge_params['metadata'])  && $charge_params['metadata']  !== NULL)
        {
            $args['metadata'] = $charge_params['metadata'];
        }

        if (isset($charge_params['source']) && $charge_params['source'] !== NULL)
        {
            $args['source'] = $charge_params['source'];
        }

        if(isset($charge_params['statement_descriptor']) && $charge_params['statement_descriptor'] !== NULL)
        {
            $args['statement_descriptor'] = $charge_params['statement_descriptor'];
        }

        if(isset($charge_params['receipt_email']) && $charge_params['receipt_email'] !== NULL)
        {
            $args['receipt_email'] = $charge_params['receipt_email'];
        }

        if(isset($charge_params['statement_descriptor']) && $charge_params['statement_descriptor'] !== NULL)
        {
            $args['statement_descriptor'] = $charge_params['statement_descriptor'];
        }

        if(isset($charge_params['statement_descriptor_suffix']) && $charge_params['statement_descriptor_suffix'] !== NULL)
        {
            $args['statement_descriptor_suffix'] = $charge_params['statement_descriptor_suffix'];
        }
        
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

    /**
     * Retrieve a charge.
     * @param string $charge_id
     * @return \Stripe\charge
    */
    public function retrieve_charge($charge_id)
    {
        $stripe_type = 'charge_retrieve';
        $result = $this->stripe_master($stripe_type, $charge_id);
        return $result;
    }


     /**
     * Create payment_method
     * 
     * @param array $payment_method_params
     * @return \Stripe\PaymentMethod
    */

    public function create_payment_method( $payment_method_params)
    {
        $stripe_type = 'payment_method_create';
        $args = [
            'type' => $payment_method_params['type']
        ];
        
        if(isset($payment_method_params['card']) && !empty($payment_method_params['card']))
        {
            $args['card'] = $payment_method_params['card'];
        }

        if(isset($payment_method_params['billing_details']) && !empty($payment_method_params['billing_details']))
        {
            $args['billing_details'] = $payment_method_params['billing_details'];
        }

        if(isset($payment_method_params['au_becs_debit']) && !empty($payment_method_params['au_becs_debit']))
        {
            $args['au_becs_debit'] = $payment_method_params['au_becs_debit'];
        }

        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }


     /**
     * Create payment_method
     * 
     * @param string $payment_method_id
     * @param string $stripe_customer_id
     * @return \Stripe\PaymentMethod
    */
    public function attach_payment_method($payment_method_id, $stripe_customer_id)
    {
        $stripe_type = 'payment_method_retrieve';
        $payment_method =  $this->stripe_master($stripe_type,$payment_method_id);
        
        if(isset($payment_method['id']))
        {
            return $payment_method->attach([
                'customer' => $stripe_customer_ids
            ]);
        }

        return NULL;

    }

    /**
     * Create product .
     * 
     * @param array $product_params
     * @return \Stripe\Product
    */
    public function create_product($product_params)
    {
        $stripe_type = 'product_create';
        $result = $this->stripe_master($stripe_type,$product_params);
        return $result;
    }

    public function create_refund($refund_params)
    {
        $stripe_type = 'refund_create';
        $args = [];

        if(isset($refund_params['charge']) && $refund_params['charge'] !== NULL)
        {
            $args['charge'] = $refund_params['charge'];
        }

        if(isset($refund_params['amount']) && $refund_params['amount'] !== NULL)
        {
            $args['amount'] = ($this->_convert_to_cents ? ($refund_params['amount'] * 100) : $refund_params['amount']);
        }
       
        if(isset($refund_params['metadata']) && $refund_params['metadata'] !== NULL)
        {
            $args['metadata'] = $refund_params['metadata'];
        }

        if(isset($refund_params['payment_intent']) && $refund_params['payment_intent'] !== NULL)
        {
            $args['payment_intent'] = $refund_params['payment_intent'];
        }

        if(isset($refund_params['reason']) && $refund_params['reason'] !== NULL)
        {
            $args['reason'] = $refund_params['reason'];
        }

        if(isset($refund_params['refund_application_fee']) && $refund_params['refund_application_fee'] !== NULL)
        {
            $args['refund_application_fee'] = $refund_params['refund_application_fee'];
        }

        if(isset($refund_params['reverse_transfer']) && $refund_params['reverse_transfer'] !== NULL)
        {
            $args['reverse_transfer'] = $refund_params['reverse_transfer'];
        }
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }


    

     /**
     * Create card by customer ID and token.
     * 
     * @param array $card_params
     * @return \Stripe\Card
     */
    public function create_card($card_params)
    {   
        $stripe_type = 'card_create';

        if(!empty($card_params['customer_stripe_id']) && !empty($card_params['source']))
        {   
            return Customer::createSource($card_params['customer_stripe_id'], ['source' => $card_params['source'] ]);
        }
        return NULL;
    }

    /**
     * retrieve card by customer ID and card ID.
     * 
     * @param string $customer_id
     * @param string $card_id
     * @return \Stripe\Card
    */
    public function retrieve_card($customer_id, $card_id)
    {
        $customer = $this->retrieve_customer($customer_id);
        if (!$customer)
        {
            return FALSE;
        }

        $card = $customer->sources->retrieve($card_id);
        return $card;
    }

    /**
     * delete card by customer ID and card ID.
     * 
     * @param string $customer_id
     * @param string $card_id
     * @return \Stripe\Card
     */
    public function delete_card($customer_id, $card_id)
    {
        $customer = $this->retrieve_customer($customer_id);
        if (!$customer)
        {
            return FALSE;
        }

        $delete = $customer->sources->retrieve($card_id)->delete();
        return $delete;
    }
    
    /**
     * Create File 
     * @param array file_params
     * @return \Stripe\File|boolean
    */
    public function create_file($file_params)
    {
        if(!empty($file_params['path']) && !empty($file_params['purpose']))
        {
            $fp = fopen($file_params['path'], 'r');
            return File::create([
                'purpose' => $file_params['purpose'],
                'file' => $fp
            ]);
        }
        return NULL;
    }

    /**
     * Decodes json data received by webhook
     * @param json  event_params
     * @return \Stripe\Event|boolean
    */
    public function get_stripe_event($event_params)
    {
        $stripe_type = 'stripe_events';
        $result = $this->stripe_master($stripe_type, $event_params);
        return $result;
    }

    /**
     * Create Coupon.
     * @param array $coupon_params
     * @return \Stripe\Coupon|boolean
    */
    public function create_coupon($coupon_params)
    {
       $stripe_type = 'coupon_create';
       
       $args = [
            'duration' => $coupon_params['duration'],      
       ]; 
       
       if(isset($coupon_params['amount_off']) && !empty($coupon_params['amount_off']))
       {
         $args['amount_off'] =  ($this->_convert_to_cents ? ($coupon_params['amount_off'] * 100) : $coupon_params['amount_off']);
       }

       if(isset($coupon_params['currency']) && !empty($coupon_params['currency']))
       {
         $args['currency'] = $coupon_params['currency'];
       }

       if(isset($coupon_params['duration_in_months']) && !empty($coupon_params['duration_in_months']))
       {
         $args['duration_in_months'] = $coupon_params['duration_in_months'];
       }

       if(isset($coupon_params['metadata']) && !empty($coupon_params['metadata']))
       {
         $args['metadata'] = $coupon_params['metadata'];
       }

       if(isset($coupon_params['name']) && !empty($coupon_params['name']))
       {
         $args['name'] = $coupon_params['name'];
       }

       if(isset($coupon_params['percent_off']) && !empty($coupon_params['percent_off']))
       {
         $args['percent_off'] = $coupon_params['percent_off'];
       }

       if(isset($coupon_params['max_redemptions']) && !empty($coupon_params['max_redemptions']))
       {
         $args['max_redemptions'] = $coupon_params['max_redemptions'];
       }

       if(isset($coupon_params['redeem_by']) && !empty($coupon_params['redeem_by']))
       {
         $args['redeem_by'] = $coupon_params['redeem_by'];
       }

       $result = $this->stripe_master($stripe_type, $args);
       return $result;
    }

    public function retrieve_coupon($coupon_id)
    {
        $stripe_type = 'coupon_retrieve';
        $result = $this->stripe_master($stripe_type, $coupon_params);
    }

    public function create_plan($plan_params)
    {
        $stripe_type = "plan_create";
        $args = [
            'amount' =>  ($this->_convert_to_cents ? ($plan_params['amount'] * 100) : $plan_params['amount']),
            'currency' => $plan_params['currency'],
            'interval' => $plan_params['interval'],
            'product' => $plan_params['product'],
        ];
        
        if(isset($plan_params['active']) && $plan_params['active'] !== NULL)
        {
            $args['active'] = $plan_params['active'];
        }

        if(isset($plan_params['metadata']) && $plan_params['metadata'] !== NULL)
        {
            $args['metadata'] = $plan_params['metadata'];
        }

        if(isset($plan_params['nickname']) && $plan_params['nickname'] !== NULL)
        {
            $args['nickname'] = $plan_params['nickname'];
        }

        if(isset($plan_params['id']) && $plan_params['id'] !== NULL)
        {
            $args['id'] = $plan_params['id'];
        }

        if(isset($plan_params['tiers']) && $plan_params['tiers'] !== NULL)
        {
            $args['tiers'] = $plan_params['tiers'];
        }

        if(isset($plan_params['tiers_mode']) && $plan_params['tiers_mode'] !== NULL)
        {
            $args['tiers_mode'] = $plan_params['tiers_mode'];
        }

        if(isset($plan_params['aggregate_usage']) && $plan_params['aggregate_usage'] !== NULL)
        {
            $args['aggregate_usage'] = $plan_params['aggregate_usage'];
        }

        if(isset($plan_params['amount_decimal']) && $plan_params['amount_decimal'] !== NULL)
        {
            $args['amount_decimal'] = $plan_params['amount_decimal'];
        }

        if(isset($plan_params['billing_scheme']) && $plan_params['billing_scheme'] !== NULL)
        {
            $args['billing_scheme'] = $plan_params['billing_scheme'];
        }

        if(isset($plan_params['interval_count']) && $plan_params['interval_count'] !== NULL)
        {
            $args['interval_count'] = $plan_params['interval_count'];
        }

        if(isset($plan_params['transform_usage']) && $plan_params['transform_usage'] !== NULL)
        {
            $args['transform_usage'] = $plan_params['transform_usage'];
        }

        if(isset($plan_params['trial_period_days']) && $plan_params['trial_period_days'] !== NULL)
        {
            $args['trial_period_days'] = $plan_params['trial_period_days'];
        }

        if(isset($plan_params['usage_type']) && $plan_params['usage_type'] !== NULL)
        {
            $args['usage_type'] = $plan_params['usage_type'];
        }
        
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

    /**
     * Retrieve plan by ID.
     * 
     * @param string $plan_id
     * @return \Stripe\Plan|boolean
    */
    public function retrieve_plan($plan_id)
    {
        $stripe_type = 'plan_retrieve';
        $args = $plan_id;
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

    /**
     * update plan by ID.
     * 
     * @param string $plan_id
     * @param array $data
     * @return \Stripe\Plan|boolean
     */
    
     public function update_plan($plan_id, $data)
    {
        //EOF validate provided parameters.
        $stripe_type = 'plan_update';
        $args = [];
        $args['plan_id'] = $plan_id;
        $args['data'] = $data;
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

     /**
     * List all disputes.
     * 
     * @param array $dispute_params
     * @return \Stripe\Dispute|boolean
     */
    public function list_all_disputes($dispute_params)
    {
        $stripe_type = 'list_all_disputes';
        $args = [];
        
        if(isset($dispute_params['charge']) && $dispute_params['charge'] !== NULL)
        {
            $args['charge'] = $dispute_params['charge'];
        }

        if(isset($dispute_params['payment_intent']) && $dispute_params['payment_intent'] !== NULL)
        {
            $args['payment_intent'] = $dispute_params['payment_intent'];
        }

        if(isset($dispute_params['created']) && $dispute_params['created'] !== NULL)
        {
            $args['created'] = $dispute_params['created'];
        }

        if(isset($dispute_params['ending_before']) && $dispute_params['ending_before'] !== NULL)
        {
            $args['ending_before'] = $dispute_params['ending_before'];
        }

        if(isset($dispute_params['starting_after']) && $dispute_params['starting_after'] !== NULL)
        {
            $args['starting_after'] = $dispute_params['starting_after'];
        }
        
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

     /**
     * List all disputes.
     * 
     * @param array $dispute_params
     * @return \Stripe\Dispute|boolean
     */
    public function update_disputes($dispute_params)
    {
        
    }


     /**
     * Create a subscription.
     * 
     * @param array subscription_params
     * @return \Stripe\Subscription|boolean
     */
    public function create_subscription($subscription_params)
    {
        $stripe_type = 'subscription_create';
        $args = [
            'customer' => $subscription_params['customer'],
            'items' => [$subscription_params['items']], 
        ];

        if(isset($subscription_params['cancel_at_period_end']) && $subscription_params['cancel_at_period_end'] !== NULL )
        {
            $args['cancel_at_period_end'] = $subscription_params['cancel_at_period_end'];
        }

        if(isset($subscription_params['default_payment_method']) && $subscription_params['default_payment_method'] !== NULL )
        {
            $args['default_payment_method'] = $subscription_params['default_payment_method'];
        }

        if(isset($subscription_params['metadata']) && $subscription_params['metadata'] !== NULL )
        {
            $args['metadata'] = $subscription_params['metadata'];
        }

        if(isset($subscription_params['application_fee_percent']) && $subscription_params['application_fee_percent'] !== NULL )
        {
            $args['application_fee_percent'] = $subscription_params['application_fee_percent'];
        }

        if(isset($subscription_params['backdate_start_date']) && $subscription_params['backdate_start_date'] !== NULL )
        {
            $args['backdate_start_date'] = $subscription_params['backdate_start_date'];
        }

        if(isset($subscription_params['billing_cycle_anchor']) && $subscription_params['billing_cycle_anchor'] !== NULL )
        {
            $args['billing_cycle_anchor'] = $subscription_params['billing_cycle_anchor'];
        }

        if(isset($subscription_params['billing_thresholds']) && $subscription_params['billing_thresholds'] !== NULL )
        {
            $args['billing_thresholds'] = $subscription_params['billing_thresholds'];
        }

        if(isset($subscription_params['cancel_at']) && $subscription_params['cancel_at'] !== NULL )
        {
            $args['cancel_at'] = $subscription_params['cancel_at'];
        }

        if(isset($subscription_params['collection_method']) && $subscription_params['collection_method'] !== NULL )
        {
            $args['collection_method'] = $subscription_params['collection_method'];
        }

        if(isset($subscription_params['coupon']) && $subscription_params['coupon'] !== NULL )
        {
            $args['coupon'] = $subscription_params['coupon'];
        }

        if(isset($subscription_params['days_until_due']) && $subscription_params['days_until_due'] !== NULL )
        {
            $args['days_until_due'] = $subscription_params['days_until_due'];
        }

        if(isset($subscription_params['default_source']) && $subscription_params['default_source'] !== NULL )
        {
            $args['default_source'] = $subscription_params['default_source'];
        }

        if(isset($subscription_params['default_tax_rates']) && $subscription_params['default_tax_rates'] !== NULL )
        {
            $args['default_tax_rates'] = $subscription_params['default_tax_rates'];
        }

        if(isset($subscription_params['off_session']) && $subscription_params['off_session'] !== NULL )
        {
            $args['off_session'] = $subscription_params['off_session'];
        }

        if(isset($subscription_params['payment_behavior']) && $subscription_params['payment_behavior'] !== NULL )
        {
            $args['payment_behavior'] = $subscription_params['payment_behavior'];
        }

        if(isset($subscription_params['pending_invoice_item_interval']) && $subscription_params['pending_invoice_item_interval'] !== NULL )
        {
            $args['pending_invoice_item_interval'] = $subscription_params['pending_invoice_item_interval'];
        }

        if(isset($subscription_params['proration_behavior']) && $subscription_params['proration_behavior'] !== NULL )
        {
            $args['proration_behavior'] = $subscription_params['proration_behavior'];
        }

        if(isset($subscription_params['trial_end']) && $subscription_params['trial_end'] !== NULL )
        {
            $args['trial_end'] = $subscription_params['trial_end'];
        }

        if(isset($subscription_params['trial_from_plan']) && $subscription_params['trial_from_plan'] !== NULL )
        {
            $args['trial_from_plan'] = $subscription_params['trial_from_plan'];
        }

        if(isset($subscription_params['trial_period_days']) && $subscription_params['trial_period_days'] !== NULL )
        {
            $args['trial_period_days'] = $subscription_params['trial_period_days'];
        }

        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

     /**
     * Retrieve a subscription.
     * 
     * @param string $subscription_id
     * @return \Stripe\Subscription|boolean
     */
    public function retrieve_subscription($subscription_id)
    {
        $stripe_type = 'subscription_retrieve';
        $args = $subscription_id;
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

     /**
     * Cancel a subscription.
     * 
     * @param string $subscription_id
     * @param boolean $force_cancel
     * @param array $cancellation_params
     * @return \Stripe\Subscription|boolean
     */
    public function cancel_subscription($subscription_id, $force_cancel = FALSE, $cancellation_params = [])
    {
        $subscription = $this->retrieve_subscription($subscription_id);
        
        if ($force_cancel)
        {
            $result = $subscription->delete($cancellation_params);
        }
        else
        {
            $subscription->cancel_at_period_end= TRUE;
            $result = $subscription->save();
        }
        return $result;
    }

    /**
     * Reactivate a subscription.
     * 
     * @param string $subscription_id
     * @return \Stripe\Subscription|boolean
     */
    public function reactivate_subscription($subscription_id)
    {
        $subscription = $this->retrieve_subscription($subscription_id);
        if (!$subscription)
        {
            return FALSE;
        }
        $subscription->cancel_at_period_end = FALSE;
        $result = $subscription->save();
        return $result;
    }

     /**
     * Update a subscription.
     * 
     * @param string $subscription_id
     * @param array $update_data
     * @return \Stripe\Subscription|boolean
     */
    public function update_subscription($subscription_id, $update_data, $default_source = '')
    {
        $stripe_type = 'subscription_update';
        $args = [
            'subscription_id' => $subscription_id,
            'data' => $update_data
        ];

        if(!empty($default_source))
        {
            $args['default_source'] = $default_source;
        }
        $result = $this->stripe_master($stripe_type, $args);
        return $result;
    }

    /**
     * Upgrading or Downgrading Plan in a subscription.
     * 
     * @param string $subscription_id
     * @param string $plan_id
     * @param boolean $prorate
     * @return \Stripe\Subscription|boolean
     */
    public function update_subscription_plan($subscription_id, $plan_id, $prorate = FALSE,$default_source = '' )
    {
        $subscription = $this->retrieve_subscription($subscription_id);
        if (!$subscription)
        {
            return FALSE;
        }
        $proration_date = time();
        $stripe_type = 'subscription_update';

        //cancel trial time if subscription is running in trial
        if ($subscription->status == 'trialing')
        {
            $trial_end_data = [
                'subscription_id' => $subscription_id,
                'data' => [
                    'trial_end' => 'now'
                ]
            ];
            $trial_end_result = $this->stripe_master($stripe_type, $trial_end_data);
            if (!$trial_end_result)
            {
                return FALSE;
            }
        }
        //EOF cancel trial time if subscription is running in trial

        $args['subscription_id'] = $subscription_id;
        $items = [
            [
                'id' => $subscription->items->data[0]->id,
                'plan' => $plan_id
            ]
        ];

        $args['data'] = [
            'cancel_at_period_end' => false,
            'items' => $items
        ];

        if ($prorate === FALSE)
        {
            $args['data']['prorate'] = FALSE;
        }
        else
        {
            $args['data']['prorate'] = TRUE;
            $args['data']['proration_date'] = $proration_date;
        }
        $result = $this->stripe_master($stripe_type, $args);

        //Immediately send invoice if $prorate is not FALSE
        if ($prorate != FALSE && $result)
        {
            $stripe_type = 'invoice_upcoming';
            $args = [
                'customer' => $subscription->customer,
                'subscription' => $subscription_id,
                'subscription_items' => $items,
                'subscription_proration_date' => $proration_date,
            ];
            $invoice_upcoming_result = $this->stripe_master($stripe_type, $args);
            if (!$invoice_upcoming_result)
            {
                return FALSE;
            }
        }
        // update payment method
        if(!empty($default_source))
        {
            $args['default_source'] = $default_source;
        }
        //EOF immediately send invoice if $prorate is not FALSE
        return $result;
    }


    /**
     * Upgrading or Downgrading Plan in a subscription.
     * 
     * @param string $invoice_id
     * @return \Stripe\Invoice|boolean
     */
    public function send_reminder_invoice($invoice_id)
    {
        $stripe_type  = 'invoice_retrieve';
        $invoice = $this->stripe_master($stripe_type,$invoice_id);
        return $invoice->sendInvoice();
    }

    /**
     * Make actual call to stripe API
     *
     * @param string $stripe_type
     * @param mixed $args
     * @return mixed
    */
    protected function stripe_master($stripe_type, $args)
    {
        try
        {
            switch ($stripe_type)
            {
                case 'customer_create':
                    $return = Customer::create($args);
                    break;
                case 'customer_retrieve':
                    $return = Customer::retrieve($args);
                    break;
                case 'customer_all':
                    $return = Customer::all($args);
                    break;

                case 'product_create':
                    $return = Product::create($args);
                    break;

                case 'charge_create':
                    $return = Charge::create($args);
                    break;
                case 'charge_retrieve':
                    $return = Charge::retrieve($args);
                    break;
                case 'charge_all':
                    $return = Charge::all($args);
                    break;

                case 'refund_create':
                    $return = Refund::create($args);
                    break;
                case 'refund_retrieve':
                    $return = Refund::retrieve($args);
                    break;
                case 'refund_all':
                    $return = Refund::all($args);
                    break;

                case 'plan_create':
                    $return = Plan::create($args);
                    break;
                case 'plan_retrieve':
                    $return = Plan::retrieve($args);
                    break;
                case 'plan_update':
                    $return = Plan::retrieve($args['plan_id']);
                    foreach ($args['data'] as $key => $value)
                    {
                        $return->$key = $value;
                    }
                    $return->save();
                    break;

                case 'subscription_create':
                    $return = Subscription::create($args);
                    break;
                case 'subscription_retrieve':
                    $return = Subscription::retrieve($args);
                    break;
                case 'subscription_update':
                    $return = Subscription::update($args['subscription_id'], $args['data']);
                    break;

                case 'coupon_create':
                    $return = Coupon::create($args);
                    break;
                case 'coupon_retrieve':
                    $return = Coupon::retrieve($args);
                    break;
                case 'coupon_update':
                    $return = Coupon::create($args);
                    break;

                case 'invoice_create':
                    $return = Invoice::create($args);
                    break;
                case 'invoice_retrieve':
                    $return = Invoice::retrieve($args);
                    break;
                case 'list_all_invoices':
                    $return = Invoice::all($args);
                    break;
                case 'invoice_upcoming':
                    $return = Invoice::upcoming($args);
                    break;

                case 'source_create':
                    $return = Source::create($args);
                    break;

                case 'dispute_retrieve':
                    $return = Dispute::retrieve($args);
                    break;

                case 'list_all_disputes':
                    $return = Dispute::all($args);
                    break;
                
                case 'stripe_events':
                    $return = Event::constructFrom(json_decode($args, true));
                    break;
                
                case 'payment_method_create':
                    $return = PaymentMethod::create($args);
                    break;
                case 'payment_method_retrieve':
                    $return = PaymentMethod::retrieve($args);
                    break;
                
            }
            return $return;
        }
        catch(CardException $e) 
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Since it\'s a decline, \Stripe\Exception\CardException will be caught');
            $this->error_handler($e);
        } 
        catch (RateLimitException $e) 
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Too many requests made to the API too quickly');
            $this->error_handler($e);
        }

        catch (InvalidRequestException $e)
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Invalid parameters were supplied to Stripe\'s API');
            $this->error_handler($e);
        }

        catch (AuthenticationException $e) 
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Authentication with Stripe\'s API failed maybe you changed API keys recently');
            $this->error_handler($e);   
        }

        catch (ApiConnectionException $e) 
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Network communication with Stripe failed');
            $this->error_handler($e);   
        }

        catch (ApiErrorException $e) 
        {    
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Display a very generic error to the user, and maybe send  yourself an email');
            $this->error_handler($e); 
        }

        catch(UnexpectedValueException $e)
        {
            $this->_error_msg =  $e->getError()->message;
            $this->_api_error = true;
            log_message('error', 'Stripe webhook exception');
            $this->error_handler($e); 
        }

        catch (Exception $e) 
        {
            $this->_error_msg =  $e->getMessage();
            $this->_api_error = true;
            log_message('error', 'Something else happened, completely unrelated to Stripe');
            $this->error_handler($e);
        }
    
        return FALSE;
    }

    /**
    * error log in <pre> tag.
    * 
    * @param string $error_text
    * @return formatted error text with <pre> tag.
    */
    public function pre_log($error_text)
    {
        log_message('error', '<pre>' . print_r($error_text, true) . '</pre>');
    }

    /**
    * Handle display an errors.
    * 
    * @param $error
    */
    private function error_handler($error)
    {
        log_message('error', print_r($err, true));
        $this->pre_log('Status is:' . $e->getHttpStatus() . "\n");

        if (isset($e->getError()->code))
        {
            $this->pre_log('Code is:' .  $e->getError()->code . "\n");
        }

        if (isset($e->getError()->type))
        {
            $this->pre_log('Type is:' . $e->getError()->type . "\n");
        }

        if (isset( $e->getError()->param))
        {
            $this->pre_log('Param is:' . $e->getError()->param . "\n");
        }

        $this->pre_log('Message is:' . $e->getError()->message . "\n");
    }
}