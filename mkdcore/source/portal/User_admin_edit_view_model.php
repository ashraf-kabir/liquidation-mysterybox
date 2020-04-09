<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Edit User View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class User_admin_edit_view_model
{
    protected $_entity;
	protected $_id;
	protected $_email;
	protected $_first_name;
	protected $_last_name;
	protected $_password;
	protected $_phone;
	protected $_role_id;
	protected $_image;
	protected $_image_id;
	protected $_status;


    public function __construct($entity)
    {
        $this->_entity = $entity;
    }

    public function get_entity ()
    {
        return $this->_entity;
    }

    /**
     * set_heading function
     *
     * @param string $heading
     * @return void
     */
    public function set_heading ($heading)
    {
        $this->_heading = $heading;
    }

    /**
     * get_heading function
     *
     * @return string
     */
    public function get_heading ()
    {
        return $this->_heading;
    }

    public function set_model ($model)
    {
        $this->_model = $model;
		$this->_id = $model->id;
		$this->_email = $model->email;
		$this->_first_name = $model->first_name;
		$this->_last_name = $model->last_name;
		$this->_password = $model->password;
		$this->_phone = $model->phone;
		$this->_role_id = $model->role_id;
		$this->_image = $model->image;
		$this->_status = $model->status;

    }

	public function verify_mapping ()
	{
		return $this->_entity->verify_mapping();

	}

	public function status_mapping ()
	{
		return $this->_entity->status_mapping();

	}

	public function role_id_mapping ()
	{
		return $this->_entity->role_id_mapping();

	}

	public function get_email ()
	{
		return $this->_email;
	}

	public function set_email ($email)
	{
		$this->_email = $email;
	}

	public function get_first_name ()
	{
		return $this->_first_name;
	}

	public function set_first_name ($first_name)
	{
		$this->_first_name = $first_name;
	}

	public function get_last_name ()
	{
		return $this->_last_name;
	}

	public function set_last_name ($last_name)
	{
		$this->_last_name = $last_name;
	}

	public function get_password ()
	{
		return $this->_password;
	}

	public function set_password ($password)
	{
		$this->_password = $password;
	}

	public function get_phone ()
	{
		return $this->_phone;
	}

	public function set_phone ($phone)
	{
		$this->_phone = $phone;
	}

	public function get_role_id ()
	{
		return $this->_role_id;
	}

	public function set_role_id ($role_id)
	{
		$this->_role_id = $role_id;
	}

	public function get_image ()
	{
		return $this->_image;
	}

	public function set_image ($image)
	{
		$this->_image = $image;
	}

	public function get_image_id ()
	{
		return $this->_image_id;
	}

	public function set_image_id ($image)
	{
		$this->_image_id = $image;
	}

	public function get_status ()
	{
		return $this->_status;
	}

	public function set_status ($status)
	{
		$this->_status = $status;
	}

	public function get_id ()
	{
		return $this->_id;
	}

	public function set_id ($id)
	{
		$this->_id = $id;
	}

}