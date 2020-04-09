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
     * Initiatize Google client.
     */
    public function init()
    {
        $this->_ci = &get_instance();
        $this->_client_id = $this->_ci->config->item('google_client_id');
        $this->_client_secret = $this->_ci->config->item('google_client_secret');
        $this->_redirect_uri = $this->_ci->config->item('google_redirect_uri');

        $this->_adapter = new Google_Client();
        $this->_adapter->setClientId($this->_ci->config->item('google_client_id'));
        $this->_adapter->setClientSecret($this->_ci->config->item('google_client_secret'));
        $this->_adapter->setRedirectUri($this->_ci->config->item('google_redirect_uri'));
        $this->_adapter->setAccessType('online'); // default: offline
        $this->_adapter->setApplicationName($this->_ci->config->item('application_name'));
        $this->_adapter->setScopes('email');
    }

    /**
     * Create auth url for google login.
     *
     * @return string
     */
    public function make_auth_url()
    {
        return $this->_adapter->createAuthUrl();
    }

    public function get_adapter()
    {
        return $this->_adapter;
    }

    public function get_me ($code)
    {
        $this->_adapter->addScope(Google_Service_Plus::PLUS_ME);
        $response = $this->_adapter->fetchAccessTokenWithAuthCode($code);

        if ($response) {
            $http_client = $this->_adapter->authorize();
            // make an HTTP request
            return $http_client->get('https://www.googleapis.com/plus/v1/people/me');
        }

        return FALSE;
    }

    public function get_email ($body) {
        $body_json = json_decode($body, TRUE);

        if (!empty($body_json['emails']) && isset($body_json['emails'][0]['value'])) {
            return $body_json['emails'][0]['value'];
        }

        return '';
    }
}
