<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Category_wise Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_category_wise_controller extends Admin_controller
{
    protected $_model_file = 'category_model';
    public $_page_name = 'Category Wise';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('inventory_model');    
        $this->load->model('pos_order_items_model');    
        $this->load->model('pos_order_items_custom_model');    
        
    }

    

    public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Category_wise_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Category_wise_admin_list_paginate_view_model(
            $this->category_model,
            $this->pagination,
            '/admin/category_wise/0');
        $this->_data['view_model']->set_heading('Category Wise');
        $this->_data['view_model']->set_name(($this->input->get('name', TRUE) != NULL) ? $this->input->get('name', TRUE) : NULL);
        $this->_data['order_date'] = $this->input->get('order_date', TRUE) != NULL ? $this->input->get('order_date', TRUE) : NULL;
		
        $where = [
            'name' => $this->_data['view_model']->get_name(), 
        ];

        $this->_data['view_model']->set_total_rows($this->category_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/category_wise/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->category_model->get_paginated(
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
            foreach ($this->_data['view_model']->get_list() as $key => &$value) 
            {
                $products_list = $this->inventory_model->get_all(['category_id' => $value->id  ]); 


                $total_items    =  0;
                $total_sale     =  0;
                $total_qty      =  0;
                $total_credit   =  0;
                $total_cash     =  0;
                if ( !empty( $products_list ) ) 
                {
                    foreach ($products_list as $key_product => $product) 
                    {
                        $where_sale_order = [
                            'transaction_date'  =>  $this->_data['order_date'],  
                            'product_id'        =>  $product->id
                        ];
        
                        $order_items = $this->pos_order_items_custom_model->get_all_pos_order( $where_sale_order ); 
                        
                        
                        if ( !empty( $order_items ) ) 
                        {
                            foreach ($order_items as $key_item => $item) 
                            {
                                $total_sale     +=  $item->amount;
                                $total_qty      +=  $item->quantity;
                                if( $item->payment_type == 1)
                                {
                                    $total_cash     +=  $item->amount;
                                }else if($item->payment_type == 2)
                                {
                                    $total_credit   +=  $item->amount;
                                } 
                            }
                        }
                        $total_items++;
                    }
                }


                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty;
                $value->total_credit  =  $total_credit;
                $value->total_cash    =  $total_cash;
                $value->total_items   =  $total_items;

            }
        }

        return $this->render('Admin/Category_wise', $this->_data);
	}

    

    

    public function view($id)
	{ 
        $category_data = $this->category_model->get( $id ); 
       


        $this->_data['search_sku']    =     $this->input->get('search_sku', TRUE) != NULL  ? $this->input->get('search_sku', TRUE) : NULL ;
        $this->_data['search_name']   =     $this->input->get('search_name', TRUE) != NULL  ? $this->input->get('search_name', TRUE) : NULL ;
        $this->_data['order_date']    =     $this->input->get('order_date', TRUE) != NULL  ? $this->input->get('order_date', TRUE) : NULL ;

		
        $where = [
            'sku'            => $this->_data['search_sku'], 
            'product_name'   => $this->_data['search_name'],  
            'category_id'    => $id 
        ];

        $products_list = $this->inventory_model->get_all($where); 

        
         
        if ( !empty( $products_list ) ) 
        {
            foreach ($products_list as $key_product => $product) 
            {
                $where_sale_order = [
                    'transaction_date'  =>  $this->_data['order_date'],  
                    'product_id'        =>  $product->id
                ];

                $order_items = $this->pos_order_items_custom_model->get_all_pos_order( $where_sale_order ); 
                 
                $total_sale     =  0;
                $total_qty      =  0;
                $total_credit   =  0;
                $total_cash     =  0;
               
                if ( !empty( $order_items ) ) 
                { 
                    foreach ($order_items as $key_item => $item) 
                    { 
                        $total_sale     +=  $item->amount;
                        $total_qty      +=  $item->quantity;
                        if( $item->payment_type == 1)
                        {
                            $total_cash     +=  $item->amount;
                        }else if($item->payment_type == 2)
                        {
                            $total_credit   +=  $item->amount;
                        } 
                        
                    }
                } 

                $product->total_sale    =  $total_sale;
                $product->total_qty     =  $total_qty;
                $product->total_credit  =  $total_credit;
                $product->total_cash    =  $total_cash; 
            } 
        }

 
        
        $this->_data['heading']          =  $category_data->name;
        $this->_data['category_id']      =  $category_data->id;
        $this->_data['products_list']    =  $products_list;

        return $this->render('Admin/Category_wiseDetail', $this->_data);
	}

    
    
    
    
    
    
    
    
}