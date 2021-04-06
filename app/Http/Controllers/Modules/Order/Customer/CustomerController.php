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

    public function getCustomers()
    {
        $customers = CustomerModel::query();

        return DataTables::of($customers)
        ->addColumn('edit', function ($customers) {
            return '<i class="fas fa-edit text-info extend-btn" onclick= "getEditId(${data})"></i>';
        })
        ->toJson();
    }
}
