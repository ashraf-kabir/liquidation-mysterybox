<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_product_controller extends CI_Controller
{
    public $_products = [];
    
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
        $this->load->model('payment_products_model');
    }

    public function index()
    {
        $data['products'] = $this->payment_products_model->get_all([
            'status' => 1
        ]);
    
        $this->load->view('Guest/Products', $data);
    }


    public function checkout($product)
    {
        $this->load->model('stripe_payments_model');
        $data['product'] = $this->payment_products_model->get($product);
        $data['error'] = '';
        $data['success'] = '';

        $this->form_validation->set_rules('quantity', 'xyzQuantity', 'required');
        $this->form_validation->set_rules('stripeToken', 'Card', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() === FALSE)
		{
            $this->load->view('Guest/Checkout', $data);
            return;
        }

        $email = $this->input->post('email');
        $source = $this->input->post('stripeToken');
        $quantity = $this->input->post('quantity');
        $amount =  $data['product']->price *   $quantity;

        
        $args = [
            'amount' => $amount,
            'currency' =>  $this->config->item('stripe_currency'),
            'description' => "{$data['product']->name} * {$quantity } checkout customer",
            'receipt_email' => $email,
            'source' => $source
        ];

        try
        {
            $payment_result = $this->payment_service->create_charge($args);
        }
        catch(Exception $e)
        {
            $data['error'] = $e->getMessage();           
            $this->load->view('Guest/Checkout', $data);    
        }

        if(isset($payment_result['id']))
        {
            $stripe_payment = [
                'stripe_id' => $payment_result['id'] ?? "",
                'object' =>  $payment_result['object'] ?? "",
                'amount' =>  ($payment_result['amount']  /100) ?? "",
                'balance_transaction' =>  $payment_result['balance_transaction'] ?? "",
                'billing_details' =>  $payment_result['billing_details'] ?? "",
                'currency' =>  $payment_result['currency'] ?? "",
                'customer' =>  $payment_result['customer'] ?? "",
                'description' =>  $payment_result['description'] ?? "",
                'payment_method' =>  $payment_result['payment_method'] ?? "",
                'refunded' =>  $payment_result['refunded'] ?? "",
                'status' => $payment_result['status'] ?? ""
            ];

            
            $result = $this->stripe_payments_model->create($stripe_payment);

            if($result)
            {
                redirect('/thank_you', 'refresh');
                return;
            }

            $data['error'] = 'xyxError creating payment';
        }

        $this->load->view('Guest/Checkout', $data);
    }

}