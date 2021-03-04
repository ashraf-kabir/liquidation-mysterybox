<?php defined('BASEPATH') OR exit('No direct script access allowed');
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
		'product_name',
		'sku',
		'category_id',
		'manifest_id',
		'store_location_id',
		'physical_location',
		'location_description',
		'weight',
		'length',
		'height',
		'width',
		'feature_image',
		'selling_price',
		'quantity',
		'inventory_note',
		'barcode_image',
		'cost_price',
		'admin_inventory_note', 
		'can_ship', 
		'pin_item_top',
		'product_type',
		'status',
		'available_in_shelf',
		
    ];
	protected $_label_fields = [
    'ID','Product Name','SKU','Category','Manifest','Store Location','Physical Location','Location Description','Weight','Length','Height','Width','Image','Selling Price','Quantity','Inventory Note','Barcode Image','Cost Price','Admin Inventory Note','Can Ship','Pin Item','Product Type','Status',
    ];
	protected $_use_timestamps = TRUE;
	protected $_created_field = 'created_at';
	protected $_updated_field = 'updated_at';
	protected $_validation_rules = [
    ['id', 'ID', ''],
		['product_name', 'Product Name', 'required'],
		['sku', 'SKU', ''],
		['category_id', 'Category', ''],
		['manifest_id', 'Manifest', ''],
		['store_location_id', 'Store Location', 'required'],
		['physical_location', 'Physical Location', ''],
		['location_description', 'Location Description', ''],
		['weight', 'Weight', ''],
		['length', 'Length', ''],
		['height', 'Height', ''],
		['width', 'Width', ''],
		['feature_image', 'Image', ''],
		['selling_price', 'Selling Price', ''],
		['quantity', 'Quantity', ''],
		['inventory_note', 'Inventory Note', ''],
		['barcode_image', 'Barcode Image', ''],
		['cost_price', 'Cost Price', ''],
		['admin_inventory_note', 'Admin Inventory Note', ''], 
		['can_ship', 'Can Ship', ''], 
		['pin_item_top', 'Pin Item', ''],
		['product_type', 'Product Type', ''],
		['status', 'Status', ''],
		['available_in_shelf', 'Available In Shelf', ''],
		
    ];
	protected $_validation_edit_rules = [
    	['id', 'ID', ''],
		['product_name', 'Product Name', 'required'],
		['sku', 'SKU', ''],
		['category_id', 'Category', ''],
		['manifest_id', 'Manifest', ''],
		['store_location_id', 'Store Location', 'required'],
		['physical_location', 'Physical Location', ''],
		['location_description', 'Location Description', ''],
		['weight', 'Weight', ''],
		['length', 'Length', ''],
		['height', 'Height', ''],
		['width', 'Width', ''],
		['feature_image', 'Image', ''],
		['selling_price', 'Selling Price', ''],
		['quantity', 'Quantity', ''],
		['inventory_note', 'Inventory Note', ''],
		['barcode_image', 'Barcode Image', ''],
		['cost_price', 'Cost Price', ''],
		['admin_inventory_note', 'Admin Inventory Note', ''],
		['can_ship', 'Can Ship', ''], 
		['pin_item_top', 'Pin Item', ''],
		['product_type', 'Product Type', ''],
		['status', 'Status', ''],
		['available_in_shelf', 'Available In Shelf', ''],
		
    ];
	protected $_validation_messages = [

    ];

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


	public function status_mapping ()
	{
		return [
			1 => 'Active',
			0 => 'Inactive',
		];
	}

	 

	public function can_ship_mapping ()
	{
		return [
			1 => 'Yes',
			2 => 'No',
		];
	}

	public function product_type_mapping ()
	{
		return [
			1 => 'Regular',
			2 => 'Generic',
		];
	}

	public function pin_item_top_mapping ()
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
	public function get_all_inventory_products($search_product_value = false, $where2 = array(), $page = 0, $limit=10)
    {
        $this->db->from($this->_table);

		$this->db->limit($limit, $page);
		
		if($search_product_value)
		{ 
			$this->db->group_start();
			 
			$this->db->or_like($this->clean_alpha_field('product_name'), $search_product_value, 'both');
			$this->db->or_like($this->clean_alpha_field('sku'), $search_product_value, 'both');
			 
			$this->db->group_end();
		}
		
 

		if(!empty($where2))
		{ 
			$this->db->group_start();
			foreach($where2 as $field => $value)
			{
				$this->db->where($field, $value);
			}  
			$this->db->group_end();
		}

        $this->db->order_by('pin_item_top','DESC');
        return $this->db->get()->result();
    }


	 /**
	 * Count number of model
	 *
	 * @access public
     * @param mixed $parameters
	 * @return integer $result
	 */

	public function get_custom_count($parameters)
	{
        if (!empty($parameters))
        {
            foreach ($parameters as $key => $value)
            {
                if (is_numeric($key) && strlen($value) > 0)
                {
                    $this->db->where($value);
                    continue;
                }

                if ($key === NULL && $value === NULL)
				{
					continue;
                }

                if (!is_null($value))
                {
                    if(is_numeric($value))
                    {
                        $this->db->where($key, $value);
                        continue;
                    }

                    if(is_string($value))
                    {
                        $this->db->like($key, $value);
                        continue;
                    }

                    $this->db->where($key, $value);
                }
            }
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
	public function get_custom_paginated($page = 0, $limit=10, $where=[], $order_by='', $direction='ASC')
    {
        $this->db->limit($limit, $page);

        if ($order_by === '')
        {
            $order_by = $this->_primary_key;
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        if (!empty($where))
        {
            foreach($where as $field => $value)
            {
                if (is_numeric($field) && strlen($value) > 0)
                {
                    $this->db->where($value);
                    continue;
                }

                if ($field === NULL && $value === NULL)
				{
					continue;
				}

                if ($value !== NULL)
                {
                    if(is_numeric($value))
                    {
                        $this->db->where($field, $value);
                        continue;
                    }

                    if(is_string($value))
                    {
                        $this->db->like($field, $value);
                        continue;
                    }

                    $this->db->where($field, $value);
				}
            }
        }

        $query = $this->db->get($this->_table);
		$result = [];

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $result[] = $row;
            }
		}

        return $result;
    }

}