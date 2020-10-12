<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '/../factories/User_factory.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * User Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class User_service
{
    private $_model;
    private $_refer_log_model = NULL;
    private $_token_model = NULL;
    private $_email_model = NULL;
    private $_email_service = NULL;
    private $_user_model = NULL;
    private $_factory;


    public function __construct($credential_model, $user_model = NULL)
    {
        $this->_model = $credential_model;
        $this->_factory = new User_factory($credential_model,$user_model );
    }

    public function set_refer_log_model($refer_log_model)
    {
        $this->_refer_log_model = $refer_log_model;
    }

    public function set_email_model($email_model)
    {
        $this->_email_model = $email_model;
    }

    public function set_token_model($token_model)
    {
        $this->_token_model = $token_model;
    }

    public function set_email_service($email_service)
    {
        $this->_email_service = $email_service;
     }

    public function set_user_model($user_model)
    {
        $this->_user_model = $user_model;
    }

    /**
     * Register User
     *
     * @param string $email
     * @param string $password
     * @param integer $role
     * @param string $refer
     * @return integer|boolean
     */
    public function register($email, $password, $role, $refer='', $refer_type=0)
    {
        $user_id = $this->_factory->create($this->_model, $email, $password, $role, 'n');

        if ($user_id)
        {
            $refer_code = (isset($refer) && strlen($refer) > 0) ? $refer : '';
            if ($this->_refer_log_model && $refer_code != '')
            {
                $referrer_exist = $this->_model->get_by_field('refer', $refer_code);

                if ($referrer_exist)
                {
                    $this->_refer_log_model->create([
                        'user_id' => $user_id,
                        'referrer_user_id' => $referrer_exist->id,
                        'status' => 0,
                        'type' => $refer_type
                    ]);
                }
            }

            return $user_id;
        }

        return FALSE;
    }

    /**
     * Register Social Login User
     *
     * @param string $email
     * @param string $type
     * @param integer $role
     * @param string $refer
     * @return integer|boolean
     */
    public function register_social($email, $type, $role, $refer='', $refer_type=0)
    {
        $user_id = $this->_factory->create($this->_model, $email, ' ', $role, $type);

        if ($user_id)
        {
            $refer_code = (isset($refer) && strlen($refer) > 0) ? $refer : '';
            if ($this->_refer_log_model && $refer_code != '')
            {
                $referrer_exist = $this->_model->get_by_field('refer', $refer_code);

                if ($referrer_exist)
                {
                    $this->_refer_log_model->create([
                        'user_id' => $user_id,
                        'referrer_user_id' => $referrer_exist->id,
                        'status' => 0,
                        'type' => $refer_type
                    ]);
                }
            }

            return $user_id;
        }

        return FALSE;
    }

    /**
     * Create Full User
     *
     * @param string $email
     * @param string $password
     * @param string $first_name
     * @param string $last_name
     * @param integer $role
     * @param string $refer
     * @return integer|boolean
     */
    public function create($email, $password, $first_name, $last_name, $role, $refer='', $refer_type=0)
    {
        $user_id = $this->_factory->create_full_user($this->_model, $email, $password, $first_name, $last_name, $role, 'n');

        if ($user_id)
        {
            $refer_code = (isset($refer) && strlen($refer) > 0) ? $refer : '';

            if ($this->_refer_log_model && $refer_code != '')
            {
                $referrer_exist = $this->_model->get_by_field('refer', $refer_code);

                if ($referrer_exist)
                {
                    $this->_refer_log_model->create([
                        'user_id' => $user_id,
                        'referrer_user_id' => $referrer_exist->id,
                        'status' => 0,
                        'refer_type' => 0
                    ]);
                }
            }
            return $this->_model->get($user_id);
        }

        return FALSE;
    }

    /**
	 * login function.
	 *
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
    public function login($email, $password)
    {
        $user = $this->_model->get_by_fields([
            'email' => $email,
            'type' => 'n',
            'status' => $this->_model->get_mapping()::ACTIVE,
        ]);

        if ($user)
        {
            return password_verify($password, $user->password) ? $user : FALSE;
        }

        return FALSE;
    }

    /**
	 * login by role function.
	 *
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
    public function login_by_role($email, $password, $role_id)
    {
        $user = $this->_model->get_by_fields([
            'email' => $email,
            'type' => 'n',
            'role_id' => $role_id,
            'status' => $this->_model->get_mapping()::ACTIVE,
        ]);

        if ($user)
        {
            return password_verify($password, $user->password) ? $user : FALSE;
        }

        return FALSE;
    }

    /**
	 * get_redirect function.
	 *
	 * @access public
	 * @param string $redirect
	 * @return string
	 */
    public function get_redirect($redirect, $default='')
    {
        return (strlen($redirect) > 0) ? $redirect : $default;
    }

    /**
     * Edit User Profile
     *
     * @param mixed $data
     * @param integer $id
     * @return boolean
     */
    public function edit_user($data, $id)
    {
        foreach ($data as $key => $value)
        {
            if (is_string($value) && $data[$key] == '')
            {
                unset($data[$key]);
            }
        }

        if (isset($data['password']) && strlen($data['password']) > 0)
		{
			$data['password'] = str_replace('$2y$', '$2b$', password_hash($data['password'], PASSWORD_BCRYPT));
        }

        return $this->_model->edit($data, $id);
    }

	/**
	 * Reset Password Token
	 *
	 * @param integer $user_id
	 * @return string
	 */
	public function reset_password_token($user_id, $limit_chars = false)
	{
        $token = rand(1000000, 9999999) . rand(1000000, 9999999) . rand(1000000, 9999999);

        if($limit_chars)
        {
            $token  = substr($token, 0, 7);
        }

        $ttl_seconds = (24 * 60 * 60);
        $this->_token_model->create([
            'token' => $token,
            'data' => '{}',
            'type' => 0,
            'user_id' => $user_id,
            'ttl' => $ttl_seconds,
            'issue_at' => date('Y-m-j H:i:s'),
            'expire_at' => date('Y-m-j H:i:s', time() + $ttl_seconds),
            'status' => 1
        ]);
        return $token;
    }

    /**
	 * Forgot Password
	 *
	 * @param integer $id
     * @param string $from_email
     * @param string $link
	 * @return boolean
	 */
	public function forgot_password($email, $from_email, $link, $role)
	{
        $user = $this->_model->get_by_field('email', $email);

        if ($user && $user->status == $this->_model->get_mapping()::ACTIVE)
        {
            $token = $this->reset_password_token($user->id, TRUE);
            $to = $email;

            if (!$this->_email_model)
            {
                throw new Exception('Missing Email Model');
            }

            $template = $this->_email_model->get_template('reset-password', [
                'email' => $email,
                'reset_token' => $token,
                'link' => $link
            ]);
            return $this->_email_service->send($from_email, $to, $template->subject, $template->html);
        }

        return FALSE;
    }

    /**
	 * Validate Reset Token
	 *
     * @param string $token
	 * @param integer $user_id
	 * @return boolean|mixed
	 */
	public function valid_reset_token($token)
	{
        $token_found = $this->_token_model->get_by_fields([
            'token' => $token,
            'status' => 1
        ]);

        if ($token_found)
        {
            return $this->_model->get($token_found->user_id);
        }

        return FALSE;
    }

    /**
	 * Invalidate Token
	 *
     * @param string $token
	 * @param integer $user_id
	 * @return boolean
	 */
	public function invalidate_token($token, $user_id)
	{
        $token_found = $this->_token_model->get_by_fields([
            'token' => $token,
            'user_id' => $user_id
        ]);

        if ($token_found)
        {
            return $this->_token_model->edit([
                'status' => 0
            ], $token_found->id);
        }

        return FALSE;
    }

    /**
	 * Reset Password
	 *
	 * @param integer $user_id
     * @param string $password
	 * @return boolean
	 */
	public function reset_password($user_id, $password)
	{
        $user = $this->_model->get($user_id);

        if ($user->status == $this->_model->get_mapping()::ACTIVE)
        {
            return $this->_model->edit([
                'password' => str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT))
            ], $user_id);
        }

        return FALSE;
    }

    /**
	 * existing_google_user_from_email function that find if user is google user.
	 *
	 * @access private
	 * @param mixed $email
	 * @return int the user id
	 */
	public function is_google_user($user)
	{
		return ($user->type == $this->_model->get_mapping()::GOOGLE_LOGIN_TYPE) && ($user->status == $this->_model->get_mapping()::ACTIVE);
	}

	/**
	 * existing_github_user_from_email function that find if user is github user.
	 *
	 * @access private
	 * @param mixed $email
	 * @return int the user id
	 */
	private function is_github_user($user)
	{
		return ($user->type == $this->_model->get_mapping()::GITHUB_LOGIN_TYPE) && ($user->status == $this->_model->get_mapping()::ACTIVE);
	}

	/**
	 * existing_facebook_user_from_email function that find if user is facebook user.
	 *
	 * @access private
	 * @param mixed $email
	 * @return int the user id
	 */
	public function is_facebook_user($user)
	{
		return ($user->type == 'f') && ($user->status == $this->_model->get_mapping()::ACTIVE);
	}
}