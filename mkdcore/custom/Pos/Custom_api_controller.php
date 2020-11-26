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
    



    public function add_product_to_cart($scan_order = FALSE,$product_data = '')
    {
        if($this->session->userdata('user_id'))
        {
            $this->load->model('pos_cart_model');


            if($scan_order)
            {
                $product_id   =  $product_data->id;
                $product_qty  =  1;
                $product_name =  $product_data->product_name;
                $unit_price   =  $product_data->selling_price;
            }else{
                $product_id   =  $this->input->post('id', TRUE);
                $product_qty  =  $this->input->post('quantity', TRUE);
                $product_name =  $this->input->post('item', TRUE);
                $unit_price   =  $this->input->post('price', TRUE);
            }
            
            
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
            $this->load->model('transactions_model');
            $this->load->model('inventory_model');

            $this->load->library('pos_checkout_service');
            $this->pos_checkout_service->set_pos_order_model($this->pos_order_model);

            /**
            * Cart Items  
            */
            $cart_items = $this->input->post('cart_items', TRUE);
             

            /**
            * Refactor Customer Data  
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

            //discount
            $discount = $this->input->post('discount', TRUE); 


            
            /**
            * Create Order 
            */ 
            $result = $this->pos_checkout_service->create_order($customer_data,$tax,$discount,$pos_user_id,$shipping_cost);


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
                    $inventory_data = $this->inventory_model->get($cart_items[$cart_item_key]['id']);  

                    $total_amount = $cart_items[$cart_item_key]['price']  * $cart_items[$cart_item_key]['quantity'];
                    $data_order_detail = array(
                        'product_id'         => $cart_items[$cart_item_key]['id'],
                        'product_name'       => $cart_items[$cart_item_key]['name'], 
                        'amount'             => $total_amount, 
                        'quantity'           => $cart_items[$cart_item_key]['quantity'], 
                        'order_id'           => $order_id, 
                        'manifest_id'        => $inventory_data->manifest_id, 
                        'category_id'        => $inventory_data->category_id,  
                        'pos_user_id'        => $pos_user_id, 
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


                /**
                * Add Transaction  
                */    
                $add_transaction = array(
                    'payment_type'      =>  $customer_data['payment'],
                    'customer_id'       =>  $customer_data['customer_id'], 
                    'pos_user_id'       =>  $pos_user_id, 
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
                    $result = $this->pos_cart_model->real_delete_by_fields(['user_id' => $user_id]);

                    

                    $output['customer_name'] = $customer_data['name'];
                    $output['order_id']      = $order_id;
                    $output['address']       = $customer_data['address'];
                    $output['status'] = 200;
                    $output['success'] = 'Order has been created successfully.';
                    echo json_encode($output);
                    exit();

                }else{
                    $output['status'] = 0;
                    $output['error'] = 'Error! While adding transaction.';
                    echo json_encode($output);
                    exit();
                }

                
                
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Please try again later.';
                echo json_encode($output);
                exit();
            }

        }
    }




    public function check_barcode_in_inventory()
    {

        $this->load->model('inventory_model'); 
        $barcode_value = $this->input->post('barcode_value', TRUE); 

        $product_data = $this->inventory_model->get_by_fields(['sku' => $barcode_value ]);

        if( !empty($product_data) )
        {
            $output = $this->add_product_to_cart(TRUE, $product_data);
            
            echo $output;
            exit();
        }else{
            $output['error'] = true;
            $output['msg']   = "No such barcode found.";
            echo json_encode($output);
            exit();
        } 
    }
    
    
    public function edit_product_in_cart()
    {
        if ($this->session->userdata('user_id')) 
        { 
            $this->load->model('pos_cart_model');
            
            $product_id   = $this->input->post('id', TRUE);
            $product_qty  = $this->input->post('quantity', TRUE);
            
            $user_id = $this->session->userdata('user_id');

            /**
            * Check if product in cart 
            *   if yes update the cart
            *    
            */

            $check_chart_if_product =  $this->pos_cart_model->get_by_fields(['product_id' => $product_id,'user_id' => $user_id]);  

            if (!empty($check_chart_if_product)) 
            {
                $cart_product_data = $check_chart_if_product;
 
                $total_price_now   =  $product_qty * $cart_product_data->unit_price;

                $data_cart = array( 
                    'product_qty'   => $product_qty, 
                    'total_price'   => $total_price_now, 
                ); 
                $result = $this->pos_cart_model->edit($data_cart,$cart_product_data->id); 
            } 


            if ($result) 
            {
                $output['status'] = 200;
                $output['success'] = 'Your data has been updated in cart successfully.'; 
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
    * Items in shelf that can be removed
    */
    public function pos_items_in_shelf()
    {
        
        if ($this->session->userdata('user_id')) 
        {  
            $pos_user_id = $this->session->userdata('user_id'); 

            $this->load->model('inventory_model');

            $shelf_items_list = $this->inventory_model->get_all(['available_in_shelf' => 1]);

            $table_content = '';
            foreach ($shelf_items_list as $shelf_item_key => $shelf_item_value) 
            {       
                $table_content   .=   '<tr>';
                $table_content   .=   '<th scope="row">'. $shelf_item_value->product_name  .'</th>';
                $table_content   .=   '<td class="text-danger"> $ <span>'.  number_format($shelf_item_value->selling_price,2)  .'</span></td>';
                $table_content   .=   '<td>'.  $shelf_item_value->quantity  .'</td>';
                $table_content   .=   '<td></td>';
                $table_content   .=   '<td>'.  $shelf_item_value->sku  .'</td>';
                $table_content   .=   '<td class="btn text-danger shelf-remove" data-product-item-id="'.  $shelf_item_value->id  .'"  > Remove Product </td>';
                $table_content   .=   '</tr>';
                 
            } 

            if($table_content == '')
            {
                $table_content = "<tr><td colspan='100%'>No more items in shelf.</td></tr>";
            }

            if($table_content)
            {
                $output['status'] = 200;
                $output['items_in_shelf'] = $table_content; 
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



    public function remove_from_shelf()
    {
        if ($this->session->userdata('user_id') and $this->input->post('product_id') ) 
        {  
            $product_id   =  $this->input->post('product_id', TRUE);

            $this->load->model('inventory_model');

            $data_update = array(
              'available_in_shelf'  => 0, 
            );
            $result = $this->inventory_model->edit($data_update,$product_id);


            if ($result) 
            {  
                $output['status']      = 200;
                $output['success']     = 'Product has been removed from shelf successfully.'; 
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
    * POS List of order that are not picked yet
    */

    public function pos_pickup_from_shelf()
    {
        
        if ($this->session->userdata('user_id')) 
        {  
            $pos_user_id = $this->session->userdata('user_id');

            $this->load->model('pos_order_model');
            $this->load->model('pos_order_items_model');
            
            $orders_list = $this->pos_order_model->get_all(['pos_user_id' => $pos_user_id,'pos_pickup_status' => 1]);

            $table_content = '';
            foreach ($orders_list as $orders_list_key => $orders_list_value) 
            {   


                /*
                * Order Details
                **/
                $items_data = $this->pos_order_items_model->get_all(['order_id' => $orders_list_value->id]);
                $total_items        =  0;
                $item_list_content  =  '';
                foreach ($items_data as $items_data_key => $item_value) 
                {
                    $item_list_content  .=  '<li> ' .  $item_value->quantity  . 'x ' .  $item_value->product_name  . '</li>'; 
                    $total_items++;
                }


                $table_content   .=  '<tr>';

                $table_content   .=  '<th scope="row">'  . ucfirst($orders_list_value->billing_name) .  '</th>';

                $table_content   .=  '<td>' . $orders_list_value->id  . '</td>';

                $table_content   .=  '<td>
                                        <ul class="list-unstyled text-left">
                                            <li>'  . $total_items .  ' items</li>
                                            '. $item_list_content .'
                                        </ul>
                                    </td>';

                $table_content   .=  '<td>Paid in '  . ucfirst($orders_list_value->payment_method) .  '</td>';

                $table_content   .=  '<td>'.  date('d F, Y',strtotime($orders_list_value->created_at))  .'</td>';

                $table_content   .=  '<td>'.  date('d F, Y',strtotime($orders_list_value->updated_at))  .'</td>';

                $table_content   .=  '<td><a href="" class="text-success mark_pickup_product" data-order-id="' . $orders_list_value->id . '" >Pick Up</a></td>';

                $table_content   .=  '</tr>';    
            } 


            if($table_content == '')
            {
                $table_content = "<tr><td colspan='100%'>No order to pickup.</td></tr>";
            }


            if($table_content)
            {
                $output['status'] = 200;
                $output['pickup_shelf'] = $table_content; 
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


    public function pos_mark_order_pickup()
    {
        if ($this->session->userdata('user_id')) 
        { 
            $this->load->model('pos_order_model');
            $this->load->model('pos_order_note_model'); 

            $pickup_order_id  =  $this->input->post('pickup_order_id', TRUE); 

            $mark_order_picked_data = array( 
                'pos_pickup_status'  => 2, 
            ); 
            $result = $this->pos_order_model->edit($mark_order_picked_data, $pickup_order_id); 

            if ($result) 
            { 
                /* 
                * Add Public Msg order is picked
                */
                $data_order_note = array( 
                    'message_note'       =>  "Order has been picked.",
                    'order_id'           =>  $pickup_order_id,
                    'msg_type'           =>  1,  
                ); 
                $this->pos_order_note_model->create($data_order_note); 

                $output['status']      = 200;
                $output['success']     = 'Order has been picked successfully.'; 
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
    * List of Past Order for Past Order page on POS
    */
    public function pos_past_order()
    {
        
        if ($this->session->userdata('user_id')) 
        {  
            $pos_user_id = $this->session->userdata('user_id');
            

            $this->load->model('pos_order_model');
            $this->load->model('pos_order_items_model');
            

            $custom_query = '';
            if(  $this->input->post('customer_name') and !empty($this->input->post('customer_name'))  )
            {
                $custom_query = ' `billing_name` LIKE "'.  $this->input->post('customer_name')  .'%" ';
            }  

            if($this->input->post('start_date') and !empty($this->input->post('start_date')) and $this->input->post('end_date') and !empty($this->input->post('end_date')) )
            {
                if(!empty($custom_query))
                {
                    $custom_query .= ' AND ';
                }
                $from =  $this->input->post('start_date');
                $to   =  $this->input->post('end_date');
                $custom_query .= " ( DATE_FORMAT(`created_at`, '%Y-%m-%d') >= '".$from."' AND  DATE_FORMAT(`created_at`, '%Y-%m-%d')  <= '".  $to  ."'   )  ";
            }


            if($custom_query == '')
            {
                $orders_list = $this->pos_order_model->get_all(['pos_user_id' => $pos_user_id,'pos_pickup_status' =>2]);
            }else{
                $orders_list = $this->pos_order_model->get_all_custom_where($custom_query);
            }

             

            $table_content = '';
            foreach ($orders_list as $orders_list_key => $orders_list_value) 
            {   
 
                /*
                * Order Details
                **/
                $items_data = $this->pos_order_items_model->get_all(['order_id' => $orders_list_value->id]);
                $total_items        =  0;
                $item_list_content  =  '';
                foreach ($items_data as $items_data_key => $item_value) 
                {
                    $item_list_content  .=  '<li> ' .  $item_value->quantity  . 'x ' .  $item_value->product_name  . '</li>'; 
                    $total_items++;
                }


                $table_content   .=  '<tr>';

                $table_content   .=  '<th scope="row">'  . ucfirst($orders_list_value->billing_name) .  '</th>';

                $table_content   .=  '<td>' . $orders_list_value->id  . '</td>';

                $table_content   .=  '<td>
                                        <ul class="list-unstyled text-left">
                                            <li>'  . $total_items .  ' items</li>
                                            ' . $item_list_content . '
                                        </ul>
                                    </td>';

                $table_content   .=  '<td>Paid in ' .    ucfirst($orders_list_value->payment_method)  . '</td>';

                $table_content   .=  '<td  class="text-danger" >$' .   number_format($orders_list_value->total,2)    . '</td>';

                $table_content   .=  '<td >$' .  number_format($orders_list_value->tax,2)   . '</td>';

                $grand_total = $orders_list_value->tax + $orders_list_value->total;

                $table_content   .=  '<td  class="text-danger">$' .  number_format($grand_total,2)  . '</td>';

                $table_content   .=  '</tr>';    
            } 


            if($table_content == '')
            {
                $table_content = "<tr><td colspan='100%'>No record found.</td></tr>";
            }


            if($table_content)
            {
                $output['status'] = 200;
                $output['pos_past_order'] = $table_content; 
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
    * Summary of Day 
    */
    public function pos_summary_report()
    {
        
        if ($this->session->userdata('user_id')) 
        {  
            $pos_user_id = $this->session->userdata('user_id');
            

            $this->load->model('pos_order_model');
            $this->load->model('transactions_model');
            $this->load->model('customer_model');
            $this->load->library('names_helper_service');
            $this->names_helper_service->set_customer_model($this->customer_model);
            $search_date = $this->input->post('search_date', TRUE );
 
            $transactions_list = $this->transactions_model->get_all( ['pos_user_id' => $pos_user_id, 'transaction_date' => $search_date ]);
             

            $table_content = '';

            $total_day_cash   =  0;
            $total_discount   =  0;
            $total_credit     =  0;
            $total_all        =  0;
             
            foreach ($transactions_list as $_key => $transaction) 
            {    

                $customer_name = $this->names_helper_service->get_customer_real_name($transaction->customer_id);
                $table_content   .=  '<tr>';

                $table_content   .=  '<th scope="row">'  . ucfirst($transaction->pos_order_id) .  '</th>'; 
                
                $table_content   .=  '<td>' .    ucfirst($customer_name)  . '</td>';
  
                $table_content   .=  '<td>' .    date('m-d-Y',strtotime( $transaction->transaction_date )) . ' ' . date('g:i A',strtotime( $transaction->transaction_time )) . '</td>';
 
                $table_content   .=  '<td>' .    ucfirst($transaction->payment_type)  . '</td>';

                $table_content   .=  '<td>$' .   number_format($transaction->tax,2)    . '</td>';

                $table_content   .=  '<td>$' .  number_format($transaction->discount,2)   . '</td>'; 

                $table_content   .=  '<td>$' .  number_format($transaction->subtotal,2)   . '</td>'; 

                $table_content   .=  '<td  class="text-danger" >$' .  number_format($transaction->total,2)  . '</td>';

                $table_content   .=  '</tr>';    

                if($transaction->payment_type == 'card')
                {
                    $total_credit     +=  $transaction->total;
                }

                if($transaction->payment_type == 'cash')
                {
                    $total_day_cash   +=  $transaction->total;
                }
               
                $total_discount   +=  $transaction->discount; 
                $total_all        +=  $transaction->total;
            } 

            $table_summary = '';
            if($table_content == '')
            {
                $table_content = "<tr><td colspan='100%'>No record found.</td></tr>";
                $table_summary = "<tr><td colspan='100%'>No record found.</td></tr>";
            }else{
                $table_summary   .=  '<tr>';
  
                $table_summary   .=  '<td>$' .   number_format($total_day_cash,2)    . '</td>';

                $table_summary   .=  '<td>$' .  number_format($total_credit,2)   . '</td>'; 

                $table_summary   .=  '<td>$' .  number_format($total_discount,2)   . '</td>'; 

                $table_summary   .=  '<td  class="text-danger" >$' .  number_format($total_all,2)  . '</td>';

                $table_summary   .=  '</tr>';    
            }


            if($table_content)
            {
                $output['status'] = 200;
                $output['report_summary']       =  $table_content; 
                $output['report_summary_total'] =  $table_summary; 
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




