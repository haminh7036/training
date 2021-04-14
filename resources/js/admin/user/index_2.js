const { default: axios } = require("axios");

//config table
var myConfig = {
    dom: "Bftipr",
    responsive: true,
    language: {
        decimal: "",
        emptyTable: "Không có dữ liệu",
        info: "Hiển thị từ _START_ đến _END_ của _TOTAL_ bản ghi",
        infoEmpty: "Hiển thị 0 đến 0 của 0 bản ghi",
        infoFiltered: "(Lọc từ _MAX_ bản ghi)",
        infoPostFix: "",
        thousands: ",",
        lengthMenu: "Hiển thị _MENU_ bản ghi",
        loadingRecords: "Đang tải...",
        processing: `<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`,
        search: "Tìm kiếm:",
        zeroRecords: "Không tìm thấy kết quả nào",
        paginate: {
            first: "Đầu tiên",
            last: "Cuối cùng",
            next: "Sau",
            previous: "Trước",
        },
        aria: {
            sortAscending: ": sắp xếp cột tăng dần",
            sortDescending: ": sắp xếp cột giảm dần",
        },
    },
    deferRender: true,
    pageLength: 20,
    ordering: false,
    searching: false,
};

//init table
var table = $("#table-user").DataTable({
    dom: myConfig.dom,
    responsive: myConfig.responsive,
    language: myConfig.language,
    pageLength: myConfig.pageLength,
    ordering: myConfig.ordering,
    deferRender: myConfig.deferRender,
    searching: myConfig.searching,
    processing: true,
    serverSide: true,
    ajax: {
        url: "/admin/user/user-list",
        data: function (d) {
            d.name = $("#name").val();
            d.email = $("#email").val();
            d.role = $("#role").val();
            d.status = $("#status").val();
        },
    },
    columns: [
        { data: "name" },
        { data: "email" },
        { data: "group_role" },
        {
            data: "is_active",
            render: function (data) {
                let text = "";
                switch (data) {
                    case 0:
                        text = `<span class="text-danger">Tạm khóa</span>`;
                        break;
                    case 1:
                        text = `<span class="text-success">Đang hoạt động</span>`;
                        break;
                    default:
                        break;
                }
                return text;
            },
        },
        {
            data: "action",
            orderable: false,
            searchable: false,
            className: "text-center",
        },
    ],
});

//search
$("#btn-search").on("click", function () {
    table.draw();
});

//refresh search
$("#btn-delete-search").on("click", function () {
    $(".search-bar div div input, .search-bar div div select").each(
        function () {
            $(this).val("");
        }
    );
    table.draw();
});

//function
window.getDeleteId = function getDeleteId (id) {
    $("#deleteUser").val(id);
    let data = table.row(`#rowId-${id}`).data();
    $(".deleteUserModalContent").html(`Bạn có muốn xóa thành viên ${data.name} không?`);
    $("#deleteUserModal").modal("show");
}

window.getBlockId = function getBlockId (id) {
    $("#blockUser").val(id);
    let data = table.row(`#rowId-${id}`).data();
    $(".blockUserModalContent").html(`Bạn có muốn ${(data.is_active === 0) ? 'mở khóa' : 'khóa'} thành viên ${data.name} không?`);
    $("#blockUserModal").modal("show");          
}

window.getEditId = function getEditId (id) {
    $("#userId").val(id);
    $("#methodPopup").val("edit");
    $("#popupLabel").text("Chỉnh sửa User");

    let data = table.row(`#rowId-${id}`).data();
    $("#inputName").val(data.name);
    $("#inputEmail").val(data.email);
    $("#inputGroup").val(data.group_role);
    $("#inputActive").prop('checked', (data.is_active === 1) ? true : false);
    //
    $("#emailUser").val(data.email);

    $("#popupModal").modal("show");

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

//submit delete user
$("#submit-delete").on("click", function () {
    axios({
        url: "/admin/user/user-delete",
        method: "POST",
        data: {
            id: $("#deleteUser").val()
        }
    }).then((res) => {
        table.draw();
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
        table.draw();
        $("#blockUserModal").modal('toggle');
    })
});

//add user button
$("#btn-add").on("click", function () {
    $("#popupLabel").text("Thêm User");
    $("#methodPopup").val("add");
    $("#popupForm div input, #popupForm div select").each(function () {
        $(this).val('');
    });
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
            email: true,
            remote : {
                url: "/admin/user/user-email-unique",
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $("meta[name=csrf-token]").attr("content"));
                },
                data : {
                    oldEmail: function () {
                        return $("#emailUser").val();
                    }
                }
            },
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
        "inputGroup" : {
            required : true
        }
    },
    messages: {
        "inputEmail" : {
            email: "Email không đúng định dạng",
            remote: "Email đã tồn tại"
        },
        "inputPassword_confirmation" : {
            equalTo: "Mật khẩu xác nhận không trùng khớp"
        },
        "inputGroup" : {
            required: "Hãy chọn nhóm thành viên"
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
    submitHandler: function(form) {
        var requestData = {
            name: $("#inputName").val(),
            password: $("#inputPassword").val(),
            group_role: $("#inputGroup").val(),
            is_active: ($("#inputActive").is(":checked") === true) ? 1 : 0,
        };

        if ($("#methodPopup").val() === 'add') {
            //add
            requestData.email = $("#inputEmail").val();
            axios({
                url: "/admin/user/user-add",
                method: "POST",
                data: requestData
            }).then((res) => {
                //reset filter
                $(".search-bar div div input, .search-bar div div select").each(
                    function () {
                        $(this).val("");
                    }
                );
                table.draw();
                $("#popupModal").modal("toggle");
                //reset input popup
                $("#popupForm div input, #popupForm div select").each(function () {
                    $(this).val('');
                });
            })
        } else {
            //edit
            requestData.id = $("#userId").val();
            var email = $("#inputEmail").val();
            var oldEmail = $("#emailUser").val();
            if (email !== oldEmail) {
                requestData.email = $("#inputEmail").val();
            }
            axios({
                url: "/admin/user/user-edit",
                method: "POST",
                data: requestData
            }).then((res) => {
                table.draw();
                $("#popupModal").modal("toggle");
                $("#popupForm div input, #popupForm div select").each(function () {
                    $(this).val('');
                });
            });
        }
    }
});

$("#submit-popup").on("click", function() {
    popupForm.submit();
});