const { default: axios } = require("axios");

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
window.getDeleteId = function getDeleteId (id) {
    $("#deleteUser").val(id);
    axios({
        url: "/admin/user/user-info",
        method: "POST",
        data: {
            id: id
        }
    }).then((res) => {
        $(".deleteUserModalContent").html(`Bạn có muốn xóa thành viên ${res.data.data.name} không?`);
        $("#deleteUserModal").modal("show");
    })
}

window.getBlockId = function getBlockId (id) {
    $("#blockUser").val(id);
    axios({
        url: "/admin/user/user-info",
        method: "POST",
        data: {
            id: id
        }
    }).then((res) => {
        $(".blockUserModalContent").html(`Bạn có muốn ${(res.data.data.is_active === 0) ? 'mở khóa' : 'khóa'} thành viên ${res.data.data.name} không?`);
        $("#blockUserModal").modal("show");
    })            
}

window.getEditId = function getEditId (id) {
    $("#userId").val(id);
    $("#methodPopup").val("edit");
    $("#popupLabel").text("Chỉnh sửa User");
    axios({
        url: "/admin/user/user-info",
        method: "POST",
        data: {
            id: id
        }        
    }).then((res) => {
        $("#inputName").val(res.data.data.name);
        $("#inputEmail").val(res.data.data.email);
        $("#inputGroup").val(res.data.data.group_role);
        $("#inputActive").prop('checked', (res.data.data.is_active === 1) ? true : false);
        //
        $("#emailUser").val(res.data.data.email);

        $("#popupModal").modal("show");
    })


}

$('#popupForm').on('keydown', 'input, select', function(e) {
    if (e.key === "Enter") {
        var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
        focusable = form.find('input,a,select,button,textarea').filter(':visible');
        next = focusable.eq(focusable.index(this)+1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});

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
        url: "/admin/user/user-list",
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
            url: "/admin/user/user-list",
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
            url: "/admin/user/user-search",
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
        url: "/admin/user/user-delete",
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
        url: "/admin/user/user-block",
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
            group_role: $("#inputGroup").val(),
            is_active: ($("#inputActive").is(":checked") === true) ? 1 : 0,
        };

        if ($("#methodPopup").val() === 'add') {
            //add user func
            
            requestData.email = $("#inputEmail").val();
            //email unique validate
            axios({
                url: "/admin/user/email-validate",
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

                    //add user
                    axios({
                        url: "/admin/user/user-add",
                        method: "POST",
                        data: requestData
                    }).then((res) => {
                        $("#table-user").DataTable().ajax.reload();
                        $("#popupModal").modal("toggle");
                    })
                }
            })
        } else {
            //edit user func
            
            requestData.id = $("#userId").val();
            //email unique validate
            var newEmail = $("#inputEmail").val();
            var oldEmail = $("#emailUser").val();
            if (newEmail !== oldEmail) {
                axios({
                    url: "/admin/user/email-validate-2",
                    method: "POST",
                    data: {
                        email: newEmail,
                        oldEmail: oldEmail
                    }
                }).then((res) => {
                    if (res.data.email === true) {
                        //valid
                        requestData.email = newEmail;

                        emailError = $(".email-error");
                        if (emailError.length) {
                            emailError.remove();
                        }
                        $("#inputEmail").removeClass("is-invalid");

                        axios({
                            url: "/admin/user/user-edit",
                            method: "POST",
                            data: requestData
                        }).then((res) => {
                            $("#table-user").DataTable().ajax.reload();
                            $("#popupModal").modal("toggle");
                        });
                    } else {
                        //invalid
                        $("#inputEmail").addClass("is-invalid");
                        if ($(".email-error").length === 0) {
                            $(".email-form").append(`
                                <span class="error-text email-error" style="">
                                    <small id="inputName-error" class="text-danger" style="">
                                        Email đã tồn tại.
                                    </small>
                                </span>`);                                
                        }
                    }
                })
            } else {
                axios({
                    url: "/admin/user/user-edit",
                    method: "POST",
                    data: requestData
                }).then((res) => {
                    $("#table-user").DataTable().ajax.reload();
                    $("#popupModal").modal("toggle");
                });
            }
        }

    }
});