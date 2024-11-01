@extends('layouts.app')
@section('title')
Chi tiết đơn hàng
@endsection
@section('content')
<div class="content">
    <div class="mb-9">
        <h2 class="mb-5">Chi tiết đơn hàng<span> #{{ $order['tracking_number'] }}</span></h2>
        <div class="row g-5 gy-7">
            <div class="col-12 col-xl-8 col-xxl-9">
                <div id="orderTable" data-list='{"valueNames":["products","color","size","price","quantity","total"],"page":10}'>
                    <div class="table-responsive scrollbar">
                        <table class="table fs-7 mb-0 border-top border-translucent">
                            <thead>
                                <tr>
                                    <th class="sort white-space-nowrap text-center align-middle" style="width:10%;" scope="col">Mã đơn hàng</th>
                                    <th class="sort white-space-nowrap text-center align-middle" scope="col" style="width:20%;" data-sort="products">Tên mặt hàng</th>
                                    <th class="sort align-middle text-center ps-4" scope="col" data-sort="price" style="width:20%;">Giá</th>
                                    <th class="sort align-middle text-center ps-4" scope="col" data-sort="quantity" style="width:25%;">Giao hàng</th>
                                </tr>
                            </thead>
                            <tbody class="list" id="order-table-body">
                                <tr class="hover-actions-trigger btn-reveal-trigger position-static">

                                    <td class="color align-middle text-center white-space-nowrap text-body py-0 ps-4 fs-8">
                                        {{ $order['tracking_number'] }}
                                    </td>

                                    <td class="price align-middle text-body fw-semibold text-center py-0 ps-4 fs-8">
                                        {{ $order['item_name'] }}
                                    </td>

                                    <td class="quantity align-middle text-center py-0 ps-4 text-body-tertiary fs-8">
                                        {{ number_format($order['value']) }} VNĐ
                                    </td>

                                    <td class="total align-middle fw-bold text-body-highlight text-center py-0 ps-4 fs-8">
                                        @foreach($shippingStatus as $status)
                                        {{ $status['id'] === $order->shipping_status ? $status['name'] : "" }}
                                        @endforeach
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row gx-4 gy-6 g-xl-7 justify-content-sm-center justify-content-xl-star mt-6">
                    <div class="col-12 col-sm-auto">
                        <h4 class="mb-5">Chi tiết bên gửi</h4>
                        <div class="row g-4 flex-sm-column">
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="user" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Họ và tên</h6>
                                </div><a class="d-block fs-9 ms-4" href="#!">{{ $order['sender_name'] }}</a>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="mail" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Ngày vận chuyển</h6>
                                </div><a class="d-block fs-9 ms-4" href="#:">{{ $order['created_at'] }}</a>
                            </div>
                            <div class="col-6 col-sm-12 order-sm-1">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="home" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Địa chỉ</h6>
                                </div>
                                @php
                                $addressParts = explode(',', $order['sender_address']);
                                @endphp

                                @if (count($addressParts) > 1)
                                <p class="text-body-secondary mb-0 fs-9">{{ trim($addressParts[0]) }}</p>
                                <p class="text-body-secondary mb-0 fs-9">
                                    {{ trim($addressParts[1]) }},
                                    @if(isset($addressParts[2]))
                                    {{ trim($addressParts[2]) }}<br class="d-none d-sm-block" />
                                    @endif
                                    {{ isset($addressParts[3]) ? trim($addressParts[3]) : '' }}
                                </p>
                                @endif
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="phone" style="stroke-width:2.5;"> </span>
                                    <h6 class="mb-0">Số điện thoại</h6>
                                </div><a class="d-block fs-9 ms-4" href="#">{{ $order['phone_number_sender'] }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto">
                        <h4 class="mb-5">Chi tiết bên nhận</h4>
                        <div class="row g-4 flex-sm-column">
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="user" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Họ và tên</h6>
                                </div><a class="d-block fs-9 ms-4" href="#!">{{ $order['recipient_name'] }}</a>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="mail" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Email</h6>
                                </div><a class="d-block fs-9 ms-4" href="#:">{{ $order['email_recipient'] }}</a>
                            </div>
                            <div class="col-6 col-sm-12 order-sm-1">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="home" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Địa chỉ</h6>
                                </div>
                                @php
                                $addressParts = explode(',', $order['recipient_address']);
                                @endphp

                                @if (count($addressParts) > 1)
                                <p class="text-body-secondary mb-0 fs-9">{{ trim($addressParts[0]) }}</p>
                                <p class="text-body-secondary mb-0 fs-9">
                                    {{ trim($addressParts[1]) }},
                                    @if(isset($addressParts[2]))
                                    {{ trim($addressParts[2]) }}<br class="d-none d-sm-block" />
                                    @endif
                                    {{ isset($addressParts[3]) ? trim($addressParts[3]) : '' }}
                                </p>
                                @endif
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="phone" style="stroke-width:2.5;"> </span>
                                    <h6 class="mb-0">Số điện thoại</h6>
                                </div><a class="d-block fs-9 ms-4" href="#">{{ $order['phone_number_recipient'] }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-auto">
                        <h4 class="mb-5">Chi tiết khác</h4>
                        <div class="row g-4 flex-sm-column">
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="shopping-bag" style="stroke-width:2.5;"></span>
                                    <h6 class="mb-0">Khối lượng (kg)</h6>
                                </div>
                                <p class="mb-0 text-body-secondary fs-9 ms-4">{{ $order['weight'] }}</p>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="credit-card" style="stroke-width:2.5;"> </span>
                                    <h6 class="mb-0">Loại mặt hàng</h6>
                                </div>
                                <p class="mb-0 text-body-secondary fs-9 ms-4">
                                    @foreach($paymentStatuses as $status)
                                    {{ $status['id'] === $order->payment_status ? $status['name'] : "" }}
                                    @endforeach
                                </p>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="file-text" style="stroke-width:2.5;"> </span>
                                    <h6 class="mb-0">Phí ship</h6>
                                </div>
                                <p class="mb-0 text-body-secondary fs-9 ms-4">{{ number_format($order['fee_ship']) }} VNĐ</p>
                            </div>
                            <div class="col-6 col-sm-12">
                                <div class="d-flex align-items-center mb-1"><span class="me-2" data-feather="mail" style="stroke-width:2.5;"> </span>
                                    <h6 class="mb-0">Ghi chú</h6>
                                </div>
                                <div class="ms-4">
                                    <p class="text-body-secondary fs-9 mb-0"> {!! nl2br(wordwrap($order['note'], 20, "<br>", true)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4 col-xxl-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Tóm Tắt</h3>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <p class="text-body fw-semibold">Tổng tiền sản phẩm :</p>
                                        <p class="text-body-emphasis fw-semibold">{{ number_format($order['value']) }} VNĐ</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="text-body fw-semibold">Phí Ship :</p>
                                        <p class="text-body-emphasis fw-semibold">{{ number_format($order['fee_ship']) }} VNĐ</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between border-top border-translucent border-dashed pt-4">
                                    <h4 class="mb-0">Tổng :</h4>
                                    <h4 class="mb-0">{{ number_format($order['value'] + $order['fee_ship']) }} VNĐ</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Trạng thái đơn hàng</h3>
                                <h6 class="mb-2">Trạng thái đơn hàng</h6>
                                <select class="form-select mb-4" aria-label="delivery type" data-id="{{ $order['id'] }}" name="order_status" onchange="updateOrderStatus(this.dataset.id, this.value)">
                                    @foreach (\App\Models\Order::STATUS_LABEL as $statusKey => $statusLabel)
                                    <option value="{{ $statusKey }}" {{ $statusKey == $order['status'] ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('order.edit.js')
@endsection