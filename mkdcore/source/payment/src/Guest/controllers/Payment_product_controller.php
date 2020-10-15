<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_product_controller extends CI_Controller
{
    public $_plans = [];
    public $_interval_mapping = [];
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
        $this->_interval_mapping = $this->stripe_plans_model->subscription_interval_mapping();

        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config); 

    }

    public function index()
    {
        $this->load->model('payment_products_model');
        $data['product'] = $this->payment_products_model->get_all([
            'status' => 1
        ]);
        $this->load->view('Guest/Products');

    }


}