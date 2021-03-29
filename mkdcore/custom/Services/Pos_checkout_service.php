<?php
 
class Pos_checkout_service {

    private $_pos_order_model; 
    private $_pos_user_model; 
    private $_inventory_model; 
    private $_coupon_model; 


    public function set_pos_order_model($pos_order_model)
    {
        $this->_pos_order_model =  $pos_order_model;
    }

    public function set_pos_user_model($pos_user_model)
    {
        $this->_pos_user_model =  $pos_user_model;
    }


    public function set_inventory_model($inventory_model)
    {
        $this->_inventory_model =  $inventory_model;
    }


    public function set_coupon_model($coupon_model)
    {
        $this->_coupon_model =  $coupon_model;
    }


    public function create_order($customer_data,$tax,$discount,$pos_user_id,$shipping_cost = 0,$split_payment = 0, $cash_amount= 0, $credit_amount = 0)
    {
        $billing_name        =  $customer_data['name'];
        $billing_address     =  $customer_data['address'];
        $billing_country     =  $customer_data['country'];
        $billing_state       =  $customer_data['state'];
        $billing_city        =  $customer_data['city'];
        $billing_zip         =  $customer_data['postal_code'];
        $payment             =  $customer_data['payment'];
        $customer_id         =  $customer_data['customer_id'];
        $checkout_type       =  $customer_data['checkout_type'];

         
        $shipping_cost_service_name = "";
        if(isset($customer_data['shipping_service_name']))
        {
            $shipping_cost_service_name = $customer_data['shipping_service_name'];
        }

        $shipping_cost_service_code = "";
        if(isset($customer_data['shipping_service_id']))
        {
            $shipping_cost_service_code = $customer_data['shipping_service_id'];
        }


        $shipping_zip = "";
        if(isset($customer_data['shipping_postal_cost']))
        {
            $shipping_zip = $customer_data['shipping_postal_cost'];
        }

        $payment_method = 1;
        if($payment == 'card')
        {
            $payment_method = 2;
        }else if($payment == 'cash')
        {
            $payment_method = 1;
        }

        if($checkout_type != 2)
        {
            $shipping_cost_service_name  = "";
            $shipping_cost_service_code  = "";
            $shipping_zip                = "";
        }

        $data_checkout_order = array( 
            'shipping_cost_service_code'      =>  $shipping_cost_service_code,
            'shipping_cost_service_name'      =>  $shipping_cost_service_name, 
            'billing_name'      =>  $billing_name, 
            'billing_address'   =>  $billing_address,
            'billing_country'   =>  $billing_country, 
            'billing_state'     =>  $billing_state, 
            'billing_city'      =>  $billing_city, 
            'billing_zip'       =>  $billing_zip, 
            'payment_method'    =>  $payment_method, 
            'customer_id'       =>  $customer_id,
            'order_date_time'   =>  Date('Y-m-d h:i:s A'),
            'shipping_name'     =>  '', 
            'shipping_address'  =>  '', 
            'shipping_country'  =>  '', 
            'shipping_state'    =>  '', 
            'shipping_city'     =>  '', 
            'shipping_zip'      =>  $shipping_zip, 
            'subtotal'          =>  0, 
            'shipping_cost'     =>  $shipping_cost, 
            'tax'               =>  $tax, 
            'total'             =>  0, 
            'discount'          =>  $discount, 
            'order_type'        =>  2,  
            'pos_user_id'       =>  $pos_user_id, 
            'status'            =>  1, 
            'checkout_type'     =>  $checkout_type, 
            'pos_pickup_status' =>  1, 
            'is_split'          =>  $split_payment, 
            'cash_amount'       =>  $cash_amount, 
            'credit_amount'     =>  $credit_amount, 
            'is_picked'         =>  1, 
        );

        if(!$split_payment)
        {
            unset( $data_checkout_order['cash_amount'] );
            unset( $data_checkout_order['credit_amount'] );
        }


        if($checkout_type == 2)
        {
            unset( $data_checkout_order['is_picked'] );
        }

        $result = $this->_pos_order_model->create($data_checkout_order);

        return $result;
    }


    public function customer_create_order($customer_data,$tax,$discount,$pos_user_id,$shipping_cost =0, $checkout_type)
    {
        $billing_name        =  $customer_data->name;
        $billing_address     =  $customer_data->billing_address;
        $billing_country     =  $customer_data->billing_country;
        $billing_state       =  $customer_data->billing_state;
        $billing_city        =  $customer_data->billing_city;
        $billing_zip         =  $customer_data->billing_zip;
        $payment_method      =  $customer_data->payment;
        $customer_id         =  $customer_data->id;


        $shipping_cost_service_name = "";
        if(isset($customer_data->shipping_service_name))
        {
            $shipping_cost_service_name = $customer_data->shipping_service_name;
        }

        $shipping_cost_service_code = "";
        if(isset($customer_data->shipping_service_id))
        {
            $shipping_cost_service_code = $customer_data->shipping_service_id;
        }

        if($payment_method != 2)
        {
            $shipping_cost_service_name  = "";
            $shipping_cost_service_code  = "";
            $shipping_zip                = "";
        }

        $data_checkout_order = array( 
            'shipping_cost_service_code'      =>  $shipping_cost_service_code,
            'shipping_cost_service_name'      =>  $shipping_cost_service_name, 
            'billing_name'      =>  $billing_name,
            'billing_address'   =>  $billing_address,
            'billing_country'   =>  $billing_country, 
            'billing_state'     =>  $billing_state, 
            'billing_city'      =>  $billing_city, 
            'billing_zip'       =>  $billing_zip, 
            'payment_method'    =>  $payment_method, 
            'customer_id'       =>  $customer_id,
            'order_date_time'   =>  Date('Y-m-d h:i:s A'),
            'shipping_name'     =>  '', 
            'shipping_address'  =>  $customer_data->shipping_address, 
            'shipping_country'  =>  $customer_data->shipping_country, 
            'shipping_state'    =>  $customer_data->shipping_state,  
            'shipping_city'     =>  $customer_data->shipping_city,  
            'shipping_zip'      =>  $customer_data->shipping_zip, 
            'customer_email'    =>  $customer_data->email, 
            'customer_phone'    =>  $customer_data->phone, 
            'subtotal'          =>  0, 
            'shipping_cost'     =>  $shipping_cost, 
            'tax'               =>  $tax, 
            'total'             =>  0, 
            'discount'          =>  $discount, 
            'order_type'        =>  1,  
            'pos_user_id'       =>  0, 
            'status'            =>  1, 
            'pos_pickup_status' =>  1, 
            'checkout_type'     =>  $checkout_type, 
        );

        $result = $this->_pos_order_model->create($data_checkout_order);

        return $result;
    }




    public function validation_cart_items_for_shipment($cart_items)
    {
        if(!empty($cart_items) )
        {
            foreach ($cart_items as $cart_item_key => $cart_item_value) 
            {
                $inventory_data = $this->_inventory_model->get($cart_items[$cart_item_key]['id']);  

                if($inventory_data->can_ship ==2)
                {
                    $output['status'] = 0;
                    $output['error']  = "Error! " . $inventory_data->product_name . " can't be shipped.";
                    return json_encode($output); 
                    exit();
                }
            }
        }
        else
        {
            $output['status'] = 0;
            $output['error']  = "Error! Items in cart are required.";
            return json_encode($output); 
            exit();
        }
    }


    public function refactor_customer_data($form_data)
    {
        $customer_data = array();
        foreach ($form_data as $form_data_key => $form_data_value) 
        {
            $form_data_value = (object) $form_data_value;
            $customer_data[$form_data_value->name] = $form_data_value->value;
        }
        return $customer_data;
    }




    public function checkout_verify_and_update_coupon($coupon_code)
    {
        if($coupon_code)
        {
            $coupon_data =  $this->_coupon_model->get_by_fields(['code' => $coupon_code]);

            if( isset($coupon_data->usage) )
            {
                if($coupon_data->usage <= 0)
                { 
                    $response['error']     = TRUE;
                    $response['success']   = FALSE;
                    $response['error_msg'] = "Error! Coupon usage has expired.";
                    return $response;

                }
                else if($coupon_data->usage == 0)
                { 
                    $response['error']     = TRUE;
                    $response['success']   = FALSE;
                    $response['error_msg'] = "Error! Coupon has been expired.";
                    return $response;
                }
                else 
                { 
                    $coupon_usage  = $coupon_data->usage - 1;
                    $coupon_amount = $coupon_data->amount;

                    $update_ed    =  $this->_coupon_model->edit(['usage' => $coupon_usage], $coupon_data->id);
                    if($update_ed)
                    { 
                        $response['success']       = TRUE;
                        $response['error']         = FALSE;
                        $response['coupon_amount'] = $coupon_amount;
                        return $response;
                    }
                    else
                    { 
                        $response['error']     = TRUE;
                        $response['success']   = FALSE;
                        $response['error_msg'] = 'Error! Please try again later.';
                        return $response;
                    }
                }
            }
            else
            {
                $response['error']     = TRUE;
                $response['success']   = FALSE;
                $response['error_msg'] = "Error! No such coupon exist.";
                return $response;
            }
        }
        else
        {
            $response['error']     = TRUE;
            $response['success']   = FALSE;
            $response['error_msg'] = "Error! Coupon is required.";
            return $response;
        }

    }




    public function update_pos_record($pos_user_id, $amount)
    {
        if( $pos_user_id )
        { 
            $pos_data = $this->_pos_user_model->get($pos_user_id);  

            $total_sale  =  (FLOAT) $pos_data->total_sale;
            $total_sale  =  $total_sale + $amount;

            $data_add = array( 
                'total_sale'     =>  $total_sale,
            ); 
            $update = $this->_pos_user_model->edit($data_add , $pos_user_id);

            if($update)
            {
                $response['success']     =  true;
                $response['success_msg'] =  "Success! User record has been updated successfully.";
                
            }
            else
            {
                $response['error']      =  true;
                $response['error_msg']  =  "Error! While updating user record.";
                 
            } 
        } 
        else
        {
            $response['error']      =  true;
            $response['error_msg']  =  "Error! While updating user record.";
             
        }

        return (object)$response;
    }


    
}