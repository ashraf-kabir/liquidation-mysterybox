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
class Health_check_controller extends CI_Controller
{
    public function index ()
    {
        try
        {
            $this->load->database();
            $error = get_instance()->db->error();

            if (!empty($error) && $error['code'] > 0)
            {
                throw new Exception('Database Connection Failed');
            }

            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => 200,
                'date' => date('Y-m-j H:i:s', time())
            )));
        }
        catch (\Exception $e)
        {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode(array(
                'status' => 500,
                'date' => date('Y-m-j H:i:s', time())
            )));
        }
    }
}