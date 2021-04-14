const { default: axios } = require("axios");
const { forEach } = require("lodash");

//form validate
form = $("#formAEProduct");
form.validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    errorElement: "small",
    errorClass: "is-invalid text-danger",
    rules: {
        "product_name" : {
            required: true,
            minlength: 5,
        },
        "product_price" : {
            required : true,
            number: true,
            min: 0,
        },
        "is_sales" : {
            required: true
        },
    },
    messages: {
        "product_name" : {
            required: "Tên không được bỏ trống",
        }
        ,
        "product_price" : {
            required : "Vui lòng nhập giá tiền"
        },
        "is_sales" : {
            required : "Hãy chọn trạng thái sản phẩm"
        }
    },
    highlight: function ( element, errorClass) { 
        $ ( element ).addClass(errorClass).removeClass("text-danger");
    },
});


$(".btn-goback").on("click", function (){
    history.back();
})

//upload
$("#btn-upload").on("click", function() {
    $("#uploadFile").click();
});

$("#uploadFile").on("change", function (e) {
    if (e.target.value.length !== 0) {
        $("#img-loading").removeClass("d-none");
        let formData = new FormData();
        let file = $("#uploadFile")[0].files[0];
        formData.append("file", file);
        formData.append("function", "upload"); //upload
        axios({
            url: "/admin/product/product/file",
            method: "POST",
            headers: {
                "Content-Type" : "multipart/form-data"
            },
            data: formData
        })
        .then((res) => {
            $("#product_image").val(res.data.filePath);
            $("#product_image").removeClass("is-invalid");

            //image
            $("#image-demo").attr("src", res.data.filePath);
        }).catch((e) => {
            let errors = '';
            var newLine = `&#13;&#10;`;
            forEach(e.response.data.errors, function (value, key) {
                errors += (value + newLine);
            })
            $("#file-error").html(errors).removeClass("d-none");
            $("#product_image").addClass("is-invalid");
        })
        .finally(() => {
            $("#uploadFile").val('');
            $("#img-loading").addClass("d-none");
        })
    }
});

//remove image
$("#btn-delete-file").on("click", function () {
    let filePath = $("#product_image").val();
    if (filePath.length === 0) {
        alert("Không có ảnh để xóa");
        return 0;
    }
    $("#img-loading").removeClass("d-none");
    axios({
        url: "/admin/product/product/file",
        method: "POST",
        data: {
            function: "delete",
            filePath: filePath
        }
    }).then((res) => {
        $("#product_image").val('');
        $("#product_image").removeClass("is-invalid");
        $("#file-error").html('').addClass("d-none");
        $("#image-demo").attr("src", '#');
    }).catch((e) => {
        let errors = '';
        var newLine = `&#13;&#10;`;
        forEach(e.response.data.errors, function (value, key) {
            errors += (value + newLine);
        })
        $("#file-error").html(errors).removeClass("d-none");
        $("#product_image").addClass("is-invalid");
    }).finally(() => {
        $("#img-loading").addClass("d-none");
    })
})