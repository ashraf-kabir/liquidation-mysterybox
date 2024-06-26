<?php defined('BASEPATH') || exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_inventory_controller extends Admin_controller
{
  protected $_model_file = 'inventory_model';
  public $_page_name     = 'Inventory';

  public function __construct()
  {
    parent::__construct();

    $this->load->model('inventory_gallery_list_model');
    $this->load->model('category_model');
    $this->load->model('store_model');
    $this->load->model('physical_location_model');
    $this->load->model('customer_model');
    $this->load->model('inventory_log_model');
    $this->load->model('user_model');
    $this->load->model('inventory_transfer_model');
    $this->load->library('names_helper_service');
    $this->load->library('barcode_service');
    $this->load->database();
  }

  public function index($page)
  {
    $this->load->library('pagination');
    include_once __DIR__ . '/../../view_models/Inventory_admin_list_paginate_view_model.php';
    $session                   = $this->get_session();
    $format                    = $this->input->get('format', TRUE) ?? 'view';
    $order_by                  = $this->input->get('order_by', TRUE) ?? '';
    $direction                 = $this->input->get('direction', TRUE) ?? 'ASC';
    $pending                   = $this->input->get('pending', TRUE) ?? '';
    $this->_data['pending']    = $pending;
    $this->_data['view_model'] = new Inventory_admin_list_paginate_view_model(
      $this->inventory_model,
      $this->pagination,
      '/admin/inventory/0'
    );
    $this->_data['view_model']->set_heading('Inventory');
    $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
    $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
    $this->_data['view_model']->set_category_id(($this->input->get('category_id', TRUE) != NULL) ? $this->input->get('category_id', TRUE) : NULL);

    $where = [
      'product_name' => $this->_data['view_model']->get_product_name(),
      'sku'          => $this->_data['view_model']->get_sku(),
      'category_id'  => $this->_data['view_model']->get_category_id(),
      'is_product'   => 0
    ];

    if ($pending) {
      $where['status'] = 2;
    }

    $this->_data['hash'] = bin2hex(json_encode([
      'where'     => $where,
      'page'      => $page,
      'order_by'  => $order_by,
      'direction' => $direction
    ]));

    $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

    $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
    $this->_data['view_model']->set_per_page(25);
    $this->_data['view_model']->set_order_by($order_by);
    $this->_data['view_model']->set_sort($direction);
    $this->_data['view_model']->set_sort_base_url('/admin/inventory/0');
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
      $this->names_helper_service->set_physical_location_model($this->physical_location_model);
      $this->names_helper_service->set_customer_model($this->customer_model);
      $this->names_helper_service->set_category_model($this->category_model);
      $this->names_helper_service->set_store_model($this->store_model);
      foreach ($this->_data['view_model']->get_list() as $key => &$value) {
        $value->category_id       = $this->names_helper_service->get_category_real_name($value->category_id);
        $value->physical_location = $this->names_helper_service->get_physical_location_real_name($value->physical_location);
        $value->store_location_id = $this->names_helper_service->get_store_name($value->store_location_id);
      }
    }

    $this->_data['categories'] = $this->category_model->get_all();
    $this->_data['products']   = $this->inventory_model->get_by_fields(['is_product' => 1]);

    return $this->render('Admin/Inventory', $this->_data);
  }

  public function approveall($page)
  {
    $this->load->library('pagination');
    include_once __DIR__ . '/../../view_models/Inventory_admin_list_paginate_view_model.php';
    $session   = $this->get_session();
    $where     = [];
    $page      = 0;
    $order_by  = 'id';
    $direction = 'DESC';

    $hash = $this->input->get('hash', TRUE) ?? '';
    if (strlen($hash) > 0) {
      $unbase64         = hex2bin($hash);
      $deserialize_list = json_decode($unbase64, TRUE);
      $where            = $deserialize_list['where'];
      $page             = $deserialize_list['page'];
      $order_by         = $deserialize_list['id'];
      $direction        = $deserialize_list['direction'];
    }

    $this->_data['view_model'] = new Inventory_admin_list_paginate_view_model(
      $this->inventory_model,
      $this->pagination,
      '/admin/inventory/0'
    );

    $this->_data['view_model']->set_heading('Inventory');
    $this->_data['view_model']->set_product_name(($this->input->get('product_name', TRUE) != NULL) ? $this->input->get('product_name', TRUE) : NULL);
    $this->_data['view_model']->set_sku(($this->input->get('sku', TRUE) != NULL) ? $this->input->get('sku', TRUE) : NULL);
    $this->_data['view_model']->set_category_id(($this->input->get('category_id', TRUE) != NULL) ? $this->input->get('category_id', TRUE) : NULL);

    $this->_data['view_model']->set_total_rows($this->inventory_model->count($where));

    $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
    $this->_data['view_model']->set_per_page(25);
    $this->_data['view_model']->set_order_by($order_by);
    $this->_data['view_model']->set_sort($direction);
    $this->_data['view_model']->set_sort_base_url('/admin/inventory/0');
    $this->_data['view_model']->set_page($page);
    $this->_data['view_model']->set_list($this->inventory_model->get_paginated(
      $this->_data['view_model']->get_page(),
      $this->_data['view_model']->get_per_page(),
      $where,
      $order_by,
      $direction
    ));

    foreach ($this->_data['view_model']->get_list() as $key => $value) {
      $this->inventory_model->edit([
        'status' => 1
      ], $value->id);
    }

    $this->success('Action completed successfully.');
    return $this->redirect('/admin/inventory/0', 'refresh');
  }

  public function add()
  {
    include_once __DIR__ . '/../../view_models/Inventory_admin_add_view_model.php';
    $session               = $this->get_session();
    $this->form_validation = $this->inventory_model->set_form_validation(
      $this->form_validation,
      $this->inventory_model->get_all_validation_rule()
    );
    $this->_data['view_model'] = new Inventory_admin_add_view_model($this->inventory_model);
    $this->_data['view_model']->set_heading('Inventory');

    $this->_data['parent_categories']         = $this->_get_grouped_categories();
    $this->_data['encoded_parent_categories'] = base64_encode(json_encode($this->_get_grouped_categories()));
    $stores                                   = $this->store_model->get_all();
    // $stores = $this->append_locations_to_store($stores);
    $this->_data['stores']                     = $stores;
    $physical_locations                        = $this->physical_location_model->get_all();
    $this->_data['physical_locations']         = $physical_locations;
    $this->_data['encoded_physical_locations'] = base64_encode(json_encode($this->_data['physical_locations']));
    $this->_data['sale_persons']               = $this->user_model->get_all_users();
    $this->_data['products']                   = $this->inventory_model->get_all(['is_product = 1']);

    if ($this->input->post('can_ship') == 1) {
      $this->form_validation->set_rules('weight', 'Weight', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('length', 'Length', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('height', 'Height', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('width', 'Width', 'required|greater_than_equal_to[1]');
    }

    $this->form_validation->set_rules('locations[]', 'Store', 'callback_validate_store_inventory');

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Admin/InventoryAdd', $this->_data);
    }

    // echo '<pre>';
    // print_r($_POST); die;

    $increment_id = $this->inventory_model->get_auto_increment_id();
    $sku          = sprintf("%05d", $increment_id);

    $sale_person_id      = $this->input->post('sale_person_id', TRUE);
    $product_name        = $this->input->post('product_name', TRUE);
    $category_id         = $this->input->post('category_id', TRUE);
    $manifest_id         = $this->input->post('manifest_id', TRUE);
    $parent_inventory_id = $this->input->post('parent_inventory_id', TRUE);
    // $physical_location = $this->input->post('physical_location', TRUE) ?? NULL;
    // $location_description = $this->input->post('location_description', TRUE);
    // $weight = $this->input->post('weight', TRUE);
    // $length = $this->input->post('length', TRUE);
    // $height = $this->input->post('height', TRUE);
    // $width = $this->input->post('width', TRUE);
    // $feature_image = $this->input->post('feature_image', TRUE);
    // $feature_image_id = $this->input->post('feature_image_id', TRUE);
    // $selling_price = $this->input->post('selling_price', TRUE);
    $quantity = $this->input->post('quantity', TRUE);
    // $inventory_note = $this->input->post('inventory_note', TRUE);
    // $cost_price = $this->input->post('cost_price', TRUE);
    // $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);

    $status = $this->input->post('status', TRUE);
    // $store_location_id = $this->input->post('store_location_id', TRUE);
    $location_stores = $this->input->post('stores', TRUE);
    $locations       = $this->input->post('locations', TRUE);

    // $can_ship = $this->input->post('can_ship', TRUE) ?? 2;
    // $can_ship_approval = $this->input->post('can_ship_approval', TRUE) ?? 2;
    // $free_ship = $this->input->post('free_ship', TRUE);
    $product_type = $this->input->post('product_type', TRUE);
    // $pin_item_top = $this->input->post('pin_item_top', TRUE);
    // $video_url = json_encode($this->input->post('video_url', TRUE));
    // $youtube_thumbnail_1 = json_encode($this->input->post('youtube_thumbnail_1', TRUE));

    //$parent_inventory_id

    $product_data = $this->inventory_model->get($parent_inventory_id);

    // echo '<pre>';
    // var_dump($locations);
    // echo '</pre>';
    // echo '<pre>';
    // var_dump($location_stores);
    // echo '</pre>';
    // echo '<pre>';
    // var_dump($quantity);
    // echo '</pre>';
    // exit;
    //SKU for category
    $category_data = $this->category_model->get($category_id);

    if (isset($category_data->sku_prefix) && !empty($category_data->sku_prefix)) {
      $sku = $category_data->sku_prefix . "" . $sku;
    }

    if ($product_type == 2) {
      $sku = '';
    }

    $store_inventory = [];
    $total_quantity  = 0;
    $unique_stores   = array_unique($location_stores);
    foreach ($unique_stores as $key => $store_id) {
      $store_location_data = [];
      foreach ($locations as $key2 => $location_id) {
        if ($store_id == $location_stores[$key2]) {
          $store_location_data[$location_id] = $quantity[$key2];
        }
      }

      $store_inventory_item['store_id'] = $store_id;
      $store_locations                  = $this->input->post("store_{$store_id}_location");

      $store_quantity = array_reduce($store_location_data, function ($sum, $location_quantity) {
        return $sum + $location_quantity;
      }, 0);

      $store_inventory_item['quantity']  = $store_quantity;
      $store_inventory_item['locations'] = $store_location_data; //id as key

      array_push($store_inventory, $store_inventory_item);
      $total_quantity += $store_quantity;
    }

    $store_inventory = json_encode($store_inventory);

    $sku_ids   = '';
    $sku_count = $product_data->last_sku;
    $sku_count = intval($sku_count);
    for ($i = 0; $i < count($quantity); $i++) {

      for ($j = 1; $j <= $quantity[$i]; $j++) {

        $sku_count++;
        $sku = $product_data->sku . "" . sprintf("%05d", $sku_count);

        $barcode_image_name = $this->barcode_service->generate_png_barcode($sku, "inventory");
        /**
         *  Upload Image to S3
         *
         */
        $barcode_image = $this->upload_image_with_s3($barcode_image_name);
        $result        = $this->inventory_model->create([
          'sale_person_id'       => $sale_person_id,
          'product_name'         => $product_name,
          'sku'                  => $sku,
          'barcode_image'        => $barcode_image,
          'parent_inventory_id'  => $parent_inventory_id,
          'category_id'          => $category_id,
          'manifest_id'          => $manifest_id,
          'physical_location'    => $locations[$i],
          'location_description' => '',
          'store_location_id'    => $location_stores[$i],
          'weight'               => $product_data->weight,
          'length'               => $product_data->length,
          'height'               => $product_data->height,
          'width'                => $product_data->width,
          'available_in_shelf'   => 2,
          'feature_image'        => $product_data->feature_image,
          'selling_price'        => $product_data->selling_price,
          'quantity'             => 1,
          'cost_price'           => $product_data->cost_price,
          'inventory_note'       => $product_data->admin_inventory_note,
          'status'               => $status,
          'can_ship'             => $product_data->can_ship,
          'can_ship_approval'    => $product_data->can_ship_approval,
          'free_ship'            => $product_data->free_ship,
          'product_type'         => $product_type,
          'pin_item_top'         => $product_data->pin_item_top,
          'video_url'            => $product_data->video_url,
          'youtube_thumbnail_1'  => $product_data->youtube_thumbnail_1,
          'store_inventory'      => $store_inventory
        ]);

        $sku_ids .= "$result-";
        $this->log_inventory($result, 'added inventory');
      }
    }

    // echo '<pre>';
    // var_dump($sku_ids);
    // echo '</pre>';
    // exit;

    $product_quantity = intval($product_data->quantity);
    if (empty($product_data->store_inventory)) {
      $store_inventory = $store_inventory;
    } else {
      // update the store_inventory here
      $store_data = json_decode($product_data->store_inventory);
      #$store_data_array = (array) $store_data;

      // Add new item to store_inventory
      foreach ($unique_stores as $key => $store_id) {
        $store_location_data = [];
        foreach ($locations as $key2 => $location_id) {
          if ($store_id == $location_stores[$key2]) {
            // check if store already exists
            $result = $this->check_existing_store($store_data, $store_id);

            $store_locations = $store_data[$result]->locations;
            if (is_int($result) != false) {

              $store_quantity                = (int) $store_data[$result]->quantity + (int) $quantity[$key2];
              $store_data[$result]->quantity = (int) $store_quantity;

              $location_result = $this->check_existing_location($store_data, $store_id, $location_id);

              if ($location_result != false) {

                // echo $location_result;
                // echo '<pre>';
                // var_dump($store_locations->{$location_result});
                // echo '</pre>';
                // exit;
                $store_locations->{$location_result} += $quantity[$key2];
                continue (1);
              } else {

                $store_data[$result]->locations->{$location_id} = $quantity[$key2];
                continue (1);
              }
              continue (1);
            } else {
              $store_location_data[$location_id] = $quantity[$key2];
            }
          }
        }

        $store_inventory_item['store_id'] = $store_id;
        #$store_locations = $this->input->post("store_{$store_id}_location");

        $store_quantity = array_reduce($store_location_data, function ($sum, $location_quantity) {
          return $sum + $location_quantity;
        }, 0);

        $store_inventory_item['quantity']  = $store_quantity;
        $store_inventory_item['locations'] = $store_location_data; //id as key

        if (!empty($store_inventory_item['locations'])) {
          array_push($store_data, $store_inventory_item);
        }

        $total_quantity += $store_quantity;
      }

      $store_inventory = json_encode($store_data);
    }

    $result = $this->inventory_model->edit([
      'last_sku'        => intval($sku_count),
      'quantity'        => $product_quantity + $total_quantity,
      'store_inventory' => $store_inventory
    ], $parent_inventory_id);

    if ($result) {
      // $inventory_id = $result;
      /**
       * Get all images that are uploaded
       * save them one by one
       */
      // $gallery_list = $this->input->post('gallery_image', TRUE);
      // foreach ($gallery_list as $gallery_key => $gallery_value) {
      //     $image_name       = $this->input->post('gallery_image', TRUE)[$gallery_key];
      //     $gallery_image_id = $this->input->post('gallery_image_id', TRUE)[$gallery_key];
      //     if (!empty($image_name)) {
      //         $data_add_gallery = array(
      //             'image_name'     => $image_name,
      //             'image_id'       => $gallery_image_id,
      //             'inventory_id'   => $inventory_id,
      //         );
      //         $this->inventory_gallery_list_model->create($data_add_gallery);
      //     }
      // }

      // var_dump($result);
      // exit;

      $this->success('Inventory has been added successfully.');
      #$sku_ids = urlencode($sku_ids);
      return $this->redirect('/admin/inventory/skus/' . $sku_ids . '?print=1');
      #return $this->redirect('/admin/inventory/add');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Admin/InventoryAdd', $this->_data);
  }

  public function edit($id)
  {
    $model   = $this->inventory_model->get($id);
    $session = $this->get_session();
    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    include_once __DIR__ . '/../../view_models/Inventory_admin_edit_view_model.php';
    $this->form_validation = $this->inventory_model->set_form_validation(
      $this->form_validation,
      $this->inventory_model->get_all_edit_validation_rule()
    );
    $this->_data['view_model'] = new Inventory_admin_edit_view_model($this->inventory_model);
    $this->_data['view_model']->set_model($model);
    $this->_data['view_model']->set_heading('Inventory');

    $this->_data['gallery_lists'] = $this->inventory_gallery_list_model->get_all(['inventory_id' => $id]);
    if (empty($this->_data['gallery_lists'])) {
      $this->_data['gallery_lists'] = $this->inventory_gallery_list_model->get_all(['inventory_id' => $model->parent_inventory_id]);
    }
    $this->_data['parent_categories']         = $this->_get_grouped_categories();
    $this->_data['encoded_parent_categories'] = base64_encode(json_encode($this->_get_grouped_categories()));
    // $this->_data['parent_categories']   =   $this->category_model->get_all(['status' => 1]);
    $stores                                    = $this->store_model->get_all();
    $stores                                    = $this->append_locations_to_store($stores);
    $this->_data['stores']                     = $stores;
    $this->_data['physical_locations']         = $this->physical_location_model->get_all();
    $this->_data['sale_persons']               = $this->user_model->get_all_users();
    $this->_data['store_inventory']            = json_decode($model->store_inventory);
    $this->_data['item_inventory_locations']   = $this->extract_locations_from_store_inventory(json_decode($model->store_inventory));
    $this->_data['item_inventory_stores']      = $this->extract_stores_from_store_inventory(json_decode($model->store_inventory));
    $this->_data['encoded_physical_locations'] = base64_encode(json_encode($this->_data['physical_locations']));
    $this->_data['products']                   = $this->inventory_model->get_all(['is_product = 1']);
    $this->_data['parent_inventory']           = $this->inventory_model->get_by_fields(["is_product" => 0, "id" => $id]);

    // echo '<pre>';
    // var_dump($this->_data['parent_inventory']);
    // echo '</pre>';
    // exit;

    // $this->_data['parent_categories']

    if ($this->input->post('can_ship') == 1) {
      $this->form_validation->set_rules('weight', 'Weight', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('length', 'Length', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('height', 'Height', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('width', 'Width', 'required|greater_than_equal_to[1]');
      $this->form_validation->set_rules('locations', 'Store', 'required');
      $this->form_validation->set_rules('stores', 'Store', 'required');
    }
    //$this->form_validation->set_rules('locations[]', 'Store', 'callback_validate_store_inventory');

    if ($this->form_validation->run() === FALSE) {
      return $this->render('Admin/InventoryEdit', $this->_data);
    }

    $product_name         = $this->input->post('product_name', TRUE);
    $sku                  = $this->input->post('sku', TRUE);
    $category_id          = $this->input->post('category_id', TRUE);
    $manifest_id          = $this->input->post('manifest_id', TRUE);
    $physical_location    = $this->input->post('physical_location', TRUE);
    $location_description = $this->input->post('location_description', TRUE);
    $weight               = $this->input->post('weight', TRUE);
    $length               = $this->input->post('length', TRUE);
    $height               = $this->input->post('height', TRUE);
    $width                = $this->input->post('width', TRUE);
    $feature_image        = $this->input->post('feature_image', TRUE);
    $feature_image_id     = $this->input->post('feature_image_id', TRUE);
    $parent_inventory_id  = $this->input->post('parent_inventory_id', TRUE);
    $selling_price        = $this->input->post('selling_price', TRUE);
    $inventory_note       = $this->input->post('inventory_note', TRUE);
    $cost_price           = $this->input->post('cost_price', TRUE);
    $admin_inventory_note = $this->input->post('admin_inventory_note', TRUE);

    $status = $this->input->post('status', TRUE);
    // $stores_inventory = $this->input->post('stores_inventory', TRUE);
    $location_stores   = $this->input->post('stores', TRUE);
    $locations         = $this->input->post('locations', TRUE);
    $quantity          = $this->input->post('quantity', TRUE);
    $stores            = $this->input->post('stores', TRUE);
    $sale_person_id    = $this->input->post('sale_person_id', TRUE);
    $can_ship          = $this->input->post('can_ship', TRUE) ?? 2;
    $can_ship_approval = $this->input->post('can_ship_approval', TRUE) ?? 2;
    $free_ship         = $this->input->post('free_ship', TRUE);
    $product_type      = $this->input->post('product_type', TRUE);
    $pin_item_top      = $this->input->post('pin_item_top', TRUE);
    $video_url         = json_encode($this->input->post('video_url', TRUE));

    $youtube_thumbnail_1 = json_encode($this->input->post('youtube_thumbnail_1', TRUE));

    if ($product_type == 2) {
      $sku = '';
    }

    $store_inventory = [];
    $total_quantity  = 1;
    // $unique_stores = array_unique($location_stores);
    // foreach ($unique_stores as $key => $store_id) {
    //     $store_location_data = [];
    //     foreach ($locations as $key2 => $location_id) {
    //         if ($store_id == $location_stores[$key2]) {
    //             $store_location_data[$location_id] = $quantity[$key2];
    //         }
    //     }

    //     $store_inventory_item['store_id'] = $store_id;
    //     $store_locations = $this->input->post("store_{$store_id}_location");

    //     $store_quantity = array_reduce($store_location_data, function ($sum, $location_quantity) {
    //         return $sum + $location_quantity;
    //     }, 0);

    //     $store_inventory_item['quantity'] = $store_quantity;
    //     $store_inventory_item['locations'] = $store_location_data; //id as key

    //     array_push($store_inventory, $store_inventory_item);
    //     $total_quantity += $store_quantity;
    // }

    // $store_inventory = json_encode($store_inventory);

    $result = $this->inventory_model->edit([
      'sale_person_id'       => $sale_person_id,
      'product_name'         => $product_name,
      'sku'                  => $sku,
      'category_id'          => $category_id,
      'manifest_id'          => $manifest_id,
      'physical_location'    => $locations,
      'location_description' => '',
      'weight'               => $weight,
      'length'               => $length,
      'height'               => $height,
      'width'                => $width,
      'feature_image'        => $feature_image,
      'feature_image_id'     => $feature_image_id,
      'selling_price'        => $selling_price,
      'quantity'             => $total_quantity,
      'inventory_note'       => $inventory_note,
      'parent_inventory_id'  => $parent_inventory_id,
      'cost_price'           => $cost_price,
      'admin_inventory_note' => $admin_inventory_note,
      'status'               => $status,
      'store_location_id'    => $stores,
      'can_ship'             => $can_ship,
      'can_ship_approval'    => $can_ship_approval,
      'free_ship'            => $free_ship,
      'product_type'         => $product_type,
      'pin_item_top'         => $pin_item_top,
      'video_url'            => $video_url,
      'youtube_thumbnail_1'  => $youtube_thumbnail_1
      //'store_inventory' => $store_inventory

    ], $id);

    if ($result) {
      $inventory_id = $id;

      if ($quantity > $model->quantity) {
        $this->load->model('notification_system_model');
        $this->load->library('helpers_service');
        $this->load->library('mail_service');
        $this->helpers_service->set_notification_system_model($this->notification_system_model);
        $this->helpers_service->set_mail_service($this->mail_service);
        $this->helpers_service->set_config($this->config);
        $this->helpers_service->notify_item_has_been_added($inventory_id);
      }

      /**
       * Get all images that are uploaded
       * save them one by one
       */
      $gallery_list = $this->input->post('gallery_image', TRUE);
      foreach ($gallery_list as $gallery_key => $gallery_value) {
        $image_name       = $this->input->post('gallery_image', TRUE)[$gallery_key];
        $gallery_image_id = $this->input->post('gallery_image_id', TRUE)[$gallery_key];
        if (!empty($image_name)) {
          $data_add_gallery = [
            'image_name'   => $image_name,
            'image_id'     => $gallery_image_id,
            'inventory_id' => $inventory_id
          ];
          $this->inventory_gallery_list_model->create($data_add_gallery);
        }
      }

      $this->_data['products'] = $this->inventory_model->get_by_fields(['is_product' => 1]);
      $this->success('Inventory has been updated successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->_data['error'] = 'Error';
    return $this->render('Admin/InventoryEdit', $this->_data);
  }

  public function view($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $this->names_helper_service->set_store_model($this->store_model);
    $this->names_helper_service->set_physical_location_model($this->physical_location_model);
    $this->names_helper_service->set_customer_model($this->customer_model);
    $this->names_helper_service->set_category_model($this->category_model);

    include_once __DIR__ . '/../../view_models/Inventory_admin_view_view_model.php';
    $this->_data['view_model'] = new Inventory_admin_view_view_model($this->inventory_model);
    $this->_data['view_model']->set_heading('Inventory');
    $model->category_id       = $this->names_helper_service->get_category_real_name($model->category_id);
    $model->physical_location = $this->names_helper_service->get_physical_location_real_name($model->physical_location);
    $model->store_location_id = $this->names_helper_service->get_store_name($model->store_location_id);
    $this->_data['view_model']->set_model($model);
    $store_inventory = !empty($model->store_inventory) ? json_decode($model->store_inventory) : [];

    foreach ($store_inventory as $key => &$store) {
      $store_inventory[$key]->store_name = $this->names_helper_service->get_store_name($store->store_id);
      $store_locations                   = isset($store->locations) ? $store->locations : [];
      foreach ($store_locations as $location_id => $location_quantity) {
        $store_inventory[$key]->location_data[] = ['name' => $this->names_helper_service->get_physical_location_real_name($location_id), 'quantity' => $location_quantity];
      }
      // $store_inventory[$key]->physical_location_name = $this->names_helper_service->get_physical_location_real_name( $value->physical_location );
    }
    $this->_data['store_inventory'] = $store_inventory;

    return $this->render('Admin/InventoryView', $this->_data);
  }

  public function delete($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $result = $this->inventory_model->delete($id);

    if ($result) {
      $this->success('Action completed successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory/0');
  }

  public function pending($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $result = $this->inventory_model->edit([
      'status' => 3
    ], $id);

    if ($result) {
      $this->success('Action completed successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory/0');
  }

  public function approve($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $result = $this->inventory_model->edit([
      'status' => 1
    ], $id);

    if ($result) {
      $this->success('Action completed successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory/0');
  }

  public function real_delete($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $result = $this->inventory_model->real_delete($id);

    if ($result) {
      $this->success('Action completed successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory/0');
  }

  public function set_active($id)
  {
    $model = $this->inventory_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    $result = $this->inventory_model->edit(['status' => 1], $id);

    if ($result) {
      $this->success('Action completed successfully.');
      return $this->redirect('/admin/inventory/0', 'refresh');
    }

    $this->error('Error');
    return redirect('/admin/inventory/0');
  }

  public function delete_gallery_image($id)
  {
    $model = $this->inventory_gallery_list_model->get($id);

    if (!$model) {
      $this->error('Error');
      return redirect($_SERVER['HTTP_REFERER']);
    }

    $result = $this->inventory_gallery_list_model->real_delete($id);

    if ($result) {
      $this->success('Image deleted successfully.');
      return redirect($_SERVER['HTTP_REFERER']);
    }

    $this->error('Error');
    return redirect($_SERVER['HTTP_REFERER']);
  }

  public function scan_product_view()
  {
    $this->_data['page_name'] = "Scan Inventory";

    return $this->render('Admin/Scan_Product_View', $this->_data);
  }

  private function _get_grouped_categories()
  {
    // get parent categoris
    $table        = 'category';
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

  public function validate_store_inventory()
  {
    // ensure the same store is not selected more than once.
    $locations = $this->input->post('locations');

    return count($locations) < 1 ? FALSE : TRUE;
  }

  public function transfer($id)
  {
    $model   = $this->inventory_model->get($id);
    $session = $this->get_session();
    if (!$model) {
      $this->error('Error');
      return redirect('/admin/inventory/0');
    }

    if (isset($_POST['submit_inventory_transfer'])) {
      // Start Inventory transfer
      $from_store    = $this->input->post('from_store');
      $from_quantity = $this->input->post('from_quantity');
      $to_store      = $this->input->post('to_store');
      if ($from_store == $to_store) {
        // error cant transfer to the same store
        $this->error('Error, Cannot transfer to the same store.');
        return redirect($_SERVER['HTTP_REFERER']);
      }
      $store_inventory = json_decode($model->store_inventory);
      foreach ($store_inventory as $key => &$value) {
        if ($value->store_id == $from_store) { // remove quantity from store
          $value->quantity = $value->quantity - $from_quantity;
          continue;
        }
        if ($value->store_id == $to_store) { // add quantity to store
          $value->quantity += $from_quantity;
        }
      }
      $this->inventory_model->edit([
        'store_inventory' => json_encode($store_inventory)
      ], $model->id);

      $this->success('Inventory Transferred Successfully');
      return redirect('/admin/inventory/view/' . $id);
    }

    $this->_data['heading']        = 'Inventory';
    $this->_data['inventory_item'] = $model;
    $this->_data['store_data']     = base64_encode($model->store_inventory);
    $store_inventory               = json_decode($model->store_inventory);
    $this->names_helper_service->set_store_model($this->store_model);
    foreach ($store_inventory as $key => $value) {
      $value->store = $this->names_helper_service->get_store_name($value->store_id);
    }
    $this->_data['store_inventory'] = $store_inventory;

    return $this->render('Admin/InventoryTransfer', $this->_data);
  }

  public function transfer_inventory()
  {
    $this->load->library('helpers_service');
    $this->load->model('inventory_transfer_log_model');
    $this->load->model('physical_location_model');
    $this->_data['heading']   = 'Inventory Transfer';
    $this->_data['page_name'] = 'Inventory Transfer';

    $this->_data['encoded_stores']    = base64_encode(json_encode($this->store_model->get_all()));
    $this->_data['encoded_locations'] = base64_encode(json_encode($this->physical_location_model->get_all()));
    $this->_data['inventory_items']   = $this->inventory_model->get_all(['quantity > 0', 'is_product = 1']);

    if (isset($_POST['submit_inventory_transfer'])) {

      $_sku           = $this->input->post('_sku[]');
      $_product_id    = $this->input->post('_product_id[]');
      $_from_store    = $this->input->post('_from[]');
      $_from_location = $this->input->post('_from_location[]');
      $_from_quantity = $this->input->post('_quantity[]');
      $_to_store      = $this->input->post('_to[]');
      $items_count    = 0;

      if (count($_sku) < 1) {
        $this->error('Atleast one request must be added to list');
        return redirect('/admin/transfer/transfer_inventory/');
      }
      for ($i = 0; $i < count($_sku); $i++) {
        // Start Inventory transfer

        $product_data = $this->inventory_model->get_all_by_limit([
          "store_location_id = $_from_store[$i]",
          "physical_location = $_from_location[$i]",
          "available_in_shelf = 2",
          "parent_inventory_id = $_product_id[$i]"
        ], $_from_quantity[$i]);

        for ($j = 0; $j < $_from_quantity[$i]; $j++) {

          //$_sku[$i];
          $sku           = $product_data[$j]->sku;
          $from_store    = $_from_store[$i];
          $from_location = $_from_location[$i];
          $from_quantity = 1;
          $to_store      = $_to_store[$i];
          $product       = $this->inventory_model->get_by_field('sku', $sku);
          if (empty($product)) {
            continue;
          }

          /**
           * TODO
           * 1) come bakc to change this if it is not itented by client
           */

          // if ($from_store == $to_store) {
          //     continue;
          // }

          $result = $this->inventory_transfer_model->create([
            'product_name'  => $product_data[$j]->product_name,
            'sku'           => $sku,
            'from_store'    => $from_store,
            'from_location' => $from_location,
            'to_store'      => $to_store,
            'quantity'      => $from_quantity,
            'status'        => '1' //pending
          ]);

          if ($result) {
            $items_count++;
          }

          // Log
          $this->helpers_service->set_inventory_transfer_log_model($this->inventory_transfer_log_model);
          $this->helpers_service->set_inventory_transfer_model($this->inventory_transfer_model);
          $this->helpers_service->set_store_model($this->store_model);
          $this->helpers_service->log_inventory_transfer($result, 'Initiate transfer');
        }
      }

      $this->success('Inventory Transfer Request Pending');
      return redirect('/admin/transfer/transfer_inventory/');
    }

    return $this->render('Admin/InventoryTransfer', $this->_data);
  }

  private function append_locations_to_store($stores = [])
  {
    $this->load->model('physical_location_model');

    foreach ($stores as $key => &$store) {
      $store->locations = $this->physical_location_model->get_all([
        'store_id' => $store->id
      ]);
    }
    return $stores;
  }

  private function extract_locations_from_store_inventory($store_inventory = [])
  {
    $locations = [];
    foreach ($store_inventory as $store_data) {
      if (empty($store_data->locations)) {
        continue;
      }
      foreach ($store_data->locations as $location_id => $location_quantity) {
        $locations[] = ['store_id' => $store_data->store_id, 'location_id' => $location_id, 'quantity' => $location_quantity];
      }
    }
    return $locations;
  }

  private function extract_stores_from_store_inventory($store_inventory = [])
  {
    $stores = [];
    foreach ($store_inventory as $store_data) {
      $stores[] = $store_data->store_id;
    }
    return $stores;
  }

  private function check_existing_store($store_inventory = [], $store_id)
  {
    #$stores = [];
    $result = false;

    foreach ($store_inventory as $key => $store_data) {
      #$stores[] = $store_data->store_id;
      //echo $store_data->store_id . " - " . $store_id;
      //exit;
      $new_key = &$key;

      if ($store_data->store_id == $store_id) {
        $result = $key;
      }
    }

    return $result;
  }

  private function check_existing_location($store_inventory = [], $store_id, $location_id)
  {
    $result = false;
    // echo '<pre>';
    // var_dump($store_inventory);
    // echo '</pre>';
    // exit;
    foreach ($store_inventory as $key => $store_data) {
      #$stores[] = $store_data->store_id;

      if ($store_data->store_id != $store_id) {
        continue;
      }

      $store_locations = $store_data->locations;
      foreach ($store_locations as $key2 => $value) {
        $new_key2 = &$key2;
        if ($new_key2 == $location_id) {

          $result = $key2;
        }
      }
    }
    return $result;
  }

  public function create_physical_location()
  {

    if ($this->session->userdata('user_id')) {
      $this->load->library('barcode_service');
      $this->load->model('physical_location_model');
      $store_id = $this->input->post('store');
      $name     = $this->input->post('physical_location');

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
        $output['status']            = 200;
        $output['encoded_locations'] = base64_encode(json_encode($this->physical_location_model->get_all()));
        echo json_encode($output);
        exit();
      }
      echo json_encode([
        'error' => 'Failed to create physical location'
      ]);
      exit();
    }
  }

  private function log_inventory($inventory_id, $action = '')
  {
    $this->load->library('helpers_service');

    $this->helpers_service->set_inventory_log_model($this->inventory_log_model);
    $this->helpers_service->set_inventory_model($this->inventory_model);
    $this->helpers_service->set_user_model($this->user_model);
    $this->helpers_service->log_inventory($inventory_id, $action);
  }

  public function print_skus($item_ids)
  {
    #$item_ids = urldecode($item_ids);
    $item_ids = explode('-', $item_ids);

    $inventories = [];
    foreach ($item_ids as $item_id) {
      if (empty($item_id)) {
        continue;
      }

      $inventories[] = $this->inventory_model->get($item_id);
    }

    $this->_data['inventories'] = $inventories;

    return $this->render('Manager/InventorySkus', $this->_data);
  }
}
