<?php

/**
 * Created by PhpStorm.
 * User: lider07
 * Date: 27.04.17
 * Time: 11:21
 */

use Phalcon\Di;

class FCMNotifications
{
    public $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendPush($data, $title)
    {
        $msg = [
            'body' 	=> $data,
            'title'		=> $title,
            'vibrate'	=> 1,
            'sound'		=> 1,
        ];

        $fields = [
            'registration_ids' 	=> $this->getTokens(),
            'notification' => $msg
        ];

        $headers = [
            'Authorization: key=' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;
    }

    private function getTokens()
    {
        $tokens = explode("\n", file_get_contents(Di::getDefault()->getShared('config')->tokensFile));
        return array_filter($tokens);
    }
}