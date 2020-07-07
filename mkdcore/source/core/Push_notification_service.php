<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Push Notification Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Push_notification_service
{
    /**
     * Push Notification URL
     *
     * @var string
     */
    private $_url;

    /**
     * Push Notification Server Key
     *
     * @var string
     */
    private $_server_key;

    /**
     * Push Notification Project Id
     *
     * @var string
     */
    private $_project_id;

    /**
     * CI.
     *
     * @var mixed
     */
    public $_ci = null;

    public function init()
    {
        $this->_url = 'https://fcm.googleapis.com/fcm/send';
        $this->_ci = &get_instance();
        $this->_server_key = $this->_ci->config->item('push_server_key');
        $this->_project_id = $this->_ci->config->item('push_project_id');
    }

    /**
     * Send push notification
     * https://firebase.google.com/docs/cloud-messaging/admin/send-messages.
     *
     * @param string $device_type
     * @param string $device_id
     * @param string $title
     * @param string $message
     * @param string $image
     */

    private function _send_request($fields)
    {
        $headers = [
            'Content-Type:application/json', 'project_id:' . $this->_project_id,
            'Authorization:key=' . $this->_server_key,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);

        return $ch;
    }



    public function send($device_type, $device_id, $title, $message, $image = '', $data = [])
    {
        $payload = [];
        $fields = [
            'registration_ids' => [
                $device_id
            ],

            'notification' => [
                'title' => $title,
                'body' => $message
            ],
        ];

        if ($device_type == 'ANDROID')
        {
            $fields = [
                'registration_ids' => [
                    $device_id
                ],

                'data' => [
                    'title' => $title,
                    'message' => $message,
                    'image' => $image,
                    "channelId" => "default",
                    "data" => $data
                ],
                
                'notification' =>  [
                    'title' => $title,
                    'body' => $message
                ]
            ];

           if(!empty($data) && is_array($data))
            {
                $fields['data'] = array_merge(  $fields['data'], $data);
            }
        }

        if($device_type == 'IOS')
        {
            $notification  =[
                'title' =>$title , 
                'text' => $message, 
                'sound' => 'default',
                'badge' => '0'
            ];

            $fields = [
                'to' => $device_id,
                'notification' => $notification, 
                'priority' => 'high',
                'data' => $data
            ];

        }

        $headers = [
            'Content-Type:application/json', 'project_id:' . $this->_project_id,
            'Authorization:key=' . $this->_server_key,
        ];

        $payload['headers'] = $headers;
        $payload['fields'] = $fields;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
        return $ch;
    } 
    
    
    public function send_multiple($devices, $title, $message, $image = '', $data = [])
    {
        $ios_ids = [];
        $android_ids = [];

        foreach($devices as $device)
        {
            if($device['type'] == 'ANDROID')
            {
                $android_ids[] = $device['device_id'];   
            }

            if($device['type'] == 'IOS')
            {
                $ios_ids[] = $device['device_id'];
            }
        }

        if(count($android_ids) > 0)
        {
            $fields = [
                'registration_ids' => $android_ids,
                'data' => [
                    'title' => $title,
                    'message' => $message,
                    'image' => $image,
                    "channelId" => "default",
                    "data" => $data
                ],
                'notification' =>  [
                    'title' => $title,
                    'body' => $message
                ]
            ];

            if(!empty($data) && is_array($data))
            {
                $fields['data'] = array_merge($fields['data'], $data);
            }

            $this->_send_request($fields);
        }

        if(count($ios_ids) > 0)
        {
            $notification  =[
                'title' =>$title , 
                'text' => $message, 
                'sound' => 'default',
                'badge' => '0'
            ];

            $fields = [
                'to' => $ios_ids,
                'notification' => $notification, 
                'priority' => 'high',
                'data' => $data
            ];
            
            $this->_send_request($fields);
        }
    }
}