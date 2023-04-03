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


class Admin_manifest_controller extends Admin_controller
{

    public function index()
    {
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
        $url = 'https://mkdlabs.com/v3/api/custom/liquidationproductrecommendation/sales_channel/start_process';
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
}
