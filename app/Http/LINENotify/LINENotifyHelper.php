<?php
/**
 * Created by PhpStorm.
 * User: Tou-ChI
 * Date: 11/11/2018
 * Time: 9:28 PM
 */
namespace App\Http\LINENotify;

use GuzzleHttp\Client;

class LINENotifyHelper{

    public static function getToken($code){
        $client = new Client();
        $res = $client->request('POST', 'https://notify-bot.line.me/oauth/token',
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => route('line.oauth.callback'),
                    'client_id' => config('line.client_id'),
                    'client_secret' => config('line.client_secret')
                ]
            ]
            );
        $res->getStatusCode();
        $result = \GuzzleHttp\json_decode($res->getBody(), true);
        return $result['access_token'];
    }

    public static function sendMessageNotification($token, $message){
        $client = new Client();
        $res = $client->request('POST', 'https://notify-api.line.me/api/notify',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token
                ],
                'multipart' => $message
            ]
        );
        return $res->getStatusCode();
    }

    public static function generateLoginURL(){
        $param = [
            'response_type' => 'code',
            'client_id' => config('line.client_id'),
            'redirect_uri' => route('line.oauth.callback'),
            'scope' => 'notify',
            'state' => csrf_token(),
            'response_mode' => 'form_post'
        ];
        $redirect = 'https://notify-bot.line.me/oauth/authorize?'.http_build_query($param);
        return $redirect;
    }
}

