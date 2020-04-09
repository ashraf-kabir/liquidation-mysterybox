<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Facebook Service
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Facebook_service
{
    /**
     * Client ID
     *
     * @var string
     */
    public $_client_id = '';

    /**
     * Client Secret
     *
     * @var string
     */
    public $_client_secret = '';

    /**
     * Redirect URI
     *
     * @var string
     */
    public $_redirect_uri = '';

    /**
     * CI
     *
     * @var mixed
     */
    public $_ci = null;

    /**
     * adapter
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * uri
     *
     * @var mixed
     */
    public $_uri = null;

    /**
     * email
     *
     * @var mixed
     */
    public $_email = '';

    /**
     * username
     *
     * @var mixed
     */
    public $_username = '';

    /**
     * username
     *
     * @var mixed
     */
    public $_first_name = '';

    /**
     * username
     *
     * @var mixed
     */
    public $_last_name = '';

    /**
     * token
     *
     * @var mixed
     */
    public $_token = '';

    /**
     * Initiatize Facebook client
     */
    public function init ()
    {
        $this->_ci = &get_instance();
        $this->_client_id = $this->_ci->config->item('facebook_client_id');
        $this->_client_secret = $this->_ci->config->item('facebook_client_secret');
        $this->_redirect_uri = $this->_ci->config->item('facebook_redirect_uri');

        $this->_adapter = null;

        $this->_uri = $this->_ci->config->item('facebook_oath_uri') . '?client_id=' .
        $this->_client_id . '&state={st=' . md5(time()) . '}&redirect_uri=' .
        $this->_redirect_uri;
    }

    /**
     * Create auth url for google login
     *
     * @return string
     */
    public function make_auth_url ()
    {
        return $this->_uri;
    }

    /**
     * Authenticate oauth login from code in uri
     *
     * @param $code string
     * @return string
     */
    public function authenticate_oauth_login_code ($code)
    {
        $url = 'https://graph.facebook.com/v3.0/oauth/access_token?client_id=' .
        $this->_client_id . '&client_secret=' . $this->_client_secret . '&redirect_uri=' .
        $this->_redirect_uri . '&code=' . $code;

        $content = $this->_curl($url);

        if (strlen($content) < 1 || !$this->_isJson($content))
        {
            log_message('debug', ' FACEBOOK LOGIN : curl to get access token failed');
            return FALSE;
        }

        $json = json_decode($content, TRUE);

        if (!isset($json['access_token']))
        {
            log_message('error', ' FACEBOOK LOGIN : access token not retrieved');
            return FALSE;
        }

        $this->_token = $json['access_token'];
        $me_url = 'https://graph.facebook.com/me?fields=email,id,first_name,last_name&access_token=' . $this->_token;

        $response = $this->_curl($me_url);

        if (strlen($response) > 0 && $this->_isJson($response))
        {
            $response_json = json_decode($response, TRUE);

            if (!isset($response_json['email']))
            {
                log_message('error', ' FACEBOOK LOGIN : User does not have email');
                return FALSE;
            }

            $this->_email = $response_json['email'];
            $this->_username = substr($this->_email, 0, strpos($this->_email, '@'));

            if (isset($response_json['first_name']))
            {
                $this->_first_name = $response_json['first_name'];
            }

            if (isset($response_json['last_name']))
            {
                $this->_last_name = $response_json['last_name'];
            }

            return TRUE;
        }
    }

    /**
     * Authenticate access token
     *
     * @param $access_token string
     * @return string
     */
    public function authenticate_oauth_access_token ($access_token)
    {
        $this->_token = $access_token;
        $me_url = 'https://graph.facebook.com/me?fields=email,id,first_name,last_name&access_token=' . $this->_token;

        $response = $this->_curl($me_url);

        if (strlen($response) > 0 && $this->_isJson($response))
        {
            $response_json = json_decode($response, TRUE);

            if (!isset($response_json['email']))
            {
                log_message('error', ' FACEBOOK LOGIN : User does not have email');
                return FALSE;
            }

            $this->_email = $response_json['email'];
            $this->_username = substr($this->_email, 0, strpos($this->_email, '@'));

            if (isset($response_json['first_name']))
            {
                $this->_first_name = $response_json['first_name'];
            }

            if (isset($response_json['last_name']))
            {
                $this->_last_name = $response_json['last_name'];
            }

            return TRUE;
        }
    }

    /**
     * Get Access token from Google Client
     *
     * @return string
     */
    public function get_access_token ()
    {
        return $this->_token;
    }

    /**
     * Get Email from Google Plus Account
     *
     * @return string
     */
    public function get_email ()
    {
        return $this->_email;
    }

    /**
     * Get Username
     *
     * @return string
     */
    public function get_username ()
    {
        return $this->_username;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function get_first_name ()
    {
        return $this->_first_name;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function get_last_name ()
    {
        return $this->_last_name;
    }

    /**
     * Make a CURL call
     *
     * @param string $url
     * @return string
     */
    private function _curl ($url)
    {
        $options = [
            CURLOPT_RETURNTRANSFER => TRUE,   // return web page
            CURLOPT_HEADER         => FALSE,  // don't return headers
            CURLOPT_FOLLOWLOCATION => TRUE,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => '',     // handle compressed
            CURLOPT_AUTOREFERER    => TRUE,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content  = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

    /**
     * Check if string is josn
     *
     * @param string $string
     * @return boolean
     */
    private function _isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}