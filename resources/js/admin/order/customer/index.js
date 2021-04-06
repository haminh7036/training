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

//init table
var table = $("#table-customer").DataTable({
    dom: myConfig.dom,
    responsive: myConfig.responsive,
    language: myConfig.language,
    pageLength: myConfig.pageLength,
    ordering: myConfig.ordering,
    deferRender: myConfig.deferRender,
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

$("#btn-search").on("click", function () {
    table.draw();
});