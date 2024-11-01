<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ParcelType;
use App\Jobs\UpdateOrderStatusJob;

class OrderController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            $orders = Order::with(['user', 'parcelTypes'])->orderBy('created_at', 'desc')->get();

            return view('order.index.list', [
                'total_order' => Order::count(),
                'orders' => $orders,
                'parcelTypes' => ParcelType::all(),
            ]);
        }
        return redirect()->route('auth.login');
    }



    public function create()
    {
        return view('order.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_name' => 'nullable|string|max:255',
                'sender_name' => 'required|string|max:255',
                'sender_address' => 'required|string|max:255',
                'phone_number_sender' => 'required|string|max:20',
                'recipient_name' => 'required|string|max:255',
                'recipient_address' => 'required|string|max:255',
                'phone_number_recipient' => 'required|string|max:20',
                'email_recipient' => 'nullable|string|max:20',
                'note' => 'nullable|string',
                'parcel_type_id' => 'nullable|exists:parcel_types,id',
                'value' => 'required|numeric|min:0',
                'weight' => 'required|numeric|min:0',
                'fee_ship' => 'required|numeric|min:0',
                'payment_status' => 'required|in:paid,cash_on_delivery',
                'shipping_status' => 'required|in:standard,express',
            ]);

            $validatedData['status'] = 'pending';
            $validatedData['user_id'] = auth()->id();
            if (!isset($validatedData['payment_status'])) {
                $validatedData['payment_status'] = 'cash_on_delivery';
            }
            if (!isset($validatedData['shipping_status'])) {
                $validatedData['shipping_status'] = 'standard';
            }
            $parcelType = ParcelType::find($validatedData['parcel_type_id']);

            if ($parcelType) {
                $words = explode(' ', $parcelType->name);
                $initials = '';

                foreach ($words as $word) {
                    $initials .= mb_substr($word, 0, 1);
                }
                $trackingNumber = strtoupper($initials) . "-00" . $parcelType->id . time() . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);
                $validatedData['tracking_number'] = $trackingNumber;
            } else {
                $validatedData['tracking_number'] = null;
            }

            $order = Order::create($validatedData);

            if ($request->has('parcel_type_id')) {
                $order->parcelTypes()->attach($validatedData['parcel_type_id']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm đơn hàng thành công!',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thêm đơn hàng thất bại!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        if (Auth::user()) {
            $order = Order::with('parcelTypes')->find($id);
            $paymentStatuses = [
                ['id' => 'paid', 'name' => 'Đã thanh toán'],
                ['id' => 'cash_on_delivery', 'name' => 'Thanh toán khi nhận hàng'],
            ];
            $shippingStatus = [
                ['id' => 'standard', 'name' => 'Giao hàng tiêu chuẩn'],
                ['id' => 'express', 'name' => 'Giao hàng hỏa tốc'],
            ];
            return view(
                'order.edit.detail',
                [
                    'order' => $order,
                    'paymentStatuses' => $paymentStatuses,
                    'shippingStatus' => $shippingStatus
                ]
            );
        }
        return redirect()->route('auth.login');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $orders = Order::where('user_id', auth()->id())
            ->where(function ($q) use ($query) {
                $q->where('tracking_number', 'LIKE', "%{$query}%")
                    ->orWhere('recipient_name', 'LIKE', "%{$query}%");
            })
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function destroy(Request $request)
    {
        try {
            $order = Order::findOrFail($request->input('id'));

            if ($order->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể xóa đơn hàng. Trạng thái của đơn hàng phải là "Chờ Xử Lý".',
                ], 400);
            }

            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa đơn hàng thành công!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xóa đơn hàng thất bại!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $order = Order::find($request->input('id'));

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Đơn hàng không tồn tại'], 404);
        }

        $data = $request->only(['status']);

        if ($order->status != $data['status']) {
            $content = '';
            switch ($data['status']) {
                case Order::STATUS_CANCELLED:
                    $content = "Đơn hàng #{$order->code} của bạn đã bị hủy bỏ";
                    break;
                case Order::STATUS_PENDING:
                    $content = "Đơn hàng #{$order->code} của bạn đang chờ xác nhận";
                    break;
                case Order::STATUS_DELIVERED:
                    $content = "Đơn hàng #{$order->code} của bạn đã được giao thành công";
                    break;
                case Order::STATUS_PROCESSING:
                    $content = "Đơn hàng #{$order->code} đã được xác nhận và sẽ sớm được giao tới bạn";
                    break;
                case Order::STATUS_IN_TRANSIT:
                    $content = "Đơn hàng #{$order->code} đang trên đường giao tới bạn";
                    break;
                default:
                    break;
            }

            if ($content) {
                dispatch(new UpdateOrderStatusJob($order->email_recipient, $order->recipient_name, $content));
            }
        }

        $order->update($data);

        return response()->json(['status' => 'success', 'message' => 'Cập nhật đơn hàng thành công']);
    }
}
