<?php defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Token Service
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Token_service
{
    private $_model;

    public function __construct()
    {

    }

    public function set_model($token_model)
    {
        $this->_model = $token_model;
    }

    /**
     * Generate access token
     *
     * @param string $key
     * @param string $domain
     * @param integer $expire_at
     * @param mixed $payload
     * @return mixed
     */
    public function generate_access_token($key, $domain, $expire_at, $payload)
    {
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;
        $expire = $notBefore + $expire_at;

        $token = [
            'iss' => $domain,
            'aud' => $domain,
            'iat'  => $issuedAt,           // Issued at: time when the token was generated
            'nbf'  => $notBefore,          // Not before
            'exp'  => $expire,             // Expire
            'data' => $payload
        ];

        $token_str = JWT::encode($token, $key, 'HS256');

        $this->_model->create([
            'token' => $token_str,
            'data' => json_encode($payload),
            'type' => $this->_model->get_mapping()::ACCESS_TOKEN,
            'user_id' => $payload['user_id'],
            'ttl' => $expire_at,
            'issue_at' => date('Y-m-j H:i:s'),
            'expire_at' => date('Y-m-j H:i:s', $expire),
            'status' => 1
        ]);

        return [
            'token' => $token_str,
            'expire_at' => $expire
        ];
    }

    /**
     * Generate Refresh Token
     * @param $user_id
     * @param $ttl
     * @return string
     */
    public function generate_refresh_token($user_id, $role_id, $ttl)
    {
        $token_str = md5(uniqid() . time());

        $token_exist = $this->_model->get_by_fields([
            'user_id' => $user_id,
            $this->_model->get_mapping()::REFRESH_TOKEN
        ]);

        if ($token_exist)
        {
            $this->_model->real_delete($token_exist->id);
        }

        $this->_model->create([
            'token' => $token_str,
            'data' => json_encode([
                'user_id' => $user_id,
                'role_id' => $role_id,
                'date' => date('Y-m-d')
            ]),
            'type' => $this->_model->get_mapping()::REFRESH_TOKEN,
            'user_id' => $user_id,
            'ttl' => $ttl,
            'issue_at' => date('Y-m-j H:i:s'),
            'expire_at' => date('Y-m-j H:i:s', time() + $ttl + 10),
            'status' => 1
        ]);

        return $token_str;
    }

    public function validate_refresh_token($user_id, $token)
    {
        $token_exist = $this->_model->get_by_fields([
            'user_id' => $user_id,
            'token' => $token,
            $this->_model->get_mapping()::REFRESH_TOKEN
        ]);

        if (!$token_exist)
        {
            return $this->_model->get_mapping()::NOT_EXIST;
        }

        $now = time();
        $expire_at = strtotime($token_exist->expire_at);

        if ($now > $expire_at)
        {
            return $this->_model->get_mapping()::EXPIRED;
        }

        return $this->_model->get_mapping()::ALIVE;
    }

    public function validate_token ($key, $authorization_token)
    {
        if (strlen($authorization_token) > 0)
        {
            $jwt = str_replace('Bearer ', '', $authorization_token);

            try
            {
                JWT::$leeway = 120;
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                return $decoded->data;
            }
            catch (\Exception $e)
            {
                log_message('error', $e->getMessage());
                return FALSE;
            }

        }

        return FALSE;
    }
}