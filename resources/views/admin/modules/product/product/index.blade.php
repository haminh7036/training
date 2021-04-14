@extends('admin.layouts.main')

@section('title')
    Quản lý sản phẩm
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Product Management List</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.product.product.index')}}">Product</a></li>
                <li class="breadcrumb-item active">Product Management List</li>
              </ol>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
    </section>
  
      <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row search-bar">
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="name">Tên sản phẩm</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="1">Đang bán</option>
                                        <option value="0">Hết hàng</option>
                                        <option value="-1">Ngừng bán</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="price_from">Giá bán từ</label>
                                    <input class="form-control" type="text" name="price_from" id="price_from">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="price_from">Giá bán đến</label>
                                    <input class="form-control" type="text" name="price_to" id="price_to">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="{{route('admin.product.product.create')}}">
                                    <button id="btn-add" class="btn btn-sm btn-primary btn-right"><i class="fas fa-user-plus"></i> Thêm mới</button>
                                </a>
                            </div>
                            <div class="col-sm-6 right-align">
                                <button id="btn-search" class="btn btn-sm btn-primary btn-right"><i class="fas fa-search"></i> Tìm kiếm</button>
                                <button id="btn-delete-search" class="btn btn-sm btn-success"><i class="fas fa-times"></i> Xóa tìm</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-product" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Mô tả</th>
                                    <th>Giá</th>
                                    <th>Tình trạng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="loading d-none" id="export-loading">
                        <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                </div>
          </div>
        </div>

        <!-- Hidden Input -->
        <input type="hidden" id="deleteProductId">
        <img src="" id="img-hover" class="img-hover" style="display: none" alt="">
        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xóa sản phẩm</h5>
                    </div>
                    <div class="modal-body" id="deleteModalContent">
                        
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" id="submit-delete" class="btn btn-danger">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
      <!-- /.content -->
@endsection

@section('script')
    <script src="{{asset('js/admin/product/index.js')}}"></script>
@endsection