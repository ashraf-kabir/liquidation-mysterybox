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
    'ID','Product Name','SKU','Category','Manifest','Store Location','Physical Location','Location Description','Weight','Length','Height','Width','Image','Selling Price','Quantity','Inventory Note','xyzBarcode Image','Cost Price','Admin Inventory Note','Can Ship','Pin Item','Product Type','Status',
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
		['barcode_image', 'xyzBarcode Image', ''],
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
		['barcode_image', 'xyzBarcode Image', ''],
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
	public function get_all_inventory_products($where = array(),$available_in_shelf = 1)
    {
        $this->db->from($this->_table);

		 
		
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

            if ($value !== NULL and !empty($value))
            { 
                $this->db->or_like($this->clean_alpha_field($field), $value, 'both');
            }
		}
		
		$this->db->where('available_in_shelf', $available_in_shelf);
        $this->db->order_by('pin_item_top','DESC');
        return $this->db->get()->result();
    }


 

}