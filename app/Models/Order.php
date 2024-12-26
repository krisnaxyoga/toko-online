<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order_item()
    {
        return $this->hasMany(Order_item::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store_setting()
    {
        return $this->belongsTo(Store_setting::class);
    }

    public function user_address()
    {
        return $this->belongsTo(User_address::class , 'shipping_address_id', 'id');
    }
}