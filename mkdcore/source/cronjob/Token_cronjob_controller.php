<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Cronjob_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Token Cronjob Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Token_cronjob_controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $this->load->database();
        $this->load->model('token_model');
        $reset_token_status = $this->token_model->raw_query("UPDATE `token` SET status=0 WHERE `expire_at` < NOW();");
        $remove_expired_tokens = $this->token_model->raw_query("DELETE FROM `token` WHERE status=0");
    }
}