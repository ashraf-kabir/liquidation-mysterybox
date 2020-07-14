<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '/../middlewares/Auth_middleware.php';
include_once __DIR__ . '/../middlewares/Acl_middleware.php';
include_once __DIR__ . '/../middlewares/Maintenance_middleware.php';
include_once __DIR__ . '/../middlewares/Affilate_middleware.php';
include_once __DIR__ . '/../middlewares/Subscription_middleware.php';
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
class {{{subclass_prefix}}}Controller extends CI_Controller
{

    // If set, this language file will automatically be loaded.
    protected $_language_file = NULL;

    // If set, this model file will automatically be loaded.
    protected $_model_file = NULL;

    //testMode flag
    protected $_test_mode = FALSE;

    protected $_setting = FALSE;

    /**
     * View Model
     * @var array
     */
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    /**
     * Flash Data
     * @var array
     */
    protected $_flash_error = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        //--------------------------------------------------------------------
        // Language & Model Files
        //--------------------------------------------------------------------

        if (!is_null($this->_language_file))
        {
            $this->lang->load($this->_language_file);
        }

        if (!is_null($this->_model_file))
        {
            $this->load->model($this->_model_file);
        }

        //Flashdata setup
        if ($this->session->flashdata('error'))
        {
            $this->_flash_error['error'] = $this->session->flashdata('error');
            $this->session->set_flashdata('error', '');
            $this->_data['error'] = $this->_flash_error['error'];
        }

        if ($this->session->flashdata('success'))
        {
            $this->_flash_error['success'] = $this->session->flashdata('success');
            $this->session->set_flashdata('success', '');
            $this->_data['success'] = $this->_flash_error['success'];
        }

        $this->load->model('setting_model');
        $this->_setting = $this->setting_model->get_config_settings();
    }

    /**
     * Set Controller into test mode
     *
     * @return void
     */
    public function set_test_mode ()
    {
        $this->_test_mode = TRUE;
    }

    /**
     * Render view
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, $data)
    {
        return (!$this->_test_mode) ? $this->_render($template, $data) : $this->_render_test($template, $data);
    }

    /**
     * Render Test Model
     *
     * @param string $template
     * @param mixed $data
     * @return mixed
     */
    protected function _render_test($template, $data)
    {
        return [
            'header' => $this->load->view('Layout/Header', $data, TRUE),
            'body' => $this->load->view($template, $data, TRUE),
            'footer' => $this->load->view('Layout/Footer', $data, TRUE),
            'data' => $data,
        ];
    }

    /**
     * Render Normal View
     *
     * @param string $template
     * @param mixed $data
     * @return void
     */
    protected function _render($template, $data)
    {
        $this->load->view('Layout/Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Layout/Footer');
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

    /**
     * Set the Flashdata
     *
     * @param string $message
     */
    public function success($message)
	{
      $this->session->set_flashdata('success', $message);
    }

    /**
     * Set the Flashdata
     *
     * @param string $message
     */
    public function error($message)
	{
      $this->session->set_flashdata('error', $message);
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
            session_unset();
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

    protected function _middleware()
    {
        return [];
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
}