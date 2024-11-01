@extends('layouts.app')
@section('title')
Danh sách đơn hàng 
@endsection
<script>
    const formatTimeAgo = (time) => {
        const dateTime = new Date(time);
        const now = new Date();
        const diff = now - dateTime;
        const second = 1000;
        const minute = 60 * second;
        const hour = 60 * minute;
        const day = 24 * hour;
        const month = 30 * day;
        const year = 12 * month;
        let text;

        if (diff < second * 30) {
            text = 'vừa mới';
        } else if (diff < minute) {
            text = Math.floor(diff / second) + ' giây trước';
        } else if (diff < minute * 2) {
            text = '1 phút trước';
        } else if (diff < hour) {
            text = Math.floor(diff / minute) + ' phút trước';
        } else if (diff < hour * 2) {
            text = '1 giờ trước';
        } else if (diff < day) {
            text = Math.floor(diff / hour) + ' giờ trước';
        } else if (diff < day * 2) {
            text = '1 ngày trước';
        } else if (diff < month) {
            text = Math.floor(diff / day) + ' ngày trước';
        } else if (diff < month * 2) {
            text = '1 tháng trước';
        } else if (diff < year) {
            text = Math.floor(diff / month) + ' tháng trước';
        } else {
            text = Math.floor(diff / year) + ' năm trước';
        }

        return text;
    }
</script>
@section('content')
<div class="content">
    <div class="mb-9">
        <div class="row g-3 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">Đơn hàng #{{ $orders[0]->user_id }}</h2>
            </div>
        </div>
        <ul class="nav nav-links mb-3 mb-lg-2 mx-n3">
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><span>All </span><span class="text-body-tertiary fw-semibold">(68817)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><span>Pending payment </span><span class="text-body-tertiary fw-semibold">(6)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><span>Unfulfilled </span><span class="text-body-tertiary fw-semibold">(17)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><span>Completed</span><span class="text-body-tertiary fw-semibold">(6,810)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><span>Refunded</span><span class="text-body-tertiary fw-semibold">(8)</span></a></li>
            <li class="nav-item"><a class="nav-link" href="#"><span>Failed</span><span class="text-body-tertiary fw-semibold">(2)</span></a></li>
        </ul>
        <div id="orderTable" data-list='{"valueNames":["order","total","customer","payment_status","fulfilment_status","delivery_type","date"],"page":10,"pagination":true}'>
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-auto">
                        <div class="search-box">
                            <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Search orders" aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">
                <div class="table-responsive scrollbar mx-n1 px-1">
                    <table class="table table-sm fs-8 mb-0">
                        <thead>
                            <tr>
                                <th class="white-space-nowrap fs-8 align-middle text-center ps-0" style="width:10%;">STT</th>
                                <th class="sort white-space-nowrap fs-8 align-middle text-center pe-3" scope="col" data-sort="order" style="width:30%;">Mã đơn hàng</th>
                                <th class="sort align-middle text-center fs-8" scope="col" data-sort="total" style="width:20%;">Tổng số tiền</th>
                                <th class="sort align-middle text-center pe-3 fs-8" scope="col" data-sort="payment_status" style="width:20%;">Trạng thái</th>
                                <th class="sort align-middle text-center pe-0 fs-8" scope="col" data-sort="date" style="width:20%;">Thời gian đặt hàng</th>
                                <th class="sort align-middle text-center fs-8" scope="col" data-sort="delivery_type" style="width:10%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="order-table-body">
                            @foreach ($orders as $order)
                            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                <td class="fs-8 align-middle text-center px-0 py-3">{{ $loop->iteration }}</td>
                                <td class="order fs-8 align-middle text-center white-space-nowrap py-0"><a class="fw-semibold" href="#!">{{ $order['code'] }}</a></td>
                                <td class="total fs-8 align-middle text-center fw-semibold text-body-highlight">{{ number_format($order['total_price']) }} VNĐ</td>
                                <td class="payment_status fs-8 align-middle white-space-nowrap text-center fw-bold text-body-tertiary">
                                    @php
                                    $status = $order['status'];
                                    $statusLabels = [
                                    0 => ['label' => 'Đang chờ', 'color' => 'primary', 'icon' => 'clock'],
                                    1 => ['label' => 'Thành công', 'color' => 'success', 'icon' => 'check'],
                                    2 => ['label' => 'Đã huỷ', 'color' => 'danger', 'icon' => 'x'],
                                    3 => ['label' => 'Đã xác nhận', 'color' => 'info', 'icon' => 'info'],
                                    4 => ['label' => 'Đang giao hàng', 'color' => 'warning', 'icon' => 'truck'],
                                    5 => ['label' => 'Đang chờ thanh toán', 'color' => 'secondary', 'icon' => 'credit-card'],
                                    ];
                                    $currentStatus = $statusLabels[$status];
                                    @endphp
                                    <span class="badge badge-phoenix fs-10 badge-phoenix-{{ $currentStatus['color'] }}">
                                        <span class="badge-label">{{ $currentStatus['label'] }}</span>
                                        <span class="ms-1" data-feather="{{ $currentStatus['icon'] }}" style="height:12.8px;width:12.8px;"></span>
                                    </span>
                                </td>
                                <td class="date fs-8 align-middle white-space-nowrap text-body-tertiaryps-4 text-center">
                                    <script>
                                        document.write(formatTimeAgo("{{ $order['created_at'] }}"));
                                    </script>
                                </td>
                                <td class="date align-middle white-space-nowrap text-body-tertiary fs-8 ps-4 text-center">
                                    <div class="btn-reveal-trigger position-relative">
                                        <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10" type="button" data-bs-toggle="dropdown" data-boundary="window">
                                            <span class="fas fa-ellipsis-h fs-10"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end py-2">
                                            <a class="dropdown-item" href="{{ route('order.detail', ['id' => $order['id']]) }}">Xem chi tiết</a>
                                            <a class="dropdown-item" href="#!">Export</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#!">Xóa</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection