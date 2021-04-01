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
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
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
                                    <label for="role">Nhóm</label>
                                    <select name="role" id="role" class="form-control">
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
                                        <option value="1">Đang hoạt động</option>
                                        <option value="0">Tạm khóa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3 col-lg-2 pb-2">
                                <button class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Thêm mới</button>
                            </div>
                            <div class="col-xs-12 col-sm-3 offset-sm-3 col-lg-2 offset-lg-6 pb-2">
                                <button class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tìm kiếm</button>
                            </div>
                            <div class="col-xs-12 col-sm-3 col-lg-2 pb-2">
                                <button class="btn btn-success btn-block"><i class="fas fa-times"></i> Xóa tìm</button>
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
    </section>
      <!-- /.content -->
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#table-user").DataTable({
                dom: 'Bfrtip',
                responsive: true,
                "language": {
                    "decimal":        "",
                    "emptyTable":     "Không có dữ liệu trên table",
                    "info":           "Hiển thị từ _START_ đến _END_ của _TOTAL_ bản ghi",
                    "infoEmpty":      "Hiển thị 0 đến 0 của 0 bản ghi",
                    "infoFiltered":   "(Lọc từ _MAX_ bản ghi)",
                    "infoPostFix":    "",
                    "thousands":      ",",
                    "lengthMenu":     "Hiển thị _MENU_ bản ghi",
                    "loadingRecords": "Đang tải...",
                    "processing":     "Đang xử lý...",
                    "search":         "Tìm kiếm:",
                    "zeroRecords":    "Không tìm thấy kết quả nào",
                    "paginate": {
                        "first":      "Đầu tiên",
                        "last":       "Cuối cùng",
                        "next":       "Sau",
                        "previous":   "Trước"
                    },
                    "aria": {
                        "sortAscending":  ": sắp xếp cột tăng dần",
                        "sortDescending": ": sắp xếp cột giảm dần"
                    }
                },
                ajax: {
                    url: "{{route('admin.user.user.getUsers')}}",
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'group_role' },
                    { 
                        data: 'is_active',
                        render: function (data) {
                            if (data === 0) {
                                return '<label class = "text-danger">Tạm khóa</label>'
                            } else {
                                return '<label class = "text-success">Đang hoạt động</label>'
                            }
                        }
                    },
                    {
                        data: null,
                        defaultContent: 
                        `<center>
                            <i class="fas fa-edit fa-md text-info"></i> <i class="fas fa-trash-alt text-danger"></i> <i class="fas fa-user-times"></i>
                         </center>
                        `
                    }
                ]

            });
        });
    </script>
@endsection