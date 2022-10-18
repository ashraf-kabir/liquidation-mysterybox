<?php defined('BASEPATH') or exit('No direct script access allowed');
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
    protected $_model_file = 'inventory_model';
    public $_page_name = 'Sales Report';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('inventory_model');
        $this->load->model('category_model');
        $this->load->model('transactions_model');
        $this->load->model('pos_order_model');
        $this->load->model('pos_order_items_model');
        $this->load->model('pos_order_items_report_model');
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Category_wise_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? 'id';

        if ($order_by == 'name') {
            $order_by = 'product_name';
        }
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Category_wise_admin_list_paginate_view_model(
            $this->inventory_model,
            $this->pagination,
            '/admin/category_wise/0'
        );
        $this->_data['view_model']->set_heading('Sale Report');

        $this->_data['order_date'] = $this->input->get('order_date', TRUE) != NULL ? $this->input->get('order_date', TRUE) : NULL;

        $this->_data['category_id']    =     $this->input->get('category_id', TRUE) != NULL  ? $this->input->get('category_id', TRUE) : NULL;
        $this->_data['from_date']      =     $this->input->get('from_date', TRUE) != NULL  ? $this->input->get('from_date', TRUE) : NULL;
        $this->_data['to_date']        =     $this->input->get('to_date', TRUE) != NULL  ? $this->input->get('to_date', TRUE) : NULL;

        $from_date = $this->_data['from_date'];
        $to_date   = $this->_data['to_date'];

        if ($from_date > $to_date) {
            $this->session->set_flashdata('error2', 'Error! From date must be greater then to date.');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        $where = [
            'category_id' => $this->_data['category_id'],
        ];

        $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/category_wise/0');
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

        $from_date = $this->_data['from_date'];
        $to_date   = $this->_data['to_date'];

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

        return $this->render('Admin/Category_wise', $this->_data);
    }





    public function view($id)
    {
        $category_data = $this->category_model->get($id);



        $this->_data['search_sku']    =     $this->input->get('search_sku', TRUE) != NULL  ? $this->input->get('search_sku', TRUE) : NULL;
        $this->_data['search_name']   =     $this->input->get('search_name', TRUE) != NULL  ? $this->input->get('search_name', TRUE) : NULL;
        $this->_data['order_date']    =     $this->input->get('order_date', TRUE) != NULL  ? $this->input->get('order_date', TRUE) : NULL;




        $where = [
            'sku'            => $this->_data['search_sku'],
            'product_name'   => $this->_data['search_name'],
            'category_id'    => $id
        ];

        $products_list = $this->inventory_model->get_all($where);



        if (!empty($products_list)) {
            foreach ($products_list as $key_product => $product) {
                $where_sale_order = [
                    'transaction_date'  =>  $this->_data['order_date'],
                    'product_id'        =>  $product->id
                ];

                $order_items = $this->pos_order_items_report_model->get_all_pos_order($where_sale_order);

                $total_sale     =  0;
                $total_qty      =  0;
                $total_credit   =  0;
                $total_cash     =  0;

                if (!empty($order_items)) {
                    foreach ($order_items as $key_item => $item) {
                        $total_sale     +=  $item->amount;
                        $total_qty      +=  $item->quantity;
                        if ($item->payment_type == 1) {
                            $total_cash     +=  $item->amount;
                        } else if ($item->payment_type == 2) {
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




    public function to_csv()
    {

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Sales_Report.csv"');

        $this->load->model('inventory_model');
        $this->load->model('pos_order_items_report_model');


        $order_by = $this->input->get('order_by', TRUE) ?? 'id';

        if ($order_by == 'name') {
            $order_by = 'product_name';
        }




        $direction      =     $this->input->get('direction', TRUE) ?? 'ASC';
        $order_date     =     $this->input->get('order_date', TRUE) != NULL ? $this->input->get('order_date', TRUE) : NULL;
        $category_id    =     $this->input->get('category_id', TRUE) != NULL  ? $this->input->get('category_id', TRUE) : NULL;
        $from_date      =     $this->input->get('from_date', TRUE) != NULL  ? $this->input->get('from_date', TRUE) : NULL;
        $to_date        =     $this->input->get('to_date', TRUE) != NULL  ? $this->input->get('to_date', TRUE) : NULL;


        $where = [
            'category_id' => $category_id,
        ];


        $list = $this->inventory_model->get_all_for_csv($where, $order_by, $direction);

        include_once __DIR__ . '/../../view_models/Category_wise_admin_list_paginate_view_model.php';

        if (!empty($list)) {

            foreach ($list as $key => $value) {
                $total_items    =  0;
                $total_sale     =  0;
                $total_qty      =  0;
                $total_credit   =  0;
                $total_cash     =  0;

                $where_sale_order = [
                    'product_id' =>  $value->id
                ];

                $from_date = $from_date;
                $to_date   = $to_date;

                $order_items = $this->pos_order_items_report_model->get_all_pos_order($where_sale_order, null, $from_date, $to_date);

                if (!empty($order_items)) {
                    foreach ($order_items as $key_item => $item) {
                        $total_sale     +=  $item->amount;
                        $total_qty      +=  $item->quantity;
                    }
                }
                $total_items++;

                $value->total_sale    =  $total_sale;
                $value->total_qty     =  $total_qty;
                $value->total_credit  =  $total_credit;
                $value->total_cash    =  $total_cash;
                $value->total_items   =  $total_items;
            }
        }


        $where = [
            'category_id' => $category_id,
        ];


        $products_list = $this->inventory_model->get_all($where);

        $grand_total       =  0;
        $total_tax         =  0;
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

        $this->_data['grand_total']    = $grand_total;
        $this->_data['total_wout_tax'] = $total_wout_tax;
        $this->_data['total_tax']      = $total_tax;







        $clean_list = [];
        foreach ($list as $key => $value) {
            $sku = preg_replace('~\G0~', 'O', $value->sku);
            // $list[$key]->status       = $this->status_mapping()[$value->status];
            $list[$key]->status       = 1;
            $clean_list_entry              = [];
            $clean_list_entry['id']        = $value->id;
            $clean_list_entry['name']      = $value->product_name;
            $clean_list_entry['sku']       = $sku;
            $clean_list_entry['qty_sold']  = $value->total_qty;
            $clean_list_entry['amount']    = "$" . number_format($value->total_sale, 2);
            $clean_list[]                  = $clean_list_entry;
        }

        $column_fields = ['ID', 'Name', 'SKU', 'Quantity Sold', 'Amount'];

        $csv = implode(",", $column_fields) . "\n";
        // $fields = array_filter($this->get_field_column());
        foreach ($clean_list as $row) {
            $row_csv = [];
            foreach ($row as $key => $column) {
                // if (in_array($key, $fields))
                // {
                $row_csv[] = '"' . $column . '"';
                // }
            }
            $csv = $csv . implode(',', $row_csv) . "\n";
        }

        $total_wout_tax_text = " Total = $" . number_format($total_wout_tax, 2);
        $total_tax_text      = " Tax = $" . number_format($total_tax, 2);
        $grand_total_text    = " Grand Total = $" . number_format($grand_total, 2);


        $total_wout_tax_text = str_replace(",", "", $total_wout_tax_text);
        $total_tax_text = str_replace(",", "", $total_tax_text);
        $grand_total_text = str_replace(",", "", $grand_total_text);


        $row_csv = [];
        $row_csv[] = "";
        $row_csv[] = "";
        $row_csv[] = $total_wout_tax_text;
        $row_csv[] = $total_tax_text;
        $row_csv[] = $grand_total_text;

        $csv = $csv . implode(',', $row_csv) . "\n";

        echo $csv;
        exit();
    }
}
