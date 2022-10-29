<?php defined('BASEPATH') or exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Product Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_product_controller extends Admin_controller
{
    protected $_model_file = 'inventory_model';
    public $_page_name = 'Products';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('inventory_gallery_list_model');
        $this->load->model('category_model');
        $this->load->model('store_model');
        $this->load->model('physical_location_model');
        $this->load->model('customer_model');
        $this->load->model('user_model');
        $this->load->model('inventory_transfer_model');
        $this->load->library('names_helper_service');
        $this->load->library('barcode_service');
        $this->load->database();
    }



    public function index($page)
    {
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Product_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Product_admin_list_paginate_view_model(
            $this->inventory_model,
            $this->pagination,
            '/admin/product/0'
        );
        $this->_data['view_model']->set_heading('Products');
        $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
        $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
        $this->_data['view_model']->set_category_id(($this->input->get('category_id', TRUE) != NULL) ? $this->input->get('category_id', TRUE) : NULL);

        $where = [
            'is_product' => 1,
            'sku' => $this->_data['view_model']->get_sku(),
            'category_id' => $this->_data['view_model']->get_category_id(),
            'product_name' => $this->_data['view_model']->get_product_name(),
        ];

        $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/product/0');
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

        if (!empty($this->_data['view_model']->get_list())) {
            $this->names_helper_service->set_category_model($this->category_model);
            foreach ($this->_data['view_model']->get_list() as $key => &$value) {
                $value->category_id  = $this->names_helper_service->get_category_real_name($value->category_id);
            }
        }

        return $this->render('Admin/Product', $this->_data);
    }

    public function add()
    {
        include_once __DIR__ . '/../../view_models/Product_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->inventory_model->set_form_validation(
            $this->form_validation,
            $this->inventory_model->get_all_validation_rule()
        );
        $this->_data['categories'] = $this->category_model->get_all();
        $this->_data['parent_categories']   =  $this->_get_grouped_categories();
        $this->_data['encoded_parent_categories']   =  base64_encode(json_encode($this->_get_grouped_categories()));
        $this->_data['view_model'] = new Product_admin_add_view_model($this->inventory_model);
        $this->_data['view_model']->set_heading('Products');


        if ($this->form_validation->run() === FALSE) {
            return $this->render('Admin/ProductAdd', $this->_data);
        }

        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';
        // exit;
        $product_name = $this->input->post('product_name', TRUE);
        $is_product = $this->input->post('is_product', TRUE);
        $sale_person_id = $this->input->post('sale_person_id', TRUE);
        $sku = $this->input->post('sku', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $feature_image = $this->input->post('feature_image', TRUE);
        $feature_image_id = $this->input->post('feature_image_id', TRUE);
        $inventory_note = $this->input->post('inventory_note', TRUE);
        $locations = $this->input->post('locations', TRUE);
        $status = $this->input->post('status', TRUE);
        $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);

        $result = $this->inventory_model->create([
            'product_name' => $product_name,
            'sale_person_id' => $sale_person_id,
            'is_product' => $is_product,
            'sku' => $sku,
            'category_id' => $category_id,
            'locations' => $locations,
            'feature_image' => "/uploads/placeholder.jpg",
            'feature_image_id' => $feature_image_id,
            'inventory_note' => $inventory_note,
            'admin_inventory_note' => $admin_inventory_note,
            'status' => $status
        ]);

        if ($result) {
            $this->success('Product has been added successfully.');

            return $this->redirect('/admin/product/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/ProductAdd', $this->_data);
    }

    public function edit($id)
    {
        $model = $this->inventory_model->get($id);
        $session = $this->get_session();
        if (!$model) {
            $this->error('Error');
            return redirect('/admin/product/0');
        }

        include_once __DIR__ . '/../../view_models/Product_admin_edit_view_model.php';
        $this->form_validation = $this->inventory_model->set_form_validation(
            $this->form_validation,
            $this->inventory_model->get_all_edit_validation_rule()
        );
        $this->_data['categories'] = $this->category_model->get_all();
        $this->_data['parent_categories']   =  $this->_get_grouped_categories();
        $this->_data['encoded_parent_categories']   =  base64_encode(json_encode($this->_get_grouped_categories()));
        $this->_data['view_model'] = new Product_admin_edit_view_model($this->inventory_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Products');


        if ($this->form_validation->run() === FALSE) {
            return $this->render('Admin/ProductEdit', $this->_data);
        }

        $product_name = $this->input->post('product_name', TRUE);
        $is_product = $this->input->post('is_product', TRUE);
        $sale_person_id = $this->input->post('sale_person_id', TRUE);
        $sku = $this->input->post('sku', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $feature_image = $this->input->post('feature_image', TRUE);
        $feature_image_id = $this->input->post('feature_image_id', TRUE);
        $inventory_note = $this->input->post('inventory_note', TRUE);
        $locations = $this->input->post('locations', TRUE);
        $status = $this->input->post('status', TRUE);
        $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);

        $result = $this->inventory_model->edit([
            'product_name' => $product_name,
            'product_name' => $product_name,
            'sale_person_id' => $sale_person_id,
            'is_product' => $is_product,
            'sku' => $sku,
            'category_id' => $category_id,
            'locations' => $locations,
            'feature_image' => "/uploads/placeholder.jpg",
            'feature_image_id' => $feature_image_id,
            'inventory_note' => $inventory_note,
            'admin_inventory_note' => $admin_inventory_note,

        ], $id);

        if ($result) {
            $this->success('Product has been updated successfully.');

            return $this->redirect('/admin/product/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/ProductEdit', $this->_data);
    }

    private function _get_grouped_categories()
    {
        // get parent categoris
        $table = 'category';
        $parent_where = '(parent_category_id IS NULL OR parent_category_id = 0) AND status = 1';
        $this->db->where($parent_where);
        $parent_categories = $this->db->get($table)->result();

        // Get child categories
        $child_where = '(parent_category_id IS NOT NULL OR parent_category_id != 0) AND status = 1';
        $this->db->where($child_where);
        $child_categories = $this->db->get($table)->result();

        $categories = [];

        foreach ($parent_categories as $key => $parent_category) {
            // Add parent
            $categories[] = $parent_category;

            // Loop through child categories and append if its the child
            foreach ($child_categories as $key => $child_category) {
                if ($child_category->parent_category_id == $parent_category->id) {
                    $categories[] = $child_category;
                }
            }
        }

        return $categories;
    }

    public function view($id)
    {
        $model = $this->inventory_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/product/0');
        }


        include_once __DIR__ . '/../../view_models/Product_admin_view_view_model.php';
        $this->_data['view_model'] = new Product_admin_view_view_model($this->inventory_model);
        $this->_data['view_model']->set_heading('Products');
        $this->_data['view_model']->set_model($model);
        $this->names_helper_service->set_category_model($this->category_model);
        $this->_data['helper_service'] = $this->names_helper_service;

        return $this->render('Admin/ProductView', $this->_data);
    }

    public function delete($id)
    {
        $model = $this->inventory_model->get($id);

        if (!$model) {
            $this->error('Error');
            return redirect('/admin/product/0');
        }

        $result = $this->inventory_model->real_delete($id);

        if ($result) {
            $this->success('Product has been deleted successfully.');
            return $this->redirect('/admin/product/0', 'refresh');
        }

        $this->error('Error');
        return redirect('/admin/product/0');
    }
}
