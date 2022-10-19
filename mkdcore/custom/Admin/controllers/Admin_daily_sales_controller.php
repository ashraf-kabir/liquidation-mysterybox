<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Daily_sales Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_daily_sales_controller extends Admin_controller
{
    protected $_model_file = 'category_model';
    public $_page_name = 'Daily Sales';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('store_model');
        $this->load->model('inventory_model');
        $this->load->model('category_model');
        $this->load->model('transactions_model');
        $this->load->model('pos_order_model');
        $this->load->model('pos_order_items_model');
        $this->load->model('pos_order_items_report_model');
        $this->load->library('names_helper_service');
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Daily_sales_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? 'id';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        if ($order_by == 'name') {
            $order_by = 'product_name';
        }

        $this->_data['view_model'] = new Daily_sales_admin_list_paginate_view_model(
            $this->inventory_model,
            $this->pagination,
            '/admin/daily_sales/0'
        );
        $this->_data['view_model']->set_heading('Daily Sales');
        $this->_data['view_model']->set_name(($this->input->get('name', TRUE) != NULL) ? $this->input->get('name', TRUE) : NULL);

        $this->_data['category_id']    =     $this->input->get('category_id', TRUE) != NULL  ? $this->input->get('category_id', TRUE) : NULL;
        $this->_data['from_date']      =     $this->input->get('from_date', TRUE) != NULL  ? $this->input->get('from_date', TRUE) : NULL;
        $this->_data['to_date']        =     $this->input->get('to_date', TRUE) != NULL  ? $this->input->get('to_date', TRUE) : NULL;

        $from_date = $this->_data['from_date'];
        $to_date   = $this->_data['to_date'];

        $where = [
            'category_id' => $this->_data['category_id'],
        ];

        $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/daily_sales/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->inventory_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction
        ));

        if ($format == 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view') {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        $from_date = date('Y-m-d');
        $to_date   = date('Y-m-d');
        $this->names_helper_service->set_category_model($this->category_model);

        if (!empty($this->_data['view_model']->get_list())) {

            foreach ($this->_data['view_model']->get_list() as $key => &$value) {
                $total_items    =  0;
                $total_sale     =  0;
                $total_qty      =  0;
                $total_credit   =  0;
                $total_cash     =  0;

                $where_sale_order = [
                    'product_id' =>  $value->id
                ];



                $order_items = $this->pos_order_items_report_model->get_all_pos_order($where_sale_order, null, $from_date, $to_date);

                if (!empty($order_items)) {
                    foreach ($order_items as $key_item => $item) {
                        $total_sale     +=  $item->amount;
                        $total_qty      +=  $item->quantity;
                        // if( $item->payment_type == 1)
                        // {
                        //     $total_cash     +=  $item->amount;
                        // }else if($item->payment_type == 2)
                        // {
                        //     $total_credit   +=  $item->amount;
                        // } 
                    }
                }
                $total_items++;



                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty;
                $value->total_credit  =  $total_credit;
                $value->total_cash    =  $total_cash;
                $value->total_items   =  $total_items;
                $value->category_name =  $this->names_helper_service->get_category_real_name($value->category_id);
            }
        }



        $this->_data['categories'] = $this->category_model->get_all();

        $where = [
            'category_id' => $this->_data['category_id'],
        ];
        $products_list = $this->inventory_model->get_all($where);




        $total_tax         =  0;
        $grand_total       =  0;
        $total_wout_tax    =  0;
        if (!empty($products_list)) {
            foreach ($products_list as $key_product => $product) {
                $where_sale_order = [
                    'product_id' =>  $product->id
                ];
                $order_items = $this->pos_order_items_report_model->get_all_pos_order($where_sale_order, null, $from_date, $to_date);

                if (!empty($order_items)) {
                    foreach ($order_items as $key_item => $item) {
                        $total_tax          += $item->transaction_tax;
                        $total_wout_tax     +=  $item->amount;
                    }
                }
            }
        }



        $grand_total = $total_wout_tax + $total_tax;

        $this->_data['total_refunded'] = $this->pos_order_items_report_model->get_refunded_transactions_total($from_date, $to_date) ?? 0;
        $this->_data['total_refunded_tax'] = $this->pos_order_items_report_model->get_refunded_transactions_tax($from_date, $to_date) ?? 0;

        $this->_data['sales_grand_total']    = $grand_total;
        $this->_data['total_wout_tax'] = $total_wout_tax;
        $this->_data['total_tax']      = $total_tax;

        return $this->render('Admin/Daily_sales', $this->_data);
    }



    public function view($id)
    {
        $model = $this->pos_order_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/orders/0');
        }

        include_once __DIR__ . '/../../view_models/Daily_sales_admin_view_view_model.php';
        $this->_data['view_model'] = new Daily_sales_admin_view_view_model($this->pos_order_model);
        $this->_data['view_model']->set_heading('Inventory');
        $this->_data['view_model']->set_model($model);
        $this->load->model('pos_order_items_model');
        $from_date = date('Y-m-d');
        $to_date   = date('Y-m-d');
        $where = [
            "product_id" => $id,
            "a.created_at" => $from_date,
        ];
        $this->_data['orders_details'] = $this->pos_order_items_model->_join("pos_order", "order_id", $where, ['created_at', 'updated_at']);

        $this->load->model('store_model');
        $this->_data['inventory_details'] = $this->inventory_model->get($id);
        $this->_data['status_mapping'] = $this->inventory_model->status_mapping();
        $this->_data['stores'] = $this->store_model->get_all();



        return $this->render('Admin/Daily_salesView', $this->_data);
    }
}
