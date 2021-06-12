<?php 

class Api_service{
    
    protected $_config;
    protected $_inventory_model;
    protected $_pos_order_items_model;
    protected $_pos_order_model;
    protected $_store_model;
    protected $_physical_location_model;
    

    public function set_config($config)
    {
        $this->_config = $config;
    }

    public function set_physical_location_model($physical_location_model)
    {
        $this->_physical_location_model = $physical_location_model;
    }


    public function set_inventory_model($inventory_model)
    {
        $this->_inventory_model = $inventory_model;
    }


    public function set_pos_order_items_model($pos_order_items_model)
    {
        $this->_pos_order_items_model = $pos_order_items_model;
    }


    public function set_pos_order_model($pos_order_model)
    {
        $this->_pos_order_model = $pos_order_model;
    }


    public function set_store_model($store_model)
    {
        $this->_store_model = $store_model;
    }



    // SEND API TO SHIPPER

    public function send_order_to_shipper($order_id)
    { 
        $order_data    =  $this->_pos_order_model->get($order_id); 
        if( $order_data )
        { 
            $order_detail  =  $this->_pos_order_items_model->get_all( [ 'order_id' => $order_id ] );

            $platform_id       =  $this->_config->item('platform_id');
            $shipping_site_url =  $this->_config->item('shipping_site_url');
            
            $detail = array();
            foreach ($order_detail as $key => $value) 
            {

                $inventory_detail  = $this->_inventory_model->get($value->product_id);
                $store_detail      = $this->_store_model->get($value->store_id);
                $physical_location = $this->_physical_location_model->get($inventory_detail->physical_location);
                    


                $store_location = "N/A";
                if(isset($store_detail->name))
                {
                    $store_location    = $store_detail->name;
                } 


                $physical_location_name = "N/A";
                if(isset($physical_location->name))
                {
                    $physical_location_name    = $physical_location->name;
                } 

                $product_type = "";
                if($inventory_detail->product_type == 1)
                {
                    $product_type = "Inventory";
                }else if ($inventory_detail->product_type == 2)
                {
                    $product_type = "Non Inventory";
                } 

                array_push($detail, array(
                        'item_id'            =>  $value->product_id,
                        'item_name'          =>  $value->product_name,
                        'product_qty'        =>  $value->quantity,  
                        'item_sku'           =>  $inventory_detail->sku, 
                        'product_type'       =>  $product_type,
                        'product_location'   =>  $physical_location_name,
                        'width'              =>  $inventory_detail->width,
                        'height'             =>  $inventory_detail->height,
                        'length'             =>  $inventory_detail->length,
                        'weight'             =>  $inventory_detail->weight, 
                        'barcode_image'      =>  $inventory_detail->barcode_image,
                        'tax_amount'         =>  0,
                        'unit_price'         =>  $value->product_unit_price,

                        'shipping_cost_name'  =>  $value->shipping_cost_name,
                        'shipping_cost_value' =>  $value->shipping_cost_value,
                        'shipping_service_id' =>  $value->shipping_service_id,
                
                    ) );
            }

            $payment_method = ""; 
            if ($order_data->payment_method == 1) 
            {
                $payment_method = "Cash"; 
            }

            if ($order_data->payment_method == 2) 
            {
                $payment_method = "Credit Card"; 
            }

            $post_data = array(
                'customer_id'       => $order_data->customer_id,
                'customer_name'     => $order_data->billing_name,
                'sale_order_no_id'  => $order_data->id,
                'sale_order_no'     => $order_data->id,   
                'customer_company'  => '',   
                'billing_address'   => $order_data->billing_address,   
                'platform_id'       => $platform_id,   
                'status'            => 1, 
                'detail'            => $detail,
                'checkout_type'     => $order_data->checkout_type,
                'delivery_method'   => $order_data->checkout_type,

                
                'ship_city'         =>  $order_data->shipping_city,
                'ship_state'        =>  $order_data->shipping_state,  
                'ship_postal_code'  =>  $order_data->shipping_zip,
                'ship_address'      =>  $order_data->shipping_address,
                'ship_country'      =>  $order_data->shipping_country,
                'ship_name'         =>  "",
                'ship_phone'        =>  $order_data->customer_phone, 
                'payment_date'      =>  "",
                'bill_city'         =>  $order_data->billing_city,
                'bill_country'      =>  $order_data->billing_country,
                'bill_postal_code'  =>  $order_data->billing_zip,
                'bill_state'        =>  $order_data->billing_state,
                'shipping_amount'   =>  $order_data->shipping_cost,
                'tax_amount'        =>  $order_data->tax,
                'amount_paid'       =>  $order_data->total,
                'customer_notes'    =>  "",
                'internal_notes'    =>  "",
                'payment_method'    =>  $payment_method,
                'order_date'        =>  $order_data->order_date_time,
                'customer_email'    =>  $order_data->customer_email,
                
            ); 


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $shipping_site_url .'v1/api/pick_order/add_pick_order',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 40,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($post_data),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain', 
                ),
            )); 
            
            $response = curl_exec($curl); 
            curl_close($curl);   
            return json_decode($response); 
            exit(); 
        }  
    }




}