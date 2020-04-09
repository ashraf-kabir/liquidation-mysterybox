<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'Member_api_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Voice Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 *
 */

 
class Payment_api_controller extends  Member_api_controller{

    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public $_format = 'json';

    protected $_supported_formats = [
        'json' => 'application/json'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config);
    }

    private function generate_password($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        
        for ($i = 0; $i < $length; $i++)
        {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        
        return  $random_string;
    }
    
    private function pay($payment_array, $customer_id = 0)
    {
        $this->load->model('stripe_payments_model');
        
        try
        {
            $payment_result = $this->payment_service->create_charge($payment_array);
            
            if(isset($payment_result['id']))
            {
                $status = $payment_result['status'];
            
                $stripe_payment = [
                    'stripe_id' => $payment_result['id'] ?? "",
                    'object' =>  $payment_result['object'] ?? "",
                    'amount' =>  ( $this->payment_service->get_convert_to_cents() ? ($payment_result['amount'] / 100) : $payment_result['amount']) ?? "",
                    'customer_id' => $customer_id,
                    'payment_intent' => $payment_result['payment_intent'] ?? "",
                    'balance_transaction' =>  $payment_result['balance_transaction'] ?? "",
                    'billing_details' =>  $payment_result['billing_details'] ?? "",
                    'currency' =>  $payment_result['currency'] ?? "",
                    'customer' =>  $payment_result['customer'] ?? "",
                    'description' =>  $payment_result['description'] ?? "",
                    'payment_method' =>  $payment_result['payment_method'] ?? "",
                    'refunded' =>  $payment_result['refunded'] ?? "",
                    'status' => $payment_result['status'] ?? ""
                ];
    
                $this->stripe_payments_model->create($stripe_payment);
    
                if($status == 'succeeded')
                {
                    return TRUE;
                }
            }
           
            return FALSE;
        }
        catch(Exception $e)
        {
            throw new Exception($e);
        }
    }

    /**
     * @param string $email
     * @param string $password
     */
    private function send_registration_email($email, $password)
    {
       //$id = $this->get_id();
       //echo json_encode('id' => $id)
    }


    public function index()
    {
        $this->load->view('payments',[]);
    }
   
    /**
     * @route api/v1/payment/user
     */
    public function new_user_payment()
    {
        $this->load->model('stripe_payments_model');
        $this->load->model('user_model');
        $this->load->library('form_validation'); 
        $this->load->model('credential_model');

        $this->form_validation->set_rules('email', 'xyzEmail', 'required|is_unique[user.email]');
        $this->form_validation->set_rules('source', 'xyzCard', 'required');
        $this->form_validation->set_rules('amount', 'xyzAmount', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => strip_tags(validation_errors())
            ], 200);  
            exit();
        }

        $email = $this->input->post('email');
        $password = $this->generate_password(8);
        $amount = $this->input->post('amount');
        $source = $this->input->post('source');
        $description = $this->input->post('description');
        $currency = $this->config->item('stripe_default_currency') ?? 'usd';
       
        $credential_id = $this->credential_model->create([
            'email' => $email,
            'password' => str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT)),
            'role_id' => $this->_role_id,
            'type' => 'n' 
        ]);

        if($credential_id)
        {
            $result = $this->user_model->create([
                'email' => $email,
                'credential_id' => $credential_id,
                'phone' => " "
            ]);
            
            if($result)
            {
                try
                {
                    $stripe_client = $this->payment_service->create_customer(['email' => $email, 'source' => $source ]);
                }
                catch(Exception $e)
                {
                    return $this->render(['code' => 500,'success' => FALSE,'message' => $e->getMessage()], 500);  
                    exit();
                }

                if(isset($stripe_client['id'])) 
                {
                    if($this->user_model->edit(['stripe_id' =>  $stripe_client['id'] ], $result))
                    {
                        $payment_param = [
                            'receipt_email' => $email,
                            'amount' => $amount,
                            'customer' =>$stripe_client['id'],
                            'currency' => $currency,
                            'description' => $description 
                        ];
                        
                        try
                        {
                            if($this->pay($payment_param, $result))
                            {
                                $this->send_registration_email($email, $password);
                                return $this->render(['code' => 200,'success' => TRUE,'message' => 'xyzPaymentSuccess'], 200);
                                exit();
                            }
                        }
                        catch(Exception $e)
                        {
                            return $this->render(['code' => 500,'success' => FALSE,'message' => $e->getMessage()], 500);  
                            exit();
                        }
                    }
                }                 
            } 
        }    
        return $this->render(['code' => 200,'success' => FALSE,'message' => 'xyzPaymentFailed'], 200);
        exit();
    }

    /**
     * @route api/v1/payment
     * @todo can use customer card instead of requiring token
     */
    public function payment()
    {
       $this->load->model('stripe_payments_model');
       $this->load->model('user_model');
       $this->load->library('form_validation'); 
       $this->form_validation->set_rules('source', 'xyzCard', 'required');
       $this->form_validation->set_rules('amount', 'xyzAmount', 'required');
     
       if ($this->form_validation->run() === FALSE)
        {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => strip_tags(validation_errors())
            ], 200);  
            exit();
        }

       $user_id =  $this->get_user_id();
       $user_obj = $this->user_model->get($user_id);
       $email = $user_obj->email;
       $source =  $this->input->post('source');
       $amount = $this->input->post('amount');
       $description = $this->input->post('description');
       $currency = $this->config->item('stripe_default_currency') ?? 'usd';
       $stripe_id = $user_obj->stripe_id;
       try 
       {
            $result = $this->pay([
                'amount' =>  $amount,
                'currency' => $currency,
                'description' => $description,
                'customer' => $stripe_id,
                'receipt_email' => $email,
                'customer' => $stripe_id
            ], $user_id);
       }
       catch(Exception $e)
       {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => $e->getMessage()
            ], 200);  
            exit();
       }
       
       if($result)
       {
           return $this->render([
                'code' => 200,
                'success' => TRUE,
                'message' => 'xyzPaymentSuccess'
            ], 200);
            exit();           
       }
       
       return $this->render([
            'code' => 200,
            'success' => FALSE,
            'message' => 'xyzPaymentFailed'
        ], 200);
        exit(); 
    }

    /**
     * @route api/v1/payment/mobile
     */

     public function mobile_payment()
     {
        $this->load->model('stripe_payments_model');
        $this->load->library('form_validation'); 
        
        $this->form_validation->set_rules('source', 'xyzCard', 'required');
        $this->form_validation->set_rules('amount', 'xyzAmount', 'required');
        $this->form_validation->set_rules('email', 'xyzEmail', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => strip_tags(validation_errors())
            ], 200);  
            exit();
        }

        $email = $this->input->post('email');
        $amount = $this->input->post('amount');
        $source = $this->input->post('source');
        $currency = $this->config->item('stripe_default_currency') ?? 'usd';

        try
        {
            $stripe_client = $this->payment_service->create_customer(['email' => $email, 'source' => $source]);
        }
        catch(Exception $e)
        {
            return $this->render([
                'code' => 500,
                'success' => FALSE,
                'message' => $e->getMessage()
            ], 500);  
            exit();
        }
        
        if(isset($stripe_client['id']))
        {
            $payment_params = [
                'amount' => $amount,
                'customer' =>$stripe_client['id'],
                'currency' =>  $currency,
                'receipt_email' => $email 
            ];

            try
            {
                if($this->pay($payment_params, 0))
                {      
                    return $this->render([
                        'code' => 200,
                        'success' => TRUE,
                        'message' => 'xyzPaymentSuccess'
                    ], 200);
                    exit();            
                }
            }
            catch(Exception $e)
            {
                return $this->render([
                    'code' => 500,
                    'success' => FALSE,
                    'message' => $e->getMessage()
                ], 500);
                exit(); 
            }

        }
        return $this->render([
            'code' => 200,
            'success' => FALSE,
            'message' => 'xyzPaymentFailure'
        ], 200);
        exit();         
     }

     /**
      * @route api/v1/payment/user/mobile
     */

     public function user_mobile_payment()
     {
        $this->load->model('user_model');
        $this->load->library('form_validation'); 
        
        $this->form_validation->set_rules('source', 'xyzCard', 'required');
        $this->form_validation->set_rules('amount', 'xyzAmount', 'required');
        $this->form_validation->set_rules('email', 'xyzEmail', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => strip_tags(validation_errors())
            ], 200);  
            exit();
        }

        $user_id = $this->get_user_id();
        $email = $this->input->post('email');
        $amount = $this->input->post('amount');
        $source = $this->input->post('source');

        $payment_params = [
            'amount' => $amount,
            'source' => $source,
            'currency' =>  $currency,
            'receipt_email' => $email 
        ];

        try
        {
            if($this->pay($payment_params,0))
            {   
                return $this->render([
                    'code' => 200,
                    'success' => TRUE,
                    'message' => 'xyzPaymentSuccess'
                ], 200);
                exit();            
            }
        }
        catch(Exception $e)
        {
            return $this->render([
                'code' => 500,
                'success' => FALSE,
                'message' => $e->getMessage()
            ], 500);
            exit(); 
        }
        return $this->render([
            'code' => 200,
            'success' => FALSE,
            'message' => 'xyzPayment Failed'
        ], 200);
        exit();        
    }

}