<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Custom Controller to Manage all JS Api and ajax calls
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
*/

class Custom_api_controller extends CI_Controller
{
     
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();  

        
    }

    

    public function pos_get_all_inventory_items()
    {
        if($this->session->userdata('user_id'))
        {
            $this->load->model('inventory_model');

            $inventory_items =  $this->inventory_model->get_all();

            $output['status'] = 200;
            $output['products_list'] = $inventory_items; 
            echo json_encode($output);
            exit(); 
        }
    }


    public function pos_get_cart_items()
    {
        if($this->session->userdata('user_id'))
        {
            $user_id = $this->session->userdata('user_id');
            $this->load->model('pos_cart_model');
            $pos_cart_items_list =  $this->pos_cart_model->get_all(['user_id' => $user_id]);  
             
            $output['status'] = 200;
            $output['pos_cart_items_list'] = $pos_cart_items_list; 
            echo json_encode($output);
            exit(); 

        }
    }
    



    public function add_product_to_cart()
    {
        if($this->session->userdata('user_id'))
        {
            $this->load->model('pos_cart_model');

            
            $product_id   =  $this->input->post('id', TRUE);
            $product_qty  =  $this->input->post('quantity', TRUE);
            $product_name =  $this->input->post('item', TRUE);
            $unit_price   =  $this->input->post('price', TRUE);
            $total_price  =  $product_qty * $unit_price;

            $user_id = $this->session->userdata('user_id');

            /**
            * Check if product already in cart 
            *   if yes then add qty and do other price calculations
            *   if no add new product to cart
            */
            $check_chart_if_product =  $this->pos_cart_model->get_by_fields(['product_id' => $product_id,'user_id'       => $user_id]);  
            if (!empty($check_chart_if_product)) 
            {
                $product_data = $check_chart_if_product;

                $product_qty_now = $product_qty + $product_data->product_qty;
                $total_price_now = $product_qty_now * $unit_price;

                $data_cart = array(
                    'product_id'    => $product_id,
                    'product_qty'   => $product_qty_now,
                    'unit_price'    => $unit_price,
                    'total_price'   => $total_price_now,
                    'product_name'  => $product_name,
                    'user_id'       => $user_id,
                ); 
                $result = $this->pos_cart_model->edit($data_cart,$product_data->id); 
            }else{ 
            
                $data_cart = array(
                    'product_id'    => $product_id,
                    'product_qty'   => $product_qty,
                    'unit_price'    => $unit_price,
                    'total_price'   => $total_price,
                    'product_name'  => $product_name,
                    'user_id'       => $user_id,
                );

                $result = $this->pos_cart_model->create($data_cart); 
            }
            if ($result) 
            {
                $output['status'] = 200;
                $output['success'] = 'Your data has been added to cart successfully.';

                echo json_encode($output);
                exit();
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Please try again later.';
                echo json_encode($output);
                exit();
            }

        }
    }

    

    public function delete_cart_item()
    {
        if ($this->session->userdata('user_id')) 
        {  

            $this->load->model('pos_cart_model');
            $user_id    = $this->session->userdata('user_id'); 
            $product_id = $this->input->post('product_id', TRUE); 
            $result = $this->pos_cart_model->real_delete_by_fields(['user_id' => $user_id, 'product_id' => $product_id]); 

            if ($result) 
            {
                $output['status'] = 200;
                $output['success'] = 'Product has been deleted from cart successfully.'; 
                echo json_encode($output);
                exit();
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Please try again later.';
                echo json_encode($output);
                exit();
            } 
        } 
    }


    public function delete_cart_all()
    {
        if ($this->session->userdata('user_id')) 
        {  
            $this->load->model('pos_cart_model');
            $user_id = $this->session->userdata('user_id'); 
            $result = $this->pos_cart_model->real_delete_by_fields(['user_id' => $user_id]); 

            if ($result) 
            {
                $output['status'] = 200;
                $output['success'] = 'Your data has been removed from cart successfully.'; 
                echo json_encode($output);
                exit();
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Please try again later.';
                echo json_encode($output);
                exit();
            } 
        } 
    }


    

}




