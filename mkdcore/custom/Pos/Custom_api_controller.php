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

            $search_product_value = $this->input->post('search_product_value', TRUE);
            
            $inventory_items =  $this->inventory_model->get_all_inventory_products(['product_name' => $search_product_value , 'sku' => $search_product_value]);

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


    public function get_customer_search()
    {
        if($this->session->userdata('user_id'))
        {
             
            if(  $this->input->get('term')  )
            {
                $this->load->model('customer_model');

                $search_term = $this->input->get('term',TRUE);

                $customers_list = $this->customer_model->get_all_where_or_like(['name' => $search_term]);

                $customer_list = array();
                foreach($customers_list as $key => $customer)
                {
                    array_push($customer_list,array('text' => $customer->name . " ( " . $customer->phone . " ) ", 'id' => $customer->id));
                }
                 
                $output['results'] = $customer_list;
                echo json_encode($output); 
                exit();
            }   
        }
    } 
    

    public function add_customer()
    {
        
        if ($this->session->userdata('user_id')) 
        { 

            $this->load->model('customer_model');
            
            $first_name   = $this->input->post('firstname', TRUE);
            $last_name    = $this->input->post('lastname', TRUE);
            $email        = $this->input->post('email', TRUE);
            $phone        = $this->input->post('phone', TRUE);
            $customer_type    = $this->input->post('customer_type', TRUE);
            $company_name     = $this->input->post('company_name', TRUE); 
              
            $data_customer = array(
                'name'            => $first_name .' '. $last_name,
                'email'           => $email,
                'phone'           => $phone,
                'status'          => 1,
                'customer_type'   => $customer_type,
                'company_name'    => $company_name,
                'customer_since'  => Date('Y-m-d'),
            );

            if($customer_type == 2)
            {
                unset($data_customer['company_name']);
            }
            
            $result = $this->customer_model->create($data_customer); 

            if ($result) 
            {
                // if($this->input->post('customer_note') and !empty($this->input->post('customer_note')) )
                // {
                //     $customer_note = $this->input->post('customer_note');
                //     $data_note = array(
                //         'note' => $customer_note,
                //     ); 
                //     $this->customer_notes_model->create($data_note); 
                // }
                 
                $output['status'] = 200;
                $output['success'] = 'Customer has been created successfully.';

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




    /*
    *  API 
    */

    public function pos_get_all_active_customers()
    { 
        if ($this->session->userdata('user_id')) 
        {   
            $this->load->model('customer_model');
            $customers_list = $this->customer_model->get_all(['status' => 1]); 

            $output['status'] = 200;
            $output['customers_list'] = $customers_list; 
            echo json_encode($output);
            exit(); 
        } 
    }


    public function pos_customer_detail()
    { 
        if ($this->session->userdata('user_id')) 
        {   
            $customer_id      =  $this->input->post('customer_id', TRUE);
            $this->load->model('customer_model');

            $customer_detail  =  $this->customer_model->get_by_fields(['id' => $customer_id]); 

            $output['status'] = 200;
            $output['customer_detail'] = $customer_detail; 
            echo json_encode($output);
            exit(); 
        } 
    }



    public function pos_checkout_order()
    {
        
        if ($this->session->userdata('user_id')) 
        { 
            $pos_user_id = $this->session->userdata('user_id');
            
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model');
            $this->load->model('pos_order_items_model');
            $this->load->model('pos_cart_model');

            //list of data from cart
            $cart_items = $this->input->post('cart_items', TRUE);
           
            

            //discount
            


            /**
            * Refactor Customer Data 
            * 
            */
            $form_data = $this->input->post('form_data', TRUE); 
            $customer_data = array();
            foreach ($form_data as $form_data_key => $form_data_value) 
            {
                $form_data_value = (object) $form_data_value;
                $customer_data[$form_data_value->name] = $form_data_value->value;
            }
            
            
            $shipping_cost = 0;
            $tax           = 0;

            $discount = $this->input->post('discount', TRUE);
            
            // address
            // message

            /**
            * Create Order
            * 
            */ 
            $data_checkout_order = array( 
                'billing_name'      =>  $customer_data['name'],
                'billing_address'   =>  $customer_data['address'],
                'billing_country'   =>  $customer_data['country'], 
                'billing_state'     =>  $customer_data['state'], 
                'billing_city'      =>  $customer_data['city'], 
                'billing_zip'       =>  $customer_data['postal_code'], 
                'shipping_name'     =>  '', 
                'shipping_address'  =>  '', 
                'shipping_country'  =>  '', 
                'shipping_state'    =>  '', 
                'shipping_city'     =>  '', 
                'shipping_zip'      =>  '', 
                'subtotal'          =>  0, 
                'shipping_cost'     =>  $shipping_cost, 
                'tax'               =>  $tax, 
                'total'             =>  0, 
                'discount'          =>  $discount, 
                'order_type'        =>  2, 
                'payment_method'    =>  $customer_data['payment'], 
                'customer_id'       =>  $customer_data['customer_id'],
                'pos_user_id'       =>  $pos_user_id, 
                'status'            =>  1, 
                'pos_pickup_status' =>  1, 
            );

            $result = $this->pos_order_model->create($data_checkout_order);


            if ($result) 
            {

                
                
                

                /**
                * Store order items detail 
                * 
                */ 
                $order_id = $result;
                $sub_total   = 0;
                $grand_total = 0;

                foreach ($cart_items as $cart_item_key => $cart_item_value) 
                {

                    $total_amount = $cart_items[$cart_item_key]['price']  * $cart_items[$cart_item_key]['quantity'];
                    $data_order_detail = array(
                        'product_id'    => $cart_items[$cart_item_key]['id'],
                        'product_name'  => $cart_items[$cart_item_key]['name'], 
                        'amount'        => $total_amount, 
                        'quantity'      => $cart_items[$cart_item_key]['quantity'], 
                        'order_id'      => $order_id, 
                        'manifest_id'   => 1, 
                        'product_unit_price' => $cart_items[$cart_item_key]['price'],
                    );
                    $sub_total += $total_amount;
                    $result = $this->pos_order_items_model->create($data_order_detail);

                }


                //Add Customer Msg
                if(isset($customer_data['message']) and !empty($customer_data['message']))
                {
                    $data_order_note = array( 
                        'employee_name'      =>  $customer_data['name'],
                        'message_note'       =>  $customer_data['message'],
                        'order_id'           =>  $order_id,
                        'msg_type'           =>  2,
                    );
    
                    $this->pos_order_note_model->create($data_order_note); 
                }

                /**
                * Update prices  
                */
                $grand_total = $sub_total + $tax + $shipping_cost - $discount;
                $data_order_prices = array( 
                    'total'    =>  $grand_total,
                    'subtotal' =>  $sub_total,  
                );
                $result = $this->pos_order_model->edit($data_order_prices,$order_id);
                
                
                $user_id = $this->session->userdata('user_id'); 
                $result = $this->pos_cart_model->real_delete_by_fields(['user_id' => $user_id]);

                $output['status'] = 200;
                $output['success'] = 'Order has been created successfully.';

                $output['customer_name'] = $customer_data['name'];
                $output['order_id']      = $order_id;
                $output['address']       = $customer_data['address'];

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




