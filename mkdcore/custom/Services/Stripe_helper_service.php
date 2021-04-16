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
    protected $_customer_model;
    protected $_customer_cards_model;


    public function set_config($config)
    {
        $this->_config = $config;
    }


    public function set_customer_model($customer_model)
    {
        $this->_customer_model = $customer_model;
    }


    public function set_customer_cards_model($customer_cards_model)
    {
        $this->_customer_cards_model = $customer_cards_model;
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
            catch (\Stripe\Exception\CardException $e)
            {
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();

            }
            catch (\Stripe\Exception\RateLimitException $e)
            {
// Too many requests made to the API too quickly
// echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\InvalidRequestException $e)
            {
// Invalid parameters were supplied to Stripe's API
// echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\AuthenticationException $e)
            {
// Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiConnectionException $e)
            {
// Network communication with Stripe failed
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiErrorException $e)
            {
// Display a very generic error to the user, and maybe send
// yourself an email
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (Exception $e)
            {
// Something else happened, completely unrelated to Stripe
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
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


    // public function create_stripe_charge($token_id, $amount, $description)
    public function create_stripe_charge($user_id, $user_card_id, $amount, $description)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key'); 

        Stripe::setApiKey( $stripe_secret_key );

        $get_user_data      = $this->_customer_model->get($user_id);
        $get_user_card_data = $this->_customer_cards_model->get($user_card_id);

        $stripe_customer_id = $get_user_data->stripe_id;
        $stripe_card_id     = $get_user_card_data->card_token;  

        if($stripe_customer_id && $amount)
        {
            $amount = str_replace(",", "", $amount);

            $price_total = 0;
            $price_total = $amount * 100; 
            $stripe_customer_id  = $stripe_customer_id;


            try 
            {
                $charge = Charge::create(array(
                    "amount"        => $price_total,
                    "currency"      => "usd",
                    "customer"      => $stripe_customer_id,
                    "source"        => $stripe_card_id,
                    "description"   => $description 
                )); 
                $output['success']   = true; 
                $output['response']  = $charge; 
                return $output;
                exit();
            } 
            catch (\Stripe\Exception\CardException $e)
            {
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();

            }
            catch (\Stripe\Exception\RateLimitException $e)
            {
// Too many requests made to the API too quickly
// echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\InvalidRequestException $e)
            {
// Invalid parameters were supplied to Stripe's API
// echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\AuthenticationException $e)
            {
// Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiConnectionException $e)
            {
// Network communication with Stripe failed
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiErrorException $e)
            {
// Display a very generic error to the user, and maybe send
// yourself an email
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (Exception $e)
            {
// Something else happened, completely unrelated to Stripe
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
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




    public function create_stripe_customer_with_card($customer_email, $stripe_token_id)
    {
        $stripe_secret_key = $this->_config->item('stripe_secret_key');
        Stripe::setApiKey($stripe_secret_key);

        if ($customer_email)
        {
            try
            {
                $customer_data = Customer::create([
                    'customer' => [
                        'email' => $customer_email,
                    ],
                ]);

                $card_data = Customer::createSource(
                    $customer_data->id,
                    [
                        'source' => $stripe_token_id,
                    ]
                );

                $output['success'] = TRUE;
                $output['card']    = $card_data;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\CardException $e)
            {
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\RateLimitException $e)
            {
                // Too many requests made to the API too quickly
                // echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\InvalidRequestException $e)
            {
                // Invalid parameters were supplied to Stripe's API
                // echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\AuthenticationException $e)
            {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiConnectionException $e)
            {
                // Network communication with Stripe failed
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiErrorException $e)
            {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (Exception $e)
            {
                // Something else happened, completely unrelated to Stripe
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
        }
        else
        {
            $output['error']     = TRUE;
            $output['error_msg'] = "Error! Please fill data correctly.";
            return $output;
            exit();
        }
    }

    public function add_new_card($stripe_token_id, $user_id)
    {
        $stripe_secret_key = $this->_config->item('stripe_secret_key');
        Stripe::setApiKey($stripe_secret_key);

        $get_user_data = $this->_customer_model->get($user_id);

        $stripe_customer_id = $get_user_data->stripe_id;

        if ($stripe_token_id && $stripe_customer_id)
        {
            try
            {
                // assign card to the user (not default)
                $card_data = Customer::createSource(
                    $stripe_customer_id,
                    [
                        'source' => $stripe_token_id,
                    ]
                );

                // set default (not needed)
                // $updated_customer_data = Customer::update(
                //   $stripe_customer_id,
                //   [
                //     'default_source' => $add_card_data->id,
                //   ]
                // );

                $output['success']   = TRUE;
                $output['card_data'] = $card_data;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\CardException $e)
            {
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\RateLimitException $e)
            {
                // Too many requests made to the API too quickly
                // echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\InvalidRequestException $e)
            {
                // Invalid parameters were supplied to Stripe's API
                // echo 'Message is:' . $e->getError()->message . '\n';

                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\AuthenticationException $e)
            {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiConnectionException $e)
            {
                // Network communication with Stripe failed
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (\Stripe\Exception\ApiErrorException $e)
            {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
            catch (Exception $e)
            {
                // Something else happened, completely unrelated to Stripe
                $output['error'] = TRUE;
                $output['error_msg']   = $e->getError()->message;
                return $output;
                exit();
            }
        }
        else
        {
            $output['error']     = TRUE;
            $output['error_msg'] = "Error! Please fill data correctly.";
            return $output;
            exit();
        }
    }
}