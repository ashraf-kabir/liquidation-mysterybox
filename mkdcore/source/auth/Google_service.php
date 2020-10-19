<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Google Service
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Google_service
{
    /**
     * Client ID.
     *
     * @var string
     */
    public $_client_id = '';

    /**
     * Client Secret.
     *
     * @var string
     */
    public $_client_secret = '';

    /**
     * Redirect URI.
     *
     * @var string
     */
    public $_redirect_uri = '';

    /**
     * CI.
     *
     * @var mixed
     */
    public $_ci = null;

    /**
     * CI.
     *
     * @var mixed
     */
    public $_adapter = null;

    /**
     * CI.
     *
     * @var mixed
     */
    public $_plus = null;

    /**
     * Config path
     * @var string
     */

     public $_auth_file = '';

    /**
     * Initiatize Google client.
     */
    public function init()
    {
        $this->_ci = &get_instance();
        $this->_client_id = $this->_ci->config->item('google_client_id');
        $this->_client_secret = $this->_ci->config->item('google_client_secret');
        $this->_redirect_uri = $this->_ci->config->item('google_redirect_uri');
        $this->_auth_file  = './application/libraries/' . $this->_ci->config->item('google_auth_json'); 
    }

    /**
     * Create auth url for google login.
     *
     * @return string
     */
    public function make_auth_url()
    {
        return $this->_get_client()->createAuthUrl();
    }

    public function get_adapter()
    {
        return $this->_adapter;
    }

    private function _get_client()
    {
        
        $client = new Google_Client();
        $client->setApplicationName($this->_ci->config->item('application_name'));
        $client->setClientId($this->_ci->config->item('google_client_id'));
        $client->setClientSecret($this->_ci->config->item('google_client_secret'));
        $client->setRedirectUri($this->_ci->config->item('google_redirect_uri'));
        
        $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
        $client->setScopes('email');
        $client->addScope('profile');
        
        $client->setAccessType('online'); // default: offlines
        $client->setPrompt('select_account consent');
        return $client;
    }


    public function get_me($code)
    {
        $google_client = $this->_get_client();
        $token = $google_client->fetchAccessTokenWithAuthCode($code);
      
        if(isset($token['access_token']))
        {
            $google_client->setAccessToken($token['access_token']);
            $google_service = new Google_Service_Oauth2($google_client);
            //$google_service = new Google_Service_Gmail($google_client);
            return $google_service->userinfo->get();
         
        }
        
        return false;
    }

    public function get_email ($body) {
        $body_json = json_decode($body, TRUE);

        if (!empty($body_json['emails']) && isset($body_json['emails'][0]['value'])) {
            return $body_json['emails'][0]['value'];
        }

        return '';
    }
}

