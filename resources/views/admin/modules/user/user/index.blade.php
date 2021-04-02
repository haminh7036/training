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
        <input type="hidden" name="deleteUser" id="deleteUser">

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
    </section>
      <!-- /.content -->
@endsection

@section('script')
    <script>

        //config table
        var myConfig = {
            dom: 'Bfrtip',
            responsive: true,
            language: {
                "decimal":        "",
                "emptyTable":     "Không có dữ liệu",
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
            deferRender: true,
            pageLength: 20,
            ordering: false,
        }

        //function
        function getDeleteId (id) {
            $("#deleteUser").val(id);
            axios({
                url: "{{route('admin.user.user.userInfo')}}",
                method: "POST",
                data: {
                    id: id
                }
            }).then((res) => {
                $(".deleteUserModalContent").html(`Bạn có muốn xóa thành viên ${res.data.data.name} không?`);
                $("#deleteUserModal").modal("show");
            })
        }

        $(document).ready(function () {
            //init table
            $("#table-user").DataTable({
                dom: 'Bfrtip',
                responsive: true,
                language: {
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
                deferRender: true,
                pageLength: 20,
                ordering: false,
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
                        data: 'id',
                        render: function (data) {
                            return `
                            <center>
                                <i class="fas fa-edit text-info extend-btn"></i>
                                <i class="fas fa-trash-alt text-danger extend-btn" onclick= "getDeleteId(${data})"></i>
                                <i class="fas fa-user-times extend-btn"></i>
                            </center>
                            `;
                        }
                    }
                ],
            });

            //refresh search
            $("#btn-delete-search").on("click", function () {
                //remove input
                $("#name").val("");
                $("#email").val("");
                $("#role").val("");
                $("#status").val("");

                //reload table
                $("#table-user").DataTable().destroy();
                $("#table-user").DataTable({
                    dom: myConfig.dom,
                    responsive: myConfig.responsive,
                    language: myConfig.language,
                    pageLength: myConfig.pageLength,
                    ordering: myConfig.ordering,
                    deferRender: myConfig.deferRender,
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
                            data: 'id',
                            render: function (data) {
                                return `
                                <center>
                                    <i class="fas fa-edit text-info"></i>
                                    <i class="fas fa-trash-alt text-danger"></i>
                                    <i class="fas fa-user-times"></i>
                                </center>
                                `;
                            }
                        }
                    ],
                });
            })

            //search
            $("#btn-search").on("click", function () {
                var input = {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    role: $("#role").val(),
                    status: $("#status").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                // console.log(input);
                // axios({
                //     url: "{{route('admin.user.user.search')}}",
                //     method: "POST",
                //     data: input,
                // }).then((res) => {
                //     console.log(res.data);
                // })

                //processing
                $("#table-user").DataTable().destroy();
                $("#table-user").DataTable({
                    dom: myConfig.dom,
                    responsive: myConfig.responsive,
                    language: myConfig.language,
                    pageLength: myConfig.pageLength,
                    ordering: myConfig.ordering,
                    deferRender: myConfig.deferRender,
                    ajax: {
                        url: "{{route('admin.user.user.search')}}",
                        type: "POST",
                        data: input,
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
                            data: 'id',
                            render: function (data) {
                                return `
                                <center>
                                    <i class="fas fa-edit text-info"></i>
                                    <i class="fas fa-trash-alt text-danger"></i>
                                    <i class="fas fa-user-times"></i>
                                </center>
                                `;
                            }
                        }
                    ],
                });
            });

            //submit delete user
            $("#submit-delete").on("click", function () {
                axios({
                    url: "{{route('admin.user.user.userDelete')}}",
                    method: "POST",
                    data: {
                        id: $("#deleteUser").val()
                    }
                }).then((res) => {
                    $("#table-user").DataTable().ajax.reload();
                    $("#deleteUserModal").modal('toggle');
                })
            });
        });

    </script>
@endsection