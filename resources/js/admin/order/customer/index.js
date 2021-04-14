const { default: axios } = require("axios");
const FileSaver = require("file-saver");

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
var table = $("#table-customer").DataTable({
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
        url: "/admin/order/customer-list",
        data: function (d) {
            d.name = $("#name").val();
            d.email = $("#email").val();
            d.address = $("#address").val();
            d.status = $("#status").val();
        },
    },
    columns: [
        { data: "customer_name" },
        { data: "email" },
        { data: "address" },
        { data: "tel_num" },
        {
            data: "edit",
            orderable: false,
            searchable: false,
            className: "text-center",
        },
    ],
});
// table.on('processing.dt', function ( e, settings, processing ) {
//     $('#table-customer_processing').css( 'display', 'none' );
//     $(".loading").css('display', processing ? 'block' : 'none');
// } )

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

$("#popupForm").on("keydown", "input, select", function (e) {
    if (e.key === "Enter") {
        var self = $(this),
            form = self.parents("form:eq(0)"),
            focusable,
            next;
        focusable = form
            .find("input,a,select,button,textarea")
            .filter(":visible");
        next = focusable.eq(focusable.index(this) + 1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});

//add customer button
$("#btn-add").on("click", function () {
    $("#popupForm div input, #popupForm div select").each(function () {
        $(this).val("");
    });
    $("#popupModal").modal("show");
});

//validate
$.validator.addMethod(
    "phoneVietnam",
    function (value, element) {
        return (
            this.optional(element) ||
            /(84|0[3|5|7|8|9])+([0-9]{8})\b/.test(value)
        );
    },
    "Số điện thoại không đúng định dạng"
);
$.validator.addMethod(
    "nameVietnam",
    function (value, element) {
        return (
            this.optional(element) ||
            /^([a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹý]+\$)*[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹý\s]+$/.test(
                value
            )
        );
    },
    "Tên không hợp lệ"
);

//popup add customer validate
popupForm = $("#popupForm");
popupForm.validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    errorElement: "small",
    errorClass: "is-invalid text-danger",
    rules: {
        inputName: {
            required: true,
            minlength: 5,
            nameVietnam: true,
        },
        inputEmail: {
            required: true,
            email: true,
            remote: {
                url: "/admin/order/customer-email-unique",
                type: "post",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        "X-CSRF-TOKEN",
                        $("meta[name=csrf-token]").attr("content")
                    );
                },
            },
        },
        inputPhone: {
            required: true,
            minlength: 5,
            number: true,
            phoneVietnam: true,
        },
        inputAddress: {
            required: true,
        },
    },
    messages: {
        inputEmail: {
            email: "Email không đúng định dạng",
            remote: "Email đã tồn tại",
        },
        inputName: {
            required: "Vui lòng nhập tên khách hàng",
        },
    },
    errorPlacement: function (label, element) {
        label.addClass("error-text-9");
        label.insertAfter(element);
    },
    wrapper: "span",
    highlight: function (element, errorClass) {
        $(element).addClass(errorClass).removeClass("text-danger");
    },
    submitHandler: function (form) {
        var requestData = {
            customer_name: $("#inputName").val(),
            email: $("#inputEmail").val(),
            tel_num: $("#inputPhone").val(),
            address: $("#inputAddress").val(),
            is_active: $("#inputActive").is(":checked") === true ? 1 : 0,
        };
        //console.log(requestData);
        axios({
            url: "/admin/order/add-customer",
            method: "POST",
            data: requestData,
        }).then((res) => {
            table.draw();
            $("#popupForm div input, #popupForm div select").each(function () {
                $(this).val("");
            });
            $("#popupModal").modal("toggle");
        });
    },
});

$("#submit-popup").on("click", function () {
    popupForm.submit();
});

//hidden edit form validate
$.validator.setDefaults({ ignore: "" });
editForm = $("#editForm");
editForm.validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    errorElement: "small",
    errorClass: "is-invalid text-danger",
    rules: {
        editName: {
            required: true,
            minlength: 5,
            nameVietnam: true,
        },
        editEmail: {
            required: true,
            email: true,
            remote: {
                url: "/admin/order/customer-edit-email-unique",
                type: "POST",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                        "X-CSRF-TOKEN",
                        $("meta[name=csrf-token]").attr("content")
                    );
                },
                data: {
                    oldEmail: function () {
                        return $("#oldEmail").val();
                    },
                },
            },
        },
        editPhone: {
            required: true,
            minlength: 5,
            number: true,
            phoneVietnam: true,
        },
        editAddress: {
            required: true,
        },
    },
    messages: {
        editEmail: {
            required: "Email không được bỏ trống",
            email: "Email không đúng định dạng",
            remote: "Email đã tồn tại",
        },
        editName: {
            required: "Vui lòng nhập tên khách hàng",
        },
        editPhone: {
            number: "Số điện thoại phải là chữ số",
            required: "Điện thoại không được bỏ trống",
        },
        editAddress: {
            required: "Địa chỉ không được bỏ trống",
        },
    },
    highlight: function (element, errorClass) {
        $(element).addClass(errorClass).removeClass("text-danger");
    },
    submitHandler: function (form) {
        var requestData = {
            customerId: $("#editId").val(),
            oldEmail: $("#oldEmail").val(),
            customer_name: $("#editName").val(),
            email: $("#editEmail").val(),
            tel_num: $("#editPhone").val(),
            address: $("#editAddress").val(),
        };

        //update customer
        axios({
            url: "/admin/order/edit-customer",
            method: "POST",
            data: requestData,
        }).then((res) => {
            var trigger = $("#editable");
            var customerId = $("#editId");
            
            //not reload table
            //table.draw();
            $("#table-customer_processing").css("display", "none");

            //change icon
            $(`#editAction-${customerId.val()}`)
                .removeClass("btn-outline-danger")
                .addClass("btn-outline-info");
            $(`#editAction-${customerId.val()} i`)
                .removeClass("fa-save")
                .addClass("fa-edit");

            var child = $(`#rowId-${customerId.val()}`).children("td");
            child.each(function () {
                //disable edit
                $(this).removeAttr("contenteditable");
            });

            //update finished
            trigger.val("0");
            customerId.val("");
        });
    },
    invalidHandler: function (e, validator) {
        //loading
        $("#table-customer_processing").css("display", "none");

        var errors = "";
        var newLine = "\r\n";

        //validator.errorMap is an object mapping input names -> error messages

        for (var i in validator.errorMap) {
            //console.log(i, ":", validator.errorMap[i]);
            errors += validator.errorMap[i];
            errors += newLine;
        }

        alert(errors);
    },
});

window.Edit = function Edit(id) {
    //
    var trigger = $("#editable");
    var customerId = $("#editId");
    if (trigger.val() === "0") {
        //edit row
        trigger.val("1");

        //save id user
        customerId.val(id);

        //change icon
        $(`#editAction-${customerId.val()}`)
            .removeClass("btn-outline-info")
            .addClass("btn-outline-danger");
        $(`#editAction-${customerId.val()} i`)
            .removeClass("fa-edit")
            .addClass("fa-save");

        //save old email
        var row = table.row(`#rowId-${id}`).data();
        $("#oldEmail").val(row.email);

        var child = $(`#rowId-${id}`).children("td");

        for (let index = 0; index < child.length - 1; index++) {
            child[index].contentEditable = true;
        }
    } else {
        //update row
        //check if edit same row
        if (customerId.val() != id) {
            alert("Đang chỉnh sửa một row khác!");
        } else {
            //empty array to save new value
            var newData = [];
            var child = $(`#rowId-${id}`).children("td");
            child.each(function () {
                newData.push($(this).html());
            });

            //check if not change
            var rowData = table.row(`#rowId-${id}`).data();
            var oldData = [
                rowData.customer_name,
                rowData.email,
                rowData.address,
                rowData.tel_num,
                newData[4],
            ];

            if (JSON.stringify(newData) === JSON.stringify(oldData)) {
                $(`#editAction-${customerId.val()}`)
                    .removeClass("btn-outline-danger")
                    .addClass("btn-outline-info");
                $(`#editAction-${customerId.val()} i`)
                    .removeClass("fa-save")
                    .addClass("fa-edit");
                var child = $(`#rowId-${customerId.val()}`).children("td");
                child.each(function () {
                    //disable edit
                    $(this).removeAttr("contenteditable");
                });
                //update finished
                trigger.val("0");
                customerId.val("");

                return 0;
            }

            var inputFields = editForm.find("input");
            //add value to input form
            inputFields.each(function (index) {
                $(this).val(newData[index]);
            });

            //loading
            $("#table-customer_processing").css("display", "block");

            //edit
            editForm.submit();
        }
    }
};

//input file
$(".custom-file-input").on("change", function (e) {
    if (e.target.value.length == 0) {
        $(this).next(".custom-file-label").html("");
        return 0;
    }
    $(this).next(".custom-file-label").html(e.target.files[0].name);
});

//import excel
$("#btn-import").on("click", function () {
    $("#importModal").modal("show");
});
//upload file
$("#btn-upload-file").on("click", function () {
    var file = $(".custom-file-input")[0].files[0];
    var body = new FormData();
    body.append("file", file);
    //loading
    $("#import-loading").removeClass("d-none");
    axios({
        url: "/admin/order/upload-file",
        method: "POST",
        headers: {
            "Content-Type": "multipart/form-data",
        },
        data: body,
    })
        .then((res) => {
            //console.log(res.data);
            var newLine = `&#13;&#10;`;
            var error =
                res.data.errorCode === 0
                    ? "Thêm dữ liệu thành công" + newLine
                    : "";

            for (var i = 0; i < Object.keys(res.data.errors).length; i++) {
                error += res.data.errors[i] + newLine;
            }

            $("#errorFileRow").html(error);
            table.draw();
        })
        .finally(() => {
            $(".custom-file-label").html("");
            $("#import-loading").addClass("d-none");
        });
});

//export excel
$("#btn-export").on("click", function () {
    var requestData = table.rows().data().toArray();
    console.log(requestData);
    //loading
    $("#export-loading").removeClass("d-none");
    axios({
        url: "/admin/order/export-customer",
        method: "POST",
        responseType: "blob",
        data: {
            data: requestData,
        },
    })
        .then((res) => {
            FileSaver.saveAs(res.data, "Customer.xlsx");
        })
        .finally(() => {
            $("#export-loading").addClass("d-none");
        });
});
