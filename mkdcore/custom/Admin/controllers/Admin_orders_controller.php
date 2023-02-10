<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Orders Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_orders_controller extends Admin_controller
{
    protected $_model_file = 'pos_order_model';
    public $_page_name = 'Orders';

    public function __construct()
    {
        parent::__construct();
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Orders_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Orders_admin_list_paginate_view_model(
            $this->pos_order_model,
            $this->pagination,
            '/admin/orders/0'
        );
        $this->_data['view_model']->set_heading('Orders');
        $this->_data['view_model']->set_billing_name(($this->input->get('billing_name', TRUE) != NULL) ? $this->input->get('billing_name', TRUE) : NULL);
        $this->_data['view_model']->set_is_picked(($this->input->get('is_picked', TRUE) != NULL) ? $this->input->get('is_picked', TRUE) : NULL);
        $this->_data['view_model']->set_is_shipped(($this->input->get('is_shipped', TRUE) != NULL) ? $this->input->get('is_shipped', TRUE) : NULL);

        $where = [
            'billing_name' => $this->_data['view_model']->get_billing_name(),
            'is_picked' => $this->_data['view_model']->get_is_picked(),
            'is_shipped' => $this->_data['view_model']->get_is_shipped(),


        ];

        $this->_data['view_model']->set_total_rows($this->pos_order_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/orders/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->pos_order_model->get_paginated(
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

        return $this->render('Admin/Orders', $this->_data);
    }





    public function view($id)
    {
        $model = $this->pos_order_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/orders/0');
        }


        include_once __DIR__ . '/../../view_models/Orders_admin_view_view_model.php';
        $this->_data['view_model'] = new Orders_admin_view_view_model($this->pos_order_model);
        $this->_data['view_model']->set_heading('Orders');
        $this->_data['view_model']->set_model($model);
        $this->load->model('pos_order_items_model');
        $this->_data['orders_details'] = $this->pos_order_items_model->get_all(['order_id' => $id]);

        $itms = $this->pos_order_items_model->get_all(['order_id' => $id]);

        foreach ($itms as $itm) {

            $pid =  $itm->product_id;
            $this->db->select('*');
            $this->db->from('inventory');
            $this->db->where('id', $pid);
            $query = $this->db->get();
            $result = $query->result();


            $this->_data['sku'][] = $result[0];
        }

        $this->load->model('store_model');
        $this->_data['stores'] = $this->store_model->get_all();
        $this->_data['boys'] = 'gurls';



        return $this->render('Admin/OrdersView', $this->_data);
    }
}
