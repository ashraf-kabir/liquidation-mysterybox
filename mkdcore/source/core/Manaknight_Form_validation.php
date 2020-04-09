<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Form Validation
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class {{{subclass_prefix}}}Form_validation extends CI_Form_validation
{
    public function error_array()
    {
        return $this->_error_array;
    }

	/**
	 * Exist
	 *
	 * Check if the input value exist in system
	 *
	 * @param	string	$str
	 * @param	string	$field
	 * @return	bool
	 */
	public function exist($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 1)
			: FALSE;
    }

    /**
     * Greater Than
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function greater_than($str, $min)
    {
        if (!is_numeric($str))
        {
            return FALSE;
        }

        return $str > $min;
    }

    /**
     * Less Than
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function less_than($str, $max)
    {
        if (!is_numeric($str))
        {
            return FALSE;
        }

        return $str < $max;
    }

    /**
     * Equal To
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function equal_to($str, $eq)
    {
        if (!is_numeric($str))
        {
            return FALSE;
        }

        return $str == $eq;
    }

    /**
     * Max Count
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function max_count($str, $max)
    {
        return count($str) <= $max;
    }

    /**
     * Min Count
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function min_count($str, $min)
    {
        return count($str) >= $min;
    }

    /**
     * Allowed domain email
     *
     * @param string $str
     * @param number $min
     * @return bool
     */
    public function domain_email($str, $params)
    {
        $parts = explode("@", $str);
        $domain = $parts[1];
        $allowed_array = explode(',', $params);
        return in_array($domain, $allowed_array);
    }
}