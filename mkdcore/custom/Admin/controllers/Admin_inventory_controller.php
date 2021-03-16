<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_inventory_controller extends Admin_controller
{
    protected $_model_file = 'inventory_model';
    public $_page_name = 'Inventory';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('inventory_gallery_list_model');
        $this->load->model('category_model');
        $this->load->model('store_model');
        $this->load->model('physical_location_model');
        $this->load->model('customer_model'); 
        $this->load->library('names_helper_service');
        $this->load->library('barcode_service');
        
    }

    

    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Inventory_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Inventory_admin_list_paginate_view_model(
            $this->inventory_model,
            $this->pagination,
            '/admin/inventory/0');
        $this->_data['view_model']->set_heading('Inventory');
        $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
        $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
        $this->_data['view_model']->set_category_id(($this->input->get('category_id', TRUE) != NULL) ? $this->input->get('category_id', TRUE) : NULL);
         
        
        $where = [
            'product_name' => $this->_data['view_model']->get_product_name(),
            'sku' => $this->_data['view_model']->get_sku(),
            'category_id' => $this->_data['view_model']->get_category_id(),  
        ];

        $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/inventory/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->inventory_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format == 'csv')
        {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        
        if ( !empty( $this->_data['view_model']->get_list() ) ) 
        {
            $this->names_helper_service->set_physical_location_model($this->physical_location_model);
            $this->names_helper_service->set_customer_model($this->customer_model);
            $this->names_helper_service->set_category_model($this->category_model); 
            $this->names_helper_service->set_store_model($this->store_model); 
            foreach ($this->_data['view_model']->get_list() as $key => &$value) 
            { 
                $value->category_id       = $this->names_helper_service->get_category_real_name( $value->category_id ); 
                $value->physical_location = $this->names_helper_service->get_physical_location_real_name( $value->physical_location ); 
                $value->store_location_id = $this->names_helper_service->get_store_name( $value->store_location_id ); 
                 
            }
        }

        $this->_data['categories'] = $this->category_model->get_all();

        return $this->render('Admin/Inventory', $this->_data);
    }

    public function add()
    {
        include_once __DIR__ . '/../../view_models/Inventory_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->inventory_model->set_form_validation(
        $this->form_validation, $this->inventory_model->get_all_validation_rule());
        $this->_data['view_model'] = new Inventory_admin_add_view_model($this->inventory_model);
        $this->_data['view_model']->set_heading('Inventory');
        

        $this->_data['parent_categories']   =   $this->category_model->get_all(['status' => 1]);
        $this->_data['stores']              =   $this->store_model->get_all();
        $this->_data['physical_locations']  =   $this->physical_location_model->get_all();

        if ($this->form_validation->run() === FALSE)
        { 
            return $this->render('Admin/InventoryAdd', $this->_data);
        }

        $increment_id  =  $this->inventory_model->get_auto_increment_id();
        $sku           =  sprintf("%05d", $increment_id); 
 

        $product_name = $this->input->post('product_name', TRUE); 
        $category_id = $this->input->post('category_id', TRUE);
        $manifest_id = $this->input->post('manifest_id', TRUE);
        $physical_location = $this->input->post('physical_location', TRUE);
        $location_description = $this->input->post('location_description', TRUE);
        $weight = $this->input->post('weight', TRUE);
        $length = $this->input->post('length', TRUE);
        $height = $this->input->post('height', TRUE);
        $width = $this->input->post('width', TRUE);
        $feature_image = $this->input->post('feature_image', TRUE);
        $feature_image_id = $this->input->post('feature_image_id', TRUE);
        $selling_price = $this->input->post('selling_price', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $inventory_note = $this->input->post('inventory_note', TRUE);
        $cost_price = $this->input->post('cost_price', TRUE);
        $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);
        
        $status = $this->input->post('status', TRUE);
        $store_location_id = $this->input->post('store_location_id', TRUE);

        $can_ship = $this->input->post('can_ship', TRUE);
        $free_ship = $this->input->post('free_ship', TRUE);
        $product_type = $this->input->post('product_type', TRUE);
        $pin_item_top = $this->input->post('pin_item_top', TRUE);
        



        
        $barcode_image_name = $this->barcode_service->generate_png_barcode($sku, "inventory"); 
        /**
         *  Upload Image to S3
         * 
        */ 
        $barcode_image  = $this->upload_image_with_s3($barcode_image_name);

        if($product_type == 2)
        {
            $sku = '';
        }

        
        $result = $this->inventory_model->create([
            'product_name' => $product_name,
            'sku' => $sku,
            'barcode_image' => $barcode_image,
            'category_id' => $category_id,
            'manifest_id' => $manifest_id,
            'physical_location' => $physical_location,
            'location_description' => $location_description,
            'weight' => $weight,
            'length' => $length,
            'height' => $height,
            'width' => $width,
            'feature_image' => $feature_image,
            'feature_image_id' => $feature_image_id,
            'selling_price' => $selling_price,
            'quantity' => $quantity,
            'inventory_note' => $inventory_note,
            'cost_price' => $cost_price,
            'admin_inventory_note' => $admin_inventory_note, 
            'status' => $status,
            'store_location_id' => $store_location_id, 
            'can_ship' => $can_ship,
            'free_ship' => $free_ship,
            'product_type' => $product_type,
            'pin_item_top' => $pin_item_top,
            
        ]);

        if ($result)
        {
            $inventery_id = $result;
            /**
             * Get all images that are uploaded
             * save them one by one
            */
            $gallery_list = $this->input->post('gallery_image',TRUE);
            foreach ($gallery_list as $gallery_key => $gallery_value)
            {
                $image_name       = $this->input->post('gallery_image', TRUE)[$gallery_key];
                $gallery_image_id = $this->input->post('gallery_image_id', TRUE)[$gallery_key];
                if (!empty($image_name))
                {
                    $data_add_gallery = array(
                        'image_name'     => $image_name,
                        'image_id'       => $gallery_image_id,
                        'inventery_id'   => $result,
                    );
                    $this->inventory_gallery_list_model->create($data_add_gallery);
                }
            }


            $this->success('Inventory has been added successfully.');
            
            return $this->redirect('/admin/inventory/view/' . $inventery_id .'?print=1');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/InventoryAdd', $this->_data);
    }

    public function edit($id)
    {
        $model = $this->inventory_model->get($id);
        $session = $this->get_session();
        if (!$model)
        {
            $this->error('Error');
            return redirect('/admin/inventory/0');
        }

        include_once __DIR__ . '/../../view_models/Inventory_admin_edit_view_model.php';
        $this->form_validation = $this->inventory_model->set_form_validation(
        $this->form_validation, $this->inventory_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Inventory_admin_edit_view_model($this->inventory_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Inventory');
        

        $this->_data['gallery_lists']       =   $this->inventory_gallery_list_model->get_all(['inventory_id' => $id]);
        $this->_data['parent_categories']   =   $this->category_model->get_all(['status' => 1]);
        $this->_data['stores']              =   $this->store_model->get_all();
        $this->_data['physical_locations']  =   $this->physical_location_model->get_all();
        
        if ($this->form_validation->run() === FALSE)
        { 
            return $this->render('Admin/InventoryEdit', $this->_data);
        }

        $product_name = $this->input->post('product_name', TRUE);
        $sku = $this->input->post('sku', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $manifest_id = $this->input->post('manifest_id', TRUE);
        $physical_location = $this->input->post('physical_location', TRUE);
        $location_description = $this->input->post('location_description', TRUE);
        $weight = $this->input->post('weight', TRUE);
        $length = $this->input->post('length', TRUE);
        $height = $this->input->post('height', TRUE);
        $width = $this->input->post('width', TRUE);
        $feature_image = $this->input->post('feature_image', TRUE);
        $feature_image_id = $this->input->post('feature_image_id', TRUE);
        $selling_price = $this->input->post('selling_price', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $inventory_note = $this->input->post('inventory_note', TRUE);
        $cost_price = $this->input->post('cost_price', TRUE);
        $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);
         
        $status = $this->input->post('status', TRUE);
        $store_location_id = $this->input->post('store_location_id', TRUE);
         
        $can_ship = $this->input->post('can_ship', TRUE);
        $free_ship = $this->input->post('free_ship', TRUE);
        $product_type = $this->input->post('product_type', TRUE);
        $pin_item_top = $this->input->post('pin_item_top', TRUE);
        
        if($product_type == 2)
        {
            $sku = '';
        }

        
        $result = $this->inventory_model->edit([
            'product_name' => $product_name,
            'sku' => $sku,
            'category_id' => $category_id,
            'manifest_id' => $manifest_id,
            'physical_location' => $physical_location,
            'location_description' => $location_description,
            'weight' => $weight,
            'length' => $length,
            'height' => $height,
            'width' => $width,
            'feature_image' => $feature_image,
            'feature_image_id' => $feature_image_id,
            'selling_price' => $selling_price,
            'quantity' => $quantity,
            'inventory_note' => $inventory_note,
            'cost_price' => $cost_price,
            'admin_inventory_note' => $admin_inventory_note, 
            'status' => $status,
            'store_location_id' => $store_location_id, 
            'can_ship' => $can_ship,
            'free_ship' => $free_ship,
            'product_type' => $product_type,
            'pin_item_top' => $pin_item_top,
            
        ], $id);
       
        if ($result)
        {
            $inventery_id = $id;

            /**
             * Get all images that are uploaded
             * save them one by one
             */
            $gallery_list = $this->input->post('gallery_image',TRUE);
            foreach ($gallery_list as $gallery_key => $gallery_value)
            {
                $image_name       = $this->input->post('gallery_image', TRUE)[$gallery_key];
                $gallery_image_id = $this->input->post('gallery_image_id', TRUE)[$gallery_key];
                if (!empty($image_name))
                {
                    $data_add_gallery = array(
                        'image_name'   => $image_name,
                        'image_id'     => $gallery_image_id,
                        'inventery_id' => $inventery_id,
                    );
                    $this->inventory_gallery_list_model->create($data_add_gallery);
                }
            }

            $this->success('Inventory has been updated successfully.'); 
            return $this->redirect('/admin/inventory/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/InventoryEdit', $this->_data);
    }

    public function view($id)
    {
        $model = $this->inventory_model->get($id);

        if (!$model)
        {
            $this->error('Error');
            return redirect('/admin/inventory/0');
        }

        $this->names_helper_service->set_store_model($this->store_model); 
        $this->names_helper_service->set_physical_location_model($this->physical_location_model);
        $this->names_helper_service->set_customer_model($this->customer_model);
        $this->names_helper_service->set_category_model($this->category_model);  

        include_once __DIR__ . '/../../view_models/Inventory_admin_view_view_model.php';
        $this->_data['view_model'] = new Inventory_admin_view_view_model($this->inventory_model);
        $this->_data['view_model']->set_heading('Inventory');
        $model->category_id       = $this->names_helper_service->get_category_real_name( $model->category_id ); 
        $model->physical_location = $this->names_helper_service->get_physical_location_real_name( $model->physical_location ); 
        $model->store_location_id = $this->names_helper_service->get_store_name( $model->store_location_id ); 
        $this->_data['view_model']->set_model($model);

        
             
         


        return $this->render('Admin/InventoryView', $this->_data);
    }

    
    
    
    
    
    
    
    
}