<?php
class Helpers_service
{

    private $_pos_user_model;
    private $_customer_model;
    private $_inventory_model;
    private $_inventory_log_model;
    private $_store_model;
    private $_inventory_transfer_log_model;
    private $_inventory_transfer_model;
    private $_notification_system_model;
    private $_mail_service;
    private $_config;
    private $_user_model;


    public function set_pos_user_model($pos_user_model)
    {
        $this->_pos_user_model = $pos_user_model;
    }

    public function set_user_model($_user_model)
    {
        $this->_user_model = $_user_model;
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

    public function set_inventory_log_model($inventory_log_model)
    {
        $this->_inventory_log_model = $inventory_log_model;
    }


    public function set_store_model($store_model)
    {
        $this->_store_model = $store_model;
    }

    public function set_inventory_transfer_log_model($inventory_transfer_log_model)
    {
        $this->_inventory_transfer_log_model = $inventory_transfer_log_model;
    }

    public function set_inventory_transfer_model($inventory_transfer_model)
    {
        $this->_inventory_transfer_model = $inventory_transfer_model;
    }



    public function get_customer_email($id)
    {
        $check_data = $this->_customer_model->get($id);

        $email = "";
        if (isset($check_data->email)) {
            $email = $check_data->email;
        }
        return  $email;
    }


    public function add_pos_sale($pos_id, $amount)
    {
        $check_if_data = $this->_pos_user_model->get($pos_id);
        if (!empty($check_if_data)) {
            $total_sale_now = 0;
            if (!empty($check_if_data->total_sale)) {
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
    public function check_item_in_inventory($product_id, $product_qty, $product_name, $checkout_type = false, $checkout_page = false)
    {
        $inventory_data =  $this->_inventory_model->get_by_fields(['id' => $product_id]);


        /**
         *
         * Product Type 2 = Generic 
         * If 2 then don't check quantity 
         * 
         */


        // if($inventory_data->product_type != 2)
        // { 
        if ($product_qty > $inventory_data->quantity) {
            if ($inventory_data->quantity == 0) {
                $output['error']  = $product_name . " is out of stock.";
            } else {
                if (!$checkout_page) {
                    $output['error']  = $product_name . " quantity exceeds available quantity.";
                } else {
                    $output['error2']  = true;
                }
            }
            return  (object)$output;
        }
        // }
        /**
         * checkout type 2 =  Delivery
         * 
         * if we can't delivery show error
         *  
         */
        // if($checkout_type == 2)
        // {
        //     // if($inventory_data->can_ship == 2)
        //     // {
        //     //     $output['error']  = "Error! " . $product_name . " can't be shipped."; 
        //     //     return  (object)$output; 
        //     // }
        // }


    }
    /**
     *  Check item in inventory 
     *  Check Quantity
     *  Post error if quantity is less
     * 
     */
    public function check_item_in_store_inventory($product_id, $product_qty, $product_name, $checkout_type = false, $checkout_page = false, $store_id = null)
    {
        if ($store_id == null) {
            return $this->check_item_in_inventory($product_id, $product_qty, $product_name, $checkout_type = false, $checkout_page = false);
        }

        $inventory_data =  $this->_inventory_model->get_by_fields(['id' => $product_id]);

        $store_data = $this->_inventory_model->get_store_inventory_data(json_decode($inventory_data->store_inventory), $store_id);
        // echo $store_data; exit(); die();
        $quantity = empty($store_data) || is_null($store_data) ? $inventory_data->quantity : $store_data->quantity;



        /**
         *
         * Product Type 2 = Generic 
         * If 2 then don't check quantity 
         * 
         */


        // if($inventory_data->product_type != 2)
        // { 
        if ($product_qty > $quantity) {
            if ($quantity < 1) {
                $output['error']  = $product_name . " is out of stock.";
            } else {
                if (!$checkout_page) {
                    $output['error']  = $product_name . " quantity exceeds available store quantity.";
                } else {
                    $output['error2']  = true;
                }
            }
            return  (object)$output;
        }
        // }
        /**
         * checkout type 2 =  Delivery
         * 
         * if we can't delivery show error
         *  
         */
        // if($checkout_type == 2)
        // {
        //     // if($inventory_data->can_ship == 2)
        //     // {
        //     //     $output['error']  = "Error! " . $product_name . " can't be shipped."; 
        //     //     return  (object)$output; 
        //     // }
        // }


    }


    public function pos_logged_in($pos_id)
    {
        $data_update = array(
            'logged_in' => 1,
        );

        $this->_pos_user_model->edit($data_update, $pos_id);
    }

    public function pos_logged_out($shipper_id)
    {
        $data_update = array(
            'logged_in' => "",
        );

        $this->_pos_user_model->edit($data_update, $shipper_id);
    }





    public function notify_item_has_been_added($item_id)
    {
        $list_data = $this->_notification_system_model->get_all(['product_id' => $item_id, 'is_notified' => 0]);
        if (!empty($list_data)) {
            $this->_mail_service->set_adapter('smtp');
            $from = $this->_config->item('from_email');

            foreach ($list_data as $key => $value) {
                $content = $value->product_name . " having sku " . $value->product_sku . " is available in stock now " . "<a href='" . base_url() . "product/" . $value->product_id . "' >Buy Now</a>.";

                $this->_mail_service->send($from, $value->email, $value->product_name . " new stock has been added", $content);

                $this->_notification_system_model->edit(['is_notified' => 1], $value->id);
            }
        }
    }

    public function log_inventory_transfer($inventory_transfer_id, $action = '')
    {
        if (empty($inventory_transfer_id)) {
            return;
        }

        $inventory_transfer_request = $this->_inventory_transfer_model->get($inventory_transfer_id);

        if (!empty($inventory_transfer_request)) {
            $from_store = $this->_store_model->get($inventory_transfer_request->from_store);
            $to_store = $this->_store_model->get($inventory_transfer_request->to_store);
            $detail = "{$inventory_transfer_request->quantity} unit(s) of {$inventory_transfer_request->product_name} 
                        with SKU {$inventory_transfer_request->sku},
                        from {$from_store->name} to {$to_store->name}.";
            $this->_inventory_transfer_log_model->create([
                'user_id'   => $_SESSION['user_id'],
                'action'    => $action,
                'last_ip'   => $this->_inventory_transfer_log_model->get_ip(),
                'user_agent'   => $this->_inventory_transfer_log_model->get_user_agent(),
                'detail'    => $detail
            ]);
        }
    }

    public function log_inventory($inventory_id, $action = '')
    {
        if (empty($inventory_id)) {
            return;
        }

        $inventory_added = $this->_inventory_model->get($inventory_id);

        if (!empty($inventory_added)) {
            $user = $this->_user_model->get($_SESSION['user_id']);
            $detail = "{$inventory_added->quantity} unit(s) of {$inventory_added->product_name} with SKU {$inventory_added->sku} was added by {$user->first_name} {$user->last_name}";
            $this->_inventory_log_model->create([
                'user_id'   => $_SESSION['user_id'],
                'action'    => $action,
                'sku'     => $inventory_added->sku,
                'quantity' => 1,
                'user_agent'   => $this->_inventory_log_model->get_user_agent(),
                'detail'    => $detail
            ]);
        }
    }
}
