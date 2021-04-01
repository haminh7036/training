<?php

namespace App\Http\Controllers\Modules\User\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.modules.user.user.index');
    }

    public function getUsers(Request $request)
    {
        //get user
        $user = UserModel::where('is_delete', 0)
        ->orderByDesc('created_at')
        ->cursor();

        return response()
        ->json([
            'data' => $user
        ], 200);
    }
}
