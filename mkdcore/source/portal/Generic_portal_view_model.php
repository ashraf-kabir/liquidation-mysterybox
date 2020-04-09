<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Edit {{{uc_name}}} View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class {{{uc_portal}}}_profile_view_model
{
    protected $_entity;
    protected $_credential_id;
    
    {{{fields}}}

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

    public function set_model ($model)
    {
        $this->_credential_id = $model->credential_id;
        {{{set_model}}}
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

    public function get_credential_id ()
	{
		return $this->_credential_id;
    }
    
    public function set_credential_id($credential_id)
    {
        $this->_credential_id = $credential_id;
    }

	{{{methods}}}

}