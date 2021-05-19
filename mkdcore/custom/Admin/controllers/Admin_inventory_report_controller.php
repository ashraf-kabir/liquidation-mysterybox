<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * InventoryReport Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_inventory_report_controller extends Admin_controller
{
    protected $_model_file = 'inventory_model';
    public $_page_name = 'Inventory Report';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('category_model');
        
    }

    

    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/InventoryReport_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new InventoryReport_admin_list_paginate_view_model(
            $this->inventory_model,
            $this->pagination,
            '/admin/inventory_report/0');
        $this->_data['view_model']->set_heading('Inventory Report');
        $this->_data['view_model']->set_category_id(($this->input->get('category_id', TRUE) != NULL) ? $this->input->get('category_id', TRUE) : NULL);
        $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
        $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
        
        $where = [
            'category_id' => $this->_data['view_model']->get_category_id(),
            'product_name' => $this->_data['view_model']->get_product_name(),
            'sku' => $this->_data['view_model']->get_sku(),
            
            
        ];

        $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/inventory_report/0');
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

        $this->load->library('names_helper_service');
        if ( !empty( $this->_data['view_model']->get_list() ) ) 
        { 
            $this->names_helper_service->set_category_model($this->category_model);  
            foreach ($this->_data['view_model']->get_list() as $key => $value) 
            { 
                $value->category_id       = $this->names_helper_service->get_category_real_name( $value->category_id );   
            }
        }

        $this->_data['categories'] = $this->category_model->get_all();

        return $this->render('Admin/InventoryReport', $this->_data);
    }

    

    public function to_csv()
    { 

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Inventory_Report.csv"');

        $this->load->model('inventory_model');
        $this->load->model('category_model');

 
         

        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $category_id      = $this->input->get('category_id', TRUE) != NULL ? $this->input->get('category_id', TRUE) : NULL; 
        $product_name     = $this->input->get('product_name', TRUE) != NULL ? $this->input->get('product_name', TRUE) : NULL; 
        $sku              = $this->input->get('sku', TRUE) != NULL ? $this->input->get('sku', TRUE) : NULL; 


        
        $where = [
            'category_id'  => $category_id,
            'product_name' => $product_name,
            'sku'          => $sku, 
        ];
        $list = $this->inventory_model->get_all_for_csv( 
            $where,
            $order_by,
            $direction);
 

        $this->load->library('names_helper_service');
        if ( !empty( $list ) ) 
        { 
            $this->names_helper_service->set_category_model($this->category_model);  
            foreach ($list as $key => $value) 
            { 
                $value->category_id       = $this->names_helper_service->get_category_real_name( $value->category_id );   
            }
        }
         
  

        $clean_list = []; 
        foreach ($list as $key => $value)
        {  
            // $sku = str_replace("0", "O", $value->sku);
            $sku = preg_replace('~\G0~', 'O', $value->sku);
            $clean_list_entry              = [];
            $clean_list_entry['id']        = $value->id;
            $clean_list_entry['name']      = $value->product_name;
            $clean_list_entry['sku']       = $sku;
            $clean_list_entry['category']  = $value->category_id;
            $clean_list_entry['quantity']  = $value->quantity; 
            $clean_list[]                  = $clean_list_entry;
        }
    
        
 
        $column_fields = ['ID' , 'Name', 'SKU', 'Category', 'Quantity'];
       
        $csv = implode(",", $column_fields) . "\n";
        // $fields = array_filter($this->get_field_column());
        foreach($clean_list as $row)
        {
            $row_csv = [];
            foreach($row as $key =>$column)
            {
                // if (in_array($key, $fields))
                // {
                    $row_csv[] = '"' . $column . '"';
                // }
            }
            $csv = $csv . implode(',', $row_csv) . "\n";
        }   
 
        echo $csv; 
        exit(); 
    }

    

    
    
    
    
    
    
    
    
}