<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Pos_order_items_model Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Pos_order_items_report_model extends Manaknight_Model
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
        'ID', 'xyzProduct', 'Category', 'xyzProduct', 'Amount', 'xyzPrice', 'Quantity', 'xyzOrder No', 'POS User', 'Store', 'Manifest',
    ];
    protected $_use_timestamps = TRUE;
    protected $_created_field = 'created_at';
    protected $_updated_field = 'updated_at';
    protected $_validation_rules = [
        ['id', 'ID', ''],
        ['product_id', 'xyzProduct', 'required|integer'],
        ['category_id', 'Category', 'required|integer'],
        ['product_name', 'xyzProduct', ''],
        ['amount', 'Amount', 'required'],
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
        ['amount', 'Amount', 'required'],
        ['product_unit_price', 'xyzPrice', 'required'],
        ['quantity', 'Quantity', 'required|integer'],
        ['order_id', 'xyzOrder No', 'required|integer'],
        ['pos_user_id', 'POS User', 'required|integer'],
        ['store_id', 'Store', 'required|integer'],
        ['manifest_id', 'Manifest', 'required|integer'],

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



    public function get_all_pos_order($where = array(), $order_by = array(), $from_date = "", $to_date = "")
    {
        $this->db->select('pos_order_items.*, transactions.tax as transaction_tax');
        $this->db->from($this->_table);


        $this->db->join('transactions', ' pos_order_items.order_id  = transactions.pos_order_id ', 'left');
        foreach ($where as $field => $value) {
            if (is_numeric($field) && strlen($value) > 0) {
                $this->db->where($value);
                continue;
            }

            if ($field === NULL && $value === NULL) {
                continue;
            }

            if ($value !== NULL) {

                $field = $this->clean_alpha_field($field);
                $this->db->where("pos_order_items." . $field, $value, TRUE);
            }
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();

            $this->db->where('transactions.created_at >= ', $from_date);
            $this->db->where('transactions.created_at <= ', $to_date);
            $this->db->group_end();
        }

        if (isset($order_by['column'])  and isset($order_by['sort_by'])) {
            $column_name = $order_by['column'];
            $sort_by     = $order_by['sort_by'];
            $this->db->order_by("pos_order_items." . $column_name, $sort_by);
        }
        return $this->db->get()->result();
    }




    public function get_all_tax($where = array(), $where2 = array(), $order_by = array(), $from_date = "", $to_date = "")
    {
        $this->db->from('transactions');
        $this->db->select_sum('tax');


        foreach ($where as $field => $value) {
            if (is_numeric($field) && strlen($value) > 0) {
                $this->db->where($value);
                continue;
            }

            if ($field === NULL && $value === NULL) {
                continue;
            }

            if ($value !== NULL) {

                $field = $this->clean_alpha_field($field);
                $this->db->where($field, $value, TRUE);
            }
        }

        if (!empty($where2)) {
            foreach ($where2 as $field => $value) {
                $this->db->or_where('transactions.pos_order_id', $value);
            }
        }


        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();

            $this->db->where('transactions.created_at >= ', $from_date);
            $this->db->where('transactions.created_at <= ', $to_date);
            $this->db->group_end();
        }

        if (isset($order_by['column'])  and isset($order_by['sort_by'])) {
            $column_name = $order_by['column'];
            $sort_by     = $order_by['sort_by'];
            $this->db->order_by("transactions." . $column_name, $sort_by);
        }

        $tax = 0;

        $tax_data = $this->db->get()->row();
        if (isset($tax_data->tax)) {
            $tax = $tax_data->tax;
        }
        return $tax;
    }













    public function get_income_from_customer($customer_id, $from_date = "", $to_date = "")
    {
        $this->db->from('transactions');
        $this->db->select_sum('total');

        $this->db->where('customer_id', $customer_id);
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();


            $this->db->where('transactions.created_at >= ', $from_date);
            $this->db->where('transactions.created_at <= ', $to_date);
            $this->db->group_end();
        }


        $total = 0;

        $total_data = $this->db->get()->row();
        if (isset($total_data->total)) {
            $total = $total_data->total;
        }
        return $total;
    }

    public function get_total_qty_sold_to_customer($customer_id, $from_date = "", $to_date = "")
    {
        $this->db->select('pos_order_items.*');
        $this->db->from('pos_order_items');
        $this->db->select_sum('quantity');


        $this->db->join('transactions', ' pos_order_items.order_id  = transactions.pos_order_id ', 'left');

        $this->db->where('transactions.customer_id', $customer_id);
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();


            $this->db->where('transactions.created_at >= ', $from_date);
            $this->db->where('transactions.created_at <= ', $to_date);
            $this->db->group_end();
        }


        $quantity = 0;
        $quantity_data = $this->db->get()->row();
        if (isset($quantity_data->quantity)) {
            $quantity = $quantity_data->quantity;
        }
        return $quantity;
    }








    /**
     * Get paginated model
     *
     * @access public
     * @param integer $page default 0
     * @param integer $limit default 10
     * @return array
     */
    public function get_paginated_shipping_cost_report($page = 0, $limit = 10, $where = [], $order_by = '', $direction = 'ASC', $from_date = '', $to_date = '')
    {


        $this->db->limit($limit, $page);

        if ($order_by === '') {
            $order_by = $this->_primary_key;
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        if (!empty($where)) {
            foreach ($where as $field => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();

            $this->db->where('pos_order.created_at >= ', $from_date);
            $this->db->where('pos_order.created_at <= ', $to_date);
            $this->db->group_end();
        }


        $this->db->group_by('pos_order.shipping_state');
        $query = $this->db->get('pos_order');

        $result = [];

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $result[] = $row;
            }
        }

        return $result;
    }




    public function get_csv_shipping_cost_report($where = [], $order_by = '', $direction = 'ASC', $from_date = '', $to_date = '')
    {


        if ($order_by === '') {
            $order_by = $this->_primary_key;
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        if (!empty($where)) {
            foreach ($where as $field => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();

            $this->db->where('pos_order.created_at >= ', $from_date);
            $this->db->where('pos_order.created_at <= ', $to_date);
            $this->db->group_end();
        }


        $this->db->group_by('pos_order.shipping_state');
        $query = $this->db->get('pos_order');

        $result = [];

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $result[] = $row;
            }
        }

        return $result;
    }



    /**
     * Count number of model
     *
     * @access public
     * @param mixed $parameters
     * @return integer $result
     */
    public function count_shipping_cost_report($parameters, $from_date = '', $to_date = '')
    {

        $this->db->group_by('pos_order.shipping_state');
        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();

            $this->db->where('pos_order.created_at >= ', $from_date);
            $this->db->where('pos_order.created_at <= ', $to_date);
            $this->db->group_end();
        }


        $this->_custom_counting_conditions($this->db);
        $this->db->from('pos_order');
        return $this->db->count_all_results();
    }




    public function get_paginated_for_sale_person($page = 0, $limit = 10, $where = [], $order_by = '', $direction = 'ASC', $from_date = '', $to_date = '')
    {
        $this->db->limit($limit, $page);
        $this->db->group_by("sale_person_id");
        $this->db->where(" (`sale_person_id` != '0' && `sale_person_id` IS NOT NULL ) ");

        if ($order_by === '') {
            $order_by = $this->_primary_key;
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        if (!empty($where)) {
            foreach ($where as $field => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();
            $this->db->where('pos_order_items.created_at >= ', $from_date);
            $this->db->where('pos_order_items.created_at <= ', $to_date);
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



    public function get_paginated_for_sale_person_to_csv($where = [], $order_by = '', $direction = 'ASC', $from_date = '', $to_date = '')
    {
        $this->db->group_by("sale_person_id");
        $this->db->where(" (`sale_person_id` != '0' && `sale_person_id` IS NOT NULL ) ");

        if ($order_by === '') {
            $order_by = $this->_primary_key;
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        if (!empty($where)) {
            foreach ($where as $field => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();
            $this->db->where('pos_order_items.created_at >= ', $from_date);
            $this->db->where('pos_order_items.created_at <= ', $to_date);
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

    public function count_for_sale_person($parameters, $from_date = '', $to_date = '')
    {
        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
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
        }

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->group_start();
            $this->db->where('pos_order_items.created_at >= ', $from_date);
            $this->db->where('pos_order_items.created_at <= ', $to_date);
            $this->db->group_end();
        }

        $this->db->group_by("sale_person_id");
        $this->db->where(" (`sale_person_id` != '0' && `sale_person_id` IS NOT NULL ) ");

        $this->_custom_counting_conditions($this->db);
        $this->db->from($this->_table);
        return $this->db->count_all_results();
    }




    public function get_total_for_sale_person($sale_person_id)
    {
        $this->db->from($this->_table);
        $this->db->select(' SUM(amount) as total_amount,  SUM(quantity) as total_quantity,  SUM(shipping_cost_value) as total_shipping_cost_value,  SUM(item_tax) as total_item_tax ');
        $this->db->where('sale_person_id', $sale_person_id);

        return $this->db->get()->row();
    }

    public function get_refunded_transactions_total($from_date = '', $to_date = '')
    {
        $this->db->from('transactions');
        $this->db->select_sum('total');

        $this->db->where('transaction_type', 2);
        if (!empty($from_date)) {
            $this->db->where('created_at >= ', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('created_at <= ', $to_date);
        }

        $total_data = $this->db->get()->row();
        return $total_data->total;
    }

    public function get_refunded_transactions_tax($from_date = '', $to_date = '')
    {
        $this->db->from('transactions');
        $this->db->select_sum('tax');

        $this->db->where('transaction_type', 2);
        if (!empty($from_date)) {
            $this->db->where('created_at >= ', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('created_at <= ', $to_date);
        }

        $tax_data = $this->db->get()->row();
        return $tax_data->tax;
    }
}
