<?php 
class Names_helper_service {

    private $_category_model;
    private $_physical_location_model;
    private $_customer_model;  


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
  





}

?>