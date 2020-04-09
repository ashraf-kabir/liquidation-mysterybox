<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Cronjob_controller.php';

class Stripe_disputes_cronjob_controller extends CI_Controller{
    
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

    private function _get_disputes()
    {
        $new_disputes = [];
        $last_dispute = $this->db->order_by('id',"desc")->limit(1)->get('stripe_dissputes')->row();
        $dispute_params = [
            'limit' => 100
        ];
        
        if(!empty($last_dispute))
        {
            $dispute_params['created'] = [
                'gt' =>  $last_dispute->updated_at
            ];
        }

        try
        {
            $dispute_list = $this->payment_service->list_all_disputes($dispute_params);
            
            if(isset($dispute_list['object']))
            {
                $new_disputes = $dispute_list['data'];
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return [];
        }

        return $new_disputes;
    }
    
    public function index()
    {
        $this->load->model('stripe_disputes_model');
        $disputes = $this->_get_disputes();
        $params = [];
        if(!empty( $disputes))
        {
            foreach($disputes as $dispute)
            {
                $params[] = [
                    'stripe_id' => $dispute['id'],
                    'amount' => $dispute['amount'],
                    'currency' =>  $dispute['currency'],
                    'reason' => $dispute['reason'],
                    'status' => $dispute['status']
                ];
            }
        } 

        if(!empty($params))
        {
           $this->stripe_disputes_model->batch_insert($params);
        }
    }   
}