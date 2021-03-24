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
use \Stripe\File;
use \Stripe\Exception;
use \Stripe\Event;
use \Stripe\InvoiceItem;
use \Stripe\PaymentMethod;
use \Stripe\PaymentIntent;


class Stripe_terminal_service { 
    
    private $_config;


    public function set_config($config)
    {
        $this->_config = $config;
    }




    public function stripe_terminal_connection_token()
    {
        // Set your secret key. Remember to switch to your live secret key in production!
        // See your keys here: https://dashboard.stripe.com/account/apikeys

        $stripe_secret_key  = $this->_config->item('stripe_secret_key'); 
        Stripe::setApiKey( $stripe_secret_key );  
        try 
        {
            // The ConnectionToken's secret lets you connect to any Stripe Terminal reader
            // and take payments with your Stripe account.
            // Be sure to authenticate the endpoint for creating connection tokens.
            $connectionToken = \Stripe\Terminal\ConnectionToken::create();

            $output['secret'] = $connectionToken->secret;
            return $output; 
            exit();

        }  
        catch (\Stripe\Exception\CardException $e)
        {
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
            
        }
        catch (\Stripe\Exception\RateLimitException $e)
        {
            // Too many requests made to the API too quickly
            // echo 'Message is:' . $e->getError()->message . '\n';

            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\InvalidRequestException $e)
        {
            // Invalid parameters were supplied to Stripe's API
            // echo 'Message is:' . $e->getError()->message . '\n';

            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\AuthenticationException $e)
        {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\ApiConnectionException $e)
        {
            // Network communication with Stripe failed
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\ApiErrorException $e)
        {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }

    }
    


    public function collect_payment($json_obj)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key'); 
        Stripe::setApiKey( $stripe_secret_key ); 

        try{

            $amount = $json_obj->amount * 100;
            // For Terminal payments, the 'payment_method_types' parameter must include
            // 'card_present' and the 'capture_method' must be set to 'manual'
            $intent = PaymentIntent::create([
                'amount'         => $amount,
                'currency'       => 'usd', 
                'capture_method' => 'manual',
                'payment_method_types' => ['card_present'],
            ]);
            
            $output['client_secret'] = $intent->client_secret;
            return $output; 

        } 
        catch (\Stripe\Exception\CardException $e)
        {
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
            
        }
        catch (\Stripe\Exception\RateLimitException $e)
        {
            // Too many requests made to the API too quickly
            // echo 'Message is:' . $e->getError()->message . '\n';

            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\InvalidRequestException $e)
        {
            // Invalid parameters were supplied to Stripe's API
            // echo 'Message is:' . $e->getError()->message . '\n';

            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\AuthenticationException $e)
        {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\ApiConnectionException $e)
        {
            // Network communication with Stripe failed
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
        catch (\Stripe\Exception\ApiErrorException $e)
        {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            
            $output['error']   = $e->getError()->message;
            return $output;
            exit();
        }
    }



    public function capture_payment($json_obj)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key'); 
        Stripe::setApiKey( $stripe_secret_key ); 
         

        try { 
            $intent = PaymentIntent::retrieve($json_obj->id);
            $intent = $intent->capture();
            

            $output['intent'] = $intent;
            return $output; 
            exit(); 
          
        } catch (Error $e) {
            $output['error'] = $e->getMessage();
            return $output;  
        }
          
    }





}

?>