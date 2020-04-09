<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Image List Paginate View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Image_asset_paginate_view_model
{
    protected $_library;
    protected $_base_url = '';
    protected $_heading = 'Image';
    protected $_total_rows = 0;
    protected $_per_page = 10;
    protected $_page;
    protected $_num_links = 5;
    protected $_column = ['URL','Image Type','User','Action'];
    protected $_list = [];
    protected $_links = '';
    protected $_entity;

    public function __construct($entity, $library, $base_url)
    {
        $this->_entity = $entity;
        $this->_library = $library;
		$this->_base_url = str_replace('/0', '/', $base_url);
    }

    /**
     * set_total_rows function
     *
     * @param integer $total_rows
     * @return void
     */
    public function set_total_rows ($total_rows)
    {
        $this->_total_rows = $total_rows;
    }

    /**
     * set_per_page function
     *
     * @param integer $per_page
     * @return void
     */
    public function set_per_page ($per_page)
    {
        $this->_per_page = $per_page;
    }

    /**
     * set_page function
     *
     * @param integer $page
     * @return void
     */
    public function set_page ($page)
    {
        $this->_page = (int) $page;
    }

    /**
     * set_list function
     *
     * @param mixed $list
     * @return void
     */
    public function set_list ($list)
    {
        $this->_list = $list;
    }

    /**
     * get_total_rows function
     *
     * @return integer
     */
    public function get_total_rows ()
    {
        return $this->_total_rows;
    }

    /**
     * get_per_page function
     *
     * @return integer
     */
    public function get_per_page ()
    {
        return $this->_per_page;
    }

    /**
     * get_page function
     *
     * @return integer
     */
    public function get_page ()
    {
        return $this->_page;
    }

    /**
     * num_links function
     *
     * @return integer
     */
    public function get_num_links ()
    {
        return $this->_num_links;
    }

    /**
     * num_pages function
     *
     * @return integer
     */
    public function get_num_page ()
    {
        $num = ceil($this->_total_rows / $this->_per_page);
        return ($num > 0) ? (int) $num : 1;
    }

    /**
     * get_list function
     *
     * @return mixed
     */
    public function get_list ()
    {
        $this->_library->initialize([
            'reuse_query_string' => TRUE,
            'base_url' => $this->_base_url,
            'total_rows' => $this->_total_rows,
            'per_page' => $this->_per_page,
            'num_links' => $this->_num_links,
            'full_tag_open' => '<ul class="pagination justify-content-end">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => FALSE,
            'last_link' => FALSE,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>'
        ]);
        return $this->_list;
    }

    /**
     * get_links function
     *
     * @return mixed
     */
    public function get_links ()
    {
        $this->_links = $this->_library->create_links();
        return $this->_links;
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

    public function set_column ($column)
    {
        $this->_column = $column;
    }

    public function get_column ()
    {
        return $this->_column;
    }

	public function get_id ()
	{
		return $this->_id;
	}

	public function set_id ($id)
	{
		$this->_id = $id;
	}

	public function get_url ()
	{
		return $this->_url;
	}

	public function set_url ($url)
	{
		$this->_url = $url;
	}

	public function get_type ()
	{
		return $this->_type;
	}

	public function set_type ($type)
	{
		$this->_type = $type;
	}

	public function get_user_id ()
	{
		return $this->_user_id;
	}

	public function set_user_id ($user_id)
	{
		$this->_user_id = $user_id;
	}

	public function type_mapping ()
	{
		return $this->_entity->type_mapping();

	}

	public function to_json ()
	{
		$list = $this->get_list();

		$clean_list = [];

		foreach ($list as $key => $value)
		{
			$list[$key]->type = $this->type_mapping()[$value->type];
			$clean_list_entry = [];
			$clean_list_entry['id'] = $list[$key]->id;
			$clean_list_entry['url'] = $list[$key]->url;
			$clean_list_entry['type'] = $list[$key]->type;
			$clean_list_entry['user_id'] = $list[$key]->user_id;
			$clean_list[] = $clean_list_entry;
		}

		return [
			'page' => $this->get_page(),
			'num_page' => $this->get_num_page(),
			'num_item' => $this->get_total_rows(),
			'per_page' => $this->get_per_page(),
			'item' => $clean_list
		];
	}

}