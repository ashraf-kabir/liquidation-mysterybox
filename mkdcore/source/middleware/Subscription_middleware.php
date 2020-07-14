<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * ACL Middleware
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Subscription_middleware
{

    private $_controller;
    private $_ci;
    
    public function __construct(&$controller, &$ci)
    {
        $this->_controller = $controller;
        $this->_ci = $ci;
        $this->_controller->load->database();
    }

    public function run()
    {
        $session = $this->_controller->get_session();
        $this->_controller->load->model('stripe_subscriptions_model');
    
        if(!empty($session))
        {
            $user_id = $session['user_id'];
            $role_id = $session['role'];
            $user_sub = $this->_controller->stripe_subscriptions_model->get_by_fields([
                'user_id' => $user_id,
                'role_id' => $role_id
            ]);
            
            $portal = $this->_controller->uri->segment(1);

            if(empty($user_sub))
            {
                $this->_controller->error('xyzSubscription required to access page');
                return $this->_controller->redirect("/{$portal}/stripe_subscriptions/0", 'refresh');
            }

            /**
             * status 5 equal canceled
             * @see subscription_model mapping
             */
            if($user_sub->status == 5)
            {
                $this->_controller->error('xyzSubscription required to access page');
                return $this->_controller->redirect("/{$portal}/stripe_subscriptions/0", 'refresh');
            }

            return FALSE;
    
        }
        $this->_controller->error('xyzSubscription required to access page');
        return $this->_controller->redirect("/{$portal}/stripe_subscriptions/0", 'refresh');
    }

}