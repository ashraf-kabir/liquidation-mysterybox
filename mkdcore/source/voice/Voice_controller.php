<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Voice Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Voice_controller extends CI_Controller
{
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

    }

    /**
     * Debug Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dl($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : <pre>' . print_r($data, TRUE) . '</pre>');
        }
    }

    /**
     * Debug json Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dj($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : ' . json_encode($data));
        }
    }

    public function get_session()
    {
        if (!$this->_test_mode)
        {
            return $_SESSION;
        }

        $session = $this->config->item('session_test');

        if (!$session)
        {
            $session = [];
        }

        return $session;
    }

    public function set_session($field, $value)
    {
        if (!$this->_test_mode)
        {
            $_SESSION[$field] = $value;
        }
        else
        {
            $session = $this->config->item('session_test');
            if (!$session)
            {
                $session = [];
            }
            $session[$field] = $value;
            $this->config->set_item('session_test', $session);
        }
    }

    public function destroy_session()
    {
        if (!$this->_test_mode)
        {
            unset($_SESSION);
        }
        else
        {
            $this->config->set_item('session_test', []);
        }
    }

    /**
     * Function to send Sms given slug, payload and phone #
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $to
     * @return void
     */
	protected function _send_sms_notification($slug, $payload, $to)
    {
		$this->load->model('sms_model');
		$this->load->library('sms_service');
        $this->sms_service->set_adapter('sms');
        $sms_template = $this->sms_model->get_template($slug, $payload);

        if ($sms_template)
        {
            return $this->sms_service->send($to, $sms_template->content);
        }

        return FALSE;
    }

    public function get_setting()
    {
        return $this->_setting;
    }

    public function get_call_message ($id)
    {
        $this->load->database();
        $this->load->model('campaign_model');
        $this->load->library('voice_service');
        $this->voice_service->set_adapter();
        $campaign = $this->campaign_model->get($id);
        $text = 'Hello';
        if ($campaign)
        {
            $text = $this->voice_service->generate_call_message($campaign->content, 'es');
        }
        echo $text;
        exit;
    }

    public function get_call_play_message ($id)
    {
        $this->load->database();
        $this->load->model('campaign_model');
        $this->load->library('voice_service');
        $this->voice_service->set_adapter();
        $campaign = $this->campaign_model->get($id);
        $text = 'Hello';
        if ($campaign)
        {
            $text = $this->voice_service->generate_call_play_message(base_url() . $campaign->content);
        }
        echo $text;
        exit;
    }

    public function get_custom_call_play_message ($id)
    {
        $this->load->database();
        $this->load->model('custom_call_list_model');
        $this->load->library('voice_service');
        $this->voice_service->set_adapter();
        $campaign = $this->custom_call_list_model->get($id);
        $text = 'Hello';
        if ($campaign)
        {
            $text = $this->voice_service->generate_call_play_message(base_url() . $campaign->voice_file);
        }
        echo $text;
        exit;
    }

    public function sms_callback ()
    {
        $this->load->model('sms_history_model');
        $this->load->model('sms_polling_stage_table_model');
        // error_log(print_r($_POST,TRUE));
        $digit = isset($_POST['Body']) ? $_POST['Body'] : '';
        $from = isset($_POST['From']) ? str_replace('+1', '', $_POST['From']) : '';
        if (strlen($from) > 0)
        {
            $exist = $this->sms_polling_stage_table_model->get_by_field('phone', $from);
            if ($exist)
            {
                $exist_sms = $this->sms_history_model->get($exist->sms_history_id);
                // error_log(print_r($exist_sms, TRUE));
                if ($exist_sms)
                {
                    error_log('sms history found');
                    $this->sms_history_model->edit([
                        'poll_result' => $digit
                    ], $exist_sms->id);
                }
            }
        }

        echo 'OK';
        exit;
    }

    public function sms_reply ($id)
    {
        $this->load->model('sms_history_model');
        $this->load->model('sms_polling_stage_table_model');

        $exist = $this->sms_history_model->get($id);
        $exist_staging = $this->sms_polling_stage_table_model->get_by_field('sms_history_id', $id);

        if ($exist && !$exist_staging)
        {
            $this->sms_polling_stage_table_model->create([
                'sms_history_id' => $id,
                'phone' => $exist->phone_1
            ]);
        }

        echo 'OK';
        exit;
    }

    public function sms_callback_fail ()
    {
        error_log(print_r($_POST,TRUE));
        echo print_r($_POST);
        exit;
    }
}