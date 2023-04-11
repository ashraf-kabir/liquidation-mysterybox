<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory_transfer Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Manifest_controller extends Manaknight_Controller
{


    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: x-project");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            header("HTTP/1.1 200 OK");
            die();
        }
    }

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
        $endpoint = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/get_palettes?sales_channel_id=3';

        // Set the header data
        $headers = array(
            'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
            'Content-Type: application/json',
        );

        // Create a new cURL resource and set the necessary options
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

        ));

        // Execute the cURL request
        $response = curl_exec($curl);
        $err = curl_error($curl);

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
            'names' => $manifest_names,
            'ids' => $manifest_ids,
            'status' => $manifest_statuses
        ];

        $manifest_items = $this->get_manifest_items(implode(",", $manifest_ids));

        $query_items['sale_channel_id'] = 3;
        $query_items['data'] = array_map(function ($items) {
            return $this->save_manifest_items($items);
        }, $manifest_items['list']);

        $postResponse = $this->send_processed_data($query_items);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($postResponse));
        // ->set_header('Access-Control-Allow-Origin: *')
        // ->set_header('Access-Control-Allow-Methods: GET, OPTIONS')
        // ->set_header('Access-Control-Allow-Headers: x-project');
    }

    public function get_manifest_items($manifest_ids)
    {

        $url = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/get_manifest_items?manifest_ids=' . $manifest_ids;
        $headers = array(
            'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
            'Content-Type: application/json'
        );

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

    public function save_manifest_items($data)
    {
        $processedItem = [
            'manifest_id' => $data['manifest_id'],
        ];
        foreach ($data['items'] as $item) {
            $id = $item['id'];
            $sku = $item['sku'];
            $manifest_item = $this->db->get_where('manifest_item', array('id' => $id, 'sku' => $sku))->row_array();
            if ($manifest_item) {

                $ItemResult = [
                    'exist' => true,
                    'save' => false,
                    'id' => $id,
                    'sku' => $sku
                ];
            } else {

                $ItemResult = [
                    'exist' => false,
                    'save' => true,
                    'id' => $id,
                    'sku' => $sku
                ];

                // Save the new manifest_item to the database
                $savedata = array(
                    'id' => $id,
                    'sku' => $sku,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'category' => $item['category'],
                    'upc' => $item['upc'],
                    'asin' => $item['asin'],
                    'qty' => $item['quantity'],
                    'manifest_price' => $item['manifest_price'],
                    'manifest_id' => $data['manifest_id'],
                    'retail_price' => $item['retail_price'],
                    'sale_price' => $item['sale_price'],
                    'list_date' => $item['list_date'],
                    'duration' => $item['duration'],
                    'sales_person' => $item['sales_person'],
                    'upload_type' => $item['upload_type'],
                    'process_status' => $item['process_status'],
                    'status' => $item['status']
                );
                $this->db->insert('manifest_item', $savedata);
            }

            $processedItem['items'][] = $ItemResult;
        }

        return $processedItem;
    }

    public function send_processed_data($processedData)
    {
        $url = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/manage_process';
        $headers = array(
            'x-project: bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt',
            'Content-Type: application/json'
        );

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

    public function get_categories()
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
        $json = json_encode(['category' => $query->result_array(), 'stores' => $this->get_store_nd_locations()]);
        $this->output
            ->set_content_type('application/json')
            ->set_output($json);
        // ->set_header('Access-Control-Allow-Origin: *')
        // ->set_header('Access-Control-Allow-Methods: GET, OPTIONS')
        // ->set_header('Access-Control-Allow-Headers: x-project');
    }

    public function get_store_nd_locations()
    {
        $this->db->select('physical_location.id, physical_location.location_name, physical_location.address, store.id as store_id, store.store_name, store.store_address');
        $this->db->from('physical_location');
        $this->db->join('store', 'physical_location.store_id = store.id');
        $this->db->order_by('store.id', 'asc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result_array = array();
            foreach ($query->result() as $row) {
                $store_id = $row->store_id;
                if (!isset($result_array[$store_id])) {
                    $result_array[$store_id] = array(
                        'store_id' => $store_id,
                        'store_name' => $row->store_name,
                        'store_address' => $row->store_address,
                        'locations' => array()
                    );
                }
                $result_array[$store_id]['locations'][] = array(
                    'id' => $row->id,
                    'location_name' => $row->location_name,
                    'address' => $row->address
                );
            }
            return $result_array;
        } else {
            return false;
        }
    }

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

        // Get the post data
        $data = $this->input->post();

        // Map the post data keys to the database column names
        $data_map = [
            'product_name' => $data['product'],
            'sku' => $data['sku_number'],
            'weight' => $data['weight'],
            'length' => $data['length'],
            'height' => $data['height'],
            'width' => $data['width'],
            'pin_item_top' => $data['pin_item'],
            'category_id' => $data['category'],
            'cost_price' => $data['cost_price'],
            'selling_price' => $data['price'],
            'can_ship' => $data['can_ship'],
            'can_ship_approval' => $data['can_ship_approval'],
            'free_shipping' => $data['free_ship'],
            'quantity' => $data['quantity'],
            'feature_image' => $data['feature_image'],
            'inventory_note' => $data['product_note'],
            'admin_inventory_note' => $data['admin_product_note'],
            'status' => $data['status'],
            'manifest_id' => $data['manifest_id'],
            'store_location_id' => 1,
            'sale_person_id' => 1,
            'physical_location' => 1,
            'parent_inventory_id' => 0,
            'store_inventory' => json_encode(['store_id' => 1, 'quantity' => $data['quantity'], 'locations' => ['1' => $data['quantity']]])
        ];

        // Remove any null or undefined values from the data map
        $data_map = array_filter($data_map, function ($value) {
            return $value !== null && $value !== '';
        });

        // Insert the data into the database
        if (!$this->db->insert('inventory', $data_map)) {
            // Return error message if insert failed
            $response = array('status' => false, 'message' => 'Error inserting record');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        // Return success message
        $response = array('status' => true, 'message' => 'Record inserted successfully');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function get_products()
    {

        $token = $this->input->get_request_header('x-project');
        if (!$token || $token !== 'bGlxdWlkYXRpb25wcm9kdWN0cmVjb21tZW5kYXRpb246aTlqYnNvaTh6aW56djJ3b29nYWVzZGtuNmRwaGE5bGlt') {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Unauthorized']));
            return;
        }

        $this->db->select('id, product_name, sku, category_id');
        $this->db->from('inventory');
        $this->db->where('is_product', 1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result_array = $query->result_array();
            $result = $result_array;
        } else {
            $result = [false];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }



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

        $data_map = array(
            'product_name' => $data['product_name'],
            'category_id' => $data['category_id'],
            'weight' => $data['weight'],
            'length' => $data['length'],
            'height' => $data['height'],
            'width' => $data['width'],
            'selling_price' => $data['selling_price'],
            'cost_price' => $data['cost_price'],
            'can_ship' => $data['can_ship'],
            'free_ship' => $data['free_ship'],
            'status' => $data['status'],
            'is_product' => $data['is_product'],
            'product_type' => $data['product_type'],
            'physical_location' => 1,
            'store_location_id' => 1,
            'sale_person_id' => 1
        );

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
}
