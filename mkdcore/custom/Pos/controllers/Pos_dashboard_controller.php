<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Pos_controller.php';

/**
 * Pos Dashboard Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Pos_dashboard_controller extends Pos_controller
{
    public $_page_name = 'Dashboard';

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        return $this->load->view('Pos/Dashboard', $this->_data);
    }
}