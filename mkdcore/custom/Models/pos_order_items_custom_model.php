<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Pos_order_items_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Pos_order_items_custom_model extends Manaknight_Model
{
	protected $_table = 'pos_order_items';
	protected $_primary_key = 'id';
	protected $_return_type = 'array';
	protected $_allowed_fields = [
        'id',
		'product_id',
		'category_id',
		'product_name',
		'amount',
		'product_unit_price',
		'quantity',
		'order_id',
		'pos_user_id',
		'store_id',
		'manifest_id',
		
    ];
	protected $_label_fields = [
    'ID','xyzProduct','Category','xyzProduct','xyzAmount','xyzPrice','Quantity','xyzOrder No','POS User','Store','Manifest',
    ];
	protected $_use_timestamps = TRUE;
	protected $_created_field = 'created_at';
	protected $_updated_field = 'updated_at';
	protected $_validation_rules = [
        ['id', 'ID', ''],
		['product_id', 'xyzProduct', 'required|integer'],
		['category_id', 'Category', 'required|integer'],
		['product_name', 'xyzProduct', ''],
		['amount', 'xyzAmount', 'required'],
		['product_unit_price', 'xyzPrice', 'required'],
		['quantity', 'Quantity', 'required|integer'],
		['order_id', 'xyzOrder No', 'required|integer'],
		['pos_user_id', 'POS User', 'required|integer'],
		['store_id', 'Store', 'required|integer'],
		['manifest_id', 'Manifest', 'required|integer'],
		
    ];
	protected $_validation_edit_rules = [
        ['id', 'ID', ''],
		['product_id', 'xyzProduct', 'required|integer'],
		['category_id', 'Category', 'required|integer'],
		['product_name', 'xyzProduct', ''],
		['amount', 'xyzAmount', 'required'],
		['product_unit_price', 'xyzPrice', 'required'],
		['quantity', 'Quantity', 'required|integer'],
		['order_id', 'xyzOrder No', 'required|integer'],
		['pos_user_id', 'POS User', 'required|integer'],
		['store_id', 'Store', 'required|integer'],
		['manifest_id', 'Manifest', 'required|integer'],
		
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



    public function get_all_pos_order($where = array(),$order_by = array())
    {
        $this->db->from($this->_table);


        $this->db->join('transactions', ' pos_order_items.order_id  = transactions.pos_order_id ','left');
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
                if($field == 'transaction_date')
                {
                    $field = $this->clean_alpha_field($field);
                    $this->db->where($field, $value, TRUE);
                }else{
                    $field = $this->clean_alpha_field($field);
                    $this->db->where("pos_order_items." . $field, $value, TRUE);
                }
                
            }
        }

        if( isset($order_by['column'])  AND isset($order_by['sort_by']) )
        {
            $column_name = $order_by['column'];
            $sort_by     = $order_by['sort_by']; 
            $this->db->order_by("pos_order_items." .$column_name, $sort_by);
        } 
        return $this->db->get()->result();
    }
    


    public function do_nothing()
    {

    }

}