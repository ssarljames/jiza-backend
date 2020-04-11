<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login (Request $request) {

        $user = User::where('username', $request->username)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client');
                // $response = ['token' => $token];
                // $user->token = $token;
                return response($token, 200);
            } else {
                $response = "Invalid Credentials";
                return response($response, 422);
            }

        }

        else {
            $response = 'Invalid Credentials';
            return response($response, 422);
        }

    }

    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->revoke();

        return response([
            'message'=>'You have been succesfully logged out!'
        ], 200);

    }

    public function register(Request $request){
        $rules = [
            'firstname' => 'required|max:50',
            'lastname'  => 'required|max:50',
            'email'     => 'required|unique:users,email|max:100|email',
            'password'  => 'required|min:8|max:20',
            'confirm_password' => 'same:password',
        ];

        $data = $request->validate($rules);
        $data['username'] = $data['email'];

        $user = User::create($data);

        return $user;
    }
}
