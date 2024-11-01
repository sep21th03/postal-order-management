$(document).ready(function () {
    if ($("#list_category").length) {
        $("#list_category").DataTable({
            ajax: {
                url: "{{ route('category.index') }}",
                type: "get",
                dataSrc: "data",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Error get data from ajax");
                },
            },
            columns: [
                { data: "name" },
                { data: "created_at" },
                { data: "updated_at" },
                { data: "id" },
            ],
            columnDefs: [
                {
                    targets: 3,
                    render: function (data, type, row) {
                        return (
                            '<button type="button" class="btn btn-primary" onclick="open_modal_edit_category(' +
                            data +
                            ')">Sửa</button> <button type="button" class="btn btn-danger" onclick="delete_category(' +
                            data +
                            ')">Xóa</button>'
                        );
                    },
                    className: "my-class",
                },
            ],
            rowId: "id",
            language: {
                lengthMenu: "Hiện thị _MENU_ loại sản phẩm mỗi trang",
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
            // processing: true,
            order: [[0, "asc"]],
        });
    }
});

document
    .querySelector('button[name="submit_add_category"]')
    .addEventListener("click", function () {
        let name = document.querySelector('input[name="name"]').value;
        let csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        this.innerHTML = "Vui lòng chờ...";
        this.disabled = true;

        fetch("/api/category/add/category", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                name: name,
            }),
        })
            .then((response) => {
                if (response.status === 201) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công!",
                        text: "Thêm danh mục thành công!",
                        timer: 1500,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Thất bại!",
                        text: "Thêm thất bại!",
                    });
                }
                return response.json();
            })
            .then((data) => {
                var table = $("#list_category").DataTable();
                table.row
                    .add({
                        name: data.data.name,
                        created_at: data.data.created_at,
                        updated_at: data.data.updated_at,
                        id: data.data.id,
                    })
                    .draw();
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: error.message || "Có lỗi xảy ra, vui lòng thử lại!",
                });
            })
            .finally(() => {
                this.innerHTML = "Thêm";
                this.disabled = false;
            });
    });



document.querySelector('button[name="submit_edit_category"]').addEventListener("click", function () {
    let updateName = document.querySelector('input[name="name_edit"]').value;
    let updateId = document.getElementById("editCategory").getAttribute("data-id");
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    this.innerHTML = "Vui lòng chờ...";
    this.disabled = true;

    fetch("/api/category/edit/category", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            name: updateName,
            id: updateId,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: "Cập nhật danh mục thành công!",
                timer: 1500,
                showConfirmButton: false,
            });
            var table = $("#list_category").DataTable();
            var rowExists = table.row(`[id="${updateId}"]`).data();
            if (rowExists) {
                table.row(`[id="${updateId}"]`).data({
                    name: data.data.name,
                    created_at: data.data.created_at,
                    updated_at: data.data.updated_at,
                    id: data.data.id,
                }).draw();
                document.getElementById('editInputCategory').value = "";
            } else {
                console.error("Hàng không tồn tại để cập nhật");
            }
        } else {
            Swal.fire({
                icon: "error",
                title: "Thất bại!",
                text: data.message || "Có lỗi xảy ra, vui lòng thử lại!",
            })
        }
    })
    .catch(error => {
        console.error("Error:", error);
    })
    .finally(() => {
        this.innerHTML = "Sửa";
        this.disabled = false;
    });
});



function delete_category(categoryId) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons
        .fire({
            title: "Bạn có chắc chắn xóa loại sản phẩm này?",
            text: "Bạn sẽ không thể khôi phục lại!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Có, xóa nó!",
            cancelButtonText: "Không, hủy!",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons.fire({
                    title: "Đang xóa...",
                    timer: 2000,
                    timerProgressBar: true,
                });

                fetch("/api/category/delete/category", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    body: JSON.stringify({
                        id: categoryId,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status === "success") {
                            var table = $("#list_category").DataTable();
                            table.row(`#${categoryId}`).remove().draw();
                            swalWithBootstrapButtons.fire({
                                title: "Đã xóa!",
                                text: "Loại sản phẩm của bạn đã được xóa.",
                                icon: "success",
                            });
                        } else {
                            swalWithBootstrapButtons.fire({
                                title: "Xóa thất bại!",
                                text: "Không thể xóa loại sản phẩm!",
                                icon: "error",
                            });
                        }
                    });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Đã hủy",
                    text: "Loại sản phẩm của bạn vẫn an toàn :)",
                    icon: "error",
                });
            }
        });
}
