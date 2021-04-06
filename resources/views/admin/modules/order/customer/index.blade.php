@extends('admin.layouts.main')

@section('title')
    Quản lý khách hàng
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Customer Management List</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.order.customer.index')}}">Customer</a></li>
                <li class="breadcrumb-item active">Customer Management List</li>
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
                        <div class="row">
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="name">Tên</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email">
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="1">Đang hoạt động</option>
                                        <option value="0">Tạm khóa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input class="form-control" type="text" name="address" id="address">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button id="btn-add" class="btn btn-sm btn-primary btn-right"><i class="fas fa-user-plus"></i> Thêm mới</button>
                                <button id="btn-import" class="btn btn-sm btn-primary btn-right"><i class="fas fa-user-plus"></i> Import CSV</button>
                                <button id="btn-export" class="btn btn-sm btn-primary"><i class="fas fa-user-plus"></i> Export CsV</button>
                            </div>
                            <div class="col-sm-6 right-align">
                                <button id="btn-search" class="btn btn-sm btn-primary btn-right"><i class="fas fa-search"></i> Tìm kiếm</button>
                                <button id="btn-delete-search" class="btn btn-sm btn-success"><i class="fas fa-times"></i> Xóa tìm</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-customer" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th>Điện thoại</th>
                                    <th style="text-align: center"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
          </div>
        </div>

        <!-- Hidden Input -->

        <!-- Modal -->
        <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="popupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="popupLabel">...</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="popupForm">
                            <div class="form-group form-inline">
                                <label for="inputName" class="pr-3">Tên</label>
                                <input type="text" class="form-control col-sm-10 ml-auto mr-0" name="inputName" id="inputName" placeholder="Nhập họ tên">
                            </div>
                            <div class="form-group form-inline email-form">
                                <label for="inputEmail" class="pr-3">Email</label>
                                <input type="text" class="form-control col-sm-10 ml-auto mr-0" name="inputEmail" id="inputEmail" placeholder="Nhập email">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputPassword" class="pr-3">Mật khẩu</label>
                                <input type="password" class="form-control col-sm-10 ml-auto mr-0" name="inputPassword" id="inputPassword" placeholder="Mật khẩu">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputRePassword" class="pr-3">Xác nhận</label>
                                <input type="password" class="form-control col-sm-10 ml-auto mr-0" name="inputPassword_confirmation" id="inputRePassword" placeholder="Xác nhận mật khẩu">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputGroup" class="pr-3">Nhóm</label>
                                <select name="inputGroup" class="form-control col-sm-10 ml-auto mr-0" id="inputGroup">
                                    <option value="Admin" selected>Admin</option>
                                    <option value="Reviewer">Reviewer</option>
                                    <option value="Editor">Editor</option>
                                </select>
                            </div>
                            <div class="form-group form-inline">
                                <label class="pr-3">Trạng thái</label>
                                <label class="font-weight-normal">
                                    <input type="checkbox" class="pr-3" value="1" name="inputActive" id="inputActive">
                                    <span>Hoạt động</span>
                                </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" id="submit-popup" class="btn btn-danger">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
      <!-- /.content -->
@endsection

@section('script')
    <script src="{{asset('js/admin/order/customer/index.js')}}"></script>
@endsection