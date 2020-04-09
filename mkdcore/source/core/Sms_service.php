<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
use Twilio\Rest\Client;
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Sms Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Sms_service
{
    /**
     * Mail Adapter
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * Adapter selected
     *
     * @var string
     */
    public $_type = '';

    /**
     * From Number
     *
     * @var string
     */
    public $_from = '';

    /**
     * CI
     *
     * @var mixed
     */
    public $_ci = null;

    /**
     * Set mail service to correct way to send emails
     *
     * @param string $type
     * @throws Exception
     */
    public function set_adapter ($type)
    {

        $this->_type = $type;
        $this->_ci = &get_instance();
        $this->_from = $this->_ci->config->item('twilio_phone_number');

        switch ($type)
        {
            case 'sms':
                $this->_adapter = new Client($this->_ci->config->item('twilio_sid'), $this->_ci->config->item('twilio_token'));
                break;

            case 'whatsapp':
                // Your Account Sid and Auth Token from twilio.com/user/account
                $this->_adapter = new Client($this->_ci->config->item('twilio_sid'), $this->_ci->config->item('twilio_token'));
            case 'test':
                break;
            default:
                break;
        }
    }

    /**
     * Send email
     *
     * @param string $to
     * @param string $message
     */
    public function send ($to, $message)
    {
        switch ($this->_type)
        {
            case 'sms':
                try {
                    $result = $this->_adapter->messages->create(
                        "{$to}",
                        [
                            'from' => "{$this->_from}",
                            'body' => $message,
                        ]
                    );
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('Result: ' . $result);
                    if (!$result || !$result->sid)
                    {
                        return NULL;
                    }
                    // error_log(print_r($result, TRUE));
                    return $result->sid;
                } catch (Exception $e) {
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('SMS Error: ' . $e->getMessage());
                    return FALSE;
                }
                break;

            case 'test':
                return TRUE;
                break;

            case 'whatsapp':
                try {
                    $result = $this->_adapter->messages->create(
                        "whatsapp:{$to}",
                        [
                            'from' => "whatsapp:{$this->_from}",
                            'body' => $message,
                        ]
                    );
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('Result: ' . $result);
                    return TRUE;
                } catch (Exception $e) {
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('SMS Error: ' . $e->getMessage());
                    return FALSE;
                }
                break;
            default:
                break;
        }

    }

    /**
     * Send email
     *
     * @param string $to
     * @param string $message
     */
    public function send_callback ($to, $message, $callback_url)
    {
        switch ($this->_type)
        {
            case 'sms':
                try {
                    $result = $this->_adapter->messages->create(
                        "{$to}",
                        [
                            'from' => "{$this->_from}",
                            'body' => $message,
                            'statusCallback' => $callback_url
                        ]
                    );
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('Result: ' . $result);
                    if (!$result || !$result->sid)
                    {
                        return NULL;
                    }
                    // error_log(print_r($result, TRUE));
                    return $result->sid;
                } catch (Exception $e) {
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('SMS Error: ' . $e->getMessage());
                    return FALSE;
                }
                break;

            case 'test':
                return TRUE;
                break;

            case 'whatsapp':
                try {
                    $result = $this->_adapter->messages->create(
                        "whatsapp:{$to}",
                        [
                            'from' => "whatsapp:{$this->_from}",
                            'body' => $message,
                        ]
                    );
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('Result: ' . $result);
                    return TRUE;
                } catch (Exception $e) {
                    error_log('TO: ' . $to);
                    error_log('Message: ' . $message);
                    error_log('SMS Error: ' . $e->getMessage());
                    return FALSE;
                }
                break;
            default:
                break;
        }

    }

    public function retrieve_single_sms_log ($sid)
    {
        return $this->_adapter->messages($sid)->fetch();
    }

    public function add_country_code ($country_code=1, $phone_number)
    {
        $str_phone = (string) $phone_number;

        if (substr($str_phone, 0, $country_code) === $country_code)
        {
            return '+' . $phone_number;
        }
        else
        {
            return '+' . $country_code . $phone_number;
        }
    }
    public function add_custom_country_code ($country_code=1, $phone_number)
    {

        $str_phone = (string) $phone_number;

        if (substr($str_phone, 0, $country_code) === $country_code)
        {
            return '+' . $phone_number;
        }
        else
        {
            return '+' . $country_code . $phone_number;
        }
    }
}