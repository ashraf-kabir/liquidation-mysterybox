<?php
class Customer_service { 

    private $_customer_model;

    public function set_customer_model($customer_model)
    {
        $this->_customer_model = $customer_model;
    } 


    public function add_customer_record($id, $order_no)
    {
        if( $id )
        { 
            $customer_data = $this->_customer_model->get($id);  

            $total_orders  =  (INT) $customer_data->num_orders;
            $total_orders  =  $total_orders + 1;

            $data_add = array(
                'last_order'     =>  $order_no,
                'num_orders'     =>  $total_orders,
            ); 
            $update = $this->_customer_model->edit($data_add , $id);

            if($update)
            {
                $response['success']     =  true;
                $response['success_msg'] =  "Success! Customer record has been updated successfully.";
                return $response;
            }
            else
            {
                $response['error']      =  true;
                $response['error_msg']  =  "Error! While updating customer record.";
                return $response;
            } 
        } 
        else
        {
            $response['error']      =  true;
            $response['error_msg']  =  "Error! While updating customer record.";
            return $response;
        }
    }

    
}

?>