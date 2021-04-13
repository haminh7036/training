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
var table = $("#table-product").DataTable({
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
        url: "/admin/product/product/list",
        data: function (d) {
            d.name = $("#name").val();
            d.status = $("#status").val();
            d.price_from = $("#price_from").val();
            d.price_to = $("#price_to").val();
        },
    },
    columns: [
        { data: "product_name" },
        { data: "description" },
        { data: "product_price" },
        {
            data: "is_sales",
            render: function (data) {
                let text = "";
                switch (data) {
                    case -1:
                        text = `<span class="text-danger">Ngừng bán</span>`;
                        break;
                    case 0:
                        text = `<span class="text-success">Hết hàng</span>`;
                        break;
                    case 1:
                        text = `<span class="text-success">Đang bán</span>`;
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
    initComplete: function (settings, json) {
        let dataRows = json.data;
        dataRows.forEach(function (value, key) {
            var child = $(`#rowId-${value.product_id}`).children("td");
            child[0].addEventListener("mouseenter", function () {
                $("#img-hover").attr("src", value.product_image)
                .show();
            });
            child[0].addEventListener("mouseleave", function() {
                $("#img-hover").fadeOut(0);
            });
        });
    },
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

//delete
window.Delete = function Delete(id) {
    rowData = table.row(`#rowId-${id}`).data();
    $("#deleteProductId").val(rowData.product_id);
    $("#deleteModalContent").html(
        `Bạn có chắc muốn xóa sản phẩm ${rowData.product_name} không?`
    );
    $("#deleteModal").modal("show");
};

$("#submit-delete").on("click", function () {
    axios({
        url: "/admin/product/product/delete",
        method: "POST",
        data: {
            product_id: $("#deleteProductId").val(),
        },
    })
        .then((res) => {
            $("#deleteProductId").val("");
            table.draw();
            $("#deleteModal").modal("hide");
        })
        .catch((e) => {
            alert(e.response.data);
        });
});
