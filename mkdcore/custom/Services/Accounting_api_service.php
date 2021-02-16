<?php 

class Accounting_api_service { 
    protected $_pos_order_model;
    protected $_pos_order_items_model;
    protected $_config; 
    protected $_inventory_model; 
    protected $_customer_model; 
    protected $_transactions_model; 


    public function set_config($config)
    {
        $this->_config = $config;
    }


    public function set_customer_model($customer_model)
    {
        $this->_customer_model = $customer_model;
    }
    


    public function set_transactions_model($transactions_model)
    {
        $this->_transactions_model = $transactions_model;
    }
    

    public function set_inventory_model($inventory_model)
    {
        $this->_inventory_model = $inventory_model;
    } 

    public function set_pos_order_model($pos_order_model)
    {
        $this->_pos_order_model = $pos_order_model;
    } 


    public function set_pos_order_items_model($pos_order_items_model)
    {
        $this->_pos_order_items_model = $pos_order_items_model;
    } 



    public function post_order_to_accounting($order_id)
    {
        
        $order_data = $this->_pos_order_model->get($order_id);  


        if($order_data)
        {
             
                $order_detail = $this->_pos_order_items_model->get_all(['order_id' => $order_id]);
                
                $platform_id         = $this->_config->item('platform_id');
                $accounting_site_url = $this->_config->item('accounting_site_url');
                  
                $detail = array();
                foreach ($order_detail as $key => $value) 
                {

                    $inventory_detail = $this->_inventory_model->get($value->product_id);  

                      

                    $type = "";
                    if($inventory_detail->product_type == 1)
                    {
                        $type = "Regular";
                    }else if ($inventory_detail->product_type == 2)
                    {
                        $type = "Generic";
                    } 

                    array_push($detail, array(
                            'item_id'            =>  $value->product_id,
                            'item_name'          =>  $value->product_name,
                            'item_sku'           =>  $inventory_detail->sku,
                            'product_qty'        =>  $value->quantity,  
                            'amount'             =>  $value->product_unit_price,  
                            'product_type'       =>  $type, 
                            'barcode_image'      =>  $inventory_detail->barcode_image,
                        ) );
                }


                $customer_detail = $this->_customer_model->get( $order_data->customer_id );
                
                $company_name = "N/A";
                if(isset($customer_detail->company_name))
                {
                    $company_name = $customer_detail->company_name;
                }

                $post_data = array(
                    'customer_id'       => $order_data->customer_id,
                    'customer_name'     => $order_data->billing_name,
                    'sale_order_no_id'  => $order_data->id,
                    'sale_order_no'     => $order_data->id,   
                    'customer_company'  => $company_name,   
                    'total'             => $order_data->total,   
                    'billing_address'   => $order_data->billing_address,   
                    'sale_order_date'   => $order_data->order_date_time,   
                    'platform_id'       => $platform_id,   
                    'cash_amount'       => $order_data->total,   
                    'payment_method'    => $order_data->payment_method,   
                    'split_payment'     => 0,   
                    'delivery_method'   => $order_data->checkout_type,   
                    'tax'               => $order_data->tax,   
                    'shipping_cost'     => $order_data->shipping_cost,   
                    'adjustment'        => 0,   
                    'subtotal'          => $order_data->subtotal,   
                    'discount'          => $order_data->discount,   
                    'status'            => 1, 
                    'detail'            => $detail,
                ); 
  

                $curl = curl_init();

                curl_setopt_array( $curl , array(
                    CURLOPT_URL => $accounting_site_url . 'v1/api/accounting/add_order',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
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




    

    public function send_transaction_to_accounting($id)
    {
        $model = $this->_transactions_model->get($id); 

        if($model)
        {
            $accounting_site_url = $this->_config->item('accounting_site_url');
            $platform_id         = $this->_config->item('platform_id');


            $transaction_type = "";
            if($model->payment_type == 1)
            {
                $transaction_type = "Cash";
            }else if ($model->payment_type == 2)
            {
                $transaction_type = "Credit";
            }

            $post_data = array(
                'pos_user_id'         =>  $model->pos_user_id,
                'sale_order_id'       =>  $model->pos_order_id, 
                'total_amount'        =>  $model->total,
                'tax'                 =>  $model->tax,
                'transaction_type'    =>  $transaction_type,
                'status'              =>  1,
                'pay_date'            =>  $model->created_at,
                'transaction_date'    =>  $model->created_at,
                'platform_id'         =>  $platform_id,
            );

            

            

            $curl = curl_init(); 
            curl_setopt_array( $curl , array(
                CURLOPT_URL => $accounting_site_url . 'v1/api/accounting/add_transaction',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
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