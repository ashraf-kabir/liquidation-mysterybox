<?php 
class Names_helper_service {

    private $_category_model;
    private $_physical_location_model;
    private $_customer_model;  
    private $_pos_user_model;  
    private $_store_model;  
    private $_department_model;  


    public function set_category_model($category_model)
    {
        $this->_category_model = $category_model;
    }

    public function get_category_real_name($id)
    {
       
        $category_name = "N/A";
        $check_data =$this->_category_model->get($id); 
        if(isset($check_data->name))
        {
            $category_name = $check_data->name;
        }
        return  $category_name;
    }

   

    public function set_department_model($department_model)
    {
        $this->_department_model = $department_model;
    }

    public function get_department_real_name($id)
    {
       
        $department_name = "N/A";
        $check_data =$this->_department_model->get($id); 
        if(isset($check_data->department_name))
        {
            $department_name = $check_data->department_name;
        }
        return  $department_name;
    }



    public function set_physical_location_model($physical_location_model)
    {
        $this->_physical_location_model = $physical_location_model;
    }

    public function get_physical_location_real_name($id)
    {
       
        $physical_location_real_name = "N/A";
        $check_data =$this->_physical_location_model->get($id); 
        if(isset($check_data->name))
        {
            $physical_location_real_name = $check_data->name;
        }
        return  $physical_location_real_name;
    }



    public function set_customer_model($customer_model)
    {
        $this->_customer_model = $customer_model;
    }

    public function get_customer_real_name($id)
    {
       
        $customer_real_name = "N/A";
        $check_data =$this->_customer_model->get($id); 
        if(isset($check_data->name))
        {
            $customer_real_name = $check_data->name;
        }
        return  $customer_real_name;
    }




    public function set_pos_user_model($pos_user_model)
    {
        $this->_pos_user_model = $pos_user_model;
    }

    public function get_pos_user_real_name($id)
    { 
        $real_name = "N/A";
        $check_data =$this->_pos_user_model->get($id); 
        if(isset($check_data->first_name))
        {
            $real_name = $check_data->first_name . " " . $check_data->last_name;
        }
        return  $real_name;
    }




    public function set_store_model($store_model)
    {
        $this->_store_model = $store_model;
    }

    public function get_store_name($id)
    { 
        $real_name = "N/A";
        $check_data =$this->_store_model->get($id); 
        if(isset($check_data->name))
        {
            $real_name = $check_data->name;
        }
        return  $real_name;
    }
  

    public function get_payment_type($case)
    {
        switch ($case) {
            case 1:
                return "Cash";
                break;
            case 2:
                return "Card";
                break;
            case 3:
                return "ACH";
                break; 
            default:
                return "";
          }
    }



}

?>