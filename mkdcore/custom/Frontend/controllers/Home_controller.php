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
        $this->_data['category_id']    =     $this->input->get('category_id', TRUE) != NULL  ? $this->input->get('category_id', TRUE) : NULL ;
        $this->_data['search_query']   =     $this->input->get('search_query', TRUE) != NULL  ? $this->input->get('search_query', TRUE) : NULL ;

        $this->_data['location_id']     =     $this->input->get('location_id', TRUE) != NULL  ? $this->input->get('location_id', TRUE) : NULL ;
        
        $where = [ 
            'physical_location'  => $this->_data['location_id'], 
            'category_id'        => $this->_data['category_id'], 
            'product_name'       => $this->_data['search_query'],  
            'sku'                => $this->_data['search_query'] 
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
         

        $data['location_id']      = $this->_data['location_id'];  
        $data['category_id']      = $this->_data['category_id'];  
        $data['all_categories']  = $this->category_model->get_all(['status' => 1]);
        $data['all_locations']   = $this->physical_location_model->get_all( );
        
        
       

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


    public function cart()
    { 
        $data['active'] = 'about';
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


            $data['tax']   =  $this->tax_model->get(1);  

        }

        $this->_render('Guest/Cart',$data);
    } 


    public function do_checkout()
    {  
        if($this->session->userdata('customer_login'))
        { 
            $user_id = $this->session->userdata('user_id');

            $this->form_validation->set_rules('full_name', "Name", "required|max_length[255]");
            $this->form_validation->set_rules('email_address', "Email", "valid_email"); 
            $this->form_validation->set_rules('postal_code', "Customer Postal Code", "integer");
            $this->form_validation->set_rules('city', "City", "max_length[255]");
            $this->form_validation->set_rules('country', "Country", "max_length[255]");
            $this->form_validation->set_rules('state', "State", "max_length[255]"); 
            $this->form_validation->set_rules('address_1', "Address", "required|min_length[5]"); 
            $this->form_validation->set_rules('payment', "Payment Method", "integer");

            // $this->form_validation->set_rules('shipping_postal_cost', "Shipping Postal Code", "integer"); 
            // $this->form_validation->set_rules('checkout_type', "Checkout Type", "integer");


            $this->load->model('pos_cart_model');
            $this->load->model('inventory_model');
            $this->load->library('helpers_service');
            $this->helpers_service->set_inventory_model($this->inventory_model);



            $cart_items =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            if( empty($cart_items) )
            {  
                $this->session->set_flashdata('error1', 'Error! Please add item in cart first.'); 
                return redirect($_SERVER['HTTP_REFERER']);
            }


            if ($this->form_validation->run() === FALSE)
            {
                $error_msg = validation_errors(); 
                $this->session->set_flashdata('error1', $error_msg); 
                return redirect($_SERVER['HTTP_REFERER']);
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
                    $this->session->set_flashdata('error1', $check_quantity->error); 
                    return redirect($_SERVER['HTTP_REFERER']);
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

            $payment = 1;
            
           
            

            
            $this->load->model('customer_model');  
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model');
            $this->load->model('pos_order_items_model'); 
            $this->load->model('transactions_model');
            
            $this->load->model('customer_model'); 
            $this->load->model('coupon_model'); 
            $this->load->model('coupon_orders_log_model'); 

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
                    $this->session->set_flashdata('error1', $coupon_response->error_msg); 
                    return redirect($_SERVER['HTTP_REFERER']); 
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
                    $this->session->set_flashdata('error1', 'Error! Please try again later.'); 
                    return redirect($_SERVER['HTTP_REFERER']);
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
            $customer_data->address                = $address_1 . " " . $address_2;
            $customer_data->payment                = $payment;

            /**
            * Create Order 
            */ 
            $result = $this->pos_checkout_service->customer_create_order($customer_data,$tax,$discount,$user_id,$shipping_cost);


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
                $grand_total = $sub_total + $tax + $shipping_cost - $discount - $coupon_amount;
                $data_order_prices = array( 
                    'total'         =>  $grand_total,
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
                    $output['order_id']      = $order_id;  
                     


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
                            $this->session->set_flashdata('error1', 'Error! Please try again later.'); 
                            return redirect($_SERVER['HTTP_REFERER']);
                        }  
                    }



                    $result = $this->pos_cart_model->real_delete_by_fields(['customer_id' => $user_id]); 


                    /**
                     * Send Order to Accounting System
                     *  
                    */ 
                    $accounting_response = $this->send_order_to_accounting( $order_id );
                    if( isset( $accounting_response->error_msg ) )
                    {
                        $this->session->set_flashdata('error1', 'Error! Please try again later.'); 
                        return redirect($_SERVER['HTTP_REFERER']);
                    }


                    /**
                     * Send Transaction to Accounting System
                     *  
                    */ 
                    $accounting_trans_response = $this->send_transaction_to_accounting( $transaction_id );
                    if( isset( $accounting_trans_response->error_msg ) )
                    {
                        $this->session->set_flashdata('error1', 'Error! Please try again later.'); 
                        return redirect($_SERVER['HTTP_REFERER']);
                    }



                    /**
                     * Send Order to Shipping System
                     *  
                    */ 
                    $order_data = $this->send_order_to_shipper($order_id);

                    if( isset( $order_data->error_msg ) )
                    {
                        $this->session->set_flashdata('error1', 'Error! Please try again later.'); 
                        return redirect($_SERVER['HTTP_REFERER']);
                    }


                    $this->session->set_flashdata('success1', 'Order has been created successfully.');
                }
                else
                {
                     
                    $this->session->set_flashdata('error1', 'Error! While adding transaction.'); 
                }

                redirect($_SERVER['HTTP_REFERER']);
                
                
            }
            else
            {
                $this->session->set_flashdata('error1', 'Error! Please try again later.');  
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }





    public function checkout()
    {   
        if($this->session->userdata('customer_login'))
        {
            $data['active'] = 'about';
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
         
        $model  = $this->inventory_model->get($id); 
        if (!$model)
		{
			$this->error('Error');
			return redirect('/categories');
        }

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
        
        $data['all_categories']  = $this->category_model->get_all(['status' => 1]);
        $data['page_section'] = $template;
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
        if ($this->session->userdata('user_id') and $cart_id ) 
        {  
            $this->load->model('pos_cart_model');
            $user_id = $this->session->userdata('user_id'); 
            $cart_id = $cart_id; 
            $result  = $this->pos_cart_model->real_delete_by_fields([ 'id' => $cart_id ]); 

            if ($result) 
            {  
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



}