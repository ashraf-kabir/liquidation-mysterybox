<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Transactions Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_transactions_controller extends Admin_controller
{
    protected $_model_file = 'transactions_model';
    public $_page_name = 'Transactions';

    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('customer_model');  
        $this->load->library('names_helper_service');
    }

    

    public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Transactions_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? 'id';
        $direction = $this->input->get('direction', TRUE) ?? 'DESC';
        $to_date = $this->input->get('to_date', TRUE) ?? '';
        $from_date = $this->input->get('from_date', TRUE) ?? '';
        $transaction_type = $this->input->get('transaction_type', TRUE) ?? '';

        $this->_data['view_model'] = new Transactions_admin_list_paginate_view_model(
            $this->transactions_model,
            $this->pagination,
            '/admin/transactions/0');
        $this->_data['view_model']->set_heading('Transactions');
        $this->_data['view_model']->set_transaction_date(($this->input->get('transaction_date', TRUE) != NULL) ? $this->input->get('transaction_date', TRUE) : NULL);
		  
		
        $where = [];
        if (!empty($transaction_type)) {
            $where['transaction_type'] = $transaction_type;
        }
        if (!empty($from_date)) {
            $where[] = "created_at >= '{$from_date}'";
        }

        if (!empty($to_date)) {
            $where[] = "created_at <= '{$to_date}'";
        }

        $this->_data['view_model']->set_total_rows($this->transactions_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/transactions/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->transactions_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));
        
        $this->_data['from_date'] = $from_date;            
        $this->_data['to_date'] = $to_date;            
        $this->_data['transaction_type'] = $transaction_type;            

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


        $this->names_helper_service->set_customer_model($this->customer_model); 
        

        if ( !empty( $this->_data['view_model']->get_list() ) ) 
        {
            foreach ($this->_data['view_model']->get_list() as $key => &$value) 
            {  
                $value->customer_id = $this->names_helper_service->get_customer_real_name( $value->customer_id );   
            }
        }

        return $this->render('Admin/Transactions', $this->_data);
	}

    

    

    public function view($id)
	{
        $model = $this->transactions_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/transactions/0');
		}


        $this->names_helper_service->set_customer_model($this->customer_model);  

         
        $model->customer_id = $this->names_helper_service->get_customer_real_name( $model->customer_id );

        include_once __DIR__ . '/../../view_models/Transactions_admin_view_view_model.php';
		$this->_data['view_model'] = new Transactions_admin_view_view_model($this->transactions_model);
		$this->_data['view_model']->set_heading('Transactions');
        $this->_data['view_model']->set_model($model); 
         
        
		return $this->render('Admin/TransactionsView', $this->_data);
	}

    
    public function to_csv()
    { 

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Transaction_report.csv"');

        $this->load->model('transactions_model');
        $this->load->model('customer_model'); 

        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';


        $transaction_date          = $this->input->get('transaction_date', TRUE) != NULL ? $this->input->get('transaction_date', TRUE) : NULL; 

        $where = [
            'transaction_date'  => $transaction_date,  
        ];

        $list = $this->transactions_model->get_all_for_csv( 
            $where,
            $order_by,
            $direction);
         
        $this->names_helper_service->set_customer_model($this->customer_model); 
        

        if ( !empty( $list ) ) 
        {
            foreach ($list as $key => $value) 
            {  
                $value->customer_id = $this->names_helper_service->get_customer_real_name( $value->customer_id );   
            }
        }

 

 
        $clean_list = [];   
        foreach ($list as $key => $value)
        {   

            $clean_list_entry                      = [];
            $clean_list_entry['id']                = $value->id;
            $clean_list_entry['pos_order_id']      = $value->pos_order_id; 
            $clean_list_entry['transaction_date']  = date('F d Y', strtotime($value->transaction_date)); 
            $clean_list_entry['customer_id']       = $value->customer_id;
            $clean_list_entry['payment_type']      = $this->transactions_model->payment_type_mapping()[$value->payment_type]; 
            $clean_list_entry['tax']               = "$" . number_format($value->tax,2); 
            $clean_list_entry['discount']          = "$" . number_format($value->discount,2); 
            $clean_list_entry['subtotal']          = "$" . number_format($value->subtotal,2); 
            $clean_list_entry['total']             = "$" . number_format($value->total,2); 
            $clean_list[]                          = $clean_list_entry;
        }
 
 
        $column_fields = ['ID', 'Order ID', 'Transaction On', 'Customer', 'Type', 'Tax', 'Discount', 'Sub Total', 'Total'];
       
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