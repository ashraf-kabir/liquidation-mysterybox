<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
use Twilio\Rest\Client;
use Twilio\TwiML;
use Twilio\TwiML\VoiceResponse;
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Voice Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 * Call international, make sure you select your country
 * https://www.twilio.com/console/voice/calls/geo-permissions/low-risk
 */
class Voice_service
{
    /**
     * Mail Adapter
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * From Number
     *
     * @var string
     */
    public $_from = '';

    /**
     * Twillio Token
     *
     * @var string
     */
    public $_token = '';

    /**
     * Twillio Sid
     *
     * @var string
     */
    public $_sid = '';

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
    public function set_adapter ()
    {
        $this->_ci = &get_instance();
        $this->_from = $this->_ci->config->item('twilio_phone_number');
        $this->_token = $this->_ci->config->item('twilio_token');
        $this->_sid = $this->_ci->config->item('twilio_sid');
        $this->_adapter = new Client($this->_ci->config->item('twilio_sid'), $this->_ci->config->item('twilio_token'));
    }

    /**
     * Get Twillio XML Builder Object
     *
     * @return Twiml
     */
	public function get_twillo_xml_builder()
	{
		return new Twiml();
    }

    /**
     * Send email
     *
     * @param string $to
     * @param string $message
     */
    public function send ($call_number, $url, $timeout=60)
    {
        // error_log($call_number, $url, $timeout);
        $result = $this->_adapter->calls->create($call_number, $this->_from, [
            'url' => $url,
            'timeout' => $timeout
        ]);
        // error_log(print_r($result,true));
        if (!$result || !$result->sid)
        {
            return NULL;
        }

        return $result->sid;
    }

    /**
     * Play Voice
     *
     * @param string $to
     * @param string $message
     */
    public function play ($call_number, $url, $timeout=60)
    {
        $result = $this->_adapter->calls->create($call_number, $this->_from, [
            'url' => $url,
            'timeout' => $timeout
        ]);
        // error_log(print_r($result,true));
        if (!$result || !$result->sid)
        {
            return NULL;
        }

        return $result->sid;
    }

    /**
     * Send Voice call with callback
     *
     * @param string $to
     * @param string $message
     */
    public function get_call_log ($parameters=[], $limit)
    {
        return $this->_adapter->calls->read($parameters, $limit);
    }

    public function retrieve_single_call_log ($sid)
    {
        return $this->_adapter->calls($sid)->fetch();
    }

    public function generate_call_message ($text, $lang='en')
    {
        $response = new VoiceResponse();
        $response->say($text, ['voice' => 'woman', 'language' => $lang]);
        return  $response;
    }

    public function generate_call_play_message ($text)
    {
        $response = new VoiceResponse();
        $response->play($text, ['loop' => 1]);
        return  $response;
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

    public function call_answer_payload ($text, $callback_url, $fail_callback_url)
    {
        try
        {
            $response = new Twilio\Twiml;
            $gather = $response->gather(array('numDigits' => 1, 'action' => $callback_url));
            $gather->say($text);
            $response->redirect($fail_callback_url);
            header('Content-Type: text/xml');
            return $response;
        }
        catch(Exception $e)
        {
            error_log(print_r($e, TRUE));
        }
    }

    public function gather_payload ($options, $post = array(), $failure_message, $fail_callback_url)
    {
        try
        {
            $response = new Twilio\Twiml;
            if (array_key_exists('Digits', $post))
            {
                $responded = FALSE;
                foreach ($options as $key => $value) {
                    if ($post['Digits'] === $value['digit'])
                    {
                        $responded = TRUE;
                        $response->say($value['message']);
                    }
                }
                if (!$responded)
                {
                    $response->say($failure_message);
                }
            }
            else
            {
                $response->redirect($fail_callback_url);
            }
            error_log(print_r($response, TRUE));
            return $response;
        }
        catch(Exception $e)
        {
            error_log(print_r($e, TRUE));
            return '';
        }
    }
}