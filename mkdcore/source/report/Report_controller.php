<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{uc_portal}}}_controller.php';
include_once __DIR__ . '/../../services/{{{ucname}}}_report_service.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{ucname}}} Report Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{uc_portal}}}_{{{name}}}_report_controller extends {{{uc_portal}}}_controller
{
    protected $_model_file = '{{{model}}}_model';
    public $_page_name = '{{{page_name}}}';

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $export = ($this->input->get('export', TRUE)) ? $this->input->get('export', TRUE) : FALSE;
{{{filter_fields}}}
{{{post_fields}}}
{{{pre_controller}}}
        $service = new {{{ucname}}}_report_service($this->{{{model}}}_model, $this->_data['start_date'], $this->_data['end_date']);
        $result = $service->process({{{process}}});
{{{display}}}
    }

    private function set_fields ($field_name)
    {
        $this->_data[$field_name] = ($this->input->get($field_name, TRUE)) ? $this->input->get($field_name, TRUE) : NULL;
    }
}