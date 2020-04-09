<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '../services/User_service.php';
include_once '{{{ucname}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Login Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_social_login_controller extends {{{ucname}}}_controller
{
	protected $_redirect = '/{{{name}}}/dashboard';

    public function google()
    {
        $this->load->model('{{{model}}}');
        $this->load->library('google_service');
        $this->load->model('credential_model');
        $this->google_service->init();

        $code = $this->input->get('code');
        $response = $this->google_service->get_me($code);
        $code = $response->getStatusCode();
        $body = $response->getBody();
        $service = new User_service($this->credential_model, $this->{{{model}}});

        if ($code != 200)
        {
            $this->error('xyzSorry, google cannot find your email.');
            return $this->redirect('/{{{name}}}/login');
        }

        $email = $this->google_service->get_email($body);
        if (strlen($email) < 1)
        {
            $this->error('xyzSorry, google cannot find your email.');
            return $this->redirect('/{{{name}}}/login');
        }

        $exist = $this->{{{model}}}->get_by_fields([
            'email' => $email
        ]);

        if (!$exist)
        {
            $user_id = $service->register_social($email, $this->{{{model}}}->get_mapping()->GOOGLE_LOGIN_TYPE,{{{role}}});

            if ($user_id)
            {
                $user = $this->{{{model}}}->get($user_id);
                $this->set_session('user_id', (int) $user->id);
                $this->set_session('email', (string) $user->email);
                $this->set_session('role', (string) $user->role_id);
                return $this->redirect($this->_redirect);
            }
        }

        if (!$service->is_google_user($exist))
        {
            $this->error('xyzSorry, your email is not a google email in our system.');
            return $this->redirect('/{{{name}}}/login');
        }

        $this->set_session('user_id', (int) $exist->id);
        $this->set_session('email', (string) $exist->email);
        $this->set_session('role', (string) $exist->role_id);
        return $this->redirect($this->_redirect);
    }

    public function facebook()
    {
        $this->load->model('{{{model}}}');
        $this->load->library('facebook_service');
        $this->google_service->init();

        $code = $this->input->get('code');
        $service = new User_service($this->{{{model}}});


        if (!$code || strlen($code) < 1)
        {
            $this->error('xyzSorry, facebook cannot find your email.');
            return $this->redirect('/{{{name}}}/login');
        }

        $authentication_result = $this->facebook_service->authenticate_oauth_login_code($code);
        if (!$authentication_result)
        {
            $this->error('xyzSorry, facebook cannot find your email.');
            return $this->redirect('/{{{name}}}/login');
        }
        $email = $this->facebook_service->get_email();

        if (strlen($email) < 1)
        {
            $this->error('xyzSorry, facebook cannot find your email.');
            return $this->redirect('/{{{name}}}/login');
        }

        $exist = $this->{{{model}}}->get_by_fields([
            'email' => $email
        ]);

        if (!$exist)
        {
            $user_id = $service->register_social($email, $this->{{{model}}}->get_mapping()->FACEBOOK_LOGIN_TYPE,{{{role}}});

            if (!$user_id)
            {
                $this->error('xyzThere was a problem creating your new account. Please try again.');
                return $this->redirect('/{{{name}}}/login');
            }

            $user = $this->{{{model}}}->get($user_id);
            $this->set_session('user_id', (int) $user->id);
            $this->set_session('email', (string) $user->email);
            $this->set_session('role', (string) $user->role_id);
            return $this->redirect($this->_redirect);
        }

        if (!$service->is_facebook_user($exist))
        {
            $this->error('xyzSorry, your email is not a facebook email in our system.');
            return $this->redirect('/{{{name}}}/login');
        }

        $this->set_session('user_id', (int) $exist->id);
        $this->set_session('email', (string) $exist->email);
        $this->set_session('role', (string) $exist->role_id);
        return $this->redirect($this->_redirect);
    }
}