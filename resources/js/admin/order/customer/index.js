const { default: axios } = require("axios");

//config table
var myConfig = {
    dom: "Bftipr",
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
        "processing":     `<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`,
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
    searching: false,
}

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
        }
    },
    columns: [
        { data: 'customer_name' },
        { data: 'email' },
        { data: 'address'},
        { data: 'tel_num' },
        {
            data: 'edit',
            orderable: false,
            searchable: false
        },
        {
            data: 'is_active',
            visible: false
        }
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
    $(".search-bar div div input, .search-bar div div select").each(function() {
        $(this).val('');
    });
    table.draw();
})

//add customer button
$("#btn-add").on("click", function() {
    $("#popupModal").modal("show");
})

//validate
$.validator.addMethod('phoneVietnam', function (value, element) {
    return this.optional(element) || /(84|0[3|5|7|8|9])+([0-9]{8})\b/.test(value);
}, 'Số điện thoại không đúng định dạng');

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
            minlength: 5
        },
        "inputEmail" : {
            required : true,
            email: true,
            remote : {
                url: "/admin/order/customer-email-unique",
                type: "post",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $("meta[name=csrf-token]").attr("content"));
                }
            }
        },
        "inputPhone" : {
            required : true,
            minlength: 5,
            number: true,
            phoneVietnam: true,
        },
        "inputAddress" : {
            required: true
        },

    },
    messages: {
        "inputEmail" : {
            email: "Email không đúng định dạng",
            remote: "Email đã tồn tại",
        }
        ,
        "inputName" : {
            required : "Vui lòng nhập tên khách hàng"
        }
    },
    errorPlacement: function(label, element) {
        label.addClass('error-text-9');
        label.insertAfter(element);
    },
    wrapper: 'span',
    highlight: function ( element, errorClass) { 
        $ ( element ).addClass(errorClass).removeClass("text-danger");
    },
    submitHandler: function(form) {
        var requestData = {
            customer_name : $("#inputName").val(),
            email: $("#inputEmail").val(),
            tel_num: $("#inputPhone").val(),
            address: $("#inputAddress").val(),
            is_active: ($("#inputActive").is(":checked") === true ? 1 : 0)
        }
        console.log(requestData);
        axios({
            url: "/admin/order/add-customer",
            method: "POST",
            data: requestData
        }).then((res) => {
            table.draw();
            $("#popupModal").modal("toggle");
        })
    }
});

$("#submit-popup").on("click", function () {
    popupForm.submit();
});

window.Edit = function Edit(id) {
    //
    var trigger = $("#editable");
    if (trigger.val() === "0") {
        //edit row
        trigger.val("1");
        var row = table.row(`#rowId-${id}`).data();
        console.log(row);
        var child = $(`#rowId-${id}`).children('td');
        child.each(function () {
            $(this).attr('contenteditable', true);
            console.log($(this).html());
        });
    }
    else {
        //update row
        var row = table.row(`#rowId-${id}`).data();
        console.log(row);
        var child = $(`#rowId-${id}`).children('td');
        child.each(function (index) {
            $(this).attr('contenteditable', true);
            
            console.log($(this).html());
        });
    }

}