<?php defined('BASEPATH') || exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory_transfer Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 * @property Inventory_model $inventory_model
 *
 */
class Manifest_controller extends Manaknight_Controller
{
  /**
   * @var int
   */
  protected $sale_channel_id = 1;

  public function __construct()
  {
    parent::__construct();
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, OPTIONS");
    header("Access-Control-Allow-Headers: x-project, Content-Type");
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == "OPTIONS") {
      header("HTTP/1.1 200 OK");
      die();
    }

    $this->load->model('inventory_model');
  }

  /**
   * @return mixed
   */
  public function index()
  {

    $token = $this->input->get_request_header('x-project');
    if (!$token || $token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(401)
           ->set_output(json_encode(['error' => 'Unauthorized']));
      return;
    }

    // Set the endpoint URL and sales channel ID
    $channelId = $this->sale_channel_id;
    $endpoint  = "https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/get_pallets?sales_channel_id=$channelId";

    // Set the header data
    $headers = [
      'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
      'Content-Type: application/json'
    ];

    // Create a new cURL resource and set the necessary options
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL            => $endpoint,
      CURLOPT_HTTPHEADER     => $headers,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_MAXREDIRS      => 10,
      CURLOPT_TIMEOUT        => 30,
      CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST  => 'GET'

    ]);

    // Execute the cURL request
    $response = curl_exec($curl);
    $err      = curl_error($curl);

    // Close the cURL resource
    curl_close($curl);

    // Check for errors and return null if there is an error
    if ($err) {
      $data = ['cURL Error' => $err];
      return $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($data));
      // ->set_header('Access-Control-Allow-Origin: *')
      // ->set_header('Access-Control-Allow-Methods: GET, OPTIONS')
      // ->set_header('Access-Control-Allow-Headers: x-project');
    }

    // Parse the response data
    $response_data = json_decode($response, true);

    // Extracting names from manifests
    $manifest_names = array_reduce($response_data['list'], function ($carry, $item) {
      $names = array_column($item['manifests'], 'name');
      return array_merge($carry, $names);
    }, []);

    // Extracting IDs from manifests
    $manifest_ids = array_reduce($response_data['list'], function ($carry, $item) {
      $ids = array_column($item['manifests'], 'id');
      return array_merge($carry, $ids);
    }, []);

    // Extracting statuses from manifests
    $manifest_statuses = array_reduce($response_data['list'], function ($carry, $item) {
      $statuses = array_column($item['manifests'], 'status');
      return array_merge($carry, $statuses);
    }, []);

    $manifest_data = [
      'names'  => $manifest_names,
      'ids'    => $manifest_ids,
      'status' => $manifest_statuses
    ];

    $manifest_items = $this->get_manifest_items(implode(",", $manifest_ids));

    $channelId                      = $this->sale_channel_id;
    $query_items['sale_channel_id'] = $channelId;
    $query_items['data']            = array_map(function ($items) {
      return $this->save_manifest_items($items);
    }, $manifest_items['list']);

    $postResponse = $this->send_processed_data($query_items);

    return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($postResponse));
    // ->set_output(json_encode($query_items));

    // ->set_header('Access-Control-Allow-Origin: *')
    // ->set_header('Access-Control-Allow-Methods: GET, OPTIONS')
    // ->set_header('Access-Control-Allow-Headers: x-project');
  }

  /**
   * @param $manifest_ids
   * @return mixed
   */
  public function get_manifest_items($manifest_ids)
  {

    $url     = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/get_manifest_items?manifest_ids=' . $manifest_ids;
    $headers = [
      'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
      'Content-Type: application/json'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_error($ch)) {
      $error_msg = curl_error($ch);
      curl_close($ch);
      return $error_msg;
    }

    curl_close($ch);

    // decode JSON response
    $data = json_decode($response, true);
    return $data;
  }

  /**
   * @param $data
   * @return mixed
   */
  public function save_manifest_items($data)
  {
    $processedItem = [
      'manifest_id' => $data['manifest_id']
    ];
    foreach ($data['items'] as $item) {

      $id            = $item['id'];
      $sku           = $item['sku'] ?? 0;
      $manifest_item = $this->db->get_where('inventory', ['sku' => $sku, 'product_name' => $item['name']])->row_array();
      if ($manifest_item) {

        $ItemResult = [
          'exist' => true,
          'save'  => false,
          'id'    => $id,
          'sku'   => $sku
        ];
      } else {

        if ($item['item_api_data']) {

          if ($item['api_type'] == 'home_depot' && $item['item_api_data'] != null) {
            $home_depot_data = json_decode($item['item_api_data']);
            $image           = $home_depot_data->media->images[0]->url;
            $ItemResult      = [
              'exist' => false,
              'save'  => true,
              'type'  => 'amazon',
              'image' => $image
            ];
          } elseif ($item['api_type'] == 'amazon' && $item['item_api_data'] != null) {
            $amazon_data = json_decode($item['item_api_data']);
            $image       = $amazon_data->images[0]->images[0]->link;

            $item_dimensions = "";

            if (isset($amazon_data->dimensions[0]->package)) {
              $item_length = $amazon_data->dimensions[0]->package->length->value;
              $item_width  = $amazon_data->dimensions[0]->package->width->value;
              $item_height = $amazon_data->dimensions[0]->package->height->value;
            } elseif (isset($amazon_data->attributes->item_package_dimensions)) {
              $item_length = $amazon_data->attributes->item_package_dimensions[0]->length->value / 2.54;
              $item_width  = $amazon_data->attributes->item_package_dimensions[0]->width->value / 2.54;
              $item_height = $amazon_data->attributes->item_package_dimensions[0]->height->value / 2.54;
            } elseif (isset($amazon_data->attributes->size)) {
              $item_dimensions = $amazon_data->attributes->size[0]->value;

              if (strlen($item_dimensions) > 12) {
                // Example: $item_dimensions = '5.2 x 0.69 x 8 inches';
                $item_dimensions       = str_replace(' ', '', $item_dimensions);
                $split_item_dimensions = explode('x', $item_dimensions);

                if (is_numeric($split_item_dimensions[0])) {
                  $item_length = (float) $split_item_dimensions[0];
                }

                $item_width  = (float) $split_item_dimensions[1];
                $item_height = (float) $split_item_dimensions[2];
              } elseif (strlen($item_dimensions) > 7 && strlen($item_dimensions) < 11) {
                // Example: $item_dimensions = '6.5 Inches';
                $item_dimensions = substr($item_dimensions, 0, strlen($item_dimensions) - 7);
                $item_height     = (float) $item_dimensions;
              }
            }

            if ($amazon_data->attributes->item_weight) {
              $item_weight_x = $amazon_data->attributes->item_weight[0]->value;
            } elseif ($amazon_data->attributes->item_package_weight) {
              $item_weight_x = $amazon_data->attributes->item_package_weight[0]->value * 2.20462;
            }

            $ItemResult = [
              'exist'  => false,
              'save'   => true,
              'type'   => 'amazon',
              'image'  => $image,
              'length' => $item_length,
              'width'  => $item_width,
              'height' => $item_height,
              'weight' => $item_weight_x
            ];
          }
        } else {
          $ItemResult = [
            'exist'    => false,
            'save'     => true,
            'id'       => $id,
            'sku'      => $sku,
            'api_data' => $item
          ];
        }

        // Save the new manifest_item to the database
        // $savedata = [
        //     'id'             => $id,
        //     'sku'            => $sku,
        //     'name'           => $item['name'],
        //     'description'    => $item['description'],
        //     'category'       => $item['category'],
        //     'upc'            => $item['upc'],
        //     'asin'           => $item['asin'],
        //     'qty'            => $item['quantity'],
        //     'manifest_price' => $item['manifest_price'],
        //     'manifest_id'    => $data['manifest_id'],
        //     'retail_price'   => $item['retail_price'],
        //     'sale_price'     => $item['sale_price'],
        //     'list_date'      => $item['list_date'],
        //     'duration'       => $item['duration'],
        //     'sales_person'   => $item['sales_person'],
        //     'upload_type'    => $item['upload_type'],
        //     'process_status' => $item['process_status'],
        //     'status'         => $item['status'] ?? 1,
        //     'feature_image' => $ItemResult['image'] ?? '',
        //     'weight' => $ItemResult['weight'] ?? '',
        //     'height' => $ItemResult['height'] ?? '',
        //     'length' => $ItemResult['length'] ?? '',
        //     'width' => $ItemResult['width'] ?? '',
        // ];

        $product_data = [
          'product_name'      => $item['name'],
          'sale_person_id'    => 1,
          'is_product'        => 1,
          'sku'               => $sku ?? 0,
          'last_sku'          => null,
          'category_id'       => $item['category'],
          'physical_location' => 0,
          'weight'            => $ItemResult['weight'] ?? '',
          'length'            => $ItemResult['length'] ?? '',
          'height'            => $ItemResult['height'] ?? '',
          'width'             => $ItemResult['width'] ?? '',
          'selling_price'     => $item['sale_price'],
          'cost_price'        => $item['sale_price'],
          'status'            => $item['status'] ?? 1,
          'store_location_id' => '',
          'can_ship'          => $item['can_ship'],
          'can_ship_approval' => $item['can_ship_approval'],
          'free_ship'         => $item['free_ship'] ?? 1,
          'product_type'      => null
        ];

        $inventory_data = [
          'product_name'         => $item['name'],
          'sku'                  => $sku ?? 0,
          'weight'               => $ItemResult['weight'] ?? '',
          'length'               => $ItemResult['length'] ?? '',
          'height'               => $ItemResult['height'] ?? '',
          'width'                => $ItemResult['width'] ?? '',
          'pin_item_top'         => $item['pin_item'] ?? 1,
          'category_id'          => $item['category'],
          'cost_price'           => $item['sale_price'],
          'selling_price'        => $item['sale_price'],
          'can_ship'             => $item['can_ship'],
          'can_ship_approval'    => $item['can_ship_approval'],
          'free_ship'            => $item['free_ship'] ?? 1,
          'quantity'             => 1,
          'inventory_note'       => $item['inventory_note'] ?? '',
          'admin_inventory_note' => $item['admin_inventory_note'] ?? '',
          'status'               => $item['status'] ?? 1,
          'manifest_id'          => $data['manifest_id'],
          'physical_location'    => $item['physical_location_id'],
          'sale_person_id'       => 1,
          'parent_inventory_id'  => 0,
          'store_inventory'      => json_encode(['store_id' => $item['store_id'] ?? 1, 'quantity' => 1, 'locations' => ['1' => 1]]),
          'product_type'         => $item['product_type'] ?? 1
        ];

        // Remove any null or undefined values from the data map
        $product_data = array_filter($product_data, function ($value) {
          return $value !== null && $value !== '';
        });

        $this->db->trans_start();
        $result1 = $this->inventory_model->create($product_data);

        if ($result1) {
          $inventory_data['product_id'] = $result1;
          $result2                      = $this->inventory_model->create($inventory_data);
          if ($result2) {
            $this->db->trans_complete();
            $ItemResult['response'] = ['status' => true, 'message' => 'Record inserted successfully'];
            // $this->output
            //     ->set_content_type('application/json')
            //     ->set_status_header(201)
            //     ->set_output(json_encode($response));
            // return $response;
          } else {
            $this->db->trans_rollback();
            $ItemResult['response'] = ['status' => false, 'message' => 'Error inserting record'];
            // $this->output
            //     ->set_content_type('application/json')
            //     ->set_status_header(500)
            //     ->set_output(json_encode($response));
            // return $response;
          }
        } else {
          $this->db->rollback();
          $ItemResult['response'] = ['status' => false, 'message' => 'Error inserting record'];
          // $this->output
          //     ->set_content_type('application/json')
          //     ->set_status_header(500)
          //     ->set_output(json_encode($response));
          // return $response;
        }

        // $this->db->insert('inventory', $savedata);
      }

      $processedItem['items'][] = $ItemResult;
    }

    return $processedItem;
  }

  /**
   * @param $processedData
   */
  public function send_processed_data($processedData)
  {
    $url     = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/manage_process';
    $headers = [
      'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
      'Content-Type: application/json'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($processedData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
      return ['success' => false, 'result' => curl_error($ch)];
    } else {
      return ['success' => true, 'result' => $response];
    }
  }

  /**
   * @return null
   */
  public function get_store_nd_categories()
  {
    // Check if the request includes a valid token
    $token = $this->input->get_request_header('x-project');
    if (!$token || $token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(401)
           ->set_output(json_encode(['error' => 'Unauthorized']));
      return;
    }

    // If the token is valid, fetch the category data and return it as JSON
    $query = $this->db->get('category');
    $json  = json_encode([
      'category'    => $query->result_array(),
      'stores'      => $this->get_store_nd_locations(),
      'inventories' => $this->get_products_main($token, 0)
    ]);
    $this->output
         ->set_content_type('application/json')
         ->set_output($json);
    // ->set_header('Access-Control-Allow-Origin: *')
    // ->set_header('Access-Control-Allow-Methods: GET, OPTIONS')
    // ->set_header('Access-Control-Allow-Headers: x-project');
  }

  public function get_store_nd_locations()
  {
    $this->db->select('physical_location.id, physical_location.name as location_name, store.id as store_id, store.name as store_name, store.address');
    $this->db->from('physical_location');
    $this->db->join('store', 'physical_location.store_id = store.id');
    $this->db->order_by('store.id', 'asc');
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $result_array = [];
      foreach ($query->result() as $row) {
        $store_id = $row->store_id;
        if (!isset($result_array[$store_id])) {
          $result_array[$store_id] = [
            'store_id'      => $store_id,
            'store_name'    => $row->store_name,
            'store_address' => $row->address,
            'locations'     => []
          ];
        }
        $result_array[$store_id]['locations'][] = (object) [
          'id'            => $row->id,
          'location_name' => $row->location_name
        ];
      }
      return array_values($result_array);
    } else {
      return false;
    }
  }
  /**
   * @return mixed
   */
  public function post_single_manifest_items()
  {
    $token = $this->input->get_request_header('x-project');
    if (!$token || $token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(401)
           ->set_output(json_encode(['error' => 'Unauthorized']));
      return;
    }

    $data = $this->input->post();

    if (isset($data['is_relist'])) {
      $prev_sale_channel_data = [];

      $prev_sale_channel_data['prev_sale_channel_id']   = $data['prev_sale_channel_id'];
      $prev_sale_channel_data['prev_sale_channel_name'] = $data['prev_sale_channel_name'];
      $prev_sale_channel_data                           = json_encode($prev_sale_channel_data);
    }

    $product_data = [
      'product_name'           => $data['product_name'],
      'sale_person_id'         => 1,
      'is_product'             => 1,
      'sku'                    => $data['sku'],
      'last_sku'               => null,
      'category_id'            => $data['category_id'],
      // 'locations' => 1,
      'physical_location'      => 0,
      'weight'                 => $data['weight'],
      'length'                 => $data['length'],
      'height'                 => $data['height'],
      'width'                  => $data['width'],
      'selling_price'          => $data['selling_price'],
      'cost_price'             => $data['cost_price'],
      'status'                 => $data['status'],
      'store_location_id'      => '',
      'can_ship'               => $data['can_ship'],
      'can_ship_approval'      => $data['can_ship_approval'],
      'free_ship'              => $data['free_ship'],
      'product_type'           => null,
      'prev_sale_channel_data' => $prev_sale_channel_data
    ];

    $inventory_data = [
      'product_name'         => $data['product_name'],
      'sku'                  => $data['sku'],
      'weight'               => $data['weight'],
      'length'               => $data['length'],
      'height'               => $data['height'],
      'width'                => $data['width'],
      'pin_item_top'         => $data['pin_item'],
      'category_id'          => $data['category_id'],
      'cost_price'           => $data['cost_price'],
      'selling_price'        => $data['selling_price'],
      'can_ship'             => $data['can_ship'],
      'can_ship_approval'    => $data['can_ship_approval'],
      'free_ship'            => $data['free_ship'],
      'quantity'             => 1,
      'inventory_note'       => $data['inventory_note'],
      'admin_inventory_note' => $data['admin_inventory_note'],
      'status'               => $data['status'],
      'manifest_id'          => $data['manifest_id'],
      'physical_location'    => $data['physical_location_id'],
      'sale_person_id'       => 1,
      'parent_inventory_id'  => 0,
      'store_inventory'      => json_encode(['store_id' => $data['store_id'], 'quantity' => $data['quantity'], 'locations' => ['1' => $data['quantity']]]),
      'product_type'         => $data['product_type'] ?? 1
    ];

    // Remove any null or undefined values from the data map
    $product_data = array_filter($product_data, function ($value) {
      return $value !== null && $value !== '';
    });

    $exist_product   = $this->inventory_model->get_by_fields(['sku' => $data['sku'], 'is_product' => 1]);
    $exist_inventory = $this->inventory_model->get_by_fields(['sku' => $data['sku'], 'is_product' => 0]);

    if ($exist_product && $exist_inventory) {
      // Update inventory & product
      $this->db->trans_start();
      $inventory_updated = $this->inventory_model->edit($inventory_data, $exist_inventory->id);
      $product_updated   = $this->inventory_model->edit($product_data, $exist_product->id);

      if (!$inventory_updated && !$product_updated) {
        $this->db->trans_rollback();
        $response = ['error' => true, 'message' => 'Error updating record'];
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(400)
             ->set_output(json_encode($response));
        return;
      } else {
        $this->db->trans_complete();
        $response = ['error' => false, 'inventory_id' => $inventory_updated, 'message' => 'Record updated successfully'];
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output(json_encode($response));
        return;
      }
    } else {
      // insert inventory & product
      $this->db->trans_start();
      $result1 = $this->inventory_model->create($product_data);

      if ($result1) {
        $inventory_data['product_id'] = $result1;
        $result2                      = $this->inventory_model->create($inventory_data);
        if ($result2) {
          $this->db->trans_complete();
          $response = ['error' => false, 'inventory_id' => $result2, 'message' => 'Record inserted successfully'];
          $this->output
               ->set_content_type('application/json')
               ->set_status_header(200)
               ->set_output(json_encode($response));
          return;
        } else {
          $this->db->trans_rollback();
          $response = ['error' => true, 'message' => 'Error inserting record'];
          $this->output
               ->set_content_type('application/json')
               ->set_status_header(500)
               ->set_output(json_encode($response));
          return;
        }
      } else {
        $this->db->rollback();
        $response = ['error' => true, 'message' => 'Error inserting record'];
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(500)
             ->set_output(json_encode($response));
      }
    }
  }

  /**
   * @return null
   */
  public function get_products_api()
  {

    $token  = $this->input->get_request_header('x-project');
    $result = $this->get_products_main($token, 1);

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($result));
  }

  /**
   * @param $get_token
   * @param null $is_product
   * @return mixed
   */
  public function get_products_main($get_token = null, $is_product)
  {
    if (!$get_token || $get_token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(401)
           ->set_output(json_encode(['error' => 'Unauthorized']));
      return;
    }

    $this->db->select('inventory.id, inventory.product_name, inventory.sku, inventory.category_id, category.name as category_name');
    $this->db->from('inventory');
    $this->db->join('category', 'inventory.category_id = category.id', 'left');
    $this->db->where('inventory.is_product', $is_product);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
      $result_array = $query->result_array();
      $result       = $result_array;
    } else {
      $result = [false];
    }

    return $result;
  }

  /**
   * @return null
   */
  public function create_products()
  {
    $token = $this->input->get_request_header('x-project');
    if (!$token || $token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(401)
           ->set_output(json_encode(['error' => 'Unauthorized']));
      return;
    }

    $data = $this->input->post();

    $data_map = [
      'product_name'      => $data['product_name'],
      'category_id'       => $data['category_id'],
      'weight'            => $data['weight'],
      'length'            => $data['length'],
      'height'            => $data['height'],
      'width'             => $data['width'],
      'selling_price'     => $data['selling_price'],
      'cost_price'        => $data['cost_price'],
      'can_ship'          => $data['can_ship'],
      'free_ship'         => $data['free_ship'],
      'status'            => $data['status'],
      'is_product'        => $data['is_product'],
      'product_type'      => $data['product_type'],
      'physical_location' => 1,
      'store_location_id' => 1,
      'sale_person_id'    => 1
    ];

    if ($this->db->insert('inventory', $data_map)) {
      $response = ['status' => 200, 'message' => 'Product created successfully.'];
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(200)
           ->set_output(json_encode($response));
    } else {
      $response = ['status' => 400, 'message' => 'Error creating product.'];
      $this->output
           ->set_content_type('application/json')
           ->set_status_header(400)
           ->set_output(json_encode($response));
    }
  }

  /**
   * @param $page
   * @return mixed
   */
  public function recommendation_endpoint($page)
  {
    $q      = $this->input->get('q', TRUE);
    $status = $this->input->get('status', TRUE);

    $limit  = 10;
    $offset = ($page - 1) * $limit;

    $rows = $this->inventory_model->get_all_inventory_items_for_manifest($q, $status);

    $total_rows = 0;
    if (!empty($rows)) {
      $total_rows = count($rows);
    }

    $items = $this->inventory_model->get_all_inventory_items_for_manifest($q, $status, $offset, $limit);

    $response = [
      'data'       => $items,
      'pagination' => [
        'total_count'    => $total_rows,
        'total_pages'    => ceil(($total_rows) / $limit),
        'current_page'   => (int) $page,
        'items_per_page' => $limit
      ]
    ];

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($response));
  }

  /**
   * @return mixed
   */
  public function update_relist_item_status()
  {

    $data = $this->input->post();

    if (!isset($_SERVER['HTTP_X_PROJECT']) || $_SERVER['HTTP_X_PROJECT'] != "bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt") {
      $res = ['error' => 'Not authorised'];
      return $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($res));
    }

    $item_id = $data['item_id'];

    $this->load->model('inventory_model');

    $result = $this->inventory_model->edit([
      'status' => 4
    ], $item_id);

    if ($result) {
      $result = ['error' => false, 'message' => 'Item updated successfully.'];
    } else {
      $result = ['error' => true, 'message' => 'Error updating item.'];
    }

    return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($result));
  }
}
