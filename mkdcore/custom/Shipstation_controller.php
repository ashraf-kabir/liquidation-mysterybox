<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * Home Controller to Manage all Frontend pages
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
 


class Shipstation_controller extends Manaknight_Controller
{
     
     

    public function __construct()
    {
        parent::__construct();  
        $this->load->model('category_model');
        $this->load->model('inventory_model'); 
        $this->load->model('physical_location_model'); 
    }

     
    public function ship_station_endpoint()
    {

        <?xml version="1.0" encoding="utf-8"?>
 
        $this->load->library('shipstation_api_service');
        $order_id       = 1;
        $order_no       = 3;
        $total          = 50;
        $tax            = 5;
        $ship_cost      = 9;

        $customer_note   = "testing";
        $internal_note   = "internal note";
        $customer_email  = "zeeshan72awan@gmail.com";
        $customer_name   = "Zeeshan Awan";

        $customer_company   = "Test";
        $customer_phone     = 0302;

        echo $this->shipstation_api_service->get_order($order_id,$order_no,$total,$tax,$ship_cost,$customer_note,$internal_note,$customer_email, $customer_name, $customer_company, $customer_phone );
        exit;
    }
 

}