<?php 

class Api_service{
    
    protected $_config;
    protected $_inventory_model;
    protected $_pos_order_items_model;
    protected $_pos_order_model;
    protected $_store_model;
    

    public function set_config($config)
    {
        $this->_config = $config;
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
                    


                $store_location = "N/A";
                if(isset($store_detail->name))
                {
                    $store_location    = $store_detail->name;
                } 

                $product_type = "";
                if($inventory_detail->product_type == 1)
                {
                    $product_type = "Regular";
                }else if ($inventory_detail->product_type == 2)
                {
                    $product_type = "Generic";
                } 

                array_push($detail, array(
                        'item_id'            =>  $value->product_id,
                        'item_name'          =>  $value->product_name,
                        'product_qty'        =>  $value->quantity,  
                        'item_sku'           =>  $inventory_detail->sku, 
                        'product_type'       =>  $product_type,
                        'width'              =>  $inventory_detail->width,
                        'height'             =>  $inventory_detail->height,
                        'length'             =>  $inventory_detail->length,
                        'weight'             =>  $inventory_detail->weight, 
                        'barcode_image'      =>  $inventory_detail->barcode_image,
                    ) );
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