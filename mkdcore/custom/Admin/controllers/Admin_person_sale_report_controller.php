<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * PersonSaleReport Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_person_sale_report_controller extends Admin_controller
{
    protected $_model_file = 'pos_order_items_report_model';
    public $_page_name = 'Person Sale Report';

    public function __construct()
    {
        parent::__construct();
        
        
        $this->load->model('user_model');
    }

    

    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/PersonSaleReport_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new PersonSaleReport_admin_list_paginate_view_model(
            $this->pos_order_items_report_model,
            $this->pagination,
            '/admin/person_sale_report/0');
        $this->_data['view_model']->set_heading('Person Sale Report');
        $this->_data['view_model']->set_sale_person_id(($this->input->get('sale_person_id', TRUE) != NULL) ? $this->input->get('sale_person_id', TRUE) : NULL);
         

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
            'sale_person_id' => $this->_data['view_model']->get_sale_person_id(), 
        ];


        $this->_data['all_users'] = $this->user_model->get_all_users();

        
        $this->_data['view_model']->set_total_rows($this->pos_order_items_report_model->count_for_sale_person($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/person_sale_report/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->pos_order_items_report_model->get_paginated_for_sale_person(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction,$from_date,$to_date));

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
            foreach ($this->_data['view_model']->get_list() as $key => $value) 
            {   
                $user_data = $this->user_model->get( $value->sale_person_id);  
                

                $name = "N/A";
                $total_amount = 0;
                $total_quantity = 0;
                $total_shipping_cost_value = 0;
                $total_item_tax = 0;
                if (isset($user_data->first_name)) 
                {
                    $total_data = $this->pos_order_items_report_model->get_total_for_sale_person( $value->sale_person_id);   

                    if (isset($total_data->total_amount)) 
                    {
                        $total_amount               = $total_data->total_amount;
                        $total_quantity             = $total_data->total_quantity;
                        $total_shipping_cost_value  = $total_data->total_shipping_cost_value;
                        $total_item_tax             = $total_data->total_item_tax;
                    }

                    $value->total_amount               = $total_amount;
                    $value->total_quantity             = $total_quantity;
                    $value->total_shipping_cost_value  = $total_shipping_cost_value;
                    $value->total_item_tax             = $total_item_tax;
                   
                    $value->phone  =   $user_data->phone;
                    $name = $user_data->first_name ." " .  $user_data->last_name; 
                }
                else
                {
                    $value->total_amount               = $total_amount;
                    $value->total_quantity             = $total_quantity;
                    $value->total_shipping_cost_value  = $total_shipping_cost_value;
                    $value->total_item_tax             = $total_item_tax;
                    $value->phone                      = "";
                }

                $value->sale_person_id = $name;
            }
        } 




        
        return $this->render('Admin/PersonSaleReport', $this->_data);
    }

    

    

    

    public function to_csv()
    { 

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Person_Sale_Report.csv"');

        $this->load->model('user_model');
        $this->load->model('pos_order_items_report_model');

 
         

        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $sale_person_id  = $this->input->get('sale_person_id', TRUE) != NULL ? $this->input->get('sale_person_id', TRUE) : NULL; 
        $from_date       = $this->input->get('from_date', TRUE) != NULL ? $this->input->get('from_date', TRUE) : NULL; 
        $to_date         = $this->input->get('to_date', TRUE) != NULL ? $this->input->get('to_date', TRUE) : NULL; 
          
         
        
        $where = [
            'sale_person_id' => $sale_person_id,  
        ];
        
        $list = $this->pos_order_items_report_model->get_paginated_for_sale_person_to_csv(
            $where,
            $order_by,
            $direction,$from_date ,$to_date );



        

        if ( !empty( $list ) ) 
        { 
            foreach ($list as $key => $value) 
            {   
                $user_data = $this->user_model->get( $value->sale_person_id);  
                

                $name = "N/A";
                $total_amount              = 0;
                $total_quantity            = 0;
                $total_shipping_cost_value = 0;
                $total_item_tax            = 0;
                if (isset($user_data->first_name)) 
                {
                    $total_data = $this->pos_order_items_report_model->get_total_for_sale_person( $value->sale_person_id);   

                    if (isset($total_data->total_amount)) 
                    {
                        $total_amount               = $total_data->total_amount;
                        $total_quantity             = $total_data->total_quantity;
                        $total_shipping_cost_value  = $total_data->total_shipping_cost_value;
                        $total_item_tax             = $total_data->total_item_tax;
                    }

                    $value->total_amount               = $total_amount;
                    $value->total_quantity             = $total_quantity;
                    $value->total_shipping_cost_value  = $total_shipping_cost_value;
                    $value->total_item_tax             = $total_item_tax;
                   
                    $value->phone  =   $user_data->phone;
                    $name = $user_data->first_name ." " .  $user_data->last_name; 
                }
                else
                {
                    $value->total_amount               = $total_amount;
                    $value->total_quantity             = $total_quantity;
                    $value->total_shipping_cost_value  = $total_shipping_cost_value;
                    $value->total_item_tax             = $total_item_tax;
                    $value->phone                      = "";
                }

                $value->sale_person_id = $name;
            }
        } 
  

        $clean_list = []; 
        foreach ($list as $key => $value)
        {  
            $clean_list_entry                    = [];
            $clean_list_entry['id']              = $value->id;
            $clean_list_entry['sale_person_id']  = $value->sale_person_id;
            $clean_list_entry['phone']           = $value->phone;
            $clean_list_entry['total_quantity']  = $value->total_quantity;
            $clean_list_entry['total_amount']    = number_format($value->total_amount,2); 
            $clean_list[]                        = $clean_list_entry;
        }
    
        
 
        $column_fields = ['ID' , 'Name', 'Phone', 'Quantity Sold', 'Total Amount'];
       
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