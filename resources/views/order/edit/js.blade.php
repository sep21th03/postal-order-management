<script>
    function updateOrderStatus(id, status) {
        $.ajax({
            url: "{{ route('order.update') }}",
            type: 'POST',
            data: JSON.stringify({
                id: id,
                status: status
            }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.status == 'success') {
                    toastr.success('Cập nhật đơn hàng thành công!');
                } else {
                    toastr.error(res.data.join(', ') + ".");
                }
            },
            error: function(res) {
                toastr.error("Cập nhật đơn hàng thành công");
            }
        });
    }
</script>
