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

 
class Subscription_api_controller extends  Member_api_controller{

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

    private function set_response($data)
    {
        return $this->output->set_content_type($this->_supported_formats[$this->_format])->set_status_header($data['code'])->set_output(json_encode($data));
    }
    
    private function subscribe($subscription_array, $coupon_id = 0, $plan_id = 0, $user_id = 0, $order_id = 0)
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
                'current_period_start' =>  $subscription_result['current_period_start'] ?? "",
                'user_id' => $user_id,
                'order_id' =>  $order_id,
                'coupon_id' =>  $coupon_id,
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

    /**
     * @param string $email
     * @param string $password
     */
    private function send_registration_email($email, $password)
    {
       //$id = $this->get_id();
       //echo json_encode('id' => $id)
    }

    /**
     * @route api/v1/subscription
     * @todo check coupons
     */
    public function subscribe_user()
    {
        $this->load->model('user_model');
        $this->load->model('stripe_plans_model');
        $this->load->library('form_validation');
    
        $this->form_validation->set_rules('plan_id', 'Plan', 'required');
        
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
        $plan_id = $this->input->post('plan_id');
        $user_obj = $this->user_model->get($user_id);
        $plan_obj = $this->stripe_plans_model->get( $plan_id);
        $coupon_obj = NULL;  
    
        try
        {
            $subscription_params = [
                'customer' => $user_obj->stripe_id,
                'items' =>  ["plan" => $plan_obj->stripe_id ] 
            ];
            $result = $this->subscribe($subscription_params, $coupon_obj->id ?? 0, $plan_id, $user_id, 0);
        }
        catch(Exception $e)
        {
            return $this->render(['code' => 500,'success' => FALSE,'message' => $e->getMessage()], 500);  
        }

        if($result)
        {
            return $this->render(['code' => 200,'success' => TRUE,'message' => 'xyzSubscription Success'], 200);  
        }
        return $this->render(['code' => 200,'success' => FALSE,'message' => 'xyzSubscription Failed'], 200); 
    }
    
    public function index()
    {
        $this->load->view('subscriptions',[]);
    }

  
    /**
     * @route api/v1/subscription/user
     */
    public function user_subscription()
    {
        $this->load->model('user_model');
        $this->load->model('stripe_plans_model');
        $this->load->model('stripe_coupons_model');
        $this->load->model('credential_model');

        $this->load->library('form_validation'); 
        
        $this->form_validation->set_rules('email', 'xyzEmail', 'required|is_unique[user.email]');
        $this->form_validation->set_rules('source', 'xyzCard', 'required');
        $this->form_validation->set_rules('plan_id', 'xyzPlan', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            return $this->render([
                'code' => 200,
                'success' => FALSE,
                'message' => strip_tags(validation_errors())
            ], 200);  
            exit();
        }
        $coupon = $this->input->post('coupon_slug');
        $email = $this->input->post('email');
        $password = $this->generate_password(8);
        $source = $this->input->post('source');
        $plan_id = $this->input->post('plan_id');
        $plan = $this->stripe_plans_model->get($plan_id);
        $coupon_obj = NULL;
        
        if(!empty($coupon))
        {
            $coupon_obj = $this->stripe_coupons_model->get_by_field('slug',$coupon);
        } 

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
                'phone' => " ",
                'credential_id' =>$credential_id
            ]);

            if($result)
            {
                try
                {
                    $stripe_client = $this->payment_service->create_customer(['email' => $email, 'source' => $source]);
                }
                catch(Exception $e)
                {
                    return $this->render(['code' => 500,'success' => FALSE,'message' => $e->getMessage()], 500);  
                }

                $this->send_registration_email($email, $password);

                if(isset($stripe_client['id']))
                {
                    if($this->user_model->edit(['stripe_id' =>  $stripe_client['id'] ], $result))
                    {
                        $subscription_params = [
                        
                            'customer' => $stripe_client['id'],
                            'items' => ['plan' => $plan->stripe_id]
                        ];
                        
                        if($coupon_obj !== NULL && !empty($coupon_obj->stripe_id) && (int) $coupon_obj->status === 1 )
                        {
                            $subscription_params['coupon'] = $coupon_obj->stripe_id;
                        }
                        
                        try
                        {
                            $stripe_subscription = $this->subscribe($subscription_params, $coupon_obj->id ?? 0, $plan_id , $result , 0);
                        } 
                        catch(Exception $e)
                        {
                            return $this->render(['code' => 500,'success' => FALSE,'message' => $e->getMessage()], 500);
                            exit(); 
                        }
                        
                        if($stripe_subscription === TRUE)
                        {
                            return $this->render(['code' => 200,'success' => TRUE,'message' => 'xyzSubscription Success'], 200);
                            exit(); 
                        }        
                    }
                }
            }
        }
        return $this->render([
            'code' => 200,
            'success' => TRUE,
            'message' => 'xyzSubscription Failed'
        ], 200); 
    }

    /**
     * @route api/v1/subscription/mobile
     */

     public function mobile_subscription()
     {
        $this->load->model('user_model');
        $this->load->model('stripe_plans_model');
        $this->load->model('stripe_coupons_model');
        $this->load->library('form_validation'); 
        
        $this->form_validation->set_rules('email', 'xyzEmail', 'required|is_unique[user.email]');
        $this->form_validation->set_rules('source', 'xyzCard', 'required');
        $this->form_validation->set_rules('plan_id', 'xyzPlan', 'required');

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
        $source = $this->input->post('source');
        $plan_id = $this->input->post('plan_id');
        $coupon = $this->input->post('coupon_slug');
        $order_id = $this->input->post('order_id') ?? 0;
        $plan_obj = $this->stripe_plans_model->get( $plan_id);
        $coupon_obj = NULL;
        
        if(!empty($coupon))
        {
            $coupon_obj = $this->stripe_coupons_model->get_by_field('slug',$coupon);
        } 
        
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
            $user_params = [
                'stripe_id' => $stripe_client['id'],
                'email' => $email,
                'role_id' => $this->_role_id,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ];
            
            $result = $this->user_model->create($user_params);

            if($result)
            {
                $this->send_registration_email($email, $password);
                
                $subscription_params = [
                   
                    'customer' => $stripe_client['id'],
                    'items' => ['plan' => $plan_obj->stripe_id ]
                ];

                if($coupon_obj !== NULL && !empty($coupon_obj->stripe_id) && (int) $coupon_obj->status === 1 )
                 {
                    $subscription_params['coupon'] = $coupon_obj->stripe_id;
                 }

                try
                {
                    $stripe_subscription = $this->subscribe($subscription_params, $coupon_obj->id ?? 0, $plan_id , $result , $order_id);
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
                
                if($stripe_subscription === TRUE)
                {
                    return $this->render([
                        'code' => 200,
                        'success' => TRUE,
                        'message' => 'xyzSubscription Success'
                    ], 200);
                }
            }
        }
        return $this->render([
            'code' => 200,
            'success' => FALSE,
            'message' => 'xyzSubscription Failed'
        ], 200);
     }

    /**
    * @route api/v1/subscription/user/mobile
    */
    public function mobile_user_subscription()
    {
        $this->load->model('user_model');
        $this->load->model('stripe_plans_model');
        $this->load->model('stripe_coupons_model');
       

        $user_id = $this->get_user_id();
        $source = $this->input->post('source');
        $plan_id = $this->input->post('plan_id') ?? 0;
        $coupon = $this->input->post('coupon_slug');
        $order_id = $this->input->post('order_id')  ?? 0;
        $user_obj = $this->user_model->get( $user_id);
        $plan = $this->stripe_plans_model->get($plan_id);
        $coupon_obj = NULL;
        
        if(!empty($coupon))
        {
            $coupon_obj = $this->stripe_coupons_model->get_by_field('slug',$coupon);
        } 

        if(!empty($user_obj) && !empty($user_obj->stripe_id))
        {
            $subscription_params = [
                'customer' => $user_obj->stripe_id,
                'items' => ['plan' => $plan->stripe_id]
            ];
            if($coupon_obj !== NULL && !empty($coupon_obj->stripe_id) && (int) $coupon_obj->status === 1 )
            {
                $subscription_params['coupon'] = $coupon_obj->stripe_id;
            }

            try
            {
                $stripe_subscription = $this->subscribe($subscription_params, $coupon_obj->id ?? 0 , $plan_id , $result , $order_id);    
            }
            catch(Exception $e)
            {
                return $this->render(['code' => 500,'success' => TRUE,'message' => $e->getMessage()], 500); 
            }
            
            if($stripe_subscription === TRUE)
            {
                return $this->render(['code' => 200,'success' => TRUE,'message' => 'xyzSubscription Success'], 200);
            }
        }
        return $this->render(['code' => 200,'success' => FALSE,'message' => 'xyzSubscription Failed'],200);
    }
}