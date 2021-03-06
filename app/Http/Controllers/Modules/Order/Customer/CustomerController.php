<?php

namespace App\Http\Controllers\Modules\Order\Customer;

use App\Exports\CustomerExport;
use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use App\Models\CustomerModel;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
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
            return '<button type="button" class="btn btn-outline-info"
                id= "editAction-'.$customers->customer_id.'" onclick= "Edit('.$customers->customer_id.')">
                <i class="fas fa-edit"></i>
            </button>';
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

    public function editEmailUnique(Request $request)
    {
        if ($request->oldEmail === $request->editEmail) {
            return response(json_encode(true) , 200,[
                'Content-type' => 'application/json'
            ]);
        }

        //validate unique if old email != new email
        $validator = Validator::make($request->all(), [
            'editEmail' => 'required|unique:mst_customers,email'
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

    public function editCustomer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|min:5',
            'email' => [
                        'required', 'email', 
                        Rule::unique("mst_customers", 'email')
                            ->ignore($request->oldEmail, "email")
                    ],
            'tel_num' => ['required', 'numeric', 'regex:/(84|0[3|5|7|8|9])+([0-9]{8})/'],
            'address' => 'required',
        ]);

        $data = $request->except('customerId', 'oldEmail');
        
        //update
        CustomerModel::where('customer_id','=', $request->customerId)
            ->update($data);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function uploadExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required | file | mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ], [
            'file.required' => 'H??y ch???n t???p tin ????? t???i l??n',
            'file.mimetypes' => 'T???p tin t???i l??n ph???i l?? excel file'
        ]);

        if ($validator->fails()) {
            return response([
                'errorCode' => 1,
                'message' => 'failed',
                'errors' => $validator
                    ->errors()->get('file')
            ], 200, [
                'Content-Type' => 'json'
            ]);
        }

        $data = $request->file;

        $import = new CustomerImport();
        $import->import($data);

        $errors = [];
        foreach ($import->failures() as $failure) {
            if ($failure->row() === 1) {
                continue;
            }

            array_push($errors, ' - D??ng th??? '. $failure->row().
                ' b??? l???i ??? c???t '. $failure->attribute());
        }

        return response([
            'errorCode' => 0,
            'message' => 'success',
            'errors' => $errors,
            'upload' => 'Okay'
        ], 200, [
            'Content-Type' => 'json'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'data' => 'required | array'
        ]);

        $export = new CustomersExport($request->data);

        return $export->download('Customer.xlsx', ExcelExcel::XLSX);
    }
}
