<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_fragile',
        'is_high_value',
        'is_perishable',
        'is_dangerous',
        'is_flammable',
        'is_cold_storage',
        'is_international',
        'is_non_standard'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_parcel_types', 'parcel_type_id', 'order_id');
    }
}
