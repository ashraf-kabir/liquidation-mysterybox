<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
include_once 'Item.php';
/**
 * Custom Controller to Manage all JS Api and ajax calls
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
*/

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;

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


    public function try_printer()
    {
           
        try 
        {
            $profile = CapabilityProfile::load("POS-5890");

            // echo "<pre>";
            // print_r($profile );
            // die();
            /* Fill in your own connector here */
            $connector = new RawbtPrintConnector();

            /* Information for the receipt */
            $items = array(
                new item("Example item #1", "4.00"),
                new item("Another thing", "3.50"),
                new item("Something else", "1.00"),
                new item("A final item", "4.45"),
            );
            $subtotal = new item('Subtotal', '12.95');
            $tax = new item('A local tax', '1.30');
            $total = new item('Total', '14.25', true);
            /* Date is kept the same for testing */
            // $date = date('l jS \of F Y h:i:s A');
            $date = "Monday 6th of April 2015 02:56:25 PM";

            /* Start the printer */
            $logo = EscposImage::load("assets/frontend_images/logo.png", false);
            $printer = new Printer($connector, $profile);


            /* Print top logo */
            if ($profile->getSupportsGraphics()) {
                $printer->graphics($logo);
            }
            if ($profile->getSupportsBitImageRaster() && !$profile->getSupportsGraphics()) {
                $printer->bitImage($logo);
            }

            /* Name of shop */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("ExampleMart Ltd.\n");
            $printer->selectPrintMode();
            $printer->text("Shop No. 42.\n");
            $printer->feed();


            /* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text("SALES INVOICE\n");
            $printer->setEmphasis(false);

            /* Items */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text(new item('', '$'));
            $printer->setEmphasis(false);
            foreach ($items as $item) {
                $printer->text($item->getAsString(32)); // for 58mm Font A
            }
            $printer->setEmphasis(true);
            $printer->text($subtotal->getAsString(32));
            $printer->setEmphasis(false);
            $printer->feed();

            /* Tax and total */
            $printer->text($tax->getAsString(32));
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text($total->getAsString(32));
            $printer->selectPrintMode();

            /* Footer */
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for shopping\n");
            $printer->text("at ExampleMart\n");
            $printer->text("For trading hours,\n");
            $printer->text("please visit example.com\n");
            $printer->feed(2);
            $printer->text($date . "\n");

            /* Barcode Default look */

            $printer->barcode("ABC", Printer::BARCODE_CODE39);
            $printer->feed();
            $printer->feed();


            // Demo that alignment QRcode is the same as text
            $printer2 = new Printer($connector); // dirty printer profile hack !!
            $printer2->setJustification(Printer::JUSTIFY_CENTER);
            $printer2->qrCode("https://rawbt.ru/mike42", Printer::QR_ECLEVEL_M, 8);
            $printer2->text("rawbt.ru/mike42\n");
            $printer2->setJustification();
            $printer2->feed();


            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();

            echo "success";
            // die;

        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $printer->close();
        }

        /* A wrapper to do organise item names & prices into columns */

        

    }

    public function add_product()
    {
        $params = json_decode(file_get_contents('php://input'), TRUE);   
        // file_put_contents('file2.txt', print_r($params, true));
        $_POST = $params;

 
        $this->form_validation->set_rules('product_name', 'Product Name', 'required');
        $this->form_validation->set_rules('type', 'Product Type', 'required|integer|greater_than[0]|less_than[3]');
        $this->form_validation->set_rules('selling_price', 'Selling Price', 'required|numeric');
        $this->form_validation->set_rules('store_location_id', 'Store ID', 'required|integer');
        $this->form_validation->set_rules('can_ship', 'Can Ship', 'required|integer|greater_than[0]|less_than[3]');

        $this->form_validation->set_rules('sale_order_no', 'Sale Order#', 'required');
        $this->form_validation->set_rules('billing_address', 'Billing Address', 'required');
        $this->form_validation->set_rules('sale_order_date', 'Order Date', 'required');

 

        if ($this->form_validation->run() === FALSE)
        {
            $error_msg = validation_errors();
            $output['error']     = TRUE;
            $output['error_msg'] = $error_msg;
            echo json_encode($output);
            exit(); 
        }


        $this->load->model('inventory_model');
        $this->load->library('barcode_service');


        /**
         * 1 for Yes
         * 2 for No 
         * 
        */
        $can_ship   = $this->input->post('can_ship', TRUE);

        
        /*
        *  Product Type 
        *  1 for Regular
        *  2 for Generic
        *   
        **/ 
        $type       = $this->input->post('type', TRUE);


        $product_name       = $this->input->post('product_name', TRUE);
        $store_location_id  = $this->input->post('store_location_id', TRUE);
        $selling_price      = $this->input->post('selling_price', TRUE);
        $category_id        = $this->input->post('category_id', TRUE);
        $feature_image      = $this->input->post('feature_image', TRUE);
        $weight             = $this->input->post('weight', TRUE);
        $length             = $this->input->post('length', TRUE);
        $height             = $this->input->post('height', TRUE);
        $width              = $this->input->post('width', TRUE);
        $quantity           = $this->input->post('quantity', TRUE);
        $cost_price         = $this->input->post('cost_price', TRUE);


      
        $random_code = $this->inventory_model->get_auto_increment_id();
        $sku_product = sprintf("%05d", $random_code); 



        $barcode_image = ""; 
        //generate sku here  
        if($type  != 2)
        {  
            $barcode_image_name = $this->barcode_service->generate_png_barcode($sku_product, "inventory"); 
            /**
             *  Upload Image to S3 
            */ 
            $barcode_image  = $this->upload_image_with_s3($barcode_image_name);
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



    public function get_product_by_sku ($sku)
    {
        // $params = json_decode(file_get_contents('php://input'), TRUE);   
        // file_put_contents('file.txt', print_r($params, true));
        if(!empty($sku))
        {
            $this->load->model('inventory_model'); 
            $sku = urldecode($sku);
            $product = $this->inventory_model->get_by_fields( [ 'sku' => $sku] );
            
            if( !empty($product) )
            {
                $output['success'] = true; 
                $output['product'] = $product; 
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



    public function update_order_status()
    {

        $params = json_decode(file_get_contents('php://input'), TRUE);   
        if (!empty($this->input->post('sale_order_id'))) {
            $params['sale_order_id'] = $this->input->post('sale_order_id');
            $params['tracking_no'] = $this->input->post('tracking_no');
            $params['item_id'] = $this->input->post('item_id');
        }
        


        if(isset($params) and !empty($params))
        { 
            if(isset($params['sale_order_id']) and empty($params['sale_order_id']))
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Order ID is required.";
                echo json_encode($output);
                exit();
            } 


            $this->load->model('pos_order_model'); 
            $this->load->model('pos_order_items_model'); 
            if(isset($params['tracking_no']) and !empty($params['tracking_no']))
            {
                $tracking_no    = $params['tracking_no'];
                $sale_order_id  = $params['sale_order_id'];
                $item_id        = $params['item_id'];


                $order_data      = $this->pos_order_model->get($sale_order_id);
                // $order_detail    = $this->pos_order_items_model->get_by_fields(['product_id' => $item_id, 'order_id' => $sale_order_id]);
 

                if ($order_data) 
                {
                    $order_update = $this->pos_order_model->edit( [  'ship_station_tracking_no' => $tracking_no ], $sale_order_id);

                    if(!$order_update)
                    {
                        $output['error'] = TRUE;
                        $output['error_msg'] = "Error! please try again later.";
                        echo json_encode($output);
                        exit(); 
                    } 
                    exit(); 
                }


                $output['error'] = TRUE;
                $output['error_msg'] = "Error! Order not found.";
                echo json_encode($output);
                exit(); 
                 
                     
            } 



            if( $params['type'] != 1 AND $params['type'] != 2    )
            {
                $output['error'] = TRUE;
                $output['error_msg'] = "Type is incorrect.";
                echo json_encode($output);
                exit();
            }

            $sale_order_id = $params['sale_order_id'];   

            

            $order_data    = $this->pos_order_model->get($sale_order_id);

            if( isset($order_data->id) )
            { 

                if($params['type'] == 1)
                {
                    //status 5 for order is picked
                    $order_update = $this->pos_order_model->edit( [  'is_picked' => 1, 'pos_pickup_status' => 2 ], $order_data->id);

                    if($order_update)
                    {
                       
                    }
                    else
                    { 
                        $output['error'] = TRUE;
                        $output['error_msg'] = "Error! please try again later.";
                        echo json_encode($output);
                        exit();
                    }
                } 
                elseif($params['type'] == 2)
                {
                    //status 6 for order is shipped
                    $order_update = $this->pos_order_model->edit( [  'is_shipped' => 1 ], $order_data->id);

                    if($order_update)
                    {
                        // $data_note = array(
                        //     'order_id'   => $order_data->id,
                        //     'order_no'   => $order_data->sale_order_no,
                        //     'order_note' => "Order has been Shipped.",
                        //     'is_public'  => 1,
                        //     'is_private' => 0,
                        // ); 
                        // $this->order_notes_model->create($data_note); 
                    }
                    else
                    { 
                        $output['error'] = TRUE;
                        $output['error_msg'] = "Error! please try again later.";
                        echo json_encode($output);
                        exit();
                    }
                }   
            }
            else
            { 
                $output['error'] = TRUE;
                $output['error_msg'] = "Error! No order found.";
                echo json_encode($output);
                exit();
            } 

        }
        else
        {
            $output['error'] = TRUE;
            $output['error_msg'] = "Data is required.";
            echo json_encode($output);
            exit();
        }

    }
    



    public function update_order_on_shipping_system()
    {
        $this->load->model('pos_order_model');

        $list = $this->pos_order_model->get_all();

        foreach ($list as $key => $value) 
        {
            $this->send_order_to_shipper($value->id);
        }

    }






    public function get_thumbnails_list()
    {
        $this->load->model('inventory_model');


        $category_id  = $this->input->get('form_category_id');
        $list_of_data = $this->inventory_model->get_all(['category_id' => $category_id ]);


        $array_with_images = array();
        if (!empty($list_of_data)) 
        { 
            foreach($list_of_data as $value)
            {
               
                $images_list = json_decode($value->youtube_thumbnail_1);
                $videos_list = json_decode($value->video_url);
                if (!empty($images_list)) 
                {
                    foreach($images_list as $k => $img)
                    {
                        if (!empty($img)) 
                        { 
                            $ilist['url'] = $img;
                            $ilist['video'] = $videos_list[$k];
                            $ilist['id']  = 0;
                            array_push($array_with_images, $ilist);
                        }
                    }
                }
            } 
        } 

        $output['item'] = $array_with_images;
        echo json_encode($output); 
        exit();
    }


}