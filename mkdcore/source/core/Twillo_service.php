<?php

use Twilio\Rest\Client;

class Twillo_service
{

    private $_sid = '';
    private $_from_number = '';
    private $_api_token = '';
    private $_twilio_verification_service = '';
    protected $_client = NULL;
    private $_ci = NULL;


    public function __construct()
    {
        $this->_ci = &get_instance();
        $this->_sid = $this->_ci->config->item('twilio_sid');
        $this->_api_token = $this->_ci->config->item('twilio_token');
        $this->_from_number = $this->_ci->config->item('twilio_phone_number');
        $this->_twilio_verification_service = $this->_ci->config->item('twilio_verification_service');

        try
        {
            $this->_client = new Client($this->_sid, $this->_api_token);
        }
        catch(Twilio\Exceptions\ConfigurationException $e)
        {
           die($e->getMessage()) ;
        }
    }

    /**
     * Send Message
     *
     * @param string $number
     * @param string $message
     * @return mixed
     */
    public function send_message($number, $message)
    {
        return $this->_client->messages->create(
            $number, // to
            ['body' => $message, 'from' => $this->_from_number]
        );
    }

    /**
     * Verify Number
     *
     * @see https://www.twilio.com/docs/verify/api/verification
     * @param [type] $number
     * @return void
     */
    public function verify_number($number)
    {
        return $this->_client->verify->v2->services($this->_twilio_verification_service)->verifications->create($number, 'sms');
    }

    /**
     * Fetch Verification
     *
     * @param string $seID
     * @return mixed
     */
    public function fetch_verification($seID)
    {
        return $this->_client->verify->v2->services($this->_twilio_verification_service)->verifications($seID)->fetch();
    }

    /**
     * Check Verification Code
     *
     * @param string $code
     * @param string $number
     * @return mixed
     */
    public function check_verification_code($code, $number)
    {
        return $this->_client->verify->v2->services($this->_twilio_verification_service)->verificationChecks->create($code, ['to' => $number]);
    }

}
