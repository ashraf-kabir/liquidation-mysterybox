<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
include_once __DIR__ . '/../../services/User_service.php';
/**
 * Home Controller to Manage all Frontend pages
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */




class Home_controller extends Manaknight_Controller
{
     
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();  
        $this->load->model('category_model');
        $this->load->model('inventory_model'); 
        $this->load->model('physical_location_model'); 
    }

    public function index($offset = 0)
    { 
        // $this->load->model('inventory_model');
        

        // $search_term = $this->input->get('search_term', TRUE);
        // $category_id = $this->input->get('category', TRUE);
        // $type_id     = $this->input->get('type', TRUE);

        // if($search_term != '')
        // {
        //     $this->load->model('search_terms_model');
        //     $this->load->library('search_record_service');

        //     $this->search_record_service->set_search_terms_model($this->search_terms_model); 
        //     $this->search_record_service->add_search_record($search_term); 
        // }

        // $this->load->library('pagination');
        // $rows_data = $this->inventory_model->all_products_list($type_id, $search_term, $category_id);

        // $total_rows = 0;
        // if(!empty($rows_data))
        // {
        //     $total_rows = count($rows_data);
        // }
        // $limit = 3;
        // $this->pagination->initialize([
        //     'reuse_query_string' => TRUE,
        //     'base_url' => 'http://localhost:9000/',
        //     'total_rows' => $total_rows,
        //     'per_page' => $limit,
        //     'num_links' => 4,
        //     'full_tag_open' => '<ul class="pagination justify-content-end">',
        //     'full_tag_close' => '</ul>',
        //     'attributes' => ['class' => 'page-link'],
        //     'first_link' => FALSE,
        //     'last_link' => FALSE,
        //     'first_tag_open' => '<li class="page-item">',
        //     'first_tag_close' => '</li>',
        //     'prev_link' => '&laquo',
        //     'prev_tag_open' => '<li class="page-item">',
        //     'prev_tag_close' => '</li>',
        //     'next_link' => '&raquo',
        //     'next_tag_open' => '<li class="page-item">',
        //     'next_tag_close' => '</li>',
        //     'last_tag_open' => '<li class="page-item">',
        //     'last_tag_close' => '</li>',
        //     'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
        //     'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
        //     'num_tag_open' => '<li class="page-item">',
        //     'num_tag_close' => '</li>'
        // ]);
        

        // $data['all_products']    = $this->inventory_model->all_products_list($type_id, $search_term, $category_id,$offset,$limit);
        
        

         
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Home',$data);
    }

    public function categories($offset = 0)
    {   
 

        $this->load->library('pagination');


        $this->_data['category']    =     $this->input->get('category', TRUE) != NULL  ? $this->input->get('category', TRUE) : NULL ;
        $this->_data['search_query']   =     $this->input->get('search_query', TRUE) != NULL  ? $this->input->get('search_query', TRUE) : NULL ; 
        $this->_data['type']           =     $this->input->get('type', TRUE) != NULL  ? $this->input->get('type', TRUE) : NULL ;
        
        $where = [ 
            'product_type'       => $this->_data['type'], 
            'category_id'        => $this->_data['category'], 
            'product_name'       => $this->_data['search_query'],  
            'sku'                => $this->_data['search_query'], 
            'status'             => 1
        ];
  
        $rows_data = $this->inventory_model->get_custom_count($where);
       
 
        $total_rows = $rows_data; 
        $limit = 3;

        $this->pagination->initialize([
            'reuse_query_string' => TRUE,
            'base_url'      => base_url() . "categories",
            'total_rows'    => $total_rows,
            'per_page'      => $limit,
            'num_links'     => 4,
            'full_tag_open' => '<ul class="pagination justify-content-end">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => FALSE,
            'last_link' => FALSE,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>'
        ]);

        $data['products_list']  = $this->inventory_model->get_custom_paginated($offset , $limit, $where );  
         

        $data['type']            = $this->_data['type'];  
        $data['category']        = $this->_data['category'];  
        $data['all_categories']  = $this->category_model->get_all(['status' => 1]); 
        
        
       

        $data['layout_clean_mode'] = FALSE;
        $data['active'] = 'home';
 
        $this->_render('Guest/Categories',$data);
             
    }
    



    public function contacts()
    {  

        if($this->input->post('email', TRUE))
        {
            $name         =  $this->input->post('name', TRUE);
            $from_email   =  $this->input->post('email', TRUE);
            $subject      =  $this->input->post('subject', TRUE);
            $subject      =  $subject . ' - ' . $name;
            $message      =  $this->input->post('message', TRUE); 

            if( $this->_send_email($from_email, $subject, $message, $name) )
            {
                $this->session->set_flashdata('success1','Your message has been sent successfully.');
            } else{
                $this->session->set_flashdata('error1','Error! Please try again later.');
            }  

            redirect($_SERVER['HTTP_REFERER']);
        }
        

        $data['active'] = 'contact';
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Contact',$data);
    }



    public function order_confirmation()
    {   
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Order_Confirmation',$data);
    }



    public function profile()
    {    

        if($this->session->userdata('customer_login'))
        {
            $this->load->model('customer_model');
            $customer = $this->customer_model->get($this->session->userdata('user_id'));

            if($this->input->post('name', TRUE)  )
            {

                $this->form_validation->set_rules('name', 'First Name', 'required');
                $this->form_validation->set_rules('phone', 'Phone', 'numeric');
                $this->form_validation->set_rules('billing_zip', 'Billing Zip', 'numeric');
                $this->form_validation->set_rules('shipping_zip', 'Shipping Zip', 'numeric');
                

                if ($this->form_validation->run() === FALSE)
                {
                    $error_msg = validation_errors(); 
                    $output['error'] = $error_msg;
                    echo json_encode($output);
                    exit();
                } 

                $name            =  $this->input->post('name', TRUE);
                $billing_zip     =  $this->input->post('billing_zip', TRUE);
                $billing_address =  $this->input->post('billing_address', TRUE);
                $billing_city    =  $this->input->post('billing_city', TRUE);
                $billing_state   =  $this->input->post('billing_state', TRUE);
                $billing_country =  $this->input->post('billing_country', TRUE);
                $phone           =  $this->input->post('phone', TRUE);

                $shipping_country       =  $this->input->post('shipping_country', TRUE);
                $shipping_state         =  $this->input->post('shipping_state', TRUE);
                $shipping_city          =  $this->input->post('shipping_city', TRUE);
                $shipping_zip           =  $this->input->post('shipping_zip', TRUE);
                $shipping_address       =  $this->input->post('shipping_address', TRUE);
                 
                $response = $this->customer_model->edit([
                    'name' => $name,
                    'billing_zip' => $billing_zip,
                    'billing_address' => $billing_address,
                    'billing_city' => $billing_city,
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country,
                    'phone' => $phone,
                    'shipping_address' => $shipping_address,
                    'shipping_zip' => $shipping_zip,
                    'shipping_city' => $shipping_city,
                    'shipping_state' => $shipping_state,
                    'shipping_country' => $shipping_country,
                ], $this->session->userdata('user_id'));


                if( $response )
                {
                    $output['status'] = 0;
                    $output['success']  = 'Profile has been updated successfully.'; 
                    echo json_encode($output);
                    exit();
                    
                }
                else
                {
                    $output['status'] = 0;
                    $output['error']  = "Error! Please try again later.";
                    echo json_encode($output);
                    exit();
                } 
                     
            }

            $data['customer'] = $customer;
            $data['active'] = 'profile';
            $data['layout_clean_mode'] = FALSE;
            $this->_render('Guest/Profile',$data);
        }
        else
        {
            redirect('');
        } 
    }





    public function cart()
    { 
        $data['active'] = 'cart';
        $data['layout_clean_mode'] = FALSE;
        $data['no_detail'] = TRUE;
        
        // if($this->session->userdata('customer_login'))
        // { 
            $user_id = $this->session->userdata('user_id');
            $this->load->model('pos_cart_model');
            $this->load->model('customer_model');
            $this->load->model('inventory_model');
            $this->load->model('tax_model');

            

            if ($this->session->userdata('user_id')) 
            { 
                $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            }
            else
            {
                $ip_address_user = $_SERVER['REMOTE_ADDR']; 
                $cart_items =  $this->pos_cart_model->get_all(['secret_key' => $ip_address_user]); 
            } 

            if(!empty($cart_items))
            {
                foreach ($cart_items as $cart_key => &$cart) 
                {
                    $data_i = $this->inventory_model->get($cart->product_id);
                    
                    $cart->feature_image = $data_i->feature_image;
                }
            }

            $data['cart_items'] = $cart_items;
            $data['customer']   =  $this->customer_model->get($user_id);  

            
            $data['tax']   =  $this->tax_model->get(1); 

            $this->_render('Guest/Cart',$data);  
        // }  
        // else
        // {
        //     redirect('');
        // }
    } 


    public function do_checkout()
    {  
        if($this->session->userdata('customer_login'))
        { 
             
            $user_id = $this->session->userdata('user_id');


            $form_data = $this->input->post('dataForm', TRUE); 
            $customer_data = array();
            foreach ($form_data as $form_data_key => $form_data_value) 
            {
                $form_data_value = (object) $form_data_value;
                $customer_data[$form_data_value->name] = $form_data_value->value;
            } 
            $_POST = $customer_data;
             
            // echo "<pre>";
            // print_r($_POST);
            // die();
            $this->form_validation->set_rules('full_name', "Name", "required|max_length[255]");
            $this->form_validation->set_rules('email_address', "Email", "valid_email"); 
            $this->form_validation->set_rules('postal_code', "Billing Postal Code", "integer");
            $this->form_validation->set_rules('city', "Billing City", "max_length[255]");
            $this->form_validation->set_rules('country', "Billing Country", "max_length[255]");
            $this->form_validation->set_rules('state', "Billing State", "max_length[255]"); 
            $this->form_validation->set_rules('address_1', "Billing Address", "required|min_length[5]"); 
            $this->form_validation->set_rules('payment', "Payment Method", "integer");
            $this->form_validation->set_rules('number', "Account Number", "required|integer");
            $this->form_validation->set_rules('exp_month', "Expiry Month", "required");
            $this->form_validation->set_rules('exp_year', "Expiry Year", "required");
            $this->form_validation->set_rules('cvc', "CVC", "required");

            $this->form_validation->set_rules('shipping_zip', "Shipping Zip", "integer");
            $this->form_validation->set_rules('shipping_city', "Shipping City", "max_length[255]");
            $this->form_validation->set_rules('shipping_country', "Shipping Country", "max_length[255]");
            $this->form_validation->set_rules('shipping_state', "Shipping State", "max_length[255]"); 
            $this->form_validation->set_rules('shipping_address', "Shipping Address", "required|min_length[5]"); 
              


            $this->load->model('pos_cart_model');
            $this->load->model('inventory_model');
            $this->load->library('helpers_service');
            $this->helpers_service->set_inventory_model($this->inventory_model);



            $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            if( empty($cart_items) )
            {   
                $output['status'] = 0;
                $output['error']  = 'Error! Please add item in cart first.';
                echo json_encode($output);
                exit();  
            }


            if ($this->form_validation->run() === FALSE)
            {
                $error_msg = validation_errors();
                $output['status'] = 0;
                $output['error']  = $error_msg;
                echo json_encode($output);
                exit();  
            }



            
            


            /**
            * Validate Items Quantity
            * and Item supports shipment 
            */ 
            $checkout_type = 2;
            foreach ($cart_items as $key => $cart_item_value) 
            {
                $cart_item_value = (object) $cart_item_value;
                 
                $check_quantity = $this->helpers_service->check_item_in_inventory($cart_item_value->product_id, $cart_item_value->product_qty, $cart_item_value->product_name, $checkout_type);

                if( isset($check_quantity->error) )
                {  
                    $output['status'] = 0;
                    $output['error']  = $check_quantity->error;
                    echo json_encode($output);
                    exit(); 
                }
            }


            $data['cart_items']  = $cart_items;
             


            $full_name      =  $this->input->post('full_name', TRUE);
            $email_address  =  $this->input->post('email_address', TRUE);
            $phone_number   =  $this->input->post('phone_number', TRUE);
            $city           =  $this->input->post('city', TRUE);
            $state          =  $this->input->post('state', TRUE);
            $country        =  $this->input->post('country', TRUE);
            $postal_code    =  $this->input->post('postal_code', TRUE);
            $address_1      =  $this->input->post('address_1', TRUE);
            $address_2      =  $this->input->post('address_2', TRUE);
            $payment        =  $this->input->post('payment', TRUE);
            $coupon_code    =  $this->input->post('coupon_code', TRUE);

            $shipping_cost_name         =  $this->input->post('shipping_cost_name', TRUE);
            $shipping_cost_value        =  $this->input->post('shipping_cost_value', TRUE);
            $shipping_service_id        =  $this->input->post('shipping_service_id', TRUE);


            $shipping_country        =  $this->input->post('shipping_country', TRUE);
            $shipping_state        =  $this->input->post('shipping_state', TRUE);
            $shipping_zip        =  $this->input->post('shipping_zip', TRUE);
            $shipping_city        =  $this->input->post('shipping_city', TRUE);
            $shipping_address        =  $this->input->post('shipping_address', TRUE);
 
 
             
            
            $this->load->model('customer_model');  
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model');
            $this->load->model('pos_order_items_model'); 
            $this->load->model('transactions_model');
            
            $this->load->model('customer_model'); 
            $this->load->model('coupon_model'); 
            $this->load->model('coupon_orders_log_model'); 
            $this->load->model('tax_model'); 

            $this->load->library('pos_checkout_service');
            $this->pos_checkout_service->set_pos_order_model($this->pos_order_model);
            $this->pos_checkout_service->set_coupon_model($this->coupon_model);


 

            /**
             * IF Coupon is used then validate
             * If Coupon is successful then use amount
             * 
            */
            $coupon_amount    = 0; 
            $coupon_condition = FALSE; 
            if(!empty($coupon_code))
            {
                $coupon_response = (object) $this->pos_checkout_service->checkout_verify_and_update_coupon( $coupon_code );
                     
                if( $coupon_response->success )
                {  
                    $coupon_amount    = $coupon_response->coupon_amount;   
                    $coupon_condition = TRUE; 
                }
                else
                { 
                    $output['status'] = 0;
                    $output['error']  = $coupon_response->error_msg;
                    echo json_encode($output);
                    exit(); 
                } 
            }



            $token_id = "";
            if($payment == 2)
            {
                $acc_number     =  $this->input->post('number', TRUE);
                $exp_month      =  $this->input->post('exp_month', TRUE);
                $exp_year       =  $this->input->post('exp_year', TRUE);
                $cvc            =  $this->input->post('cvc', TRUE);
    
                $this->load->library('stripe_helper_service');
    
                $this->stripe_helper_service->set_config($this->config);
                $response = $this->stripe_helper_service->create_stripe_token($acc_number, $exp_month, $exp_year, $cvc);
    
                
                if( isset($response['success']) )
                {
                    $token_id = $response['response']->id;
                }
                else
                { 
                    $output['status'] = 0;
                    $output['error']  = $response['error_msg'];
                    echo json_encode($output);
                    exit();  
                } 
            }
 



            $data['customer']       =  $this->customer_model->get($user_id);   
            $data['customer']->name =  $full_name; 


            /**
            * Cart Items  
            */
            $cart_items     =  $this->pos_cart_model->get_all(['customer_id' => $user_id ]); 
            $customer_data  =  $this->customer_model->get( $user_id ); 
            $shipping_cost  =  $shipping_cost_value;
            $discount       =  0;
            $tax            =  0;
             
            $tax_data       =  $this->tax_model->get(1); 

            $tax_amount  = 0;
            

            if (strtolower($state) == 'nv' or strtolower($state) == 'nevada') 
            {
               if(isset($tax_data->tax) )
                {
                  $tax_amount = $tax_data->tax/100;
                }  
            }
            // $this->db->trans_strict(TRUE);
            // $this->db->trans_begin();
 
            $customer_data->shipping_service_name  = $shipping_cost_name;
            $customer_data->shipping_service_id    = $shipping_service_id;
            $customer_data->name                   = $full_name;
            // $customer_data->shipping_service_id    = $email_address;
            // $customer_data->shipping_service_id    = $phone_number;
            $customer_data->city                   = $city;
            $customer_data->state                  = $state;
            $customer_data->country                = $country;
            $customer_data->billing_zip            = $postal_code;
            $customer_data->billing_address        = $address_1 . " " . $address_2;
            $customer_data->payment                = $payment;


            $customer_data->shipping_address              = $shipping_address;
            $customer_data->shipping_country              = $shipping_country;
            $customer_data->shipping_state                = $shipping_state;
            $customer_data->shipping_zip                  = $shipping_zip;
            $customer_data->shipping_city                 = $shipping_city;

            /**
            * Create Order 
            */ 

             
            $result = $this->pos_checkout_service->customer_create_order($customer_data,$tax,$discount,$user_id,$shipping_cost, $checkout_type);


            if ($result) 
            { 
                /**
                * Store order items detail 
                * 
                */ 
                $order_id    = $result;
                $sub_total   = 0;
                $grand_total = 0;

                foreach ($cart_items as $cart_item_key => $cart_item_value) 
                {
                    $inventory_data = $this->inventory_model->get($cart_item_value->product_id);   
                    $total_amount   = $cart_item_value->unit_price  * $cart_item_value->product_qty;

                    $data_order_detail = array(
                        'product_id'         => $cart_item_value->product_id,
                        'product_name'       => $cart_item_value->product_name, 
                        'amount'             => $total_amount, 
                        'quantity'           => $cart_item_value->product_qty, 
                        'order_id'           => $order_id, 
                        'manifest_id'        => $inventory_data->manifest_id, 
                        'category_id'        => $inventory_data->category_id,  
                        'pos_user_id'        => 0, 
                        'product_unit_price' => $cart_item_value->unit_price,
                    );
                    $sub_total += $total_amount;
                    $detail_id = $this->pos_order_items_model->create($data_order_detail); 

                    /**
                     *
                     * Product Type 2 = Generic 
                     * If 2 then don't decrease quantity 
                     * 
                    */
                    if($detail_id and $inventory_data->product_type != 2 )
                    {
                        $quantity_left = $inventory_data->quantity - $cart_item_value->product_qty;
                        $this->inventory_model->edit([
                            'quantity' => $quantity_left
                        ], $inventory_data->id ); 
                    }
                }
                


                //if coupon used then save log
                $coupon_log_id = "";
                if($coupon_condition)
                {
                    $coupon_log_id = $this->coupon_orders_log_model->create(['code' => $coupon_code, 'order_id' => $order_id, 'user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'amount' => $coupon_amount ]);
                }



                /**
                * Update prices  
                */ 
                $tax              =  $tax_amount * $sub_total;


                $grand_total =   $tax + $sub_total - $discount - $coupon_amount  +  $shipping_cost;
                $data_order_prices = array( 
                    'total'         =>  $grand_total,
                    'tax'           =>  $tax,
                    'subtotal'      =>  $sub_total,  
                    'coupon_log_id' =>  $coupon_log_id,  
                );
                $result = $this->pos_order_model->edit($data_order_prices, $order_id);


                /**
                * Add Transaction  
                */    
                $add_transaction = array(
                    'payment_type'      =>  $payment,
                    'customer_id'       =>  $user_id, 
                    'pos_user_id'       =>  0, 
                    'transaction_date'  =>  Date('Y-m-d'), 
                    'transaction_time'  =>  Date('g:i:s A'), 
                    'pos_order_id'      =>  $order_id, 
                    'tax'               =>  $tax,  
                    'discount'          =>  $discount, 
                    'subtotal'          =>  $sub_total, 
                    'total'             =>  $grand_total, 
                );
                $transaction_id = $this->transactions_model->create($add_transaction);
                
                

                if($transaction_id)
                {
                    $user_id = $this->session->userdata('user_id');  
                    // $output['order_id']      = $order_id;  
                     


                    if($payment == 2)
                    { 
                        $response = $this->stripe_helper_service->create_stripe_charge($token_id, $grand_total, "Ecom Order");
             
                        if( isset($response['success']) )
                        { 
                            $this->pos_order_model->edit(['intent_data' => json_encode($response['response']) ], $order_id);
                            $this->pos_cart_model->real_delete_by_fields(['customer_id' => $user_id]); 
                        }
                        else
                        { 
                            $output['status'] = 0;
                            $output['error']  = 'Error! Please try again later.';
                            echo json_encode($output);
                            exit(); 
                        }  
                    }



                    $this->pos_cart_model->real_delete_by_fields(['customer_id' => $user_id]); 


                    /**
                     * Send Order to Accounting System
                     *  
                    */ 
                    $accounting_response = $this->send_order_to_accounting( $order_id );
                    if( isset( $accounting_response->error_msg ) )
                    {
                        $output['status'] = 0;
                        $output['error']  = $accounting_response->error_msg;
                        echo json_encode($output);
                        exit();
                    }


                    /**
                     * Send Transaction to Accounting System
                     *  
                    */ 
                    $accounting_trans_response = $this->send_transaction_to_accounting( $transaction_id );
                    if( isset( $accounting_trans_response->error_msg ) )
                    {
                        $output['status'] = 0;
                        $output['error']  = $accounting_trans_response->error_msg;
                        echo json_encode($output);
                        exit();
                    }



                    /**
                     * Send Order to Shipping System
                     *  
                    */ 
                    $order_data = $this->send_order_to_shipper($order_id);

                    if( isset( $order_data->error_msg ) )
                    {
                        $output['status'] = 0;
                        $output['error']  = $order_data->error_msg;
                        echo json_encode($output);
                        exit();
                    }

                    $output['status'] = 0;
                    $output['success']  = 'Order has been created successfully.';
                    $output['redirect_url']  = base_url() . 'order_confirmation';
                    echo json_encode($output);
                    exit(); 
                }
                else
                {
                    $output['status'] = 0;
                    $output['error']  = 'Error! Please try again later.';
                    echo json_encode($output);
                    exit();   
                }
 
                
                
            }
            else
            {
                $output['status'] = 0;
                $output['error']  = 'Error! Please try again later.';
                echo json_encode($output);
                exit(); 
            }
        }
    }





    public function checkout()
    {   
        if($this->session->userdata('customer_login'))
        {
            $data['active'] = 'checkout';
            $data['layout_clean_mode'] = FALSE;
            $data['no_detail'] = TRUE;
    

            if($this->session->userdata('customer_login'))
            { 
                $user_id = $this->session->userdata('user_id');
                $this->load->model('pos_cart_model');
                $this->load->model('customer_model');
                $this->load->model('tax_model');
 


            

                $data['cart_items'] =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
                $data['customer']   =  $this->customer_model->get($user_id); 
                $data['tax']        =  $this->tax_model->get(1);
            }


            $this->_render('Guest/Checkout',$data);
        }
        else{
            redirect('');
        }
    }

    

    public function product($id = 0)
    { 
        $data['layout_clean_mode'] = FALSE;
        $this->load->model('inventory_gallery_list_model');
        
         
        $model  = $this->inventory_model->get_by_fields(['id' =>$id, 'status' => 1]); 
        if (!$model)
        {
            $this->error('Error');
            return redirect('/categories');
        }

        $this->load->library('names_helper_service');
        $this->load->model('category_model');
        $this->load->model('physical_location_model'); 


        
        $this->names_helper_service->set_category_model($this->category_model);
        $this->names_helper_service->set_physical_location_model($this->physical_location_model);

        $model->category_real_name = $this->names_helper_service->get_category_real_name( $model->category_id );
        $model->location_real_name = $this->names_helper_service->get_physical_location_real_name( $model->physical_location );

        $data['product']        =   $model;
        $data['gallery_lists']  =   $this->inventory_gallery_list_model->get_all(['inventory_id' => $id]);
 
        $data['no_detail'] = TRUE; 

        $this->_render('Guest/Product',$data);
    }

    
    public function about_us()
    {
        $data['layout_clean_mode'] = FALSE; 
        $data['no_detail'] = TRUE; 

        $this->_render('Guest/AboutUs',$data);
    }
     
    public function contact_us()
    {
        $data['layout_clean_mode'] = FALSE; 
        $data['no_detail'] = TRUE; 

        $this->_render('Guest/ContactUs',$data);
    }

     

    protected function _render($template, $data)
    {
        
        $all_categories  = $this->category_model->get_all(['status' => 1]);
         

        $data['all_categories']   = $all_categories;
        $data['page_section']     = $template;
        $data['contact_us_email'] = $this->config->item('contact_us_email'); 
         

        $this->load->view('Guest/Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Guest/Footer', $data);
    }

    protected function _send_email( $from_email ,$subject, $template, $name)
    { 
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp'); 
         
        $email = $this->config->item('contact_us_email'); 
        return $this->mail_service->send($from_email, $email, $subject, $template); 
        return FALSE;
    }




    public function sign_up()
    { 

        if($this->input->post('email', TRUE))
        {
            $name       = $this->input->post('first_name', TRUE);
            $email      = $this->input->post('email', TRUE);
            $password   = $this->input->post('password', TRUE); 
            $password   = password_hash($password, PASSWORD_BCRYPT); 
            
            $this->load->model('customer_model');

            $user = $this->customer_model->get_by_fields([
                'email'  => $email,  
            ]);

            if ($user)
            {
                $output['status'] = 0;
                $output['error'] = 'Error! Email already exists.';
                echo json_encode($output);
                exit();
            }

            $result = $this->customer_model->create([
                'name'      => $name,
                'email'     => $email,   
                'status'    => 1,  
            ]);
    
            if($result)
            {
                $this->customer_model->edit([ 'password' => $password ], $result);
                $output['status']   = 200;
                $output['success']  =  "Your account has been registered successfully,you can login now.";  
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



    public function login_customer()
    {
        if($this->input->post('email', TRUE))
        { 
            $email      = $this->input->post('email', TRUE);
            $password   = $this->input->post('password', TRUE); 

            $this->load->model('customer_model');

            $user = $this->customer_model->get_by_fields([
                'email'  => $email, 
                'status' => 1,
            ]);

            if ($user)
            {    
                if( password_verify($password, $user->password) )
                {  

                    $this->set_session('user_id', (int) $user->id); 
                    $this->set_session('email', (string) $user->email); 
                    $this->set_session('customer_login', 1);  

                    $this->add_user_id_for_orders();
                    $output['status'] = 0;
                    $output['success'] = 'Success!.';
                    echo json_encode($output);
                    exit();

                }
                else
                {
                    $output['status'] = 0;
                    $output['error'] = 'Error! Invalid email or password.';
                    echo json_encode($output);
                    exit();
                } 
            }
            else
            {
                $output['status'] = 0;
                $output['error'] = 'Error! Invalid password or email.';
                echo json_encode($output);
                exit();
            } 
        }
    }


    public function cart_remove($cart_id)
    {
        if ( $cart_id ) 
        {  
            $this->load->model('pos_cart_model');
            $this->load->model('inventory_model');  
            $cart_id = $cart_id; 


            // $model  = $this->pos_cart_model->get($cart_id);



            $result  = $this->pos_cart_model->real_delete_by_fields([ 'id' => $cart_id ]); 

            if ($result) 
            {  
                // if($model)
                // {
                //     $inventory = $this->inventory_model->get($model->product_id);
                //     $quantity  = $inventory->quantity + $model->product_qty;

                //     $this->inventory_model->edit(['quantity' => $quantity], $model->product_id);
                // }

                $this->session->set_flashdata('success1', 'Item has been deleted successfully.');
            }else{
                $this->session->set_flashdata('error1','Error! Please try again later.'); 
            }  
            return redirect($_SERVER['HTTP_REFERER']); 
        }
    }


    public function logout ()
    {
        $this->destroy_session();
        return $this->redirect('');
    }



    public function add_user_id_for_orders()
    {
        $this->load->model('pos_cart_model');

        $ip_address_user = $_SERVER['REMOTE_ADDR'];
        $user_id         = $this->session->userdata('user_id');

        $cart_items =  $this->pos_cart_model->get_all(['secret_key' => $ip_address_user]); 

        foreach ($cart_items as $cart_item_key => $cart_item_value) 
        {
            $this->pos_cart_model->edit(['customer_id' => $user_id], $cart_item_value->id); 
        }
    }





    public function check_cart_total_items()
    {

        
        $this->load->model('pos_cart_model');
          

        if ($this->session->userdata('user_id')) 
        { 
            $user_id = $this->session->userdata('user_id');
            $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
        }
        else
        {
            $ip_address_user = $_SERVER['REMOTE_ADDR']; 
            $cart_items =  $this->pos_cart_model->get_all(['secret_key' => $ip_address_user]); 
        }  

        $quantity = 0;
        foreach ($cart_items as $key => $value) 
        { 
            $quantity += $value->product_qty;
        }
        $output['cart_items'] = $quantity;

        echo json_encode($output);
        exit();  
    }

}