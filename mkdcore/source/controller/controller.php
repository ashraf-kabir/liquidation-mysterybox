<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{uc_portal}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{uc_name}}} Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{controller_name}}} extends {{{uc_portal}}}_controller
{
    protected $_model_file = '{{{model}}}_model';
    public $_page_name = '{{{page_name}}}';

    public function __construct()
    {
        parent::__construct();
        {{{load_libraries}}}
{{{dynamic_mapping_load}}}
    }

{{{listing}}}

{{{add}}}

{{{edit}}}

{{{view}}}

{{{delete}}}
{{{dynamic_mapping}}}
{{{autocomplete}}}
{{{method}}}
{{{dynamic_mapping_add}}}
{{{dynamic_mapping_edit}}}
{{{dynamic_mapping_view}}}
{{{autocomplete_methods}}}
}