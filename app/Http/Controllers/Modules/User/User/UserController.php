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
        $users = UserModel::where('is_delete', 0)
        ->orderByDesc('created_at')
        ->cursor();

        return response()
        ->json([
            'data' => $users
        ], 200);
    }

    public function search(Request $request)
    {
        $users = UserModel::where('is_delete', 0);

        //processing search value
        $users = $users->where( function ($query) use ($request) {
            if (!empty($request->name)) {
                $query->orWhere('name', 'like', '%'. $request->name .'%');
            }
            if (!empty($request->email)) {
                $query->orWhere('email', 'like', '%'. $request->email .'%');
            }
        });

        if (!empty($request->role)) {
            $users = $users->role($request->role);
        }

        if ($request->status != "") {
            $users = $users->active($request->status);
        }
        
        $users = $users
        ->orderByDesc('created_at')
        ->cursor();

        return response()
        ->json([
            'data' => $users
        ], 200);
    }

    public function getInfoUser(Request $request)
    {
        //user information
        $user = UserModel::where('id', $request->id)
        ->first();

        return response()
        ->json([
            'data' => $user
        ], 200);
    }

    public function deleteUser(Request $request)
    {
        //delete user
        UserModel::where('id', $request->id)
            ->update([
                'is_delete' => 1
            ]);
        return response()
            ->json([
                'message' => 'success'
            ], 200);
    }

    public function blockUser(Request $request)
    {
        //block / unblock user
        $user = UserModel::where('id', $request->id)
            ->first();
        if ($user->is_active === 0) {
            $user->is_active = 1;
        } else {
            $user->is_active = 0;
        }
        $user->save();

        return response()
            ->json([
                'message' => 'success'
            ], 200);
    }
}
