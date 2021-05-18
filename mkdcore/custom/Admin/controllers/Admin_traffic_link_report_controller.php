<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * TrafficLinkReport Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_traffic_link_report_controller extends Admin_controller
{
    protected $_model_file = 'pos_order_model';
    public $_page_name = 'Traffic Link Report';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    

    	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/TrafficLinkReport_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new TrafficLinkReport_admin_list_paginate_view_model(
            $this->pos_order_model,
            $this->pagination,
            '/admin/traffic_link_report/0');
        $this->_data['view_model']->set_heading('Traffic Link Report');
        $this->_data['view_model']->set_referrer(($this->input->get('referrer', TRUE) != NULL) ? $this->input->get('referrer', TRUE) : NULL);
		
        $where = [
            'referrer' => $this->_data['view_model']->get_referrer(),
			
            
        ];

        $this->_data['view_model']->set_total_rows($this->pos_order_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/traffic_link_report/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->pos_order_model->get_paginated(
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

        return $this->render('Admin/TrafficLinkReport', $this->_data);
	}

    

    

    public function to_csv()
    { 

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Traffice_Link_Report.csv"');


        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';


        $referrer          = $this->input->get('referrer', TRUE) != NULL ? $this->input->get('referrer', TRUE) : NULL; 
        
 
        
        $where = [
            'referrer' => $referrer,   
        ];


        $list = $this->pos_order_model->get_all_for_csv( 
            $where,
            $order_by,
            $direction);
  

         
 
 
        $clean_list = []; 
        $i = 1;
        foreach ($list as $key => $value)
        {  
            $clean_list_entry               = [];
            $clean_list_entry['id']         = $value->id;
            $clean_list_entry['order']      = $value->id;   
            $clean_list_entry['date_time']  = date('F d Y h:i A', strtotime($value->order_date_time)); 
            $clean_list_entry['total_sale'] = "$" . number_format($value->total,2);  
            $clean_list_entry['referrer']   = $value->referrer; 
            $clean_list[]                   = $clean_list_entry;
        }
 
 
        $column_fields = ['ID', 'Order ID', 'Order On', 'Total', 'Referrer'];
       
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