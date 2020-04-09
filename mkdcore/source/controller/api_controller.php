<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{uc_portal}}}_api_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{uc_name}}} API Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{api_controller}}} extends {{{uc_portal}}}_api_controller
{
    protected $_model_file = '{{{model}}}_model';

    public function __construct()
    {
        parent::__construct();
    }

{{{listing}}}

{{{add}}}

{{{edit}}}

{{{view}}}

{{{delete}}}

{{{dynamic_mapping_add}}}
{{{dynamic_mapping_edit}}}
{{{dynamic_mapping_view}}}
}