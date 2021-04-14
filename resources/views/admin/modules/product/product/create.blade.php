@extends('admin.layouts.main')

@section('title')
    Thêm mới sản phẩm
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Thêm mới sản phẩm</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.product.product.index')}}">Product</a></li>
                <li class="breadcrumb-item active">Chi tiết sản phẩm</li>
            </ol>
            </div>
        </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="" method="post" class="row">
                @include('admin.modules.product.product._control')
            </form>
        </div>
    </section>
@endsection

@section('script')
    
@endsection