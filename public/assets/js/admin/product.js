$(document).ready(function () {
    let base_url = window.location.origin;
    if ($("#list_product").length) {
        $("#list_product").DataTable({
            ajax: {
                url: "/api/product/get/product",
                type: "get",
                dataSrc: "data",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                },
            },
            columns: [
                {
                    data: "id",
                    render: function (data, type, row) {
                        return `<div class="form-check mb-0 fs-8">
                                    <input class="form-check-input" type="checkbox" data-bulk-select-row='${JSON.stringify(
                                        row
                                    )}' />
                                </div>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        let html = "";
                        if (row.variants && row.variants.length > 0) {
                            row.variants.forEach((variant) => {
                                if (
                                    variant.images &&
                                    variant.images.length > 0
                                ) {
                                    for (
                                        let i = 0;
                                        i < Math.min(3, variant.images.length);
                                        i++
                                    ) {
                                        html += `
                                            <td class="text-center">
                                                <img src="${base_url}/${variant.images[i].image_url}" alt="${row.title}" style="width: 50px; height: auto;">
                                            </td>
                                        `;
                                    }
                                } else {
                                    html +=
                                        '<td class="text-center">Không có hình ảnh</td>';
                                }
                            });
                        } else {
                            html +=
                                '<td class="text-center">Không có hình ảnh</td>';
                        }
                        return html;
                    },
                },
                {
                    data: "title",
                    render: function (data) {
                        return `<a class="fw-semibold line-clamp-3 mb-0" href="">${data}</a>`;
                    },
                },
                { data: "category_name" },
                {
                    data: null,
                    render: function (data, type, row) {
                        let html = "";
                        if (row.variants && row.variants.length > 0) {
                            html += `<span class="badge bg-primary">${row.variants[0].rom.capacity}</span>`;
                        } else {
                            html +=
                                '<span class="badge bg-secondary">Không có</span>';
                        }
                        return html;
                    },
                },
                {
                    data: "id",
                    render: function (data) {
                        return `
                            <div class="btn-reveal-trigger position-relative">
                                <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10" type="button" data-bs-toggle="dropdown" data-boundary="window">
                                    <span class="fas fa-ellipsis-h fs-10"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a class="dropdown-item" href="/admin/product/${data}">Xem chi tiết</a>
                                    <a class="dropdown-item" href="#!">Export</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#!" onclick="removeProduct(${data})">Xóa</a>
                                </div>
                            </div>`;
                    },
                },
            ],
            rowId: "id",
            language: {
                lengthMenu: "Hiện thị _MENU_ sản phẩm mỗi trang",
                zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                info: "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ nguồn dữ liệu",
                infoEmpty: "Không hiển thị dữ liệu",
                infoFiltered: "(được lọc từ tổng số _MAX_ nguồn dữ liệu)",
                search: "Tìm kiếm:",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Tiếp",
                    previous: "Trước",
                },
            },
        });
    }

    // Add color

    $("#submitColor").click(function () {
        let color = $("#colorName").val();
        let color_code = $("#colorPickerInput").val();
        let price = parseInt(
            $("#pricePhone").val().replace(/\./g, "").replace(" ₫", "")
        );
        let stock = $("#stockPhone").val();
        let image_url = $("#imageUpload")[0].files[0];
        let product_id = $("#addproductID").val();
        let rom_id = $("#addromID").val();
        let availability = stock > 0 ? 1 : 0;

        if (!image_url) {
            swal("Cảnh báo!", "Vui lòng chọn ảnh!", "warning");
            return;
        }

        var formData = new FormData();
        formData.append("color", color);
        formData.append("color_code", color_code);
        formData.append("price", price);
        formData.append("stock", stock);
        formData.append("image_url", image_url);
        formData.append("product_id", product_id);
        formData.append("rom_id", rom_id);
        formData.append("availability", availability);

        $.ajax({
            url: "/api/product/add/color",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: "Thêm màu thành công!",
                    timer: 1500,
                    showConfirmButton: false,
                });
                $("#colorName").val("");
                $("#colorPickerInput").val("#000000");
                $("#pricePhone").val("");
                $("#stockPhone").val("");
                $("#imageUpload").val(null);
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: error.message || "Có lỗi xảy ra, vui lòng thử lại!",
                });
            },
        });
    });

    // update product

    $("#editBtnProduct").click(function () {
        let formValues = {
            id: $("input[name='edit_id']").val(),
            title: $("input[name='edit_title']").val(),
            info: $("textarea[name='edit_info']").val(),
            description: $("textarea[name='edit_description']").val(),
            category_id: $("select[name='edit_category']").val(),
            discount: $("input[name='edit_discount']").val(),
            specifications_id: $("input[name='edit_specifications_id']").val(),
            screen_size: $("input[name='edit_screen_size']").val(),
            screen_resolution: $("input[name='edit_screen_resolution']").val(),
            screen_type: $("input[name='edit_screen_type']").val(),
            ram: $("input[name='edit_ram']").val(),
            memory_card_slot: $("input[name='edit_memory_card_slot']").val(),
            camera_front: $("input[name='edit_camera_front']").val(),
            camera_rear: $("input[name='edit_camera_rear']").val(),
            sim: $("input[name='edit_sim']").val(),
            operating_system: $("input[name='edit_operating_system']").val(),
            connectivity: $("input[name='edit_connectivity']").val(),
            bluetooth: $("input[name='edit_bluetooth']").val(),
            pin: $("input[name='edit_pin']").val(),
            chip: $("input[name='edit_chip']").val(),
            dimensions: $("input[name='edit_dimensions']").val(),
            weight: $("input[name='edit_weight']").val(),
            rom: $("select[name='edit_rom']").val(),
            color: $("select[name='edit_color']").val(),
            stock: $("input[name='edit_stock']").val(),
            price: parseInt(
                $("input[name='edit_price']")
                    .val()
                    .replace(/\./g, "")
                    .replace(" ₫", "")
            ),
            variant_id: $("select[name='edit_color']")
                .find("option:selected")
                .data("variant_id"),
            rom_id: $("select[name='edit_color']")
                .find("option:selected")
                .data("rom_id"),
            availability: $("input[name='edit_stock']").val() > 0 ? 1 : 0,
            image: $("#file-input")[0].files[0],
        };

        var formData = new FormData();

        for (const key in formValues) {
            formData.append(key, formValues[key]);
        }
        $.ajax({
            url: "/api/product/edit/product",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: "Sửa sản phẩm thành công!",
                    timer: 1500,
                    showConfirmButton: false,
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: error.message || "Có lỗi xảy ra, vui lòng thử lại!",
                });
            },
        });
    });

    // add product

    $("#addProduct").click(function () {
        let formValues = {
            id: $("input[name='add_id']").val(),
            title: $("input[name='add_title']").val(),
            info: CKEDITOR.instances.editor4.getData(),
            description: CKEDITOR.instances.editor3.getData(),
            category_id: $("select[name='add_category']").val(),
            discount: $("input[name='add_discount']").val(),
            specifications_id: $("input[name='add_specifications_id']").val(),
            screen_size: $("input[name='add_screen_size']").val(),
            screen_resolution: $("input[name='add_screen_resolution']").val(),
            screen_type: $("input[name='add_screen_type']").val(),
            ram: $("input[name='add_ram']").val(),
            memory_card_slot: $("input[name='add_memory_card_slot']").val(),
            camera_front: $("input[name='add_camera_front']").val(),
            camera_rear: $("input[name='add_camera_rear']").val(),
            sim: $("input[name='add_sim']").val(),
            operating_system: $("input[name='add_operating_system']").val(),
            connectivity: $("input[name='add_connectivity']").val(),
            bluetooth: $("input[name='add_bluetooth']").val(),
            pin: $("input[name='add_pin']").val(),
            chip: $("input[name='add_chip']").val(),
            dimensions: $("input[name='add_dimensions']").val(),
            weight: $("input[name='add_weight']").val(),
            rom_id: parseInt($("select[name='add_rom_id']").val()),
            color: $("input[name='add_color_name']").val(),
            color_code: $("input[name='add_color_code']").val(),
            stock: $("input[name='add_stock']").val(),
            price: parseInt(
                $("input[name='add_price']")
                    .val()
                    .replace(/\./g, "")
                    .replace(" ₫", "")
            ),
            variant_id: $("select[name='add_color']")
                .find("option:selected")
                .data("variant_id"),
            availability: $("input[name='add_stock']").val() > 0 ? 1 : 0,
            image: $("#file-add-product")[0].files[0],
        };
        var formData = new FormData();
        for (const key in formValues) {
            formData.append(key, formValues[key]);
        }

        $.ajax({
            url: "/api/product/add/product",
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: "Thêm sản phẩm thành công!",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: error.message || "Có lỗi xảy ra, vui lòng thử lại!",
                });
            },
        });
    });

    

    // reload page
    $("#reloadDetalProduct").click(function () {
        location.reload();
    });

    // checkbox
    $("#checkbox-bulk-products-select").on("change", function () {
        let isChecked = $(this).is(":checked");

        $("#products-table-body input.form-check-input").prop(
            "checked",
            isChecked
        );
    });
});

function removeProduct(productID) {
    Swal.fire({
        title: "Xác nhận xóa",
        text: "Bạn có chắc muốn xóa sản phẩm này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xóa",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/product/delete/product",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { id: productID },
                success: function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: "success",
                        title: "Xóa thành công",
                        text: "Sản phẩm đã được xóa!",
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        $("#list_product tr").each(function () {
                            let trID = parseInt($(this).attr("id"));
                            if (trID === productID) {
                                $(this).remove();
                            }
                        });
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text:
                            error.responseJSON?.message ||
                            "Có lỗi xảy ra, vui lòng thử lại!",
                    });
                },
            });
        }
    });
}

function deleteSelectedProducts() {
    let selectedProductIds = [];

    $("input.form-check-input:checked").each(function () {
        let row = $(this).data("bulk-select-row");

        if (row && row.id) {
            selectedProductIds.push(row.id);
        }
    });

    if (selectedProductIds.length > 0) {
        Swal.fire({
            title: "Xác nhận xóa",
            text: "Bạn có chắc muốn xóa các sản phẩm này?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/product/delete/products",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: { ids: selectedProductIds }, 
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Xóa thành công",
                            text: "Các sản phẩm đã được xóa!",
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            selectedProductIds.forEach((id) => {
                                $(`#list_product tr[id="${id}"]`).remove();
                            });
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi",
                            text:
                                xhr.responseJSON?.message ||
                                "Có lỗi xảy ra, vui lòng thử lại!",
                        });
                    },
                });
            }
        });
    } else {
        Swal.fire({
            icon: "warning",
            title: "Không có sản phẩm nào được chọn",
            text: "Vui lòng chọn ít nhất một sản phẩm để xóa.",
        });
    }
}

function deleteColor($id){
    Swal.fire({
        title: "Xác nhận xóa",
        text: "Bạn có chắc muốn xóa màu này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xóa",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/product/delete/color",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: { id: $id }, 
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "Xóa thành công",
                        text: "Màu này đã được xóa!",
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    })
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi",
                        text:
                            xhr.responseJSON?.message ||
                            "Có lỗi xảy ra, vui lòng thử lại!",
                    });
                },
            });
        }
    });
}
