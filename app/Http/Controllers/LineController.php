<?php

namespace App\Http\Controllers;

use App\Http\LINENotify\LINENotifyHelper;
use Illuminate\Http\Request;

class LineController extends Controller
{
    //
    public function handleCallback(Request $request){
        if($request->state != csrf_token()){
            return 'invalid request';
        }
        $code = $request->code;
        $token = LINENotifyHelper::getToken($code);
        LINENotifyHelper::sendMessageNotification($token, [
            [
                'name' => 'message',
                'contents' => 'You are now connected to My First Service through Line Notify API'
            ]
        ]);
        return 'message sent';
    }

    public function login(){
        return redirect()->away(LINENotifyHelper::generateLoginURL());
    }
}
