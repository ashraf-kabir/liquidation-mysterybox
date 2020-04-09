<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Cronjob_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Backup Database Cronjob Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Backup_db_cronjob_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $date = date('Y-m-d');
        $this->database_backup($date);
        // $this->send_backup($date);
    }

    private function database_backup($date)
    {
        $this->load->helper('file');
        $this->load->dbutil();
        @$backup = $this->dbutil->backup();
        write_file("database_backup_{$date}.zip", $backup);
    }
}