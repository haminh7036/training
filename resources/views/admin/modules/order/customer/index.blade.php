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
                        <div class="row search-bar">
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
                                <button id="btn-import" class="btn btn-sm btn-primary btn-right"><i class="fas fa-file-import"></i> Import CSV</button>
                                <button id="btn-export" class="btn btn-sm btn-primary"><i class="fas fa-file-export"></i> Export CSV</button>
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
                    <div class="loading d-none" id="export-loading">
                        <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                </div>
          </div>
        </div>

        <!-- Hidden Input -->
        <input type="hidden" id="editable" value="0">
        <input type="hidden" id="editId">
        <input type="hidden" id="oldEmail">
        <!-- Modal -->
        <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="popupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="popupLabel">Thêm khách hàng</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="popupForm">
                            <div class="form-group form-inline">
                                <label for="inputName">Tên</label>
                                <input type="text" class="form-control col-sm-9" name="inputName" id="inputName" placeholder="Nhập họ tên">
                            </div>
                            <div class="form-group form-inline email-form">
                                <label for="inputEmail">Email</label>
                                <input type="text" class="form-control col-sm-9" name="inputEmail" id="inputEmail" placeholder="Nhập email">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputPhone">Điện thoại</label>
                                <input type="text" class="form-control col-sm-9" name="inputPhone" id="inputPhone" placeholder="Điện thoại">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputAddress">Địa chỉ</label>
                                <input type="text" class="form-control col-sm-9" name="inputAddress" id="inputAddress" placeholder="Địa chỉ">
                            </div>
                            <div class="form-group form-inline">
                                <label>Trạng thái</label>
                                <div class="checkbox col-sm-9">
                                    <label class="font-weight-normal">
                                        <input type="checkbox" value="1" name="inputActive" id="inputActive"> Hoạt động
                                    </label>
                                </div>
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
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="importModalTitle">Import Excel</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile04">
                                <label class="custom-file-label" for="inputGroupFile04"></label>
                            </div>
                            <div class="input-group-append">
                                <button id="btn-upload-file" class="btn btn-outline-secondary" type="button">Upload</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" readonly id="errorFileRow" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
                <div class="loading d-none" id="import-loading">
                    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
              </div>
            </div>
          </div>


        <div class="d-none">
            <form class="form-horizontal" id="editForm">
                <input type="text" class="form-control col-sm-9" name="editName" id="editName" placeholder="Nhập họ tên">
                <input type="text" class="form-control col-sm-9" name="editEmail" id="editEmail" placeholder="Nhập email">
                <input type="text" class="form-control col-sm-9" name="editAddress" id="editAddress" placeholder="Địa chỉ">
                <input type="text" class="form-control col-sm-9" name="editPhone" id="editPhone" placeholder="Điện thoại">
            </form>
        </div>

    </section>
      <!-- /.content -->
@endsection

@section('script')
    <script src="{{asset('js/admin/order/customer/index.js')}}"></script>
@endsection