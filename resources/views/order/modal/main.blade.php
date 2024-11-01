<div class="modal fade" id="addOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm đơn hàng</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOrderForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3">
                        <label for="item_name" class="form-label mb-2">Tên mặt hàng</label>
                        <input type="text" name="item_name" class="form-control" id="item_name">
                    </div>

                    <div class="mb-3">
                        <label for="sender_name" class="form-label mb-2">Tên người gửi</label>
                        <input type="text" name="sender_name" class="form-control" id="sender_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="sender_address" class="form-label mb-2">Địa chỉ người gửi</label>
                        <input type="text" name="sender_address" class="form-control" id="sender_address" required>
                        <small class="text-danger">(*) địa chỉ cách nhau bằng dấu ,</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number_sender" class="form-label mb-2">Số điện thoại người gửi</label>
                        <input type="text" name="phone_number_sender" class="form-control" id="phone_number_sender" required>
                    </div>

                    <div class="mb-3">
                        <label for="recipient_name" class="form-label mb-2">Tên người nhận</label>
                        <input type="text" name="recipient_name" class="form-control" id="recipient_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="recipient_address" class="form-label mb-2">Địa chỉ người nhận</label>
                        <input type="text" name="recipient_address" class="form-control" id="recipient_address" required>
                        <small class="text-danger">(*) địa chỉ cách nhau bằng dấu ,</small>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number_recipient" class="form-label mb-2">Số điện thoại người nhận</label>
                        <input type="text" name="phone_number_recipient" class="form-control" id="phone_number_recipient" required>
                    </div>

                    <div class="mb-3">
                        <label for="email_recipient" class="form-label mb-2">Email người nhận</label>
                        <input type="text" name="email_recipient" class="form-control" id="email_recipient" required>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label mb-2">Ghi chú</label>
                        <textarea name="note" class="form-control" id="note"></textarea>
                    </div>

                    
                    <div class="mb-3">
                        <label for="value" class="form-label mb-2">Giá trị</label>
                        <input type="text" name="value" class="form-control" id="value" required>
                    </div>

                    <div class="mb-3">
                        <label for="fee_ship" class="form-label mb-2">Phí ship</label>
                        <input type="text" name="fee_ship" class="form-control" id="fee_ship" required>
                    </div>

                    <div class="mb-3">
                        <label for="weight" class="form-label mb-2">Khối lượng (kg)</label>
                        <input type="text" name="weight" class="form-control" id="weight" required>
                    </div>

                    <div class="mb-3">
                        <label for="payment_status" class="form-label mb-2">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-select" id="payment_status" required>
                            <option value="">Chọn trạng thái thanh toán</option>
                            @foreach($paymentStatuses as $status)
                            <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="parcel_type_id" class="form-label mb-2">Loại hàng</label>
                        <select name="parcel_type_id" class="form-select" id="parcel_type_id">
                            <option value="">Chọn loại hàng</option>
                            @foreach($parcelTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="shipping_status" class="form-label mb-2">Tình trạng ship</label>
                        <select name="shipping_status" class="form-select" id="shipping_status" required>
                            <option value="">Chọn tình trạng ship</option>
                            <option value="standard">Giao hàng tiêu chuẩn</option>
                            <option value="express">Giao hàng hỏa tốc</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" name="submit_add" class="btn btn-primary">Thêm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document
        .querySelector('button[name="submit_add"]')
        .addEventListener("click", function() {
            let item_name = document.querySelector('input[name="item_name"]').value;
            let sender_name = document.querySelector('input[name="sender_name"]').value;
            let sender_address = document.querySelector('input[name="sender_address"]').value;
            let phone_number_sender = document.querySelector('input[name="phone_number_sender"]').value;
            let recipient_name = document.querySelector('input[name="recipient_name"]').value;
            let recipient_address = document.querySelector('input[name="recipient_address"]').value;
            let phone_number_recipient = document.querySelector('input[name="phone_number_recipient"]').value;
            let email_recipient = document.querySelector('input[name="email_recipient"]').value;
            let note = document.querySelector('textarea[name="note"]').value;
            let parcel_type_id = document.querySelector('select[name="parcel_type_id"]').value;
            let payment_status = document.querySelector('select[name="payment_status"]').value;
            let shipping_status = document.querySelector('select[name="shipping_status"]').value;
            let value = document.querySelector('input[name="value"]').value;
            let fee_ship = document.querySelector('input[name="fee_ship"]').value;
            let weight = document.querySelector('input[name="weight"]').value;

            let csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            this.innerHTML = "Vui lòng chờ...";
            this.disabled = true;

            fetch("{{ route('order.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        item_name: item_name,
                        sender_name: sender_name,
                        sender_address: sender_address,
                        phone_number_sender: phone_number_sender,
                        phone_number_recipient: phone_number_recipient,
                        email_recipient: email_recipient,
                        recipient_name: recipient_name,
                        recipient_address: recipient_address,
                        payment_status: payment_status,
                        note: note,
                        parcel_type_id: parcel_type_id,
                        value: value,
                        weight: weight,
                        fee_ship: fee_ship,
                        shipping_status: shipping_status,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: "success",
                            title: "Thành công!",
                            text: "Đơn hàng thêm thành công!",
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Đơn hàng thêm thất bại!",
                            text: data.message,
                        });
                    }
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
</script>