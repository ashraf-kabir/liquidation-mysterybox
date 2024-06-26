<?php defined('BASEPATH') || exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory_Location Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_inventory_location_controller extends Admin_controller
{
  /**
   * @var string
   */
  protected $_model_file = 'physical_location_model';
  /**
   * @var string
   */
  public $_page_name = 'Inventory Physical Location';

  public function __construct()
  {
    parent::__construct();

    $this->load->library('barcode_service');

  }

  /**
   * @param $page
   * @return mixed
   */
  public function index($page)
  {
    $this->load->library('pagination');
    include_once __DIR__ . '/../../view_models/Inventory_location_admin_list_paginate_view_model.php';
    $session   = $this->get_session();
    $format    = $this->input->get('format', TRUE) ?? 'view';
    $order_by  = $this->input->get('order_by', TRUE) ?? '';
    $direction = $this->input->get('direction', TRUE) ?? 'ASC';

    $this->load->model('store_model');

    $this->_data['view_model'] = new Inventory_location_admin_list_paginate_view_model(
      $this->physical_location_model,
      $this->pagination,
      '/admin/inventory_location/0');
    $this->_data['view_model']->set_heading('Inventory Physical Location');
    $this->_data['view_model']->set_name(($this->input->get('name', TRUE) != NULL) ? $this->input->get('name', TRUE) : NULL);

    $where = [
      'name' => $this->_data['view_model']->get_name()
    ];

    $this->_data['view_model']->set_total_rows($this->physical_location_model->count($where));

    $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
    $this->_data['view_model']->set_per_page(25);
    $this->_data['view_model']->set_order_by($order_by);
    $this->_data['view_model']->set_sort($direction);
    $this->_data['view_model']->set_sort_base_url('/admin/inventory_location/0');
    $this->_data['view_model']->set_page($page);
    $this->_data['view_model']->set_list($this->physical_location_model->get_paginated(
      $this->_data['view_model']->get_page(),
      $this->_data['view_model']->get_per_page(),
      $where,
      $order_by,
      $direction));

    foreach ($this->_data['view_model']->get_list() as &$location) {
      $location->store = $this->store_model->get($location->store_id);
    }

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

    return $this->render('Admin/Inventory_location', $this->_data);
  }

  /**
   * @return mixed
   */
  public function add()
  {
    include_once __DIR__ . '/../../view_models/Inventory_Location_admin_add_view_model.php';
    $session               = $this->get_session();
    $this->form_validation = $this->physical_location_model->set_form_validation(
      $this->form_validation, $this->physical_location_model->get_all_validation_rule());
    $this->_data['view_model'] = new Inventory_Location_admin_add_view_model($this->physical_location_model);
    $this->_data['view_model']->set_heading('Inventory Physical Location');

    $this->load->model('store_model');

    $this->_data['stores'] = $this->store_model->get_all();

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Admin/Inventory_LocationAdd', $this->_data);
    }

    $name     = $this->input->post('name', TRUE);
    $store_id = $this->input->post('store_id', TRUE);

    $increment_id = $this->physical_location_model->get_auto_increment_id();
    $code         = sprintf("%05d", $increment_id);
    $code         = $store_id . "-" . $code;

    $barcode_image_name = $this->barcode_service->generate_png_barcode($code, "location");
    /**
     *  Upload Image to S3
     *
     */
    $barcode_image = $this->upload_image_with_s3($barcode_image_name);

    $result = $this->physical_location_model->create([
      'name'          => $name,
      'store_id'      => $store_id,
      'barcode_image' => $barcode_image

    ]);

    if ($result) {
      $physical_location_id = $result;
      $this->success('Inventory Location has been added successfully.');

      return $this->redirect('/admin/inventory_location/view/' . $physical_location_id . '?print=1', 'refresh');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Admin/Inventory_locationAdd', $this->_data);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function edit($id)
  {
    $model   = $this->physical_location_model->get($id);
    $session = $this->get_session();
    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory_location/0');
    }

    include_once __DIR__ . '/../../view_models/Inventory_Location_admin_edit_view_model.php';
    $this->form_validation = $this->physical_location_model->set_form_validation(
      $this->form_validation, $this->physical_location_model->get_all_edit_validation_rule());
    $this->_data['view_model'] = new Inventory_location_admin_edit_view_model($this->physical_location_model);
    $this->_data['view_model']->set_model($model);
    $this->_data['view_model']->set_heading('Inventory Physical Location');

    $this->load->model('store_model');

    $this->_data['stores'] = $this->store_model->get_all();

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Admin/Inventory_locationEdit', $this->_data);
    }

    $name     = $this->input->post('name', TRUE);
    $store_id = $this->input->post('store_id', TRUE);

    $result = $this->physical_location_model->edit([
      'name'     => $name,
      'store_id' => $store_id

    ], $id);

    if ($result) {
      $this->success('Inventory Location has been updated successfully.');

      return $this->redirect('/admin/inventory_location/0', 'refresh');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Admin/Inventory_locationEdit', $this->_data);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function view($id)
  {
    $model = $this->physical_location_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory_location/0');
    }

    include_once __DIR__ . '/../../view_models/Inventory_Location_admin_view_view_model.php';
    $this->_data['view_model'] = new Inventory_Location_admin_view_view_model($this->physical_location_model);
    $this->_data['view_model']->set_heading('Inventory Physical Location');
    $this->_data['view_model']->set_model($model);

    $this->load->model('store_model');
    $this->_data['store'] = $this->store_model->get($model->store_id);

    return $this->render('Admin/Inventory_locationView', $this->_data);
  }

  /**
   * @param $id
   * @return mixed
   */
  public function delete($id)
  {
    $model = $this->physical_location_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory_location/0');
    }

    $result = $this->physical_location_model->real_delete($id);

    if ($result) {
      $this->success('Inventory Location has been deleted successfully.');
      return $this->redirect('/admin/inventory_location/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory_location/0');
  }

}
