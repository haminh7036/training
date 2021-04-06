<?php

namespace App\Http\Controllers\Modules\Order\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
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
        $customers = CustomerModel::query();

        return DataTables::of($customers)
        ->addColumn('edit', function ($customers) {
            return '<i class="fas fa-edit text-info extend-btn" onclick= "Edit('.$customers->customer_id.')"></i>';
        })
        ->rawColumns(['edit'])
        ->setRowId('customer_id')
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

}
