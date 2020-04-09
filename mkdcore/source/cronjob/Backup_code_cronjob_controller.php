<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Cronjob_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Backup Code Cronjob Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Backup_code_cronjob_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $date = date('Y-m-d');
        $this->project_backup($date);
        // $this->send_backup($date);
    }

    private function project_backup($date)
    {
        $this->load->library('zip');
        $this->zip->read_dir(FCPATH, FALSE);
        $this->zip->archive("project_backup_{$date}.zip");
    }
}