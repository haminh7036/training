<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], (bool) $request->get('remember', false))) {
            return response([
                'msg' => 'Error',
            ], 400, [
                'Content-Type' => 'application/json'
            ]);
        }

        $user = UserModel::where('id', Auth::id())
            ->first();
            
        $user->last_login_at = Carbon::now();
        $user->last_login_ip = $request->getClientIp();

        if ((bool) $request->get('remember', false) === false) {
            $user->remember_token = '';
        }

        $user->save();

        //$remember_token = $user->remember_token;

        $token = $user->createToken('user');

        return response([
            'msg' => 'Success',
            //'user' => $user,
            //'remember_token' => $remember_token,
            'token' => $token->plainTextToken
        ], 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    public function user(Request $request)
    {
        return response([
            'msg' => 'Success',
            'currentUser' => UserModel::where('id', Auth::id())->first()
        ], 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
