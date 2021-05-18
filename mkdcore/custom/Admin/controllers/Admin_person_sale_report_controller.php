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
    protected $_model_file = 'pos_order_items_model';
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
            $this->pos_order_items_model,
            $this->pagination,
            '/admin/person_sale_report/0');
        $this->_data['view_model']->set_heading('Person Sale Report');
        $this->_data['view_model']->set_sale_person_id(($this->input->get('sale_person_id', TRUE) != NULL) ? $this->input->get('sale_person_id', TRUE) : NULL);
        $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
        
        $where = [
            'sale_person_id' => $this->_data['view_model']->get_sale_person_id(),
            'product_name' => $this->_data['view_model']->get_product_name(), 
        ];


        $this->_data['all_users'] = $this->user_model->get_all_users();

        
        $this->_data['view_model']->set_total_rows($this->pos_order_items_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/person_sale_report/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->pos_order_items_model->get_paginated(
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
            foreach ($this->_data['view_model']->get_list() as $key => $value) 
            {   
                $user_data = $this->user_model->get( $value->sale_person_id);  

                $name = "N/A";
                if (isset($user_data->first_name)) 
                {
                    $name = $user_data->first_name ." " .  $user_data->last_name; 
                }

                $value->sale_person_id = $name;
            }
        } 

        
        return $this->render('Admin/PersonSaleReport', $this->_data);
    }

    

    

    

    
    
    
    
    
    
    
    
}