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
class Member_stripe_subscriptions_api_controller extends  Member_api_controller{

    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public $_format = 'json';

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


    




}