<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.product.product.index');
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
            'email' => 'required | string | email | exists:mst_users,email',
            'password' => 'required'
        ], [], [
            'email' => 'Email',
            'password' => 'Mật khẩu'
        ]);

        //Check account
        $user = UserModel::where('email', $request->email)
            ->where('is_active', 1)
            ->first() ?? null;

        if (empty($user)) {
            return back()
                ->withErrors([
                    'email' => 'Tài khoản bị vô hiệu hóa'
                ]);
        }

        $info = $this->getData($request->only('email', 'password'));

        if (isset($request->remember)) {
            $remember = (bool) $request->remember;
        } else {
            $remember = false;
        }

        if (Auth::attempt($info, $remember)) {
            //Login succeed

            //Save login time and ip
            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $request->getClientIp();

            $user->save();

            //Redirect to Product Management List
            return redirect()->route('admin.product.product.index');
        } else {
            return back()
                ->withErrors([
                    'password' => 'Mật khẩu không chính xác'
                ]);
        }
    }

    public function postLogout(Request $request)
    {
        Auth::logout();
        return redirect()
            ->route('home');
    }
}
