<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * ShippingCostReport Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_shipping_cost_report_controller extends Admin_controller
{
    protected $_model_file = 'pos_order_model';
    public $_page_name = 'Shipping Cost Report';

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('pos_order_items_report_model');
        $this->load->model('pos_order_items_model');
        
    }

    

    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/ShippingCostReport_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new ShippingCostReport_admin_list_paginate_view_model(
            $this->pos_order_model,
            $this->pagination,
            '/admin/shipping_cost_report/0');
        $this->_data['view_model']->set_heading('Shipping Cost Report');
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

        $this->_data['view_model']->set_total_rows($this->pos_order_items_report_model->count_shipping_cost_report($where, $from_date, $to_date));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/shipping_cost_report/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->pos_order_items_report_model->get_paginated_shipping_cost_report(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction, $from_date, $to_date));


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
                $total_qty  = 0;
                $total_sale = 0;
                $where_2 = [ 
                    'shipping_state' =>  $value->shipping_state
                ];

                $from_date = $this->_data['from_date'];
                $to_date   = $this->_data['to_date'];

                // , $from_date , $to_date
                $order_data = $this->pos_order_model->get_all( $where_2, null); 
                if ( !empty( $order_data ) ) 
                {
                    foreach ($order_data as $key_item => $item) 
                    {
                        // , $from_date , $to_date
                        $order_details = $this->pos_order_items_model->get_all( ['order_id' => $item->id ], null);
                        
                        foreach ($order_details as $key_order_detail => $order_detail) 
                        {
                            $total_sale += $order_detail->amount;
                            $total_qty  += $order_detail->quantity;
                        }
                    }
                } 

                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty; 
                $value->total_avg     =  $total_sale / $total_qty;; 

            }
        }



        return $this->render('Admin/ShippingCostReport', $this->_data);
    }

    

    

    

    
    public function to_csv()
    { 

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Shipping_cost_report.csv"');

        $this->load->model('pos_order_items_report_model');
        $this->load->model('pos_order_model');
        $this->load->model('pos_order_items_model');

        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';


        $name          = $this->input->get('name', TRUE) != NULL ? $this->input->get('name', TRUE) : NULL; 
        $email         = $this->input->get('email', TRUE) != NULL ? $this->input->get('email', TRUE) : NULL; 
        $from_date     = $this->input->get('from_date', TRUE) != NULL ? $this->input->get('from_date', TRUE) : NULL; 
        $to_date       = $this->input->get('to_date', TRUE) != NULL ? $this->input->get('to_date', TRUE) : NULL; 


        $where = [
            'name' => $name,
            'email' => $email, 
        ];

        
        $list = $this->pos_order_items_report_model->get_csv_shipping_cost_report( 
            $where,
            $order_by,
            $direction, $from_date, $to_date);

        if ( !empty( $list ) ) 
        { 
            foreach ($list as $key => &$value) 
            {
                $total_qty  = 0;
                $total_sale = 0;
                $where_2 = [ 
                    'shipping_state' =>  $value->shipping_state
                ]; 

                // , $from_date , $to_date
                $order_data = $this->pos_order_model->get_all( $where_2, null); 
                if ( !empty( $order_data ) ) 
                {
                    foreach ($order_data as $key_item => $item) 
                    {
                        // , $from_date , $to_date
                        $order_details = $this->pos_order_items_model->get_all( ['order_id' => $item->id ], null);
                        
                        foreach ($order_details as $key_order_detail => $order_detail) 
                        {
                            $total_sale += $order_detail->amount;
                            $total_qty  += $order_detail->quantity;
                        }
                    }
                } 

                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty; 
                $value->total_avg     =  $total_sale / $total_qty;; 

            }
        }
 
 
        $clean_list = []; 
        $i = 1;
        foreach ($list as $key => $value)
        {  
            $clean_list_entry               = [];
            $clean_list_entry['id']         = $i++;
            $clean_list_entry['state']      = $value->shipping_state; 
            $clean_list_entry['total_qty']  = $value->total_qty; 
            $clean_list_entry['total_sale'] = number_format($value->total_sale,2); 
            $clean_list_entry['total_avg']  = number_format($value->total_avg,2); 
            $clean_list[]                   = $clean_list_entry;
        }
 
 
        $column_fields = ['ID', 'State', 'Quantity', 'Total', 'Average'];
       
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