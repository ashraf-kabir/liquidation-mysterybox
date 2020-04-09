<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Mapping Class
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Mapping
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    const SUSPEND = 2;
    const PENDING = 3;

    const GOOGLE_LOGIN_TYPE = 'g';
    const FACEBOOK_LOGIN_TYPE = 'f';
    const GITHUB_LOGIN_TYPE = 'h';
    const TWITTER_LOGIN_TYPE = 'h';

    const FORGOT_TOKEN = 0;
    const ACCESS_TOKEN = 1;
    const REFRESH_TOKEN = 2;
    const OTHER = 3;
    const API_KEY = 4;
    const API_SECRET = 5;
    const VERIFY = 6;

    const ALIVE = 0;
    const EXPIRED = 1;
    const NOT_EXIST = 2;

    /**
     * User Mapping.
     *
     * @return array
     */
    public function user_mapping()
    {
        return [
            0 => 'Inactive',
            1 => 'Active',
            2 => 'Suspend',
            3 => 'Pending',
        ];
    }

    /**
     * Status Mapping.
     *
     * @return array
     */
    public function status_mapping()
    {
        return [
            0 => 'Inactive',
            1 => 'Active',
        ];
    }
}
