<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

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

            $data['cart_items'] =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            $data['customer']   =  $this->customer_model->get($user_id); 
        }

        $this->_render('Guest/Cart',$data);
    }



    public function do_checkout()
    {
        if($this->session->userdata('customer_login'))
        {

            $full_name      =  $this->input->post('full_name', TRUE);
            $email_address  =  $this->input->post('email_address', TRUE);
            $phone_number   =  $this->input->post('phone_number', TRUE);
            $city           =  $this->input->post('city', TRUE);
            $state          =  $this->input->post('state', TRUE);
            $country        =  $this->input->post('country', TRUE);
            $address_1      =  $this->input->post('address_1', TRUE);
            $address_2      =  $this->input->post('address_2', TRUE);
            $payment        =  $this->input->post('payment', TRUE);

            $user_id = $this->session->userdata('user_id');
            $this->load->model('pos_cart_model');
            $this->load->model('customer_model');

            $data['cart_items'] =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            if( empty($data['cart_items']) )
            {  
                $this->session->set_flashdata('error1', 'Error! Please add item in cart first.'); 
                return redirect($_SERVER['HTTP_REFERER']);
            }

            $data['customer']   =  $this->customer_model->get($user_id); 
             
            
            $data['customer']->name =  $name;

            $user_id = $this->session->userdata('user_id');
                
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model');
            $this->load->model('pos_order_items_model');
            $this->load->model('pos_cart_model');
            $this->load->model('transactions_model');
            $this->load->model('inventory_model');
            $this->load->model('customer_model');

            $this->load->library('pos_checkout_service');
            $this->pos_checkout_service->set_pos_order_model($this->pos_order_model);

            /**
            * Cart Items  
            */
            $cart_items = $this->pos_cart_model->get_all(['customer_id' => $user_id ]);
                
            $customer_data  =  $this->customer_model->get( $user_id ); 
            $shipping_cost  = 0;
            $discount       = 0;
            $tax            = 0;
    

                
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
                    $result = $this->pos_order_items_model->create($data_order_detail); 
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


                /**
                * Add Transaction  
                */    
                $add_transaction = array(
                    'payment_type'      =>  1,
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
                    $result = $this->pos_cart_model->real_delete_by_fields(['customer_id' => $user_id]);  
                    $output['order_id']      = $order_id;  
                     

                    $this->session->set_flashdata('success1', 'Order has been created successfully.');
                }else{
                     
                    $this->session->set_flashdata('error1', 'Error! While adding transaction.'); 
                }

                redirect($_SERVER['HTTP_REFERER']);
                
                
            }else{
                $this->session->set_flashdata('error1', 'Error! Please try again later.');  
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }


    public function checkout()
    { 
        $data['active'] = 'about';
        $data['layout_clean_mode'] = FALSE;
        $data['no_detail'] = TRUE;
 

        if($this->session->userdata('customer_login'))
        { 
            $user_id = $this->session->userdata('user_id');
            $this->load->model('pos_cart_model');
            $this->load->model('customer_model');

            $data['cart_items'] =  $this->pos_cart_model->get_all(['customer_id' => $user_id]); 
            $data['customer']   =  $this->customer_model->get($user_id); 
        }


        $this->_render('Guest/Checkout',$data);
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
                'password'  => password_hash($password, PASSWORD_BCRYPT),  
                'status'    => 1,  
            ]);
    
            if($result)
            {
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
                if(password_verify($password, $user->password))
                {
                    // $this->destroy_session();
                    
                    $this->set_session('user_id', (int) $user->id); 
                    $this->set_session('email', (string) $user->email); 
                    $this->set_session('customer_login', 1); 


                    

                    $output['status'] = 0;
                    $output['success'] = 'Success!.';
                    echo json_encode($output);
                    exit();

                }else{
                    $output['status'] = 0;
                    $output['error'] = 'Error! Invalid email or password.';
                    echo json_encode($output);
                    exit();
                }
               
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Invalid email or password.';
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
		return $this->redirect('pos/login');
    }

}