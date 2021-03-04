<?php
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
use \Stripe\Token;
use \Stripe\File;
use \Stripe\Exception;
use \Stripe\Event;
use \Stripe\InvoiceItem;
use \Stripe\PaymentMethod;
use \Stripe\PaymentIntent;

class Stripe_helper_service {  
    
    protected $_config;
    

    public function set_config($config)
    {
        $this->_config = $config;
    }

    public function create_stripe_token($number, $exp_month, $exp_year, $cvc)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key');  
        Stripe::setApiKey( $stripe_secret_key );
       
        if($number AND $exp_month AND $exp_year AND $cvc)
        { 
            try 
            {

                $token = Token::create([
                    'card' => [
                        'number'    => $number,
                        'exp_month' => $exp_month,
                        'exp_year'  => $exp_year,
                        'cvc'       => $cvc,
                    ],
                ]);

                $output['success']   = true; 
                $output['response']  = $token; 
                return $output;
                exit();
            }
            catch (Exception $e) 
            { 
                $output['error']     = true; 
                $output['error_msg'] = $e; 
                return $output;
                exit(); 
            }  
        } 
        else
        {
            $output['error']     = true; 
            $output['error_msg'] = "Error! Please fill data correctly."; 
            return $output;
            exit(); 
        }
    }
    
    
    public function create_stripe_charge($token_id, $amount, $description)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key'); 

        Stripe::setApiKey( $stripe_secret_key );

        if($token_id AND $amount)
        {
            $token  = $token_id;
            $amount = $amount;
            try 
            {
                $charge = Charge::create(array(
                    "amount"        => $amount * 100,
                    "currency"      => "usd",
                    "card"          => $token,
                    "description"   => $description 
                )); 
                $output['success']   = true; 
                $output['response']  = $charge; 
                return $output;
                exit();
            } 
            catch (Exception $e) 
            {
                $output['error']     = true; 
                $output['error_msg'] = $e; 
                return $output;
                exit(); 
            }  
        } 
        else
        {
            $output['error']     = true; 
            $output['error_msg'] = "Error! Token and Amount is required."; 
            return $output;
            exit(); 
        }
    }


}