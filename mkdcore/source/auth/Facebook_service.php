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
     * Initiatize Facebook client
     */
    public function init ()
    {
        $this->_ci = &get_instance();
        $this->_client_id = $this->_ci->config->item('facebook_client_id');
        $this->_client_secret = $this->_ci->config->item('facebook_client_secret');
        $this->_redirect_uri = $this->_ci->config->item('facebook_redirect_uri');
        $this->_adapter = null;
        $this->_redirect_uri = $this->_ci->config->item('facebook_redirect_uri');
        $this->_uri = $this->_ci->config->item('facebook_oath_uri') . '?client_id=' .
        $this->_client_id . '&state={st=' . md5(time()) . '}&redirect_uri=' .
        $this->_redirect_uri;

    }

    private function _get_adapter()
    {
        $this->_adapter = new \Facebook\Facebook([
            'app_id' => $this->_client_id,
            'app_secret' => $this->_client_secret,
            'default_graph_version' => 'v2.10',
            //'default_access_token' => '{access-token}', // optional
        ]);

        return  $this->_adapter;
    }
   
    /**
     * Create auth url for facebook login
     *
     * @return string
     */
    public function make_auth_url ()
    {
        $fb_client = $this->_get_adapter();
        $facebook_helper = $fb_client->getRedirectLoginHelper();
        $facebook_permissions = ['email']; // Optional permissions
        return $facebook_helper->getLoginUrl($this->_redirect_uri, $facebook_permissions);
    }


}