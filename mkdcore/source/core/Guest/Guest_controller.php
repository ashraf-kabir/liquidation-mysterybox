<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once __DIR__ . '/../../middlewares/Auth_middleware.php';
include_once __DIR__ . '/../../middlewares/Acl_middleware.php';
include_once __DIR__ . '/../../middlewares/Maintenance_middleware.php';
include_once __DIR__ . '/../../middlewares/Affilate_middleware.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Guest Abstract Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Guest_controller extends CI_Controller
{
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    //testMode flag
    protected $_test_mode = FALSE;

    public function __construct()
    {
        parent::__construct();

        $this->config->load('setting');
        $this->_setting = $this->config->item('setting');

        $this->_run_middlewares();

    }

    protected function _middleware()
    {
        return [
            'affilate',
            'maintenance'
        ];
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
     * Function to send Emails given slug, payload and email
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $email
     * @return void
     */
    protected function _send_email_notification($slug, $payload, $email)
    {
		$this->load->model('email_model');
		$this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');
        $email_template = $this->email_model->get_template($slug, $payload);

        if ($email_template)
        {
            $from = $this->config->item('from_email');
            return $this->mail_service->send($from, $email, $email_template->subject, $email_template->html);
        }

        return FALSE;
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

    /**
     * Function to send Push notification
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $to
     * @return void
     */
	protected function _send_push_notification($device_type, $device_id, $title, $message, $image)
    {
        $this->load->library('push_notification_service');
        $this->push_notification_service->init();
        return $this->push_notification_service->send($device_type, $device_id, $title, $message, $image);
    }

    protected function _run_middlewares ()
    {

        $middlewares = [
            'affilate' => new Affilate_middleware($this, $this->config),
            'auth' => new Auth_middleware($this, $this->config),
            'acl' => new Acl_middleware($this, $this->config),
            'maintenance' => new Maintenance_middleware($this, $this->config)
        ];

        foreach ($this->_middleware() as $middleware_key)
        {
            if (isset($middlewares[$middleware_key]))
            {
                $result = $middlewares[$middleware_key]->run();

                if (!$result)
                {
                    return FALSE;
                }
            }
        }
    }

    public function get_setting()
    {
        return $this->_setting;
    }

    /**
     * Redirect to URL
     *
     * @param string $template
     * @param array $data
     */
    public function redirect($url, $option = [])
    {
        return ($option) ? redirect($url, $option) : redirect($url);
    }
}