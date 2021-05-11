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

        if ( $this->input->get('showshow', TRUE)  ) 
        {
            $showshow = $this->input->get('showshow', TRUE);
            if ( strtolower($showshow) == 'facebook') 
            {
                $this->set_session('referrer', 2);  
            }


            if ( strtolower($showshow) == 'instagram') 
            {
                $this->set_session('referrer', 3);  
            }



            if (strtolower($showshow) == 'tiktok') 
            {
                $this->set_session('referrer', 4);  
            }


        }
    }

    public function index($offset = 0)
    {  
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Home',$data);
    }

    public function categories($offset = 0)
    {   
 

        $this->load->library('pagination');


        $this->_data['category']       =     $this->input->get('category', TRUE) != NULL  ? $this->input->get('category', TRUE) : NULL ;
        $this->_data['search_query']   =     $this->input->get('search_term', TRUE) != NULL  ? $this->input->get('search_term', TRUE) : NULL ; 
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



    public function forgot_password()
    {  
        

        if($this->input->post('email', TRUE))
        {
            $email         =  $this->input->post('email', TRUE);
             
 
            $this->load->model('customer_model'); 
            $model = $this->customer_model->get_by_fields(['email' => $email ]);

            if ($model)
            {     

                $this->load->library('mail_service');
                $this->mail_service->set_adapter('smtp');

                $token_b = uniqid();
                $email_b = $email;

                $data['reset_link'] = base_url() . 'update_password/' . $token_b;


                $output = $this->customer_model->edit(['token' => $token_b], $model->id );

                if ($output) 
                { 
                    ob_start();  
                    $this->load->view('Guest/ResetPasswordTemplate', $data); 
                    $content = ob_get_contents(); 
                    ob_end_clean(); 

                     
                    $from = $this->config->item('from_email');
                    

                    $response = $this->mail_service->send($from, $email, "Reset your password", $content);

                    if( $response )
                    {
                        $this->session->set_flashdata('success1','Success! Please check your mail for further instructions.');
                        redirect($_SERVER['HTTP_REFERER']);
                    }   
                }

                $this->session->set_flashdata('error1','Error! Please try again later.');
                redirect($_SERVER['HTTP_REFERER']);
            }  

              
            $this->session->set_flashdata('error1','Error! Invalid Email/Password.'); 
            redirect($_SERVER['HTTP_REFERER']);
        }
        
 
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/ForgotPassword',$data);
    }


    public function update_password($token)
    {  
        

        if($token)
        {
            $token         =  $token;  
            $this->load->model('customer_model'); 
            $model = $this->customer_model->get_by_fields(['token' => $token]);

            if (!$model)
            {     
                return redirect('/forgot_password');
            }  
              
            $data['layout_clean_mode'] = FALSE;
            $data['token'] = $token;
            $this->_render('Guest/ChangePassword',$data);
        } 
        else
        {
            return redirect('');
        }
    }



    public function set_new_password()
    {  
        

        if($this->input->post('token_b', TRUE))
        {
            $token             =  $this->input->post('token_b', TRUE);
            $password          =  $this->input->post('password', TRUE);
            $password2         =  $this->input->post('password2', TRUE);


            if ($password != $password2) 
            {
                $this->session->set_flashdata('error1','Error! Both password should be same.'); 
                redirect($_SERVER['HTTP_REFERER']);
            }

            $this->load->model('customer_model'); 
            $model = $this->customer_model->get_by_fields(['token' => $token]);

            if ($model) 
            {
                $password   = password_hash($password, PASSWORD_BCRYPT); 
                $response = $this->customer_model->edit([ 'password' => $password ,'token' => '' ], $model->id);

                if ($response) 
                {
                    $this->session->set_flashdata('success1','Success! Your password has been updated successfully.');
                    
                    redirect('');
                } 
            }
            
            $this->session->set_flashdata('error1','Error! Please try again later.'); 
            redirect($_SERVER['HTTP_REFERER']);
        } 
        else
        {
            return redirect('');
        }
    }



    public function order_confirmation()
    {   
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Order_Confirmation',$data);
    }



    public function profile()
    {    

        if($this->session->userdata('customer_login') && $this->session->userdata('user_id'))
        {
            $this->load->model('customer_model');
            $customer = $this->customer_model->get($this->session->userdata('user_id'));

            if( $this->input->post('name', TRUE)  || $this->input->post('address_fill_form', TRUE)  )
            {
                
                if (!$this->input->post('address_fill_form', TRUE) ) 
                {
                    $this->form_validation->set_rules('name', "Name", "required|max_length[255]");
                    $this->form_validation->set_rules('phone', 'Phone', 'numeric|max_length[15]');
                }
                

                $this->form_validation->set_rules('billing_address', "Billing Address", "required|min_length[5]");
                $this->form_validation->set_rules('billing_zip', "Billing Zip Code", "required|integer|max_length[10]");
                $this->form_validation->set_rules('billing_city', "Billing City", "max_length[255]");
                $this->form_validation->set_rules('billing_country', "Billing Country", "max_length[255]");
                $this->form_validation->set_rules('billing_state', "Billing State", "max_length[255]");  

                $this->form_validation->set_rules('shipping_address', "Shipping Address", "required|min_length[5]");
                $this->form_validation->set_rules('shipping_zip', "Shipping Zip", "required|integer|max_length[10]");
                $this->form_validation->set_rules('shipping_city', "Shipping City", "required|max_length[255]");
                $this->form_validation->set_rules('shipping_country', "Shipping Country", "required|max_length[255]");
                $this->form_validation->set_rules('shipping_state', "Shipping State", "required|max_length[255]"); 
                  

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
                $address_type           =  $this->input->post('address_type', TRUE);


                $payload = [ 
                    'billing_zip' => $billing_zip,
                    'billing_address' => $billing_address,
                    'billing_city' => $billing_city,
                    'billing_state' => $billing_state,
                    'billing_country' => $billing_country, 
                    'shipping_address' => $shipping_address,
                    'address_type' => $address_type,
                    'shipping_zip' => $shipping_zip,
                    'shipping_city' => $shipping_city,
                    'shipping_state' => $shipping_state,
                    'shipping_country' => $shipping_country,
                ];

                if (!$this->input->post('address_fill_form', TRUE) ) 
                {
                    $payload['name']  =  $name;
                    $payload['phone'] =  $phone; 
                }
                 
                $response = $this->customer_model->edit($payload, $this->session->userdata('user_id'));


                if( $response )
                {
                    $output['status'] = 0;
                    $output['success']  = 'Profile has been updated successfully.'; 
                    if ($this->input->post('address_fill_form', TRUE) ) 
                    {
                        $output['success']       = 'Data has been updated successfully.';  
                        $output['redirect_url']  = base_url() . 'checkout';  
                    }

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
        if($this->session->userdata('customer_login') && $this->session->userdata('user_id'))
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
             
            
            $this->form_validation->set_rules('full_name', "Name", "required|max_length[255]");
            $this->form_validation->set_rules('email_address', "Email", "valid_email"); 
            $this->form_validation->set_rules('billing_zip', "Billing Postal Code", "required|integer");
            $this->form_validation->set_rules('billing_city', "Billing City", "max_length[255]");
            $this->form_validation->set_rules('billing_country', "Billing Country", "max_length[255]");
            $this->form_validation->set_rules('billing_state', "Billing State", "max_length[255]"); 
            $this->form_validation->set_rules('billing_address', "Billing Address", "required|min_length[5]"); 
            $this->form_validation->set_rules('payment', "Payment Method", "integer");
            // $this->form_validation->set_rules('number', "Account Number", "required|integer");
            // $this->form_validation->set_rules('exp_month', "Expiry Month", "required");
            // $this->form_validation->set_rules('exp_year', "Expiry Year", "required");
            // $this->form_validation->set_rules('cvc', "CVC", "required");

            $this->form_validation->set_rules('shipping_zip', "Shipping Zip", "required|integer");
            $this->form_validation->set_rules('shipping_city', "Shipping City", "required|max_length[255]");
            $this->form_validation->set_rules('shipping_country', "Shipping Country", "required|max_length[255]");
            $this->form_validation->set_rules('shipping_state', "Shipping State", "required|max_length[255]"); 
            $this->form_validation->set_rules('shipping_address', "Shipping Address", "required|min_length[5]"); 
            $this->form_validation->set_rules('customer_card', "Card is required", "required"); 
              


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

                $this->form_validation->set_rules('shipping_service_id_' . $cart_item_value->id, "", "required", array('required' => 'Shipping service must be selected for all items.')); 

                if ($this->form_validation->run() === FALSE)
                {
                    $error_msg = validation_errors();
                    $output['status'] = 0;
                    $output['error']  = $error_msg;
                    echo json_encode($output);
                    exit();  
                }



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
            $city           =  $this->input->post('billing_city', TRUE);
            $state          =  $this->input->post('billing_state', TRUE);
            $country        =  $this->input->post('billing_country', TRUE);
            $postal_code    =  $this->input->post('billing_zip', TRUE);
            $address_1      =  $this->input->post('billing_address', TRUE); 
            $payment        =  $this->input->post('payment', TRUE);
            $payment        =  2;
            $coupon_code    =  $this->input->post('coupon_code', TRUE);

            


            $shipping_country        =  $this->input->post('shipping_country', TRUE);
            $shipping_state          =  $this->input->post('shipping_state', TRUE);
            $shipping_zip            =  $this->input->post('shipping_zip', TRUE);
            $shipping_city           =  $this->input->post('shipping_city', TRUE);
            $shipping_address        =  $this->input->post('shipping_address', TRUE);
 
 
             
            
            $this->load->model('customer_model');  
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model');
            $this->load->model('pos_order_items_model'); 
            $this->load->model('transactions_model');
            
            $this->load->model('customer_model'); 
            $this->load->model('customer_cards_model'); 
            $this->load->model('coupon_model'); 
            $this->load->model('coupon_orders_log_model'); 
            $this->load->model('tax_model'); 

            $this->load->library('pos_checkout_service');
            $this->pos_checkout_service->set_pos_order_model($this->pos_order_model);
            $this->pos_checkout_service->set_coupon_model($this->coupon_model);

            $this->db->trans_begin();
 

            /**
             * IF Coupon is used then validate
             * If Coupon is successful then use amount
             * 
            */
            $coupon_amount    = 0; 
            $coupon_condition = FALSE; 
            // if(!empty($coupon_code))
            // {
            //     $coupon_response = (object) $this->pos_checkout_service->checkout_verify_and_update_coupon( $coupon_code );
                     
            //     if( $coupon_response->success )
            //     {  
            //         $coupon_amount    = $coupon_response->coupon_amount;   
            //         $coupon_condition = TRUE; 
            //     }
            //     else
            //     { 
            //         $output['status'] = 0;
            //         $output['error']  = $coupon_response->error_msg;
            //         echo json_encode($output);
            //         exit(); 
            //     } 
            // }

            $this->load->library('stripe_helper_service'); 
            $this->stripe_helper_service->set_config($this->config);
            $this->stripe_helper_service->set_customer_model($this->customer_model);
            $this->stripe_helper_service->set_customer_cards_model($this->customer_cards_model);

            $token_id = $this->input->post('customer_card', TRUE);
            // if($payment == 2)
            // {
            //     $acc_number     =  $this->input->post('number', TRUE);
            //     $exp_month      =  $this->input->post('exp_month', TRUE);
            //     $exp_year       =  $this->input->post('exp_year', TRUE);
            //     $cvc            =  $this->input->post('cvc', TRUE);
    
            //     $this->load->library('stripe_helper_service');
    
            //     $this->stripe_helper_service->set_config($this->config);
            //     $response = $this->stripe_helper_service->create_stripe_token($acc_number, $exp_month, $exp_year, $cvc);
    
                
            //     if( isset($response['success']) )
            //     {
            //         $token_id = $response['response']->id;
            //     }
            //     else
            //     { 
            //         $output['status'] = 0;
            //         $output['error']  = $response['error_msg'];
            //         echo json_encode($output);
            //         exit();  
            //     } 
            // }
 



            $data['customer']       =  $this->customer_model->get($user_id);   
            $data['customer']->name =  $full_name; 


            /**
            * Cart Items  
            */
            $cart_items     =  $this->pos_cart_model->get_all(['customer_id' => $user_id ]); 
            $customer_data  =  $this->customer_model->get( $user_id ); 
            $shipping_cost  =  0;
            $discount       =  0;
            $tax            =  0;
             
            $tax_data       =  $this->tax_model->get(1); 

            $tax_amount  = 0;
            

            if (strtolower($customer_data->billing_state) == 'nv' or strtolower($customer_data->billing_state) == 'nevada') 
            {
                if(isset($tax_data->tax) )
                {
                    $tax_amount = $tax_data->tax/100;
                }  
            }
            

            $referrer = 1;
            if ($this->session->userdata('referrer')) 
            {
                $referrer = $this->session->userdata('referrer');
            }
 
            $customer_data->shipping_service_name  = $shipping_cost_name;
            $customer_data->shipping_service_id    = $shipping_service_id;
            // $customer_data->name                   = $full_name;
            // $customer_data->email                  = $email_address;
            // $customer_data->phone                  = $phone_number;
            $customer_data->city                   = $customer_data->billing_city;
            $customer_data->state                  = $customer_data->billing_state;
            $customer_data->country                = $customer_data->billing_country;
            $customer_data->billing_zip            = $customer_data->billing_zip;
            $customer_data->billing_address        = $customer_data->billing_address;
            


            // $customer_data->shipping_address              = $shipping_address;
            // $customer_data->shipping_country              = $shipping_country;
            // $customer_data->shipping_state                = $shipping_state;
            // $customer_data->shipping_zip                  = $shipping_zip;
            // $customer_data->shipping_city                 = $shipping_city;
            $customer_data->payment  = $payment;
            $customer_data->referrer = $referrer;



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
                $shipping_cost_total = 0;

                foreach ($cart_items as $cart_item_key => $cart_item_value) 
                {
                    $inventory_data = $this->inventory_model->get($cart_item_value->product_id);   
                    $total_amount   = $cart_item_value->unit_price  * $cart_item_value->product_qty;


                    $shipping_cost_name         =  $this->input->post('shipping_cost_name_' . $cart_item_value->id, TRUE);
                    $shipping_cost_value        =  $this->input->post('shipping_cost_value_' . $cart_item_value->id, TRUE);
                    $shipping_service_id        =  $this->input->post('shipping_service_id_' . $cart_item_value->id, TRUE);


                    $shipping_service_code        =  $this->input->post('shipping_service_name_code_' . $cart_item_value->id, TRUE);
                    $shipping_service_name        =  $this->input->post('shipping_service_name_' . $cart_item_value->id, TRUE);

                    $shipping_cost_total += (Float) $shipping_cost_value; 
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
                        'shipping_cost_name'  => $shipping_cost_name,
                        'shipping_cost_value' => $shipping_cost_value,
                        'shipping_service_id' => $shipping_service_id,
                        
                        'shipping_service_code' => $shipping_service_code,
                        'shipping_service_name' => $shipping_service_name,
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
                


                // //if coupon used then save log
                $coupon_log_id = "";
                // if($coupon_condition)
                // {
                //     $coupon_log_id = $this->coupon_orders_log_model->create(['code' => $coupon_code, 'order_id' => $order_id, 'user_id' => $user_id, 'user_ip' => $_SERVER['REMOTE_ADDR'], 'amount' => $coupon_amount ]);
                // }



                /**
                * Update prices  
                */ 
                $tax              =  $tax_amount * $sub_total;


                $grand_total =   $tax + $sub_total - $discount - $coupon_amount  +  $shipping_cost_total;
                $data_order_prices = array( 
                    'total'         =>  $grand_total,
                    'tax'           =>  $tax,
                    'subtotal'      =>  $sub_total,  
                    'coupon_log_id' =>  $coupon_log_id,  
                    'shipping_cost' =>  $shipping_cost_total,  
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
                        $grand_total = number_format($grand_total,2);
                        $response = $this->stripe_helper_service->create_stripe_charge($user_id, $token_id, $grand_total, "Ecom Order");
             
                        if( isset($response['success']) )
                        { 
                            $this->pos_order_model->edit(['intent_data' => json_encode($response['response']) ], $order_id);
                            $this->pos_cart_model->real_delete_by_fields(['customer_id' => $user_id]); 
                        }
                        else
                        { 
                            $this->db->trans_rollback();
                            $output['status'] = 0;
                            $output['error']  = $response['error_msg'];
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




                    $this->send_email_on_order($order_id);
                    $this->send_email_new_order_to_admin($order_id);


                    $this->db->trans_commit();
                    $output['status'] = 0;
                    $output['success']  = 'Order has been created successfully.';
                    $output['redirect_url']  = base_url() . 'order_confirmation';
                    echo json_encode($output);
                    exit(); 
                }
                else
                {
                    $this->db->trans_rollback();
                    $output['status'] = 0;
                    $output['error']  = 'Error! Please try again later.';
                    echo json_encode($output);
                    exit();   
                }
 
                
                
            }
            else
            {
                $this->db->trans_rollback();
                $output['status'] = 0;
                $output['error']  = 'Error! Please try again later.';
                echo json_encode($output);
                exit(); 
            }
        }
    }





    public function checkout()
    {   
        if( $this->session->userdata('customer_login') && $this->session->userdata('user_id') )
        {
            $user_id = $this->session->userdata('user_id');
            $this->load->model('customer_model');

            $customer     =  $this->customer_model->get($user_id);

            if ( empty($customer->shipping_address) || empty($customer->shipping_state)  || empty($customer->shipping_zip)  || empty($customer->shipping_city)  || empty($customer->shipping_country)  || empty($customer->billing_address)  || empty($customer->billing_zip) )
            {
                redirect('/address_details');
            }

            $data['active'] = 'checkout';
            $data['layout_clean_mode'] = FALSE;
            $data['no_detail'] = TRUE;
    

             
            
            $this->load->model('pos_cart_model');
            
            $this->load->model('tax_model');



        

            $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 

            if (!empty($cart_items)) 
            {                     
                foreach ($cart_items as $key => &$value)
                {
                    $item_data = $this->inventory_model->get($value->product_id);

                    $value->free_ship     = $item_data->free_ship;
                    $value->can_ship      = $item_data->can_ship;
                    $value->feature_image = $item_data->feature_image;
                    $value->description   = $item_data->inventory_note; 
                }
            }

            
            $data['cart_items']   =  $cart_items; 
            $data['customer']     =  $customer; 
            $data['tax']          =  $this->tax_model->get(1); 

            $this->_render('Guest/Checkout',$data);
        }
        else{
            redirect('');
        }
    }


    public function address_details()
    {   
        if( $this->session->userdata('customer_login') && $this->session->userdata('user_id') )
        {
            $this->load->model('customer_model');

            $user_id = $this->session->userdata('user_id');
            $data['customer']     =  $this->customer_model->get($user_id); 
            $data['active'] = 'checkout';
            $data['layout_clean_mode'] = FALSE;
            
            $this->_render('Guest/AddressDetails',$data);
        }
        else
        {
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

        if($this->input->post('email', TRUE))
        {
            $name         =  $this->input->post('name', TRUE);
            $from_email   =  $this->input->post('email', TRUE);
            $subject      =  $this->input->post('subject', TRUE);
            $subject      =  $subject . ' - ' . $name;
            $message      =  $this->input->post('message', TRUE); 

            if( $this->_send_email($from_email, $subject, $message, $name) )
            {
                $this->session->set_flashdata('success2','Your message has been sent successfully.');
            } else{
                $this->session->set_flashdata('error2','Error! Please try again later.');
            }  

            return redirect($_SERVER['HTTP_REFERER']);
        }
         

        $this->_render('Guest/ContactUs',$data);
    }

     

    protected function _render($template, $data)
    {
        
        $all_categories  = $this->category_model->get_all(['status' => 1]);
         

        $data['all_categories']   = $all_categories;
        $data['page_section']     = $template;
        $data['support_email']    = $this->config->item('support_email'); 
         

        $this->load->view('Guest/Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Guest/Footer', $data);
    }

    protected function _send_email( $from_email ,$subject, $template, $name)
    { 
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp'); 
         
        $support_email = $this->config->item('support_email'); 
        return $this->mail_service->send($from_email, $support_email, $subject, $template); 
        return FALSE;
    }




    public function sign_up()
    { 

        if($this->input->post('email', TRUE))
        {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('first_name', 'Name', 'required|max_length[255]'); 
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[255]'); 

            if ($this->form_validation->run() === FALSE)
            {
                $error_msg = validation_errors(); 
                $output['error'] = $error_msg;
                echo json_encode($output);
                exit(); 
            }


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
                $output['success']  =  "Your account has been registered successfully. You can login now.";  
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



    public function add_alert_notification()
    {

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('product_sku', 'Product Sku', 'required');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required|integer'); 
        
        if ($this->form_validation->run() === FALSE)
        {
            $error_msg = validation_errors(); 
            $output['error'] = $error_msg;
            echo json_encode($output);
            exit(); 
        }
        
        $this->load->model('notification_system_model');
        
        $product_id    =   $this->input->post('product_id', TRUE);
        $email         =   $this->input->post('email', TRUE);
        $product_name  =   $this->input->post('product_name', TRUE);
        $product_sku   =   $this->input->post('product_sku', TRUE);
        $product_upc   =   $this->input->post('product_upc', TRUE);
        
        $already_exist = $this->notification_system_model->get_by_fields(['email' => $email , 'product_id' => $product_id , 'is_notified' => 0 ]); 

        if (empty($already_exist)) 
        {
            $response =  $this->notification_system_model->create([
                'product_id'   => $product_id,
                'email'        => $email,
                'product_name' => $product_name,
                'product_sku'  => $product_sku,
                'product_upc'  => $product_upc,
                'is_notified'  => 0
            ]);


            if ($response) 
            {
                $output['success'] = "Success! Your notification has been added successfully."; 
                echo json_encode($output);
                exit(); 
            }

            $output['error'] = "Error! Please try again later."; 
            echo json_encode($output);
            exit(); 
        }
  
        $output['error'] = "Error! Your notification is already there."; 
        echo json_encode($output);
        exit();  
    }


    public function update_customer_address()
    {
        if($this->session->userdata('customer_login') && $this->session->userdata('user_id'))
        {
            $user_id = $this->session->userdata('user_id');
            $this->load->model('customer_model');
            

            if ( $this->input->post('shipping_address', TRUE) ) 
            {  
                $this->form_validation->set_rules('full_name', "Name", "required|max_length[255]");
                $this->form_validation->set_rules('phone_number', 'Phone', 'numeric|max_length[15]'); 

                $this->form_validation->set_rules('shipping_address', "Shipping Address", "required|min_length[5]");
                $this->form_validation->set_rules('shipping_zip', "Shipping Zip", "required|integer|max_length[10]");
                $this->form_validation->set_rules('shipping_city', "Shipping City", "required|max_length[255]");
                $this->form_validation->set_rules('shipping_country', "Shipping Country", "required|max_length[255]");
                $this->form_validation->set_rules('shipping_state', "Shipping State", "required|max_length[255]"); 

                if ($this->form_validation->run() === FALSE)
                {
                    $error_msg = validation_errors(); 
                    $output['error'] = $error_msg;
                    echo json_encode($output);
                    exit(); 
                }

                $shipping_address    =   $this->input->post('shipping_address', TRUE);
                $shipping_country    =   $this->input->post('shipping_country', TRUE);
                $shipping_state      =   $this->input->post('shipping_state', TRUE);
                $shipping_city       =   $this->input->post('shipping_city', TRUE);
                $shipping_zip        =   $this->input->post('shipping_zip', TRUE);
                $address_type        =   $this->input->post('address_type', TRUE);
                $full_name           =   $this->input->post('full_name', TRUE);
                $phone_number        =   $this->input->post('phone_number', TRUE);
                

                $response = $this->customer_model->edit([
                    'name'              => $full_name, 
                    'phone'             => $phone_number,
                    'shipping_address'  => $shipping_address, 
                    'shipping_country'  => $shipping_country, 
                    'shipping_state'    => $shipping_state, 
                    'shipping_city'     => $shipping_city, 
                    'shipping_zip'      => $shipping_zip,  
                    'address_type'      => $address_type,  
                ], $user_id); 

                $output['success'] = "Success! Shipping address has been updated."; 
            }


            if ( $this->input->post('billing_address', TRUE) ) 
            { 
                $this->form_validation->set_rules('billing_address', "Billing Address", "required|min_length[5]");
                $this->form_validation->set_rules('billing_zip', "Billing Zip Code", "required|integer|max_length[10]");
                $this->form_validation->set_rules('billing_city', "Billing City", "max_length[255]");
                $this->form_validation->set_rules('billing_country', "Billing Country", "max_length[255]");
                $this->form_validation->set_rules('billing_state', "Billing State", "max_length[255]");  

                if ($this->form_validation->run() === FALSE)
                {
                    $error_msg = validation_errors(); 
                    $output['error'] = $error_msg;
                    echo json_encode($output);
                    exit(); 
                }


                $billing_address    =   $this->input->post('billing_address', TRUE);
                $billing_country    =   $this->input->post('billing_country', TRUE);
                $billing_state      =   $this->input->post('billing_state', TRUE);
                $billing_city       =   $this->input->post('billing_city', TRUE);
                $billing_zip        =   $this->input->post('billing_zip', TRUE);
                
                $response = $this->customer_model->edit([
                    'billing_address'  => $billing_address, 
                    'billing_country'  => $billing_country, 
                    'billing_state'    => $billing_state, 
                    'billing_city'     => $billing_city, 
                    'billing_zip'      => $billing_zip,  
                ], $user_id); 

                $output['success'] = "Success! Billing address has been updated."; 
            }



            

             

            if (isset($response) && $response) 
            { 
                echo json_encode($output);
                exit(); 
            }

            $output['error'] = "Error! Please try again later."; 
            echo json_encode($output);
            exit(); 
             
        } 
    }






    public function add_new_card()
    {
        $user_id = $this->session->userdata('user_id');

        if (empty($user_id))
        { 
            $output['error'] = 'Error! Login to continue.'; 
            echo json_encode($output);
            exit();
        }
        else
        {
            $this->load->model('customer_model');
            $this->load->model('customer_cards_model');
            
            $this->load->library('stripe_helper_service'); 
            $this->stripe_helper_service->set_config($this->config);
            $this->stripe_helper_service->set_customer_model($this->customer_model);

            
            $this->load->library('helpers_service'); 
            $this->helpers_service->set_customer_model($this->customer_model);


            

            // stripe_helper_service
            $card_number  = $this->input->post('card_number', TRUE);
            $exp_month    = $this->input->post('exp_month', TRUE);
            $exp_year     = $this->input->post('exp_year', TRUE);
            $cvc          = $this->input->post('cvc', TRUE); 
            $card_default = $this->input->post('card_default', TRUE); 

            $new_card_last4 = substr($card_number, 12);

            // check card already added or not
            $prev_card = $this->customer_cards_model->get_by_field('user_id', $user_id);
     

            if (!empty($prev_card))
            {
                if (!empty($card_number) && !empty($exp_month) && !empty($exp_year) && !empty($cvc))
                {



                    $check_card = $this->customer_cards_model->get_all(['user_id' => $user_id]);

                    foreach ($check_card as $check_card_key => $check_card_value) 
                    {
                        if ($check_card_value->last4 == $new_card_last4 && $check_card_value->account_no == $card_number && !empty($check_card_value->account_no))
                        {
                            $error_msg = 'This card last4->(...' . $new_card_last4 . ') is already added. Try again with a new card.';
                             
                            $output['error'] = $error_msg; 
                            echo json_encode($output);
                            exit();
                        }

                        if ($card_default == 1) 
                        {
                            $this->customer_cards_model->edit(['is_default' => 0], $check_card_value->id);
                        }
                    }

                     

                    // else
                    // {

                        // add card
                        $response = $this->stripe_helper_service->create_stripe_token($card_number, $exp_month, $exp_year, $cvc);

                        if ( isset($response['success']) && isset($response['response'])  && isset($response['response']->id)  )
                        {
                            $stripe_token_id = $response['response']->id;
  

                            // pass token_id to assign card to user
                            $res_card_data = $this->stripe_helper_service->add_new_card($stripe_token_id, $user_id);

                            if (isset($res_card_data['success']))
                            {
                                $stripe_card_id   = $res_card_data['card_data']->id;
                                $stripe_brand     = $res_card_data['card_data']->brand;
                                $stripe_exp_month = $res_card_data['card_data']->exp_month;
                                $stripe_exp_year  = $res_card_data['card_data']->exp_year;
                                $stripe_last4     = $res_card_data['card_data']->last4;


                                $check_card = $this->customer_cards_model->get_by_fields_custom( $user_id, $new_card_last4);

                                $payload = [
                                    'is_default'     => $card_default,
                                    'account_no'     => $card_number,
                                    'user_id'        => $user_id,
                                    'card_token'     => $stripe_card_id,
                                    'brand'          => $stripe_brand,
                                    'month'          => $stripe_exp_month,
                                    'year'           => $stripe_exp_year,
                                    'last4'          => $stripe_last4,
                                    'cvc'            => $cvc,
                                    'status'         => 1,
                                ];

                                if (!empty($check_card)) 
                                {
                                    $check_new_card = $this->customer_cards_model->edit($payload, $check_card->id);

                                    $output['success'] = 'Card updated successfully.'; 
                                }else{
                                    // store the card id with the associated user
                                    $check_new_card = $this->customer_cards_model->create($payload);
                                    $output['success'] = 'Card added successfully.'; 
                                }
 
                                

                                if ($check_new_card)
                                {
                                    
                                    echo json_encode($output);
                                    exit();  
                                }
                                else
                                {
                                    $output['error'] = "Error! Card add failed. Try Again."; 
                                    echo json_encode($output);
                                    exit();  
                                } 
                            }
                            else
                            {
                                // when user do not have the user->stripe_id
                                
                                $output['error'] = $res_card_data['error_msg']; 
                                echo json_encode($output);
                                exit(); 
                            }
                        }
                        else
                        {
                            $output['error'] = $response['error_msg']; 
                            echo json_encode($output);
                            exit();  
                        }
                    // }
                }
                else
                {
                    $output['error'] = "All fields are required."; 
                    echo json_encode($output);
                    exit();   
                }
            }
            else
            { 
                // create stripe_customer_id and add the new card
                // $this->error('No prev record found');
                // return redirect($_SERVER['HTTP_REFERER']);
                
                $response = $this->stripe_helper_service->create_stripe_token($card_number, $exp_month, $exp_year, $cvc);

                if (  isset($response['success'])  && isset($response['response'])  && isset($response['response']->id)  )
                {
                    
                    $stripe_token_id = $response['response']->id;
                    

                    // get user email from credential model
                    $customer_email = $this->helpers_service->get_customer_email($user_id);

                    
                    $res_customer = $this->stripe_helper_service->create_stripe_customer_with_card($customer_email, $stripe_token_id);

                    if (isset($res_customer['success']))
                    {
                        $stripe_customer_id = $res_customer['card']->customer;
                        $stripe_card_id     = $res_customer['card']->id;
                        $stripe_brand       = $res_customer['card']->brand;
                        $stripe_exp_month   = $res_customer['card']->exp_month;
                        $stripe_exp_year    = $res_customer['card']->exp_year;
                        $stripe_last4       = $res_customer['card']->last4;

                        // update user->stripe_id
                        $update_stripe_id = $this->customer_model->edit([
                            'stripe_id' => $stripe_customer_id
                        ], $user_id);

                        // add card on user_card
                        if ($update_stripe_id)
                        {
                            // store the card id with the associated user
                            $check_new_card = $this->customer_cards_model->create([
                                'is_default'     => $card_default,
                                'account_no'     => $card_number,
                                'user_id'        => $user_id,
                                'card_token'     => $stripe_card_id,
                                'brand'          => $stripe_brand,
                                'month'          => $stripe_exp_month,
                                'year'           => $stripe_exp_year,
                                'last4'          => $stripe_last4,
                                'cvc'            => $cvc,
                                'status'         => 1,
                            ]);

                            if ($check_new_card)
                            {
                                $output['success'] = 'Card added successfully and set to default.'; 
                                echo json_encode($output);
                                exit(); 
                            }
                            else
                            {
                                $output['error'] = "Card add failed. Try Again. (Y)"; 
                                echo json_encode($output);
                                exit();   
                            } 
                        }
                    }
                    else
                    {
                        $output['error'] = $res_customer['error_msg']; 
                        echo json_encode($output);
                        exit();  
                    }

                }
                else
                {
                    // when new card validation failed
                    $output['error'] = $response['error_msg']; 
                    echo json_encode($output);
                    exit();  
                }
            }
        }
    }


    public function load_customer_cards()
    {
        if ($this->session->userdata('user_id')) 
        {
            $user_id = $this->session->userdata('user_id'); 
            $this->load->model('customer_cards_model');
            $all_cards = $this->customer_cards_model->get_all(['user_id' => $user_id]);


            $output['all_cards'] = $all_cards; 
            echo json_encode($output);
            exit();

        }
        
    }



    public function load_checkout_calculations()
    {
        if($this->session->userdata('customer_login') && $this->session->userdata('user_id') && $this->input->post('id'))
        {
             
            $user_id    = $this->session->userdata('user_id');
            $product_id = $this->input->post('id', TRUE);
            $this->load->model('pos_cart_model'); 
            $this->load->model('tax_model'); 

            $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            $tax        =  $this->tax_model->get(1); 

            $total = 0;
            $current_itemprice = 0;
            if (!empty($cart_items)) 
            {                     
                foreach ($cart_items as $key => &$value)
                {
                    $total = $total + $value->total_price;   

                    if ($value->product_id == $product_id) 
                    {
                        $current_itemprice = $value->total_price;
                    }
                }
            } 

            $sub_total = $total;

            $tax_amount  = 0;
            if(isset($tax->tax) and $total != 0)
            {
                $tax_amount = $tax->tax/100 * $total;
            }

            $total_without_tax = $total;
            $total             = $total + $tax_amount;


            $output['sub_total_value']   = number_format($sub_total,2);
            $output['total_value']       = number_format($total,2);
            $output['tax_amount_value']  = number_format($tax_amount,2);
            $output['total_without_tax'] = number_format($total_without_tax,2);
            $output['current_itemprice'] = number_format($current_itemprice,2);
            $output['success']           = true;
            echo json_encode($output);
            exit();
        }

        $output['error'] = "Error! Login to continue.";
        echo json_encode($output);
        exit();
    }





}