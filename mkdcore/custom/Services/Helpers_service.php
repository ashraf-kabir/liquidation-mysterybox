<?php 
class Helpers_service {

    private $_pos_user_model;
    private $_customer_model;
    private $_inventory_model;
    private $_notification_system_model;
    private $_mail_service;
    private $_config;


    public function set_pos_user_model($pos_user_model)
    {
        $this->_pos_user_model = $pos_user_model;
    }

    public function set_customer_model($customer_model)
    {
        $this->_customer_model = $customer_model;
    }



    public function set_config($config)
    {
        $this->_config = $config;
    }




    public function set_mail_service($_mail_service)
    {
        $this->_mail_service = $_mail_service;
    }



    public function set_notification_system_model($notification_system_model)
    {
        $this->_notification_system_model = $notification_system_model;
    }




    public function set_inventory_model($inventory_model)
    {
        $this->_inventory_model = $inventory_model;
    }



    public function get_customer_email($id)
    { 
        $check_data = $this->_customer_model->get($id); 

        $email = "";
        if(isset($check_data->email))
        {
            $email = $check_data->email;
        }
        return  $email;
    }

    
    public function add_pos_sale($pos_id, $amount)
    {  
        $check_if_data = $this->_pos_user_model->get( $pos_id ); 
        if( !empty($check_if_data) )
        { 
            $total_sale_now = 0;
            if (!empty($check_if_data->total_sale) ) 
            {
                $total_sale_now = $check_if_data->total_sale;
            }
            $total_s  =  $total_sale_now + $amount;
            
            $data_add = array( 
                'total_sale' =>  $total_s,
            ); 
            $this->_pos_user_model->edit($data_add, $pos_id);
        }   
    }




    /**
     *  Check item in inventory 
     *  Check Quantity
     *  Post error if quantity is less
     * 
    */
    public function check_item_in_inventory($product_id, $product_qty, $product_name, $checkout_type = false)
    {
        $inventory_data =  $this->_inventory_model->get_by_fields(['id' => $product_id]);


        /**
         *
         * Product Type 2 = Generic 
         * If 2 then don't check quantity 
         * 
        */

        
        if($inventory_data->product_type != 2)
        { 
            if( $product_qty > $inventory_data->quantity )
            { 
                if($inventory_data->quantity == 0)
                {
                    $output['error']  = $product_name . " is out of stock.";
                }
                else
                {
                    $output['error']  = $product_name . " quantity can't be greater than be available quantity.";
                } 
                return  (object)$output;
            }
        }
        /**
         * checkout type 2 =  Delivery
         * 
         * if we can't delivery show error
         *  
        */
        if($checkout_type == 2)
        {
            // if($inventory_data->can_ship == 2)
            // {
            //     $output['error']  = "Error! " . $product_name . " can't be shipped."; 
            //     return  (object)$output; 
            // }
        }
        
        
    }
     

    public function pos_logged_in($pos_id)
    {
        $data_update = array(
            'logged_in' => 1,
        );

        $this->_pos_user_model->edit($data_update,$pos_id);
    }

    public function pos_logged_out($shipper_id)
    {
        $data_update = array(
            'logged_in' => "",
        );

        $this->_pos_user_model->edit($data_update,$shipper_id);
    }





    public function notify_item_has_been_added($item_id)
    {  
        $list_data = $this->_notification_system_model->get_all( ['product_id' => $item_id , 'is_notified' => 0 ] ); 
        if( !empty($list_data) )
        {  
            $this->_mail_service->set_adapter('smtp');
            $from = $this->_config->item('from_email');

            foreach ($list_data as $key => $value) 
            {
                $content = $value->product_name . " having sku " . $value->product_sku . " is available in stock now.";

                $this->_mail_service->send($from, $value->email, $value->product_name . " new stock has been added", $content);

                $this->_notification_system_model->edit( ['is_notified' => 1 ], $value->id);
            }
        }   
    }

}