<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.product.index');
        }

        return view('auth.login');
    }

    public function getData($data)
    {
        return [
            'email' => $data['email'],
            'password' => $data['password']
        ];
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required | string | email',
            'password' => 'required'
        ], [], [
            'email' => 'Email',
            'password' => 'Mật khẩu'
        ]);

        $info = $this->getData($request->only('email', 'password'));

        if (isset($request->remember)) {
            $remember = (bool) $request->remember;
        } else {
            $remember = false;
        }

        if(Auth::attempt($info, $remember)) {
            //Redirect to Product Management List
            return redirect()->route('admin.product.index');
        }
    }
}
