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
        success: function(data) {
            if (data && data.status) {
                Swal.fire({
                    icon: data.status,
                    title: data.status === 'success' ? 'Cập nhật thành công!' : 'Cập nhật thất bại!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                throw new Error('Invalid response format');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Cập nhật thất bại!',
                text: 'Có lỗi xảy ra trong quá trình cập nhật. Vui lòng thử lại.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>