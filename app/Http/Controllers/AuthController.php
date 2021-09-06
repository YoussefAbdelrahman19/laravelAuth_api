<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
     $fileds=$request->validate([
        'name'=>'required|string',
        'email'=>'required|string|unique:users,email|email',
        'password'=>'required|string|confirmed'
        ]);
     $user=User::create([
    'name' => $fileds['name'],
    'email' => $fileds['email'],
    'password' =>bcrypt($fileds['password'])
    ]);
    $token = $user->createToken('myUserToken')->plainTextToken;
    $response = [
        'user'=>$user,
        'token'=>$token
    ];
    return response($response,201);

    }
    //login
    public function login(Request $request){
        $fileds=$request->validate([
           'email'=>'required|string|email',
           'password'=>'required|string'
           ]);
        //check if the email exits
       $user=User::where('email',$fileds['email'])->first();
       //check the password
       if(!$user||!Hash::check($fileds['password'],$user->password)){
           return response([
               'message' =>'There is an error in email or Password'
           ],401);
          //401 means the user unauthorized
        }

       $token = $user->createToken('myUserToken')->plainTextToken;
       $response = [
           'user'=>$user,
           'token'=>$token
       ];
       return response($response,201);

       }
       //logout the users
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>'Logged out',
        ];
    }
}
