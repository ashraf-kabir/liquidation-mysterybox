<?php

class Redirect_custom_service
{
     
    private $_url_redirect_model;
   

    public function __construct()
    {
         
    }

    public function set_url_redirect_model($url_redirect_model)
    {
        $this->_url_redirect_model = $url_redirect_model;
    }

     

    public function check_redirect()
    {
        /**
         * STEPS
         * check if url is in db
         * if found redirect to that url
         * 
         * 
        */
        $current_url = current_url(); 

        $params      = $_SERVER['QUERY_STRING'];  

        if( !empty($params) )
        {
            $full_url = $current_url . '?' . $params;
        }else{
            $full_url = $current_url;
        }

        

        $check_data =$this->_url_redirect_model->get_by_fields(['url' => $full_url]); 
  
        if(!empty($check_data))
        {
            header("Location:" . $check_data->rewrite_url);
        }
    }

     
}