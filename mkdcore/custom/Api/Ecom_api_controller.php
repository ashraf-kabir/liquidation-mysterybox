<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Custom Controller to Manage all JS Api and ajax calls
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
*/

class Ecom_api_controller extends Manaknight_Controller
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

    public function add_product()
    {
        $params = json_decode(file_get_contents('php://input'), TRUE);   
        file_put_contents('file2.txt', print_r($params, true));
        if(isset($params) and !empty($params))
        {
            

            if(!isset($params['product_name']) and empty($params['product_name']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Product name is required.";
                echo json_encode($output);
                exit();
            }
 

            

            /*
            *  Product Type 
            *  1 for Regular
            *  2 for Generic
            *   
            **/
            if(!isset($params['type']) or empty($params['type']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Product type is required.";
                echo json_encode($output);
                exit();
            }

            if($params['type'] != 1 AND $params['type'] != 2)
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Product type is incorrect.";
                echo json_encode($output);
                exit();
            }


            if(!isset($params['selling_price']) or empty($params['selling_price']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Selling price is required.";
                echo json_encode($output);
                exit();
            }

            /**
             * 1 for Yes
             * 2 for No 
             * 
            */
            if(!isset($params['can_ship']) or empty($params['can_ship']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Can ship is required.";
                echo json_encode($output);
                exit();
            }


            if(!isset($params['store_location_id']) or empty($params['store_location_id']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Store is required.";
                echo json_encode($output);
                exit();
            }


            $this->load->model('inventory_model');
            $this->load->library('barcode_service');



            $product_name       =  $params['product_name'];  
            $type 		        =  $params['type'];    
            $store_location_id  =  $params['store_location_id'];
            $can_ship           =  $params['can_ship'];
            $selling_price      =  $params['selling_price'];


            $random_code = $this->inventory_model->get_auto_increment_id();
            $sku_product = sprintf("%05d", $random_code); 

            $barcode_image = ""; 
            //generate sku here  
            if($type  != 2)
            {  
                $barcode_image_name = $this->barcode_service->generate_png_barcode($sku_product, "inventory"); 
                /**
                 *  Upload Image to S3
                 * 
                */ 
                $barcode_image  = $this->upload_image_with_s3($barcode_image_name);
            }

            $category_id = "";
            if( isset($params['category_id']) )
            {
                $category_id = $params['category_id'];
            } 

            $feature_image = "";
            if( isset($params['feature_image']) )
            {
                $feature_image = $params['feature_image'];
            }


            $weight = 0;
            if( isset($params['weight']) )
            {
                $weight = $params['weight'];
            }


            $length = 0;
            if( isset($params['length']) )
            {
                $length = $params['length'];
            }

            $height = 0;
            if( isset($params['height']) )
            {
                $height = $params['height'];
            }



            $width = 0;
            if( isset($params['width']) )
            {
                $width = $params['width'];
            }


            $quantity = 1;
            if( isset($params['quantity']) )
            {
                $quantity = $params['quantity'];
            }


            $cost_price = 0;
            if( isset($params['cost_price']) )
            {
                $cost_price = $params['cost_price'];
            }


            $data_detail = array( 
                'product_name'         => $product_name, 
                'type'                 => $type,  
                'sku'                  => $sku_product,
                'category_id'          => $category_id,
                'store_location_id'    => $store_location_id,
                'feature_image'        => $feature_image, 
                'weight'               => $weight, 
                'length'               => $length,  
                'width'                => $width,
                'height'               => $height,
                'quantity'             => $quantity,
                'barcode_image'        => $barcode_image,
                'pin_item_top'         => 1,
                'available_in_shelf'   => 1,
                'cost_price'           => $cost_price,
                'can_ship'             => $can_ship, 
                'selling_price'        => $selling_price, 
            ); 
            $inventory = $this->inventory_model->create($data_detail); 


            if($inventory)
            {
                $output['success']       = TRUE;
                $output['success_msg']   = "Success! Inventory has been created successfully.";
                $output['id']            = $inventory;
                echo json_encode($output);
                exit();
            } 
            else
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Error! please try again later.";
                echo json_encode($output);
                exit(); 
            }


        }else{
            $output['error'] = TRUE;
            $output['error_msg'] = "Data is required.";
            echo json_encode($output);
            exit();
        }

    }




    
    public function get_stores()
    {
        $this->load->model('store_model');

        $output['stores'] = $this->store_model->get_all();
        echo json_encode($output);
        exit();
    }



    public function get_categories()
    {
        $this->load->model('category_model');

        $output['categories'] = $this->category_model->get_all();
        echo json_encode($output);
        exit();
    }



    public function scan_product()
    {
        $params = json_decode(file_get_contents('php://input'), TRUE);   
        // file_put_contents('file.txt', print_r($params, true));
        if(isset($params) and !empty($params))
        {
            $this->load->model('inventory_model'); 

            $barcode_value = $params['product_sku']; 
            $product_id    = $params['product_id']; 
            
            $check_order_data = $this->inventory_model->get_by_fields( [ 'sku' => $barcode_value, 'id' => $product_id ] );
            
            if( !empty($check_order_data) )
            {
                $output['success'] = true; 
                echo json_encode($output);
                exit();
            }else{
                $output['error'] = true;
                $output['msg']   = "No such barcode found.";
                echo json_encode($output);
                exit();
            }
        }
    }


    
}