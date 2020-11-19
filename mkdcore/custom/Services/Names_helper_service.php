<?php 
class Names_helper_service {

    private $_category_model;
      


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


  





}

?>