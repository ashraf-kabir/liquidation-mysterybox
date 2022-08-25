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
        $this->load->model('user_model'); 
        $this->load->model('inventory_transfer_model'); 
        $this->load->library('names_helper_service');
        $this->load->library('barcode_service');
        $this->load->database();
        
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
        

        $this->_data['parent_categories']   =   $this->_get_grouped_categories();
        $this->_data['stores']              =   $this->store_model->get_all();
        $this->_data['physical_locations']  =   $this->physical_location_model->get_all();
        $this->_data['sale_persons']        =   $this->user_model->get_all_users();

        // echo '<pre>'; print_r($this->_data['parent_categories']); die();
        if ($this->input->post('can_ship') == 1) 
        {
            $this->form_validation->set_rules('weight', 'Weight', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('length', 'Length', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('height', 'Height', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('width', 'Width', 'required|greater_than_equal_to[1]');
        }

        $this->form_validation->set_rules('store_location_id[]', 'Store', 'callback_validate_store_inventory');


         

        if ($this->form_validation->run() === FALSE)
        { 
            return $this->render('Admin/InventoryAdd', $this->_data);
        }
    


        $increment_id  =  $this->inventory_model->get_auto_increment_id();
        $sku           =  sprintf("%05d", $increment_id); 
 

        $sale_person_id = $this->input->post('sale_person_id', TRUE); 
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

        $can_ship = $this->input->post('can_ship', TRUE) ?? 2;
        $can_ship_approval = $this->input->post('can_ship_approval', TRUE) ?? 2;
        $free_ship = $this->input->post('free_ship', TRUE);
        $product_type = $this->input->post('product_type', TRUE);
        $pin_item_top = $this->input->post('pin_item_top', TRUE);
        $video_url = json_encode($this->input->post('video_url', TRUE)); 
        $youtube_thumbnail_1 = json_encode($this->input->post('youtube_thumbnail_1', TRUE));
         


        //SKU for category
        $category_data = $this->category_model->get($category_id);

        if (isset($category_data->sku_prefix) and !empty($category_data->sku_prefix))
        {
            $sku = $category_data->sku_prefix . "" . $sku;
        }


        
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

        $store_inventory = [];
        $total_quantity = 0;
        foreach ($store_location_id as $key => $store) {
            //TODO skip duplicates or return validation error (loop through store id and thro validation error if duplicate entry found)
            $store_inventory_item['store_id'] = $store_location_id[$key];
            $store_inventory_item['quantity'] = isset($quantity[$key]) ? $quantity[$key] : '';
            $store_inventory_item['physical_location'] = isset($physical_location[$key]) ? $physical_location[$key] : '';
            $store_inventory_item['location_description'] = isset($location_description[$key]) ? $location_description[$key] : '';
            array_push($store_inventory, $store_inventory_item);
            $total_quantity += !empty($quantity[$key]) ? $quantity[$key] : 0;
        }

        $store_inventory = json_encode($store_inventory);
        
        $result = $this->inventory_model->create([
            'sale_person_id' => $sale_person_id,
            'product_name' => $product_name,
            'sku' => $sku,
            'barcode_image' => $barcode_image,
            'category_id' => $category_id,
            'manifest_id' => $manifest_id,
            'physical_location' => '',
            'location_description' => '',
            'weight' => $weight,
            'length' => $length,
            'height' => $height,
            'width' => $width,
            'feature_image' => $feature_image,
            'feature_image_id' => $feature_image_id,
            'selling_price' => $selling_price,
            'quantity' => $total_quantity,
            'inventory_note' => $inventory_note,
            'cost_price' => $cost_price,
            'admin_inventory_note' => $admin_inventory_note, 
            'status' => $status,
            'store_location_id' => '', 
            'can_ship' => $can_ship,
            'can_ship_approval' => $can_ship_approval,
            'free_ship' => $free_ship,
            'product_type' => $product_type,
            'pin_item_top' => $pin_item_top,
            'video_url' => $video_url, 
            'youtube_thumbnail_1' => $youtube_thumbnail_1, 
            'store_inventory' => $store_inventory
        ]);
 
        if ($result)
        {
            $inventory_id = $result;
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
                        'inventory_id'   => $inventory_id,
                    );
                    $this->inventory_gallery_list_model->create($data_add_gallery);
                }
            }


            $this->success('Inventory has been added successfully.');
            
            return $this->redirect('/admin/inventory/view/' . $inventory_id .'?print=1');
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
        $this->_data['parent_categories']   =   $this->_get_grouped_categories();
        // $this->_data['parent_categories']   =   $this->category_model->get_all(['status' => 1]);
        $this->_data['stores']              =   $this->store_model->get_all();
        $this->_data['physical_locations']  =   $this->physical_location_model->get_all();
        $this->_data['sale_persons']        =   $this->user_model->get_all_users();
        $this->_data['store_inventory']     =   json_decode($model->store_inventory);

        // $this->_data['parent_categories'] 

        if ($this->input->post('can_ship') == 1) 
        {
            $this->form_validation->set_rules('weight', 'Weight', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('length', 'Length', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('height', 'Height', 'required|greater_than_equal_to[1]');
            $this->form_validation->set_rules('width', 'Width', 'required|greater_than_equal_to[1]');
        }
        $this->form_validation->set_rules('store_location_id[]', 'Store', 'callback_validate_store_inventory');


     

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
         
        $sale_person_id = $this->input->post('sale_person_id', TRUE);
        $can_ship = $this->input->post('can_ship', TRUE) ?? 2;
        $can_ship_approval = $this->input->post('can_ship_approval', TRUE) ?? 2;
        $free_ship = $this->input->post('free_ship', TRUE);
        $product_type = $this->input->post('product_type', TRUE);
        $pin_item_top = $this->input->post('pin_item_top', TRUE);
        $video_url = json_encode($this->input->post('video_url', TRUE));
        
 
        $youtube_thumbnail_1 = json_encode($this->input->post('youtube_thumbnail_1', TRUE));

        if($product_type == 2)
        {
            $sku = '';
        }
        
        $store_inventory = [];
        $total_quantity = 0;
        foreach ($store_location_id as $key => $store) {
            $store_inventory_item['store_id'] = $store_location_id[$key];
            $store_inventory_item['quantity'] = isset($quantity[$key]) ? $quantity[$key] : '';
            $store_inventory_item['physical_location'] = isset($physical_location[$key]) ? $physical_location[$key] : '';
            $store_inventory_item['location_description'] = isset($location_description[$key]) ? $location_description[$key] : '';
            array_push($store_inventory, $store_inventory_item);
            $total_quantity += !empty($quantity[$key]) ? $quantity[$key] : 0;
        }

        $store_inventory = json_encode($store_inventory);
        
        $result = $this->inventory_model->edit([
            'sale_person_id' => $sale_person_id,
            'product_name' => $product_name,
            'sku' => $sku,
            'category_id' => $category_id,
            'manifest_id' => $manifest_id,
            'physical_location' => '',
            'location_description' => '',
            'weight' => $weight,
            'length' => $length,
            'height' => $height,
            'width' => $width,
            'feature_image' => $feature_image,
            'feature_image_id' => $feature_image_id,
            'selling_price' => $selling_price,
            'quantity' => $total_quantity,
            'inventory_note' => $inventory_note,
            'cost_price' => $cost_price,
            'admin_inventory_note' => $admin_inventory_note, 
            'status' => $status,
            'store_location_id' => '', 
            'can_ship' => $can_ship,
            'can_ship_approval' => $can_ship_approval,
            'free_ship' => $free_ship,
            'product_type' => $product_type,
            'pin_item_top' => $pin_item_top,
            'video_url' => $video_url,
            'youtube_thumbnail_1' => $youtube_thumbnail_1,  
            'store_inventory' => $store_inventory
            
        ], $id);
       
        if ($result)
        {
            $inventory_id = $id;



            if($quantity > $model->quantity )
            {
                $this->load->model('notification_system_model');
                $this->load->library('helpers_service');
                $this->load->library('mail_service');
                $this->helpers_service->set_notification_system_model($this->notification_system_model);
                $this->helpers_service->set_mail_service($this->mail_service);
                $this->helpers_service->set_config($this->config);
                $this->helpers_service->notify_item_has_been_added($inventory_id); 
            }
            



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
                        'inventory_id' => $inventory_id,
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
        $store_inventory = !empty($model->store_inventory) ? json_decode($model->store_inventory) : [];
        
        foreach ($store_inventory as $key => &$value) {
            $store_inventory[$key]->store_name = $this->names_helper_service->get_store_name( $value->store_id ); 
            $store_inventory[$key]->physical_location_name = $this->names_helper_service->get_physical_location_real_name( $value->physical_location );
        }
        $this->_data['store_inventory'] = $store_inventory;
 
        return $this->render('Admin/InventoryView', $this->_data);
    }

    
    
    
    
    public function delete_gallery_image($id)
    {
        $model = $this->inventory_gallery_list_model->get($id);

        if (!$model)
        {
            $this->error('Error');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        $result = $this->inventory_gallery_list_model->real_delete($id);

        if ($result)
        {
            $this->success('Image deleted successfully.');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        $this->error('Error');
        return redirect($_SERVER['HTTP_REFERER']);
    }
    
    
    
    public function scan_product_view()
    { 
        $this->_data['page_name'] = "Scan Inventory";
            
        
        return $this->render('Admin/Scan_Product_View', $this->_data);
    }


    private function _get_grouped_categories(){
        // get parent categoris
        $table = 'category';
        $parent_where = '(parent_category_id IS NULL OR parent_category_id = 0) AND status = 1';
		$this->db->where($parent_where);
        $parent_categories = $this->db->get($table)->result();

        // Get child categories
        $child_where = '(parent_category_id IS NOT NULL OR parent_category_id != 0) AND status = 1';
		$this->db->where($child_where);
        $child_categories = $this->db->get($table)->result();

        $categories = [];

        foreach ($parent_categories as $key => $parent_category) {
            // Add parent
            $categories[] = $parent_category;

            // Loop through child categories and append if its the child
            foreach ($child_categories as $key => $child_category) {
                if($child_category->parent_category_id == $parent_category->id){
                    $categories[] = $child_category;
                }
            }
        }

        return $categories;

	}

    public function validate_store_inventory ()
    {
        // ensure the same store is not selected more than once.
        $stores = $this->input->post('store_location_id');
        $quantity = $this->input->post('quantity');

        if(count($stores) < 1 || count($quantity) < 1){
            $this->form_validation->set_message('validate_store_inventory', 'The Store and Quantity fields are required.');
            return FALSE;
        }

        if(count(array_unique($stores)) < count($stores)){
            $this->form_validation->set_message('validate_store_inventory', 'The {field} fields contain duplicates.');
            return FALSE;
        }

        return TRUE;
    }

    public function transfer ($id)
    {
        $model = $this->inventory_model->get($id);
        $session = $this->get_session();
        if (!$model)
        {
            $this->error('Error');
            return redirect('/admin/inventory/0');
        }

        if(isset($_POST['submit_inventory_transfer']))
        {
            // Start Inventory transfer
            $from_store = $this->input->post('from_store');
            $from_quantity = $this->input->post('from_quantity');
            $to_store = $this->input->post('to_store');
            if($from_store == $to_store){
                // error cant transfer to the same store
                $this->error('Error, Cannot transfer to the same store.');
                return redirect($_SERVER['HTTP_REFERER']);
            }
            $store_inventory = json_decode($model->store_inventory);
            foreach ($store_inventory as $key => &$value) 
            { 
                 if($value->store_id == $from_store ) 
                 { // remove quantity from store
                    $value->quantity = $value->quantity - $from_quantity;
                    continue;
                 }
                 if($value->store_id == $to_store ) 
                 { // add quantity to store
                    $value->quantity += $from_quantity;
                 }
            }
            $this->inventory_model->edit([
                'store_inventory' => json_encode($store_inventory)
            ], $model->id);

            $this->success('Inventory Transferred Successfully');
            return redirect('/admin/inventory/view/'.$id);
        }


        $this->_data['heading'] = 'Inventory';
        $this->_data['inventory_item'] = $model;
        $this->_data['store_data'] = base64_encode($model->store_inventory);
        $store_inventory = json_decode($model->store_inventory);
        $this->names_helper_service->set_store_model($this->store_model); 
        foreach ($store_inventory as $key => $value) 
        { 
            $value->store = $this->names_helper_service->get_store_name( $value->store_id ); 
             
        }
        $this->_data['store_inventory'] = $store_inventory;

        return $this->render('Admin/InventoryTransfer', $this->_data);
    }

    public function transfer_inventory()
    {
        $this->load->library('helpers_service');
        $this->load->model('inventory_transfer_log_model');
        $this->_data['heading'] = 'Inventory Transfer';
        $this->_data['page_name'] = 'Inventory Transfer';

        $this->_data['encoded_stores'] = base64_encode(json_encode($this->store_model->get_all()));
        $this->_data['inventory_items'] = $this->inventory_model->get_all(['quantity > 0']);
        
        if(isset($_POST['submit_inventory_transfer']))
        {
            // Start Inventory transfer
            $sku = $this->input->post('sku');
            $from_store = $this->input->post('from_store');
            $from_quantity = $this->input->post('from_quantity');
            $to_store = $this->input->post('to_store');
            $product = $this->inventory_model->get_by_field('sku', $sku);
            if(empty($product)){
                // error cant transfer to the same store
                $this->error('Error, Product not found.');
                return redirect($_SERVER['HTTP_REFERER']);
            }
            if($from_store == $to_store){
                // error cant transfer to the same store
                $this->error('Error, Cannot transfer to the same store.');
                return redirect($_SERVER['HTTP_REFERER']);
            }

            $result = $this->inventory_transfer_model->create([
                'product_name' => $product->product_name,
                'sku' => $sku,
                'from_store' => $from_store,
                'to_store' => $to_store,
                'quantity' => $from_quantity,
                'status' => '1' //pending
            ]);

            // Log
            $this->helpers_service->set_inventory_transfer_log_model($this->inventory_transfer_log_model);
            $this->helpers_service->set_inventory_transfer_model($this->inventory_transfer_model);
            $this->helpers_service->set_store_model($this->store_model);
            $this->helpers_service->log_inventory_transfer($result, 'Initiate transfer');

            $this->success('Inventory Transfer Request Pending');
            return redirect('/admin/transfer/transfer_inventory/');
        }

        

        return $this->render('Admin/InventoryTransfer', $this->_data);
    }



}