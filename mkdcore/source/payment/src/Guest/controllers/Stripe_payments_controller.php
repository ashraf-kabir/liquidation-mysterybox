<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stripe_payments_controller extends CI_Controller
{
    public $_plans = [];
    public $_interval_mapping = [];
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('stripe_plans_model');
        $this->_plans = $this->stripe_plans_model->get_all();
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
        $data['title'] = 'xyzSubscribe';
        $data['site_title'] = 'Konfor';
        $this->load->view('Guest/Layout', $data);
    }

    public function subscribe()
    {

    }

    public function checkout()
    {

    }

    public function save_email()
    {
        $this->load->model('stripe_pending_email_model');
        $email = $this->input->get('email');
        if(!empty($email))
        {
            $params = [
                'email' => $email
            ];
            $this->stripe_pending_email_model->create($params);
        }
        echo json_encode([
            'error' => FALSE,
        ]);
        exit();
    }

}
