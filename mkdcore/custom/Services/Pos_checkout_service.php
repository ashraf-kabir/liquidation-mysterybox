<?php
 
class Pos_checkout_service {

    private $_pos_order_model; 


    public function set_pos_order_model($pos_order_model)
    {
        $this->_pos_order_model =  $pos_order_model;
    }

    public function create_order($customer_data,$tax,$discount,$pos_user_id,$shipping_cost =0)
    {
        $billing_name        =  $customer_data['name'];
        $billing_address     =  $customer_data['address'];
        $billing_country     =  $customer_data['country'];
        $billing_state       =  $customer_data['state'];
        $billing_city        =  $customer_data['city'];
        $billing_zip         =  $customer_data['postal_code'];
        $payment             =  $customer_data['payment'];
        $customer_id         =  $customer_data['customer_id'];

        $data_checkout_order = array( 
            'billing_name'      =>  $billing_name,
            'billing_address'   =>  $billing_address,
            'billing_country'   =>  $billing_country, 
            'billing_state'     =>  $billing_state, 
            'billing_city'      =>  $billing_city, 
            'billing_zip'       =>  $billing_zip, 
            'payment_method'    =>  $payment, 
            'customer_id'       =>  $customer_id,
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
            'pos_user_id'       =>  $pos_user_id, 
            'status'            =>  1, 
            'pos_pickup_status' =>  1, 
        );

        $result = $this->_pos_order_model->create($data_checkout_order);

        return $result;
    }



    
}