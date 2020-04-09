<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
abstract class Controller_interface
{
    public $route;
    public $controller;
    public $paginate;
    public $portal;
    public $is_crud;
    public $is_add;
    public $is_edit;
    public $is_delete;
    public $is_list;
    public $method;
    public $is_menu;
    public $menu_name;
    public $is_filter;
    public $filter_fields = [];
    public $add_fields = [];
    public $edit_fields = [];
}