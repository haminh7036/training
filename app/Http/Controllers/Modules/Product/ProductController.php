<?php

namespace App\Http\Controllers\Modules\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use Illuminate\Http\Request;
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
            <button type="button" class="btn btn-outline-primary" onclick= "Edit(`' . $products->product_id . '`)">
                <i class= "fas fa-edit"></i>
            </button>
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
        $request->validate([
            'product_id' => 'required | exists:mst_products,product_id'
        ]);

        ProductModel::where("product_id", "like", $request->product_id)
            ->delete();

        return response([
            'message' => 'success'
        ], 200);
    }
}
