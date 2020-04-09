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

 
class Payment_controller extends CI_Controller{

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
        $this->load->database();
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
    
    private function pay($payment_array)
    {
        $this->load->model('stripe_payments_model');
        $payments_array = [$payment_array];
        
        try
        {
            $payment_result = $this->payment_service->create_charge($payments_array);
            $status = $payment_result['status'];
            
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

            $this->stripe_payments_model->create($stripe_payment);

            if($status == 'succeeded')
            {
                return TRUE;
            }
           
            return FALSE;
        }
        catch(Exception $e)
        {
            return FALSE;
        }
    }

    /**
     * @param string $email
     * @param string $password
     */
    private function send_registration_email($email, $password)
    {
       $id = $this->get_id();
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
        $email = $this->input->post('email');
        $password = $this->generate_password(8);
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $currency = $this->config->item('stripe_default_currency') ?? 'usd';

        $result = $this->user_model->create([
            'email' => $email,
            'password' =>  password_hash($password, PASSWORD_BCRYPT)
        ]);
        
        if($result)
        {
            $stripe_client = $this->payment_service->create_customer(['email' => $email]);
            
            if(isset($stripe_client['id'])) 
            {
                if($this->user_model->edit(['stripe_id' =>  $stripe_client['id'] ], $id))
                {
                    $payment_param = [
                        'customer' =>  $stripe_client['id'],
                        'receipt_email' => $email,
                        'amount' => $amount,
                        'currency' => $currency,
                        'description' => $description 
                    ];
                    
                    if($this->pay($payment_param))
                    {
                        echo "<h1>Payment Success</h1>";
                        exit();
                    }
                }
            }             
        }
        echo "<h1>Payment Failed</h1>";
        exit();
    }

    /**
     * @route api/v1/payment
     */
    public function payment()
    {
       $this->load->model('stripe_payments_model');
       $this->load->model('user_model');
       $user_id = $session['user_id'];
       $user_obj = $this->user_model->get($user_id);
       $source = $_POST["stripeToken"];
       $email = $user_obj->email;
       $amount = $this->input->post('amount');
       $description = $this->input->post('description');
       $currency = $this->config->item('stripe_default_currency') ?? 'usd';
       $stripe_id = $user_obj->stripe_id;
       $result = $this->pay([
           'amount' =>  $amount,
           'currency' => $currency,
           'description' => $description,
           'receipt_email' => $email,
           'customer' => $stripe_id
       ]);

       if($result)
       {
            echo "<h1>Payment Success</h1>";
            exit();           
       }
       
       echo "<h1>Payment Failed</h1>";
        exit(); 
    }

    /**
     * @route api/v1/payment/mobile
     */

     public function mobile_payment()
     {
        $this->load->model('stripe_payments_model');
        $this->load->model('user_model');
        $email = $this->input->post('email');
        $amount = $this->input->post('amount');
        $password = $this->generate_password(8);
        $stripe_client = $this->payment_service->create_customer(['email' => $email]);
        $currency = $this->config->item('stripe_default_currency') ?? 'usd';

        if(isset($stripe_client['id']))
        {
            $result = $this->user_model([
                'email' => $email,
                'password' =>  password_hash($password, PASSWORD_BCRYPT),
                'stripe_id' => $stripe_client['id']
            ]);

            $payment_params = [
                'customer' => $stripe_client['id'],
                'amount' => $amount,
                'currency' =>  $currency,
                'receipt_email' => $email 
            ];

            if($this->pay($payment_params))
            {      
                echo "<h1>Payment Success</h1>";
                exit();            
            }
        }
        echo "<h1>Payment Failed</h1>";
        exit();         
     }

     /**
      * @route api/v1/payment/user/mobile
     */

     public function user_mobile_payment()
     {
        echo " api/vi/payment/user/mobile";
        exit();
     }

}