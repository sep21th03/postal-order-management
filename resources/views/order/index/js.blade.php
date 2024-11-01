<script>
    function delete_order(categoryId) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger",
            },
            buttonsStyling: false,
        });

        swalWithBootstrapButtons
            .fire({
                title: "Bạn có chắc chắn xóa đơn hàng này?",
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

                    fetch("{{ route('order.destroy') }}", {
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
                                swalWithBootstrapButtons.fire({
                                    title: "Đã xóa!",
                                    text: data.message,
                                    icon: "success",
                                });
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "Xóa thất bại!",
                                    text: data.message,
                                    icon: "error",
                                });
                            }
                        });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Đã hủy",
                        text: "Đơn hàng của bạn vẫn an toàn :)",
                        icon: "error",
                    });
                }
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const orderTableBody = document.getElementById('order-table-body');
        const rows = orderTableBody.getElementsByTagName('tr');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                let isMatch = false;
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        isMatch = true;
                        break;
                    }
                }
                row.style.display = isMatch ? '' : 'none';
            }
        });
    });
</script>