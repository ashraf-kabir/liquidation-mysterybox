<?php 
class Helpers_service {

    private $_pos_user_model;
    private $_inventory_model;


    public function set_pos_user_model($pos_user_model)
    {
        $this->_pos_user_model = $pos_user_model;
    }


    public function set_inventory_model($inventory_model)
    {
        $this->_inventory_model = $inventory_model;
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
    public function check_item_in_inventory($product_id, $product_qty, $product_name)
    {
        $inventory_data =  $this->_inventory_model->get_by_fields(['id' => $product_id]);

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


}