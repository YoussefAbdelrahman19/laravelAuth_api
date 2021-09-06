<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    //fn for email verifiy
    public function sendVerificationEmail(Request $request){
        if($request->user()->hasVerifiedEmail()){
            return ([
                'message' =>'Already Verified'
            ]);
        }
        $request->user()->sendEmailVerificationNotification();
        return (['status'=>'verification-link-sent']);
    }
    //check if the user verify or not
    public function verifiy(EmailVerificationRequest $request){

        if($request->user()->hasVerifiedEmail()){
            return ([
                'message' =>'Email Already Verified'
            ]);
        }
        if($request->user()->markEmailAsVerified()){
            event( new Verified($request->user()));

        }
        return ([
            'message' =>'Email has been Verified'
        ]);
    }
}
