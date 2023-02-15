<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Inventory_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Inventory_model extends Manaknight_Model
{
	protected $_table = 'inventory';
	protected $_primary_key = 'id';
	protected $_return_type = 'array';
	protected $_allowed_fields = [
		'id',
		'sale_person_id',
		'product_name',
		'is_product',
		'parent_inventory_id',
		'sku',
		'category_id',
		'manifest_id',
		'store_location_id',
		'physical_location',
		'location_description',
		'available_in_shelf',
		'weight',
		'length',
		'height',
		'width',
		'feature_image',
		'selling_price',
		'quantity',
		'inventory_note',
		'barcode_image',
		'last_sku',
		'cost_price',
		'admin_inventory_note',
		'can_ship',
		'can_ship_approval',
		'pin_item_top',
		'product_type',
		'free_ship',
		'status',
		'video_url',
		'youtube_thumbnail_1',
		'youtube_thumbnail_2',
		'youtube_thumbnail_3',
		'youtube_thumbnail_4',
		'available_in_shelf',
		'store_inventory',
	];
	protected $_label_fields = [
		'ID', 'Product Name', 'SKU', 'Category', 'Manifest', 'Store', 'Inventory Location', 'Inventory Location Description', 'Weight', 'Length', 'Height', 'Width', 'Image', 'Selling Price', 'Quantity', 'Description', 'Barcode Image', 'Cost Price', 'Admin Inventory Note', 'Can Ship', 'Pin Item', 'Product Type', 'Status',
	];
	protected $_use_timestamps = TRUE;
	protected $_created_field = 'created_at';
	protected $_updated_field = 'updated_at';
	protected $_validation_rules = [
		['id', 'ID', ''],
		['product_name', 'Product Name', 'required'],
		['sale_person_id', 'Sale Person', 'required'],
		['item_tax', 'Item Tax', ''],
		['sku', 'SKU', ''],
		['category_id', 'Category', 'required'],
		['manifest_id', 'Manifest', ''],
		// ['store_location_id[]', 'Store', 'required'],
		['locations[]', 'Store Inventory', 'required'],
		['physical_location', 'Inventory Location', ''],
		['location_description', 'Inventory Location Description', ''],
		["is_product", "xyzProduct", ""],
		["parent_inventory_id", "xyzParent Inventory", ""],
		['weight', 'Weight', ''],
		['length', 'Length', ''],
		['height', 'Height', ''],
		['width', 'Width', ''],
		['feature_image', 'Image', ''],
		['selling_price', 'Selling Price', ''],
		['quantity', 'Quantity', ''],
		['inventory_note', 'Description', ''],
		['barcode_image', 'Barcode Image', ''],
		['cost_price', 'Cost Price', ''],
		['admin_inventory_note', 'Admin Inventory Note', ''],
		['can_ship', 'Can Ship', ''],
		['can_ship_approval', 'Can Ship Approval', ''],
		['pin_item_top', 'Pin Item', ''],
		['product_type', 'Product Type', ''],
		['status', 'Status', ''],
		['video_url', 'Video URL', ''],
		['youtube_thumbnail_1', 'Youtube Thumbnail 1', ''],
		['youtube_thumbnail_2', 'Youtube Thumbnail 2', ''],
		['youtube_thumbnail_3', 'Youtube Thumbnail 3', ''],
		['youtube_thumbnail_4', 'Youtube Thumbnail 4', ''],
		['available_in_shelf', 'Available In Shelf', ''],

	];
	protected $_validation_edit_rules = [
		['id', 'ID', ''],
		['product_name', 'Inventory Name', 'required'],
		['sale_person_id', 'Sale Person', 'required'],
		['item_tax', 'Item Tax', ''],
		['sku', 'SKU', ''],
		['category_id', 'Category', 'required'],
		['manifest_id', 'Manifest', ''],
		// ['store_location_id', 'Store', ''],
		['locations[]', 'Store Inventory', 'required'],
		['physical_location', 'Inventory Location', ''],
		['location_description', 'Inventory Location Description', ''],
		["is_product", "xyzProduct", ""],
		["parent_inventory_id", "xyzParent Inventory", ""],
		['weight', 'Weight', ''],
		['length', 'Length', ''],
		['height', 'Height', ''],
		['width', 'Width', ''],
		['feature_image', 'Image', ''],
		['selling_price', 'Selling Price', ''],
		['quantity', 'Quantity', ''],
		['inventory_note', 'Description', ''],
		['last_sku', 'Last SKU', ''],
		['barcode_image', 'Barcode Image', ''],
		['cost_price', 'Cost Price', ''],
		['admin_inventory_note', 'Admin Inventory Note', ''],
		['can_ship', 'Can Ship', ''],
		['can_ship_approval', 'Can Ship Approval', ''],
		['pin_item_top', 'Pin Item', ''],
		['product_type', 'Product Type', ''],
		['video_url', 'Video URL', ''],
		['status', 'Status', ''],
		['available_in_shelf', 'Available In Shelf', ''],
		['youtube_thumbnail_1', 'Youtube Thumbnail 1', ''],
		['youtube_thumbnail_2', 'Youtube Thumbnail 2', ''],
		['youtube_thumbnail_3', 'Youtube Thumbnail 3', ''],
		['youtube_thumbnail_4', 'Youtube Thumbnail 4', ''],

	];
	protected $_validation_messages = [];

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * If you need to modify payload before create, overload this function
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	protected function _pre_create_processing($data)
	{

		return $data;
	}

	/**
	 * If you need to modify payload before edit, overload this function
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	protected function _post_edit_processing($data)
	{

		return $data;
	}

	/**
	 * Allow user to add extra counting condition so user don't have to change main function
	 *
	 * @param mixed $parameters
	 * @return $db
	 */
	protected function _custom_counting_conditions(&$db)
	{

		return $db;
	}


	public function status_mapping()
	{
		return [
			3 => 'Waiting Approval',
			2 => 'Pending',
			1 => 'Active',
			0 => 'Inactive',
		];
	}



	public function can_ship_mapping()
	{
		return [
			1 => 'Delivery Or Pickup',
			2 => 'Pickup only',
			3 => 'Shipping only'
		];
	}

	public function can_ship_approval_mapping()
	{
		return [
			1 => 'Yes',
			2 => 'No',
		];
	}


	public function free_ship_mapping()
	{
		return [
			1 => 'Yes',
			2 => 'No',
		];
	}

	public function product_type_mapping()
	{
		return [
			1 => 'Inventory',
			2 => 'Non Inventory',
		];
	}

	public function pin_item_top_mapping()
	{
		return [
			1 => 'No',
			2 => 'Yes',
		];
	}

	public function available_in_shelf_mapping()
	{
		return [
			1 => 'No',
			2 => 'Yes',
		];
	}


	/**
	 * Get all using or like Model
	 *
	 * @return array
	 */
	public function get_all_inventory_products($search_product_value = false, $where2 = array(), $page = 0, $limit = 10)
	{
		$this->db->from($this->_table);

		$this->db->limit($limit, $page);

		if ($search_product_value) {
			$this->db->group_start();

			$this->db->or_like($this->clean_alpha_field('product_name'), $search_product_value, 'both');
			$this->db->or_like($this->clean_alpha_field('sku'), $search_product_value, 'both');

			$this->db->group_end();
		}



		if (!empty($where2)) {
			$this->db->group_start();
			foreach ($where2 as $field => $value) {
				$this->db->where($field, $value);
			}
			$this->db->group_end();
		}

		$this->db->order_by('pin_item_top', 'DESC');
		return $this->db->get()->result();
	}


	/**
	 * Get Model using table and field
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function get_by_table($table, $field, $value)
	{
		$this->db->from($table);
		$this->db->where($field, $value, TRUE);
		return $this->db->get()->row();
	}


	public function update_by_table($table, $field, $value, $data)
	{
		$this->db->where($field, $value, TRUE);
		$updateResult = $this->db->update($table, $data);
		return $updateResult ? true : false;
	}

	// public function get_itm_by_product($product_id)
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('inventory');
	// 	$this->db->where(array('parent_inventory_id' => $product_id, 'status' => 1));
	// 	$this->db->order_by('id', 'ASC');
	// 	$this->db->limit(1);
	// 	$query = $this->db->get();
	// 	$result = $query->row();



	// 	return $result;
	// }

	public function get_itm_by_product($product_id)
	{
		$this->db->select('*');
		$this->db->from('inventory');
		$this->db->where(array('parent_inventory_id' => $product_id, 'status' => array(1, 2)));
		$this->db->order_by('id', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();

		return $result;
	}



	public function get_product_details_by_id($product_id)
	{
		$this->db->select('*');
		$this->db->from('inventory');
		$this->db->where('id', $product_id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_cart_total($user_id, $product_id)
	{
		$this->db->select_sum('product_qty');
		$this->db->where('customer_id', $user_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('pos_cart');
		return $query->row()->product_qty;
	}






	public function update_itm_status($itm_id)
	{
		// update the status of retrieved items
		$data = array(
			'status' => 0
		);

		$this->db->from('inventory');
		$this->db->where(array('id' => $itm_id));
		$this->db->update('inventory', $data);
	}





	/**
	 * Count number of model
	 *
	 * @access public
	 * @param mixed $parameters
	 * @return integer $result
	 */

	public function get_custom_count($parameters, $parameters2 = [])
	{
		if (!empty($parameters)) {
			foreach ($parameters as $key => $value) {
				if ($key == 'product_name' or $key == 'sku') {
					continue;
				}

				if (is_numeric($key) && strlen($value) > 0) {
					$this->db->where($value);
					continue;
				}

				if ($key === NULL && $value === NULL) {
					continue;
				}

				if (!is_null($value)) {
					if (is_numeric($value)) {
						$this->db->where($key, $value);
						continue;
					}

					if (is_string($value)) {
						$this->db->like($key, $value);
						continue;
					}

					$this->db->where($key, $value);
				}
			}

			if (isset($parameters['product_name']) and !empty($parameters['product_name'])) {
				$this->db->group_start();
				$this->db->or_like('product_name', $parameters['product_name'], 'both');
				$this->db->or_like('sku', $parameters['sku'], 'both');
				$this->db->group_end();
			}
		}

		if (isset($parameters2) and !empty($parameters2)) {
			$this->db->group_start();
			foreach ($parameters2 as $key => $value) {
				$this->db->or_where('category_id', $value['category_id']);
			}
			$this->db->group_end();
		}

		$this->_custom_counting_conditions($this->db);
		$this->db->from($this->_table);
		return $this->db->count_all_results();
	}





	/**
	 * Get paginated model
	 *
	 * @access public
	 * @param integer $page default 0
	 * @param integer $limit default 10
	 * @return array
	 */
	public function get_custom_paginated($page = 0, $limit = 10, $where = [], $order_by = '', $direction = 'ASC', $parameters2 = [])
	{
		$this->db->limit($limit, $page);

		if ($order_by === '') {
			$order_by = $this->_primary_key;
		}

		$this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

		if (!empty($where)) {
			foreach ($where as $field => $value) {
				if ($field == 'product_name' or $field == 'sku') {
					continue;
				}
				if (is_numeric($field) && strlen($value) > 0) {
					$this->db->where($value);
					continue;
				}

				if ($field === NULL && $value === NULL) {
					continue;
				}

				if ($value !== NULL) {
					if (is_numeric($value)) {
						$this->db->where($field, $value);
						continue;
					}

					if (is_string($value)) {
						$this->db->like($field, $value);
						continue;
					}

					$this->db->where($field, $value);
				}
			}


			if (isset($where['product_name']) and !empty($where['product_name'])) {
				$this->db->group_start();
				$this->db->or_like('product_name', $where['product_name'], 'both');
				$this->db->or_like('sku', $where['sku'], 'both');
				$this->db->group_end();
			}
		}


		if (isset($parameters2) and !empty($parameters2)) {
			$this->db->group_start();
			foreach ($parameters2 as $key => $value) {
				$this->db->or_where('category_id', $value['category_id']);
			}
			$this->db->group_end();
		}


		$query = $this->db->get($this->_table);
		$result = [];

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$result[] = $row;
			}
		}

		return $result;
	}

	public function get_store_inventory_data($store_inventory = [], $store_id = null)
	{

		foreach ($store_inventory as $key => $store_data) {
			if ($store_id == $store_data->store_id) {
				return $store_data;
			}
		}

		return null;
	}
}
