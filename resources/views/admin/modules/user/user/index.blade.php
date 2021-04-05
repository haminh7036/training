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
        <input type="hidden" id="deleteUser">
        <input type="hidden" id="blockUser">
        <input type="hidden" id="methodPopup">
        <input type="hidden" id="userId">

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

        function getBlockId (id) {
            $("#blockUser").val(id);
            axios({
                url: "{{route('admin.user.user.userInfo')}}",
                method: "POST",
                data: {
                    id: id
                }
            }).then((res) => {
                $(".blockUserModalContent").html(`Bạn có muốn ${(res.data.data.is_active === 0) ? 'mở khóa' : 'khóa'} thành viên ${res.data.data.name} không?`);
                $("#blockUserModal").modal("show");
            })            
        }

        function getEditId (id) {
            $("#userId").val(id);
            $("#methodPopup").val("edit");
            $("#popupLabel").text("Chỉnh sửa User");
            $("#popupModal").modal("show");
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
                                <i class="fas fa-edit text-info extend-btn" onclick= "getEditId(${data})"></i>
                                <i class="fas fa-trash-alt text-danger extend-btn" onclick= "getDeleteId(${data})"></i>
                                <i class="fas fa-user-times extend-btn" onclick= "getBlockId(${data})"></i>
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
                                    <i class="fas fa-edit text-info extend-btn" onclick= "getEditId(${data})"></i>
                                    <i class="fas fa-trash-alt text-danger extend-btn" onclick= "getDeleteId(${data})"></i>
                                    <i class="fas fa-user-times extend-btn" onclick= "getBlockId(${data})"></i>
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
                                    <i class="fas fa-edit text-info extend-btn" onclick= "getEditId(${data})"></i>
                                    <i class="fas fa-trash-alt text-danger extend-btn" onclick= "getDeleteId(${data})"></i>
                                    <i class="fas fa-user-times extend-btn" onclick= "getBlockId(${data})"></i>
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

            //submit block/unblock user
            $("#submit-block").on("click", function () {
                axios({
                    url: "{{route('admin.user.user.userBlock')}}",
                    method: "POST",
                    data: {
                        id: $("#blockUser").val()
                    }
                }).then((res) => {
                    $("#table-user").DataTable().ajax.reload();
                    $("#blockUserModal").modal('toggle');
                })
            });

            //add user button
            $("#btn-add").on("click", function () {
                $("#popupLabel").text("Thêm User");
                $("#methodPopup").val("add");
                $("#popupModal").modal("show");
            })

            //validate
            $.validator.addMethod("passwordRegexp", function (value, element) {
                return this.optional(element) || /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/.test(value);
            }, 'Mật khẩu không bảo mật');

            popupForm = $("#popupForm");
            popupForm.validate({
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                errorElement: "small",
                errorClass: "is-invalid text-danger",
                rules: {
                    "inputName" : {
                        required: true,
                    },
                    "inputEmail" : {
                        required : true,
                        email: true
                    },
                    "inputPassword" : {
                        required : true,
                        minlength: 5,
                        passwordRegexp: true
                    },
                    "inputPassword_confirmation" : {
                        required: true,
                        equalTo: "#inputPassword"
                    },

                },
                messages: {
                    "inputEmail" : {
                        email: "Email không đúng định dạng"
                    },
                    "inputPassword_confirmation" : {
                        equalTo: "Mật khẩu xác nhận không trùng khớp"
                    }
                },
                errorPlacement: function(label, element) {
                    label.addClass('error-text');
                    label.insertAfter(element);
                },
                wrapper: 'span',
                highlight: function ( element, errorClass) { 
                    $ ( element ).addClass(errorClass).removeClass("text-danger");
                }, 
            });

            $("#submit-popup").on("click", function() {
                var result = popupForm.valid();

                if (result === true) {
                    var requestData = {
                        name: $("#inputName").val(),
                        password: $("#inputPassword").val(),
                        email: $("#inputEmail").val(),
                        group_role: $("#inputGroup").val(),
                        is_active: ($("#inputActive").is(":checked") === true) ? 1 : 0,
                    };

                    //email unique
                    axios({
                        url: "{{route('admin.user.user.uniqueEmail')}}",
                        method: "POST",
                        data: {
                            email : requestData.email
                        }
                    }).then((res) => {
                        if (res.data.email === false) {
                            $("#inputEmail").addClass("is-invalid");
                            if ($(".email-error").length === 0) {
                                $(".email-form").append(`
                                    <span class="error-text email-error" style="">
                                        <small id="inputName-error" class="text-danger" style="">
                                            Email đã tồn tại.
                                        </small>
                                    </span>`);                                
                            }
                        } else {
                            emailError = $(".email-error");
                            if (emailError.length) {
                                emailError.remove();
                            }
                            $("#inputEmail").removeClass("is-invalid");

                            if ($("#methodPopup").val() === 'add') {
                                //add user
                                axios({
                                    url: "{{route('admin.user.user.userAdd')}}",
                                    method: "POST",
                                    data: requestData
                                }).then((res) => {
                                    $("#table-user").DataTable().ajax.reload();
                                    $("#popupModal").modal("toggle");
                                })
                            }
                        }
                    })
                }
            });
        });


    </script>
@endsection