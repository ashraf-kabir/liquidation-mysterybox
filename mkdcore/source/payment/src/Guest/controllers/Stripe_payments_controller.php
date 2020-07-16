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

    private function _subscribe($subscription_array, $coupon_id = 0, $plan_id = 0, $user_id = 0, $role_id = 0,  $order_id = 0)
    {
        $this->load->model('stripe_subscriptions_model');
        $subscriptions_array = $subscription_array;
        
        try
        {
            $subscription_result = $this->payment_service->create_subscription( $subscriptions_array);
            $status = $subscription_result['status'] ?? '';
            $stripe_subscription = [
                'stripe_id' =>  $subscription_result['id'] ?? "",
                'plan' =>  json_encode($subscription_result['plan'] ?? ""),
                'cancel_at_period_end' =>  $subscription_result['cancel_at_period_end'] ?? "",
                'current_period_start' =>  date('Y-m-d', $subscription_result['current_period_start']) ?? "",
                'current_period_end' =>  date('Y-m-d', $subscription_result['current_period_end']),
                'user_id' => $user_id ?? 0,
                'role_id' => $role_id,
                'plan_id' => $plan_id,
                'order_id' =>  $order_id ?? 0,
                'coupon_id' =>  $coupon_id ?? 0,
                'stripe_customer_id' => $subscription_result['customer'] ?? "",
                'subscription_interval' =>  $subscription_result['interval'] ?? "",
                'interval_count' =>  $subscription_result['interval_count'] ?? "",      
                'trial_period_days' =>  $subscription_result['trial_period_days'] ?? "",
                'trial_end' =>  $subscription_result['trial_end'] ?? "",
                'trial_start' =>  $subscription_result['trial_start'] ?? "",
                'status' => $subscription_result['status'] ?? ""
            ];

            $this->stripe_subscriptions_model->create($stripe_subscription);

            if($status == 'active')
            {
                return TRUE;
            }
            return FALSE;
        }
        catch(Exception $e)
        {
           throw new Exception($e);
        }
    }

    public function index()
    {
        $data['title'] = 'xyzSubscribe';
        $data['site_title'] = 'Konfor';
        $this->load->view('Guest/Layout', $data);
    }

    public function subscribe()
    {
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->model('credential_model');
        $this->load->model('stripe_cards_model');
        $this->load->model('stripe_plans_model');
        $this->load->model('stripe_pending_email_model');

        $data['title'] = 'xyzSubscribe';
        $data['site_title'] = 'Konfor';
        $data['page'] = 'Guest/Subscribe';
        $data['success'] = '';
        $data['error'] = '';
        $this->form_validation->set_rules('plan_id', 'Plan', 'required');
        $this->form_validation->set_rules('card_name', 'Card Name', 'required');
        $this->form_validation->set_rules('stripeToken', 'Card', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[credential.email]');

        if ($this->form_validation->run() === FALSE)
		{
            $this->load->view('Guest/Layout', $data);
            return;
        }

        $plan_id = $this->input->post('plan_id');
        $card_name = $this->input->post('card_name');
        $source = $this->input->post('stripeToken');
        $email = $this->input->post('email');
        $plan_obj = $this->stripe_plans_model->get($plan_id);

        if(empty($plan_obj))
        {
            $data['error'] = 'xyzInvalid Plan';
            $this->load->view('Guest/Layout', $data);
            return;
        }

        $credential_params = [
            'email' => $email,
            'role_id' => 1,
            'refer' => uniqid(),
            'verify' => 0,
            'type' => 'n',
            'password' => str_replace('$2y$', '$2b$', password_hash(uniqid(), PASSWORD_BCRYPT))
        ];

        $credential_id = $this->credential_model->create($credential_params);
        
        $user_params = [
            "email" => $email,
            'first_name' => '',
            'last_name' => '',
            'phone' => '',
            'credential_id' => $credential_id
        ];

        $user_id = $this->user_model->create( $user_params);
        $role_id = 1;
       
        try
        {
            $stripe_client = $this->payment_service->create_customer(['email' => $email, 'source' => $source]);

            if(isset($stripe_client['id']))
            {
                $this->user_model->edit(['stripe_id' => $stripe_client['id']],$user_id);
                $card = $stripe_client['sources']['data'][0] ?? [];
                if(!empty($card))
                {
                    $card_params = [
                        'card_last' => $card['last4'] ?? " ",
                        'card_brand' =>  $card['brand'] ?? " ",
                        'card_exp_month' =>  $card['exp_month'] ?? " ",
                        'exp_year' => $card['exp_year'] ?? " ",
                        'card_name' =>  $card_name  ?? ( $card['brand'] ?? " "),
                        'stripe_card_customer' =>  $card['customer'],
                        'stripe_card_id' => $card['id'],
                        'is_default' => 1,
                        'user_id' => $user_id
                    ];
                        
                    $this->stripe_cards_model->create($card_params);
                } 
            }

            $params = [
                'customer' => $stripe_client['id'],
                'items' => ['plan' => $plan_obj->stripe_id]
            ];

            $stripe_subscription = $this->subscribe($params, 0, $plan_obj->id , $user_id , $role_id, 0);

            if($stripe_subscription)
            {
                $data['success'] = 'Subscription check your email to complete registration';
                $this->stripe_pending_email_model->remove($email);
                $this->load->view('Guest/Layout', $data);
                return;     
            }

            $data['error'] = 'Error creating subscription';
            $this->load->view('Guest/Layout', $data);
            return;

        }
        catch(Exception $e)
        {
            $data['error'] = $e->getMessage();
            $this->load->view('Guest/Layout', $data);
            return;
        }

        $this->load->view('Guest/Layout', $data);
        return;
    }

    public function checkout()
    {

    }

    public function save_email()
    {
        $this->load->model('stripe_pending_email_model');
        $email = $this->input->get('email');
        $obj = $this->stripe_pending_email_model->get_by_field('email', $email);
        if(!empty($email) && empty($obj))
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
