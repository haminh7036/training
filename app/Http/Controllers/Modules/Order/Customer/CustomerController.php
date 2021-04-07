<?php

namespace App\Http\Controllers\Modules\Order\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.modules.order.customer.index');
    }

    public function getCustomers(Request $request)
    {
        //get customer data
        $customers = CustomerModel::query()->latest();

        return DataTables::of($customers)
        ->addColumn('edit', function ($customers) {
            return '<i class="fas fa-edit text-info extend-btn" onclick= "Edit('.$customers->customer_id.')"></i>';
        })
        ->rawColumns(['edit'])
        ->setRowId(function ($customers) {
            return "rowId-".$customers->customer_id;
        })
        ->filter(function ($query) use ($request) {
            if (!empty($request->name)) {
                $query->where('customer_name', 'like', '%'.$request->name.'%');
            }
            if (!empty($request->email)) {
                $query->where('email', 'like', '%'.$request->email.'%');
            }
            if (!empty($request->address)) {
                $query->where('address', 'like', '%'.$request->address.'%');
            }
            if (isset($request->status)) {
                $query->where('is_active', '=', intval($request->status));
            }
        })
        ->toJson();
    }

    public function emailUnique(Request $request)
    {
        //validate unique
        $validator = Validator::make($request->all(), [
            'inputEmail' => 'required|unique:mst_customers,email'
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

    public function addCustomer(Request $request)
    {
        //add customer
        $request->validate([
            'customer_name' => 'required|string|min:5',
            'email' => 'required|email|unique:mst_customers,email',
            'tel_num' => ['required', 'numeric', 'regex:/(84|0[3|5|7|8|9])+([0-9]{8})/'],
            'address' => 'required',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        //insert
        CustomerModel::create($data);

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
