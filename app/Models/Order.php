<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $guarded = ['id'];
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    public const STATUS_LABEL = [
        self::STATUS_PENDING => 'Đang chờ',
        self::STATUS_DELIVERED => 'Thành công',
        self::STATUS_CANCELLED => 'Đã hủy',
        self::STATUS_PROCESSING => 'Đã xác nhận',
        self::STATUS_IN_TRANSIT => 'Đang giao hàng'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parcelTypes()
    {
        return $this->belongsToMany(ParcelType::class, 'order_parcel_types', 'order_id', 'parcel_type_id');
    }
}
