<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe_payments_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        parent::__construct();
        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config); 

    }

    public function index()
    {
        $this->load->view('MarketingPage');
    }

    public function subscribe()
    {

    }

    public function checkout()
    {

    }

}
