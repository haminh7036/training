<?php

namespace App\Http\Controllers\Modules\User\User;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        //\DebugBar::info('message');
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
        if (!empty($request->name)) {
            $users = $users->where('name', 'like', '%'. $request->name .'%');
        }
        
        if (!empty($request->email)) {
            $users = $users->where('email', 'like', '%'. $request->email .'%');
        }

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

    public function addUser(Request $request) {
        //add user
        $request->validate([
            'name' => 'required | string',
            'email' => 'required | string | email | unique:mst_users,email',
            'password' => 'required | string | min:5 | regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/',
            'group_role' => 'required | in:Admin,Reviewer,Editor',
            'is_active' => 'boolean'
        ]);
        
        $data = $request->except('password', 'is_active');

        $active = isset($request->is_active) ? (int) $request->is_active : 1;
        $password = Hash::make($request->password);

        UserModel::create(array_merge($data, [
            'password' => $password,
            'is_active' => $active
        ]));

        return response()
        ->json([
            'message' => 'success'
        ], 200);
    }

    public function uniqueEmail(Request $request)
    {
        //check if email has been existed
        $validator = Validator::make($request->all(), [
            'email' => 'unique:mst_users,email',
        ]);

        if ($validator->fails()) {
            return response()
            ->json([
                'email' => false
            ], 200);
        }

        return response()
            ->json([
                'email' => true
            ], 200);
    }

    public function uniqueEmailEdit(Request $request)
    {
        //check if email has been existed
        $validator = Validator::make($request->all(), [
            'email' => Rule::unique('mst_users', 'email')
                ->ignore($request->oldEmail)
        ]);

        if ($validator->fails()) {
            return response()
            ->json([
                'email' => false
            ], 200);
        }

        return response()
            ->json([
                'email' => true
            ], 200);
    }

    public function editUser(Request $request)
    {
        //edit user
        $request->validate([
            'id' => 'required | exists:mst_users,id',
            'name' => 'required | string',
            'password' => 'required | string | min:5 | regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/',
            'group_role' => 'required | in:Admin,Reviewer,Editor',
            'is_active' => 'boolean'
        ]);

        if ($request->has('email')) {
            $request->validate([
                'email' => 'string | email | unique:mst_users,email'
            ]);
        }

        $data = $request->except('password', 'is_active');

        $active = isset($request->is_active) ? (int) $request->is_active : 1;
        $password = Hash::make($request->password);

        UserModel::where('id','=', $request->id) 
            ->update(array_merge($data, [
                'password' => $password,
                'is_active' => $active
            ]));

        return response()
        ->json([
            'message' => 'success'
        ], 200);
    }

    public function getUsersAjax(Request $request)
    {
        $users = UserModel::query()
            ->where('is_delete', 0)->latest();

        return DataTables::of($users)
            ->addColumn('action', function ($users) {
                return '
                <button type="button" class="btn btn-outline-primary"
                    onclick ="getEditId(' . $users->id . ')">
                    <i class= "fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-danger" 
                    onclick ="getDeleteId(' . $users->id . ')">
                    <i class= "fas fa-trash-alt"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" 
                    onclick ="getBlockId(' . $users->id . ')">
                    <i class= "fas fa-user-times"></i>
                </button>
                
                ';
            })
            ->rawColumns(['action'])
            ->setRowId(function ($users) {
                return "rowId-" . $users->id;
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->name)) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                }

                if (isset($request->status)) {
                    $query->where('is_active', '=', intval($request->status));
                }

                if (!empty($request->email)) {
                    $query->where('email', 'like', '%'.$request->email.'%');
                }

                if (!empty($request->role)) {
                    $query->where('group_role', 'like', $request->role);
                }
            })
            ->toJson();
    }

    public function emailUniqueUser(Request $request)
    {
        //validate
        $validator = Validator::make($request->all(), [
            'inputEmail' => ['required',
                Rule::unique('mst_users','email')
                    ->ignore($request->get('oldEmail'), 'email')
                ]
        ]);
        if ($validator->fails()) {
            return response(json_encode(false), 200,[
                'Content-type' => 'application/json'
            ]);
        }
        return response(json_encode(true) , 200,[
            'Content-type' => 'application/json'
        ]);
    }
}
