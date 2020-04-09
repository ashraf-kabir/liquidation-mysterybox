<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{ucname}}}_controller.php';

/**
 * {{{ucname}}} Dashboard Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_dashboard_controller extends {{{ucname}}}_controller
{
    public $_page_name = 'Dashboard';

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        return $this->render('{{{ucname}}}/Dashboard', $this->_data);
    }
}