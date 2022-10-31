<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory_transfer Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_inventory_transfer_controller extends Admin_controller
{
    protected $_model_file = 'inventory_transfer_model';
    public $_page_name = 'Inventory Transfer Requests';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('store_model');
        $this->load->model('physical_location_model');
        $this->load->model('inventory_model');
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Inventory_transfer_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Inventory_transfer_admin_list_paginate_view_model(
            $this->inventory_transfer_model,
            $this->pagination,
            '/manager/inventory_transfer/0'
        );
        $this->_data['view_model']->set_heading('Inventory Transfer Requests');
        $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
        $this->_data['view_model']->set_status(($this->input->get('status', TRUE) != NULL) ? $this->input->get('status', TRUE) : NULL);

        $where = [
            'sku' => $this->_data['view_model']->get_sku(),
            'status' => $this->_data['view_model']->get_status(),


        ];

        $this->_data['view_model']->set_total_rows($this->inventory_transfer_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/manager/inventory_transfer/0');
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_list($this->inventory_transfer_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction
        ));

        $this->_data['inventory_items_list']  = $this->inventory_model->get_all(['status' => 1]);
        $this->_data['encoded_physical_locations']  =   base64_encode(json_encode($this->physical_location_model->get_all()));

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

        $stores = $this->store_model->get_all();
        $store_map = [];
        foreach ($stores as $store) {
            $store_map[$store->id] = $store->name;
        }

        $locations = $this->physical_location_model->get_all();
        $location_map = [];
        foreach ($locations as $location) {
            $location_map[$location->id] = $location->name;
        }
        $this->_data['store_map'] = $store_map;
        $this->_data['location_map'] = $location_map;


        return $this->render('Admin/Inventory_transfer', $this->_data);
    }

    public function add()
    {
        include_once __DIR__ . '/../../view_models/Inventory_transfer_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->inventory_transfer_model->set_form_validation(
            $this->form_validation,
            $this->inventory_transfer_model->get_all_validation_rule()
        );
        $this->_data['view_model'] = new Inventory_transfer_admin_add_view_model($this->inventory_transfer_model);
        $this->_data['view_model']->set_heading('Inventory Transfer Requests');


        if ($this->form_validation->run() === FALSE) {
            return $this->render('Manager/Inventory_transferAdd', $this->_data);
        }


        $result = $this->inventory_transfer_model->create([]);

        if ($result) {
            $this->success('Inventory has been added successfully.');
            $this->admin_operation_model->log_activity("Add inventory_transfer", $this->inventory_transfer_model->get($result),  $this->get_session()["user_id"]);
            return $this->redirect('/manager/inventory_transfer/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/Inventory_transferAdd', $this->_data);
    }



    public function view($id)
    {
        $model = $this->inventory_transfer_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/inventory_transfer/0');
        }


        include_once __DIR__ . '/../../view_models/Inventory_transfer_admin_view_view_model.php';
        $this->_data['view_model'] = new Inventory_transfer_admin_view_view_model($this->inventory_transfer_model);
        $this->_data['view_model']->set_heading('Inventory Transfer Requests');
        $this->_data['view_model']->set_model($model);

        $stores = $this->store_model->get_all();
        $store_map = [];
        foreach ($stores as $store) {
            $store_map[$store->id] = $store->name;
        }

        $locations = $this->physical_location_model->get_all();
        $location_map = [];
        foreach ($locations as $location) {
            $location_map[$location->id] = $location->name;
        }
        $this->_data['store_map'] = $store_map;
        $this->_data['location_map'] = $location_map;

        return $this->render('Admin/Inventory_transferView', $this->_data);
    }

    public function delete($id)
    {
        $model = $this->inventory_transfer_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/inventory_transfer/0');
        }

        $result = $this->inventory_transfer_model->real_delete($id);

        if ($result) {
            $this->success('Inventory has been deleted successfully.');
            return $this->redirect('/admin/inventory_transfer/0', 'refresh');
        }

        $this->error('Error');
        return redirect('/admin/inventory_transfer/0');
    }

    public function accept($id)
    {
        $model = $this->inventory_transfer_model->get($id);
        $sku_confirmation = $this->input->get('sku_c');
        $to_location = $this->input->get('physical_location');

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/inventory_transfer/0?order_by=id&direction=DESC');
        }

        if ($model->sku != $sku_confirmation) {
            $this->error('Error! Inventory Transfer Confirmation Failed.');
            return redirect('/admin/inventory_transfer/0?order_by=id&direction=DESC');
        }
        // Handle transfer here

        $from_store = $model->from_store;
        $from_location = $model->from_location;
        $from_quantity = $model->quantity;
        $to_store = $model->to_store;
        // if ($from_store == $to_store) {
        //     // error cant transfer to the same store
        //     $this->error('Error, Cannot transfer to the same store.');
        //     return redirect($_SERVER['HTTP_REFERER']);
        // }
        $inventory = $this->inventory_model->get_by_field('sku', $model->sku);
        $store_inventory = json_decode($inventory->store_inventory);

        // remove items;
        foreach ($store_inventory as $key => &$value) {
            if ($value->store_id != $from_store) {
                continue;
            }

            if ($from_quantity > $value->locations->{$from_location}) {
                $this->error('Error, Cannot transfer due to insufficient quantity.');
                return redirect($_SERVER['HTTP_REFERER']);
            }

            $value->locations->{$from_location} -=  $from_quantity;
            // subtract from store quantity
            $value->quantity -= $from_quantity;
        }

        // add items;
        foreach ($store_inventory as $key => &$value) {
            if ($value->store_id != $to_store) {
                continue;
            }

            $value->locations->{$to_location} = empty($value->locations->{$to_location}) ? $from_quantity : $value->locations->{$to_location} + $from_quantity;
            // increase store quantity
            $value->quantity += $from_quantity;
        }

        $this->inventory_model->edit([
            'store_inventory' => json_encode($store_inventory)
        ], $inventory->id);

        $result = $this->inventory_transfer_model->edit([
            'status' => 2 //Completed
        ], $id);

        $this->log_transfer($id, 'accept transfer');

        if ($result) {
            $this->success('Inventory Transfer Request Accepted.');
            return $this->redirect('/admin/inventory_transfer/0', 'refresh');
        }

        $this->error('Error');
        return redirect('/admin/inventory_transfer/0');
    }


    private function log_transfer($inventory_transfer_id, $action = '')
    {
        $this->load->library('helpers_service');
        $this->load->model('inventory_transfer_log_model');
        $this->load->model('store_model');

        $this->helpers_service->set_inventory_transfer_log_model($this->inventory_transfer_log_model);
        $this->helpers_service->set_inventory_transfer_model($this->inventory_transfer_model);
        $this->helpers_service->set_store_model($this->store_model);
        $this->helpers_service->log_inventory_transfer($inventory_transfer_id, $action);
    }
}
