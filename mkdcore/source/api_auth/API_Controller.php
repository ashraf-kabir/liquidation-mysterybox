<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '/../../middlewares/Auth_middleware.php';
include_once __DIR__ . '/../../middlewares/Acl_middleware.php';
include_once __DIR__ . '/../../middlewares/Maintenance_middleware.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * API Abstract Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class {{{ucname}}}_api_auth_controller extends CI_Controller
{
    protected $_supported_formats = [
        'json' => 'application/json',
        'test' => 'application/json',
        'array' => 'application/json',
        'csv' => 'application/csv',
        'html' => 'text/html',
        'jsonp' => 'application/javascript',
        'php' => 'text/plain',
        'xml' => 'application/xml'
    ];

    public $_format = 'json';
    protected $_test_mode = FALSE;
    public $_user_id = 0;
    public $_role_id = 0;
    public $_valid_roles = [{{{valid_roles}}}];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Set Controller into test mode
     *
     * @return void
     */
    public function set_test_mode ()
    {
        $this->_test_mode = TRUE;
        $this->_format = 'test';
    }

    /**
     * Render api output
     *
     * @param mixed $data
     * @param number $code
     * @return string
     */
    public function render($data, $code)
    {
        http_response_code($code);
        header('Content-Type: ' . $this->_supported_formats[$this->_format]);
        switch ($this->_format)
        {
            case 'json':
                return $this->output->set_content_type($this->_supported_formats[$this->_format])
                ->set_status_header($code)
                ->set_output(json_encode($data));
                break;
            case 'test':
                return $data;
                break;
            default:
                return '<pre>' . print_r($data, TRUE) . '</pre>';
                break;
        }
    }

    /**
     * User token invalid
     *
     * @return string
     */
    public function unauthorize_error_message()
    {
        $this->render([
            'code' => 401,
            'success' => FALSE,
            'message' => 'xyzinvalid credentials'
        ], 401);
    }

    /**
     * User token expire
     *
     * @return string
     */
    public function expire_token_error_message()
    {
        $this->render([
            'code' => 401,
            'success' => FALSE,
            'message' => 'xyzTokenExpire'
        ], 401);
    }

    /**
     * Success API Call
     *
     * @return string
     */
    public function success($success)
    {
        $success['code'] = 200;
        $success['success'] = TRUE;
        $this->render($success, 200);
    }

    /**
     * Invalid form input
     *
     * @return string
     */
    protected function _render_validation_error ()
	{
        $data = [];
        $data['code'] = 403;
        $data['success'] = FALSE;
        $data['error'] = $this->form_validation->error_array();
        return $this->output->set_content_type($this->_supported_formats[$this->_format])
            ->set_status_header(403)
            ->set_output(json_encode($data));
    }

    /**
     * Render Custom Error
     *
     * @return string
     */
    protected function _render_custom_error ($errors)
	{
        $data = [];
        $data['code'] = 403;
        $data['success'] = FALSE;
        $data['error'] = $errors;
        return $this->output->set_content_type($this->_supported_formats[$this->_format])
            ->set_status_header(403)
            ->set_output(json_encode($data));
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

    /**
     * Get Session
     *
     * @return mixed
     */
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

    /**
     * Set Session Field
     *
     * @param string $field
     * @param mixed $value
     * @return void
     */
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

    /**
     * Destroy Session
     *
     * @return void
     */
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

    protected function _middleware()
    {
        return [];
    }

    protected function _run_middlewares ()
    {

        $middlewares = [
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

    public function get_user_id ()
    {
        return $this->_user_id;
    }

    public function set_user_id ($user_id)
    {
        $this->_user_id = $user_id;
    }

    public function get_role_id ()
    {
        return $this->_role_id;
    }

    public function set_role_id ($role_id)
    {
        $this->_role_id = $role_id;
    }
}