<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once dirname(__FILE__) . '/Mapping.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Abstract Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class {{{subclass_prefix}}}Model extends CI_Model
{
    protected $_mapping;
    protected $_table = '';
    protected $_primary_key = 'id';
    protected $_return_type = 'array';
    protected $_allowed_fields = [];
    protected $_label_fields = [];
    protected $_use_timestamps = FALSE;
    protected $_created_field = 'created_at';
    protected $_updated_field = 'updated_at';
    protected $_validation_rules = [];
    protected $_validation_messages = [];

    public function __construct()
    {
        parent::__construct();
        $this->_mapping = new Mapping();
    }

    /**
     * Get Mapping Delegate
     *
     * @return mixed
     */
    public function get_mapping()
    {
        return $this->_mapping;
    }

	/**
	 * Raw Mysql query
	 *
	 * @param string $sql
	 * @return mixed
	 */
	public function raw_query($sql)
	{
		return $this->db->query($sql);
    }

    /**
	 * Raw no error query for writes
	 *
	 * @param string $sql
	 * @return mixed
	 */
	public function raw_no_error_query($sql)
	{
		return $this->db->simple_query($sql);
    }

    /**
	 * Raw Mysql query
	 *
	 * @param string $sql
	 * @return mixed
	 */
	public function raw_prepare_query($sql, $parameters)
	{
		return $this->db->query($sql, $parameters);
    }

    /**
	 * Get Model
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function get($id)
    {
		$this->db->from($this->_table);
        $this->db->where($this->_primary_key, $id, TRUE);
        return $this->db->get()->row();
	}

    /**
	 * Get Model by field
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return mixed
	 */
	public function get_by_field($field, $value)
    {
		$this->db->from($this->_table);
        $this->db->where($this->clean_alpha_field($field), $value, TRUE);
        return $this->db->get()->row();
	}

    /**
	 * Get Model by fields
	 *
	 * @param string $field
	 * @param mixed $value
	 * @return mixed
	 */
	public function get_by_fields($where)
    {
        $this->db->from($this->_table);

        if (!empty($where))
        {
            foreach($where as $field => $value)
            {
                if (is_numeric($field) && strlen($value) > 0)
                {
                    $this->db->where($value);
                    continue;
                }

                if ($value === NULL)
				{
					continue;
				}

                if ($value !== NULL)
                {
                    $this->db->where($this->clean_alpha_field($field), $value, TRUE);
				}

            }
        }

        return $this->db->get()->row();
	}

	/**
	 * Get all Model
	 *
	 * @return array
	 */
	public function get_all($where = array())
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

            if ($value !== NULL)
            {
                $this->db->where($this->clean_alpha_field($field), $value, TRUE);
            }
        }

        return $this->db->get()->result();
	}

    /**
	 * Get all Model
	 *
	 * @return array
	 */
	public function get_all_by_status($status)
    {
        $this->db->from($this->_table);
        $this->db->where('status', $status, TRUE);
        return $this->db->get()->result();
	}

    /**
	 * Get all Model key value
	 *
	 * @return array
	 */
	public function get_all_by_key_value($field, $status)
    {
        $this->db->from($this->_table);
        $this->db->where('status', $status, TRUE);

        $data = [];
        $results = $this->db->get()->result();

        foreach ($results as $key => $value)
        {
            $data[$value->id] = $value->{$field};
        }

        return $data;
	}

	/**
	 * Create
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function create($data)
	{
        if ($this->_use_timestamps)
        {
            $data[$this->_created_field] = date('Y-m-j');
            $data[$this->_updated_field] = date('Y-m-j H:i:s');
        }

        $data = $this->_pre_create_processing($data);

        $inserted_row = $this->db->insert($this->_table, $this->_filter_allow_keys($data), TRUE);

        if ($inserted_row)
        {
            return $this->db->insert_id();
        }

        return FALSE;
	}

    /**
     * Bulk Create
     *
     * @param [type] $params
     * @return void
     */
    public function batch_insert($params)
	{
        if ($this->_use_timestamps)
        {
            foreach ($params as $key => $value)
            {
                $params[$key][$this->_created_field] = date('Y-m-j');
                $params[$key][$this->_updated_field] = date('Y-m-j H:i:s');
            }
        }

        return $this->db->insert_batch($this->_table, $params);
    }

	/**
	 * Edit {{{upper_case_model}}}
	 * @param array $data
	 * @param integer $id
	 * @return bool
	 */
	public function edit($data, $id)
	{
        if ($this->_use_timestamps)
        {
            $data[$this->_updated_field] = date('Y-m-j H:i:s');
        }

        $data = $this->_post_edit_processing($data);

        $this->db->where($this->_primary_key, $id, TRUE);

		return $this->db->update($this->_table, $this->_filter_allow_keys($data));
    }

    /**
	 * Edit {{{upper_case_model}}}
	 * @param array $data
	 * @param integer $id
	 * @return bool
	 */
	public function edit_raw($data, $id)
	{
        if ($this->_use_timestamps)
        {
            $data[$this->_updated_field] = date('Y-m-j H:i:s');
        }

        $this->db->where($this->_primary_key, $id, TRUE);

		return $this->db->update($this->_table, $this->_filter_allow_keys($data));
    }

    /**
	 * Soft Delete Model
	 * @param array $data
	 * @param integer $id
	 * @return bool
	 */
	public function delete($id)
	{
        $data = [];

        if ($this->_use_timestamps)
        {
            $data[$this->_updated_field] = date('Y-m-j H:i:s');
            $data['status'] = 0;
        }

        $this->db->where($this->_primary_key, $id, TRUE);

		return $this->db->update($this->_table, $this->_filter_allow_keys($data));
    }

    /**
	 * Real Delete Model
	 * @param integer $id
	 * @return bool
	 */
	public function real_delete($id)
	{
		return $this->db->delete($this->_table, ['id' => $id]);
    }

    /**
	 * Real Delete Model
	 * @param integer $id
	 * @return bool
	 */
	public function real_delete_by_fields($where=[])
	{
        $payload = [];

        if (!empty($where))
        {
            foreach($where as $field => $value)
            {
                if (is_numeric($field) && strlen($value) > 0)
                {
                    $payload[] = $value;
                    continue;
                }

                if ($value === null)
				{
					continue;
				}

                if ($value !== NULL)
                {
                    $payload[$field] = $value;
				}

            }
        }
		return $this->db->delete($this->_table, $payload);
    }

    /**
	 * Real Delete Model
	 * @param integer $id
	 * @return bool
	 */
	public function real_delete_all()
	{
		return $this->db->truncate($this->_table);
    }

    /**
     * Debug json Model to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dj($key, $data)
    {
        if (ENVIRONMENT == 'development') {
            error_log($key . ' MODEL : ' . json_encode($data));
        }
    }

    /**
     * Debug Model to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dl($key, $data)
    {
        if (ENVIRONMENT == 'development') {
            error_log($key . ' MODEL: ' . '<pre>' . print_r($data, TRUE) . '</pre>');
        }
    }

    /**
     * Get All Validation Rules
     *
     * @param string $key
     * @return array
     */
    public function get_all_validation_rule ()
    {
        return $this->_validation_rules;
    }

    /**
     * Get All Edit Validation Rules
     *
     * @param string $key
     * @return array
     */
    public function get_all_edit_validation_rule ()
    {
        return $this->_validation_edit_rules;
    }

    /**
     * Fill validation rules
     *
     * @param mixed $form_validation
     * @param mixed $validation_rules
     * @return void
     */
    public function set_form_validation($form_validation, $validation_rules)
    {
        $rules = $validation_rules;

        $messages = $this->_validation_messages;

        foreach ($rules as $key => $value)
        {
            $error_object = [];

            if (array_key_exists($key, $messages))
            {
                $error_object = $messages[$key];
            }

            $form_validation->set_rules($value[0], $value[1], $value[2], $error_object);
        }

        return $form_validation;
    }

    /**
	 * Count number of model
	 *
	 * @access public
     * @param mixed $parameters
	 * @return integer $result
	 */
	public function count($parameters)
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
	public function get_paginated($page = 0, $limit=10, $where=[], $order_by='', $direction='ASC')
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

    public function _join ($table, $field, $where, $custom_duplicate_names=[])
    {
        $select_statement = '`a`.id as a_id, `b`.id as b_id,' ;

        $duplicate_names = [
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'type',
            // 'image',
            // 'data',
            // 'slug',
            // 'description',
            // 'amount',
            // 'subtotal',
            // 'total',
            // 'lat',
            // 'lng',
            // 'address',
            // 'state',
            // 'country',
            // 'city',
            // 'order'
        ];

        if (count($custom_duplicate_names) > 0)
        {
            $duplicate_names = $custom_duplicate_names;
        }

        foreach ($this->_allowed_fields as $key => $value)
        {
            if (in_array($value, $duplicate_names))
            {
                $select_statement .= "`a`.{$value} as a_{$value}, `b`.{$value} as b_{$value}," ;
            }
        }

        $this->db->select($select_statement . ' `a`.*, `b`.*');
        $this->db->from($this->_table . ' a');
        $this->db->join($table . ' b', "a.{$field} = b.id");

        if (!empty($where))
        {
            foreach ($where as $key => $value)
            {
                $this->db->where($key, $value);
            }
        }

        $result = [];
        $query = $this->db->get();
        foreach ($query->result() as $row)
        {
            $result[] = $row;
        }

        return $result;
    }

    public function _join_paginate ($table, $field, $where, $page = 0, $limit = 10, $order_by='', $direction='ASC', $custom_duplicate_names=[])
    {
        $select_statement = '`a`.id as a_id, `b`.id as b_id,' ;

        $duplicate_names = [
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'type',
            // 'image',
            // 'data',
            // 'slug',
            // 'description',
            // 'amount',
            // 'subtotal',
            // 'total',
            // 'lat',
            // 'lng',
            // 'address',
            // 'state',
            // 'country',
            // 'city',
            // 'order'
        ];

        if (count($custom_duplicate_names) > 0)
        {
            $duplicate_names = $custom_duplicate_names;
        }

        foreach ($this->_allowed_fields as $key => $value)
        {
            if (in_array($value, $duplicate_names))
            {
                $select_statement .= "`a`.{$value} as a_{$value}, `b`.{$value} as b_{$value}," ;
            }
        }

        $this->db->select($select_statement . ' `a`.*, `b`.*');
        $this->db->from($this->_table . ' a');
        $this->db->join($table . ' b', "a.{$field} = b.id");

        if (!empty($where))
        {
            foreach ($where as $key => $value)
            {
                if ($value === NULL)
				{
					continue;
				}

                if (is_numeric($key) && strlen($value) < 1)
                {
                    $this->db->where($value);
                    continue;
                }

                if ($value !== NULL)
                {
                    $this->db->where($key, $value);
				}

            }
        }

        $result = [];

        $this->db->limit($limit, $page);

        if ($order_by === '')
        {
            $order_by = 'a_id';
        }

        $this->db->order_by($this->clean_alpha_num_field($order_by), $this->clean_alpha_field($direction));

        $query = $this->db->get();
        foreach ($query->result() as $row)
        {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Filter all keys before inserting to make sure they are allowed
     *
     * @param mixed $data
     * @return mixed
     */
    protected function _filter_allow_keys ($data)
    {
        $clean_data = [];
        $allowed_fields = $this->_allowed_fields;
        $allowed_fields[] = $this->_primary_key;

        if($this->_use_timestamps)
        {
            $allowed_fields[] = $this->_created_field;
            $allowed_fields[] = $this->_updated_field;
        }

        foreach ($data as $key => $val)
        {
            if (!in_array($key, $allowed_fields))
            {
                continue;
            }
            $clean_data[$key] = $val;
        }
        return $clean_data;
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
        return $this->db;
    }

    /**
     * Escape data
     *
     * @param mixed $data
     * @return mixed
     */
    public function escape ($data)
    {
        return $this->db->escape($data);
    }

    /**
     * Get Last ID in table
     *
     * @return integer
     */
    public function get_last_id()
    {
        $last = $this->db->order_by('id','desc')
		->limit(1)
		->get($this->_table)
        ->row();

        if ($last)
        {
            return (int)$last->id;
        }

        return FALSE;
    }

    /**
     * Get Database Table Schema
     *
     * @return mixed
     */
    public function get_schema()
    {
        return $this->db->field_data($this->_table);
    }

    /**
     * Get Type Mapping
     *
     * @param string $text
     * @param string $database_expected_field
     * @return boolean
     */
    public function verify_field_type ($text, $database_expected_field)
    {
        $type_mapping = [
            'string' => ['varchar', 'text', 'char', 'tinytext','mediumtext','longtext', 'binary', 'blob', 'enum'],
            'integer' => ['int', 'tinyint', 'bigint', 'mediumint', 'bit', 'timestamp', 'year'],
            'float' => ['float','decimal', 'double'],
            'date' => ['date'],
            'datetime' => ['datetime'],
            'time' => ['time']
        ];

        $field_allow = $type_mapping['string'];

        if (in_array($database_expected_field, $type_mapping['string']))
        {
            return TRUE;
        }

        if (preg_match("/^[0-9]+$/i", $text))
        {
            // $this->dl('integer', $text);
            $field_allow = $type_mapping['integer'];
        }
        elseif (is_numeric($text))
        {
            // $this->dl('float', $text);
            $field_allow = $type_mapping['float'];
        }
        elseif ($this->validate_date($text, 'Y-m-d'))
        {
            // $this->dl('date', $text);
            $field_allow = $type_mapping['date'];
        }
        elseif ($this->validate_date($text, 'Y-m-d H:i:s'))
        {
            // $this->dl('datetime', $text);
            $field_allow = $type_mapping['datetime'];
        }
        elseif ($this->validate_date($text, 'H:i:s'))
        {
            // $this->dl('time', $text);
            $field_allow = $type_mapping['time'];
        }

        // $this->dl('keys', implode(',', $field_allow));

        if (in_array($database_expected_field,$field_allow))
        {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Validate string matches date
     *
     * @param string $date
     * @param string $format
     * @return boolean
     */
    protected function validate_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }


    protected function clean_alpha_num_field ($text)
    {
        return preg_replace("/[^0-9a-zA-Z\_]/", "", $text );
    }

    protected function clean_alpha_field ($text)
    {
        return preg_replace("/[^a-zA-Z\_]/", "", $text );
    }
}