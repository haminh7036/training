@extends('admin.layouts.main')

@section('title')
    Quản lý người dùng
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>User Management List</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.user.user.index')}}">User</a></li>
                <li class="breadcrumb-item active">User Management List</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
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
                                    <label for="role">Nhóm</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Reviewer">Reviewer</option>
                                        <option value="Editor">Editor</option>
                                    </select>
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
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 col-lg-2 pb-2">
                                <button id="btn-add" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Thêm mới</button>
                            </div>
                            <div class="col-xs-12 col-sm-3 offset-sm-3 col-lg-2 offset-lg-6 pb-2">
                                <button id="btn-search" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tìm kiếm</button>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-lg-2 pb-2">
                                <button id="btn-delete-search" class="btn btn-success btn-block"><i class="fas fa-times"></i> Xóa tìm</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-user" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Nhóm</th>
                                    <th>Trạng thái</th>
                                    <th></th>
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
        <input type="hidden" id="deleteUser">
        <input type="hidden" id="blockUser">
        <input type="hidden" id="methodPopup">
        <input type="hidden" id="userId">
        <input type="hidden" id="emailUser">

        <!-- Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserLabel">Nhắc nhở</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body deleteUserModalContent">
                    ...
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Hủy bỏ</button>
                    <button type="button" id="submit-delete" class="btn btn-sm btn-primary">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="blockUserModal" tabindex="-1" role="dialog" aria-labelledby="blockUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="blockUserLabel">Nhắc nhở</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body blockUserModalContent">
                    ...
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Hủy bỏ</button>
                    <button type="button" id="submit-block" class="btn btn-sm btn-primary">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>

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
                                <input type="text" class="form-control col-sm-10" name="inputName" id="inputName" placeholder="Nhập họ tên">
                            </div>
                            <div class="form-group form-inline email-form">
                                <label for="inputEmail" class="pr-3">Email</label>
                                <input type="text" class="form-control col-sm-10" name="inputEmail" id="inputEmail" placeholder="Nhập email">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputPassword" class="pr-3">Mật khẩu</label>
                                <input type="password" class="form-control col-sm-10" name="inputPassword" id="inputPassword" placeholder="Mật khẩu">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputRePassword" class="pr-3">Xác nhận</label>
                                <input type="password" class="form-control col-sm-10" name="inputPassword_confirmation" id="inputRePassword" placeholder="Xác nhận mật khẩu">
                            </div>
                            <div class="form-group form-inline">
                                <label for="inputGroup" class="pr-3">Nhóm</label>
                                <select name="inputGroup" class="form-control col-sm-10" id="inputGroup">
                                    <option value="Admin" selected>Admin</option>
                                    <option value="Reviewer">Reviewer</option>
                                    <option value="Editor">Editor</option>
                                </select>
                            </div>
                            <div class="form-group form-inline">
                                <label class="pr-3">Trạng thái</label>
                                <label class="text-nowrap font-weight-normal user-checkbox">
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
    <script src="{{asset('js/admin/user/index_2.js')}}"></script>
@endsection