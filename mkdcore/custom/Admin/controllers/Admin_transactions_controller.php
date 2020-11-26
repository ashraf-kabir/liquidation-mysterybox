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
        $this->load->model('pos_user_model');
        $this->load->library('names_helper_service');
    }

    

    public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Transactions_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Transactions_admin_list_paginate_view_model(
            $this->transactions_model,
            $this->pagination,
            '/admin/transactions/0');
        $this->_data['view_model']->set_heading('Transactions');
        $this->_data['view_model']->set_transaction_date(($this->input->get('transaction_date', TRUE) != NULL) ? $this->input->get('transaction_date', TRUE) : NULL);
		$this->_data['view_model']->set_pos_user_id(($this->input->get('pos_user_id', TRUE) != NULL) ? $this->input->get('pos_user_id', TRUE) : NULL);
		$this->_data['view_model']->set_customer_id(($this->input->get('customer_id', TRUE) != NULL) ? $this->input->get('customer_id', TRUE) : NULL);
		
        $where = [
            'transaction_date'  => $this->_data['view_model']->get_transaction_date(),
			'pos_user_id'       => $this->_data['view_model']->get_pos_user_id(),
			'customer_id'       => $this->_data['view_model']->get_customer_id(), 
        ];

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
        $this->names_helper_service->set_pos_user_model($this->pos_user_model); 

        if ( !empty( $this->_data['view_model']->get_list() ) ) 
        {
            foreach ($this->_data['view_model']->get_list() as $key => &$value) 
            { 
                $value->pos_user_id = $this->names_helper_service->get_pos_user_real_name( $value->pos_user_id ); 
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
        $this->names_helper_service->set_pos_user_model($this->pos_user_model); 

       
        $model->pos_user_id = $this->names_helper_service->get_pos_user_real_name( $model->pos_user_id ); 
        $model->customer_id = $this->names_helper_service->get_customer_real_name( $model->customer_id );

        include_once __DIR__ . '/../../view_models/Transactions_admin_view_view_model.php';
		$this->_data['view_model'] = new Transactions_admin_view_view_model($this->transactions_model);
		$this->_data['view_model']->set_heading('Transactions');
        $this->_data['view_model']->set_model($model); 
         
        
		return $this->render('Admin/TransactionsView', $this->_data);
	}

    
    
    
    
    
    
    
    
}