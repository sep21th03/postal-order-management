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
<style>
    .d-flex-a {
        display: flex;
        width: 100% !important;
        justify-content: flex-end;
    }
</style>
@section('content')
<div class="content">
    <div class="mb-9">
        <div class="row g-3 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">Đơn hàng</h2>
            </div>
        </div>
        <div id="orderTable" data-list='{"valueNames":["order","total","customer","payment_status","fulfilment_status","delivery_type","date"],"page":10,"pagination":true}'>
            <div class="mb-4">
                <div class="row g-3 justify-content-between">
                    <div class="col-auto">
                        <div class="search-box">
                            <form class="position-relative" id="searchForm">
                                <input id="searchInput" class="form-control search-input search" type="search" placeholder="Tìm kiếm" aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>
                            </form>
                        </div>
                    </div>
                    <div class="col-auto"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrder"><span class="fas fa-plus me-2"></span>Thêm đơn hàng</button></div>
                </div>
            </div>
            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">
                <div class="table-responsive scrollbar mx-n1 px-1">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="white-space-nowrap fs-8 align-middle ps-0" style="width:26px;">STT</th>
                                <th class="sort white-space-nowrap align-middle text-start pe-3" scope="col" data-sort="order" style="width:10%;">Mã đơn hàng</th>
                                <th class="sort align-middle text-start" scope="col" data-sort="total" style="width:15%;">Tên đơn hàng</th>
                                <th class="sort align-middle text-start" scope="col" data-sort="total" style="width:15%;">Tổng số tiền</th>
                                <th class="sort align-middle text-start" scope="col" data-sort="delivery_type" style="width:15%;">Số điện thoại</th>
                                <th class="sort align-middle text-start" scope="col" data-sort="delivery_type" style="width:15%;">Mặt hàng</th>
                                <th class="sort align-middle text-start pe-3" scope="col" data-sort="payment_status" style="width:10%;">Trạng thái</th>
                                <th class="sort align-middle text-start pe-0" scope="col" data-sort="date" style="width:20%;">Thời gian đặt hàng</th>
                                <th class="sort align-middle text-start" scope="col" data-sort="delivery_type" style="width:10%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="order-table-body">
                            @forelse ($orders as $order)
                            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                                <td class="fs-8 align-middle text-center px-0 py-3">{{ $loop->iteration }}</td>
                                <td class="fs-9 order align-middle text-start white-space-nowrap py-0">
                                    <a class="fw-semibold" href="{">
                                        {{ $order->tracking_number }}
                                    </a>
                                </td>
                                <td class="customer align-middle white-space-nowrap">
                                    {{ $order->item_name }}
                                </td>
                                <td class="total align-middle text-start fw-semibold text-body-highlight">
                                    {{ number_format($order->value, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="delivery_type align-middle white-space-nowrap text-body fs-8 text-start">
                                    {{ $order->phone_number }}
                                </td>
                                <td class="item align-middle white-space-nowrap text-start">
                                    @foreach($order->parcelTypes as $parcelType)
                                    {{ $parcelType->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                                <td class="payment_status align-middle white-space-nowrap text-center fw-bold text-body-tertiary">
                                    @php
                                    $statusColors = [
                                    'pending' => ['color' => 'warning', 'label' => 'Chờ xử lý', 'icon' => 'clock'],
                                    'processing' => ['color' => 'info', 'label' => 'Đang xử lý', 'icon' => 'refresh-cw'],
                                    'in_transit' => ['color' => 'primary', 'label' => 'Đang vận chuyển', 'icon' => 'truck'],
                                    'delivered' => ['color' => 'success', 'label' => 'Đã giao', 'icon' => 'check-circle'],
                                    'cancelled' => ['color' => 'danger', 'label' => 'Đã hủy', 'icon' => 'x-circle']
                                    ];
                                    $currentStatus = $statusColors[$order->status] ?? $statusColors['pending'];
                                    @endphp
                                    <span class="badge badge-phoenix fs-9 badge-phoenix-{{ $currentStatus['color'] }}">
                                        <span class="badge-label">{{ $currentStatus['label'] }}</span>
                                        <span class="ms-1" data-feather="{{ $currentStatus['icon'] }}" style="height:12.8px;width:12.8px;"></span>
                                    </span>
                                </td>
                                <td class="date align-middle white-space-nowrap text-body-tertiary fs-8 ps-4 text-start">
                                    {{ \Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}
                                </td>
                                <td class="date align-middle white-space-nowrap text-body-tertiary fs-8 ps-4 text-center">
                                    <div class="btn-reveal-trigger position-relative">
                                        <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-8" type="button" data-bs-toggle="dropdown" data-boundary="window">
                                            <span class="fas fa-ellipsis-h fs-8"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end py-2">
                                            <a class="dropdown-item" href="{{ route('order.show', ['id' => $order['id']]) }}">Xem chi tiết</a>
                                            <a class="dropdown-item text-danger" onclick="delete_order({{ $order['id'] }})" href="#!">Xóa</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Không có đơn hàng nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('modal')
@include('order.modal.main')
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
@include('order.index.js')
@endsection