<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * User Factory
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class User_factory
{
    private $_user_model;
    private $_credential_model;

    
  
    public function __construct($credential_model = NULL, $user_model = NULL)
    {
        $this->_user_model = $user_model;
        $this->_credential_model = $credential_model; 
    }
    
    
    /**
     * @param object $user_model
     */
    public function set_user_model($user_model)
    {
        $this->_user_model = $user_model;
    }


    /**
     * @param object $user_model
    */
    public function set_credential_model($credential_model)
    {
        $this->_credential_model = $credential_model;
    }

    /**
     * Create User
     *
     * @param mixed $user_model
     * @param string $email
     * @param string $password
     * @param integer $role
     * @param string $type
     * @return mixed
     */
    public function create($user_model, $email, $password, $role, $type='n')
    {
        $credential_params = [
            'email' => $email,
            'role_id' => $role,
            'refer' => uniqid(),
            'verify' => 0,
            'type' => $type,
            'password' => str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT))
        ];

        $credential_id = $this->_credential_model->create($credential_params);
        
        if($credential_id)
        {
            $user_id = $this->_user_model->create([
                'email' => $email,
                'username' => $email,
                'first_name' => '',
                'last_name' => '',
                'profile_id' => 0,
                'stripe_id' => '',
                'image' => 'https://i.imgur.com/AzJ7DRw.png',
                'image_id' => 1,
                'credential_id' => $credential_id
            ]);
            return $user_id;
        }
        return FALSE;
    }

    public function create_full_user($user_model, $email, $password, $first_name, $last_name, $role, $type='n')
    {
        $credential_params = [
            'email' => $email,
            'role_id' => $role,
            'refer' => uniqid(),
            'verify' => 0,
            'type' => $type ?? 'n',
            'password' => str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT))
        ];

        $credential_id = $this->_credential_model->create($credential_params);

        if($credential_id)
        {
            $user_id =  $this->_user_model->create([
                'email' => $email,
                'username' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => '',
                'profile_id' => 0,
                'stripe_id' => '',
                'image' => 'https://i.imgur.com/AzJ7DRw.png',
                'image_id' => 1,
                'password' => str_replace('$2y$', '$2b$', password_hash($password, PASSWORD_BCRYPT)),
                'credential_id' => $credential_id
            ]);
            
            return $user_id;
        }
        return FALSE;
    }
}