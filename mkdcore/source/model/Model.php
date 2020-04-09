<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{upper_case_model}}} Model
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{upper_case_model}}} extends {{{subclass_prefix}}}Model
{
	protected $_table = '{{{name}}}';
	protected $_primary_key = 'id';
	protected $_return_type = 'array';
	protected $_allowed_fields = [
    {{{allowed_fields}}}
    ];
	protected $_label_fields = [
    {{{labels}}}
    ];
	protected $_use_timestamps = {{{timestamp}}};
	protected $_created_field = 'created_at';
	protected $_updated_field = 'updated_at';
	protected $_validation_rules = [
    {{{validation_rules}}}
    ];
	protected $_validation_edit_rules = [
    {{{validation_edit_rules}}}
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
        {{{pre}}}
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
        {{{post}}}
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
        {{{count}}}
        return $db;
    }

{{{mapping}}}
{{{method}}}
{{{join}}}
}