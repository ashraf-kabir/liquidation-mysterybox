<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Health Check Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Custom404 extends CI_Controller
{
	public function __construct()
    { 
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();

		$this->load->library('redirect_custom_service');
        $this->load->model('url_redirect_model'); 
        $this->redirect_custom_service->set_url_redirect_model( $this->url_redirect_model );
        $this->redirect_custom_service->check_redirect();
	}


	public function index()
	{
		$this->output->set_status_header('404'); 
	    $data["heading"] = "404 Page Not Found";
	    $data["message"] = "The page you requested was not found ";
         
        $this->load->view('errors/html/error_404',$data);
	}
}
?>