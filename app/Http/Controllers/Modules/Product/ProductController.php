<?php

namespace App\Http\Controllers\Modules\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Barryvdh\Debugbar\Facade as DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.modules.product.product.index');
    }

    public function getProducts(Request $request)
    {
        $products = ProductModel::query()->latest();

        return DataTables::of($products)
            ->addColumn('action', function ($products) {
                return '
                <a href="'.route('admin.product.product.edit', $products->product_id).'">
                    <button type="button" class="btn btn-outline-primary">
                        <i class= "fas fa-edit"></i>
                    </button>
                </a>
                <button type="button" class="btn btn-outline-danger" onclick ="Delete(`' . $products->product_id . '`)">
                    <i class= "fas fa-trash-alt"></i>
                </button>
                ';
            })
            ->rawColumns(['action'])
            ->setRowId(function ($products) {
                return "rowId-" . $products->product_id;
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->name)) {
                    $query->where('product_name', 'like', '%' . $request->name . '%');
                }

                if (isset($request->status)) {
                    $query->where('is_sales', '=', intval($request->status));
                }

                if (!empty($request->price_from)) {
                    $query->where('product_price', '>=', $request->price_from);
                }

                if (!empty($request->price_to)) {
                    $query->where('product_price', '<=', $request->price_to);
                }
            })
            ->toJson();
    }

    public function destroy(Request $request)
    {
        //delete product
        $request->validate([
            'product_id' => 'required | exists:mst_products,product_id'
        ]);

        ProductModel::where("product_id", "like", $request->product_id)
            ->delete();

        return response([
            'message' => 'success'
        ], 200);
    }

    public function create()
    {
        return view('admin.modules.product.product.create');
    }

    public function store(Request $request)
    {
        //insert new product
        $request->validate([
            'product_name' => 'required | string | min:5',
            'product_price' => 'required | numeric | min:0',
            'is_sales' => 'required'
        ]);

        $data = $request->all();
        
        //product id
        $firstCharacter = strtoupper(substr($data ['product_name'], 0, 1));
        $count = ProductModel::where("product_id", "like", $firstCharacter."%")
            ->count();
        $productId = $firstCharacter.str_pad(($count + 1), 9, '0', STR_PAD_LEFT);
        $data ['product_id'] = $productId;

        ProductModel::create($data);

        return redirect()
            ->route('admin.product.product.index');
    }

    public function edit(Request $request, $id)
    {
        //edit product
        $product = ProductModel::where("product_id", $id)
        ->first() ?? abort(404);
        
        session()->flashInput(json_decode(json_encode($product), true));
        
        return view('admin.modules.product.product.edit');
    }

    public function update(Request $request, $id)
    {
        //update product
        $product = ProductModel::where("product_id", $id)
        ->first() ?? abort(404);

        $request->validate([
            'product_name' => 'required | string | min:5',
            'product_price' => 'required | numeric | min:0',
            'is_sales' => 'required'
        ]);

        $data = $request->all();
        $product->update($data);

        return redirect()
            ->route('admin.product.product.index');
    }

    public function file(Request $request)
    {
        //upload and delete file
        $request->validate([
            'function' => 'required | in:upload,delete'
        ]);
        
        if ($request->function === "upload") {
            //upload
            $validator = Validator::make([
                'file' => $request->file('file')
            ], [
                'file' => 'required | file | max:2048 | mimes:jpg,jpeg,png'
            ]);
            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->get('file')
                ], 422, [
                    'Content-Type' => 'application/json; charset=utf-8'
                ]);
            }
            $file = $request->file('file');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('files/images'), $fileName);
            
            return response([
                'filePath' => '/files/images/'.$fileName
            ], 200, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        } else {
            //delete
            $validator = Validator::make([
                'filePath' => $request->get('filePath')
            ], [
                'filePath: required'
            ]);
            if ($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->get('filePath')
                ], 422, [
                    'Content-Type' => 'application/json; charset=utf-8'
                ]);
            }
            $filePath = $request->get('filePath');

            if(File::exists(public_path().$filePath)) {
                File::delete(public_path().$filePath);
            } else {
                return response([
                    'errors' => ['Không có tìm thấy file']
                ], 422, [
                    'Content-Type' => 'application/json; charset=utf-8'
                ]);
            }
            
            return response([
                'message' => 'Đã xóa file'
            ], 200, [
                'Content-Type' => 'application/json; charset=utf-8'
            ]);
        }
    }
}
