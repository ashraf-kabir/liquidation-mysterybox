<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * CustomerReport Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_customer_report_controller extends Admin_controller
{
    protected $_model_file = 'customer_model';
    public $_page_name = 'Customer Report';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('pos_order_items_report_model');
        
    }

    

	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/CustomerReport_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new CustomerReport_admin_list_paginate_view_model(
            $this->customer_model,
            $this->pagination,
            '/admin/customer_report/0');
        $this->_data['view_model']->set_heading('Customer Report');
        $this->_data['view_model']->set_name(($this->input->get('name', TRUE) != NULL) ? $this->input->get('name', TRUE) : NULL);
		$this->_data['view_model']->set_email(($this->input->get('email', TRUE) != NULL) ? $this->input->get('email', TRUE) : NULL);

        $this->_data['from_date']      =     $this->input->get('from_date', TRUE) != NULL  ? $this->input->get('from_date', TRUE) : NULL ;
        $this->_data['to_date']        =     $this->input->get('to_date', TRUE) != NULL  ? $this->input->get('to_date', TRUE) : NULL ;
        
        $from_date = $this->_data['from_date'];
        $to_date   = $this->_data['to_date'];

        if($from_date > $to_date)
        {  
            $this->session->set_flashdata('error2', 'Error! From date must be greater then to date.');
            return redirect($_SERVER['HTTP_REFERER']);
        }
		
        $where = [
            'name' => $this->_data['view_model']->get_name(),
			'email' => $this->_data['view_model']->get_email(), 
        ];

        $this->_data['view_model']->set_total_rows($this->customer_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/customer_report/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->customer_model->get_paginated(
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
                
                $total_sale = $this->pos_order_items_report_model->get_income_from_customer( $value->id, $from_date , $to_date); 


                $total_qty = $this->pos_order_items_report_model->get_total_qty_sold_to_customer( $value->id, $from_date , $to_date);  
                  
                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty; 
            }
        }

        return $this->render('Admin/CustomerReport', $this->_data);
	}

    

    

    

    
    
    
    
    
    
    
    
}