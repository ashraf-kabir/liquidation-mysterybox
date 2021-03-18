<?php defined('BASEPATH') OR exit('No direct script access allowed');  
/**
 * Custom Controller to Manage all JS Api and ajax calls
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
*/ 

class Image_portal_controller extends Manaknight_Controller
{
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS"); 
    }  
    
    public function get_images()
    {
        
        if ($this->input->post('page_no', TRUE)) 
        {
            $page_no = $this->input->post('page_no', TRUE);
       
            $image_site_url = $this->config->item('image_site_url'); 


            $post_data = array(
                'page_no'       => $page_no,
                'email'         => "member@manaknight.com", 
            ); 


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $image_site_url .'v1/api/get_images',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
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
            $data_list =  json_decode($response); 
             
            
            if (isset($data_list->images_list)) 
            {
                $total_rows   =  $data_list->total_rows;
                $images_list  =  $data_list->images_list;


                $content_div = "<div class='row' >";
                foreach ($images_list as $key => $value) 
                {
                    $content_div .= "<div class='col-4' style='text-align: center;' >";
                    $content_div .= "<img src='" . $value->url . "' image-url='" . $value->url . "'    image-id='" . $value->id . "' style='width:100%'   />";
                    $content_div .= "<input type='radio' class='trigger-checkbox-event' name='image_portal_check'  image-url='" . $value->url . "'    image-id='" . $value->id . "'  /> ";
                    $content_div .= "</div>";
                }

                $paginations = $this->get_pagination($total_rows, 'image_portal_pagination', 10, $page_no);


                $content_div .= "<div class='col-12' >";
                $content_div .=  $paginations;
                $content_div .= "</div></div>";


              



                $output['content_div']    =  $content_div;  
                echo json_encode($output);
                exit();

            }
            else
            {

            } 

        }
    }




    public function get_pagination($total_records, $class_for_evnt, $total_records_per_page, $page_no)
    {  

        $offset         =  ($page_no-1) * $total_records_per_page;
        $previous_page  =  $page_no - 1;
        $next_page      =  $page_no + 1;
        $adjacents      =  "2"; 
        

        $active_link = '<nav aria-label="Page navigation example"><ul class="pagination">';

        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last       = $total_no_of_pages - 1; // total pages minus 1


        if ($total_no_of_pages <= 10)
        {   
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++)
            {
                if ($counter == $page_no) {
                    $active_link .=  "<li class='page-item active'><a  class='page-link   " . $class_for_evnt . " '  page-no=" . $counter . " >" . $counter . "</a></li>"; 
                }
                else
                {
                    $active_link .=  "<li  class='page-item' ><a  class='page-link  " . $class_for_evnt . " '  page-no=" . $counter . " >" . $counter . "</a></li>";
                }
            }
        } 
        else if($total_no_of_pages > 10)
        {
            if($page_no <= 4) 
            { 
                for ($counter = 1; $counter < 8; $counter++)
                { 
                    if ($counter == $page_no) 
                    {
                        $active_link .=  "<li   class='page-item active' ><a  class='page-link   " . $class_for_evnt . " '  page-no=" . $counter . " >" . $counter . "</a></li>"; 
                    }else{
                        $active_link .=  "<li  class='page-item' ><a  class='page-link   " . $class_for_evnt . " '  page-no=" . $counter . "   >" . $counter . "</a></li>";
                    }
               }
               $active_link .=  "<li  class='page-item' ><a  class='page-link   " . $class_for_evnt . " ' >...</a></li>";
               $active_link .=  "<li  class='page-item' ><a  class='page-link   " . $class_for_evnt . " ' page-no=" . $second_last . "    >" . $second_last . "</a></li>";
               $active_link .=  "<li  class='page-item' ><a  class='page-link   " . $class_for_evnt . " ' page-no=" . $total_no_of_pages . " >" . $total_no_of_pages . "</a></li>";
            }
        }
        $active_link .= '</ul></nav>';


        return $active_link;
    }



    public function upload_image_portal_image_to_s3()
    {

        if ($this->input->post('image_url')) 
        { 
            $image_name_url  =  $this->input->post('image_url'); 
            $image_name_id   =  $this->input->post('image_id');

            $user_id  = $this->session->userdata('user_id');

            $this->load->model('image_model');


            $image_id = $this->image_model->create([
                'url'     => $image_name_url,
                'type'    => 0,
                'user_id' => $user_id,
                'width'   => 0,
                'caption' => '',
                'height'  => 0
            ]);

            $output[ 'id' ]     = $image_id;
            $output[ 'image' ]  = $image_name_url;
              

            echo json_encode($output);
            exit;
        }
    }



    
}