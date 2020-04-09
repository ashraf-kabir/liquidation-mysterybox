<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Abstract Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Migration_cron_controller extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->library('migration');

    if ($this->migration->current() === FALSE)
    {
      print_r($this->migration->error_string());
    } else {
      echo 'Complete';
      exit;
    }
  }

}