<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Product_variant::class);
    }

    public function images()
    {
        return $this->hasMany(Product_image::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class)->where('user_id', auth()->id());
    }

    public function reviews()
    {
        return $this->hasMany(Product_review::class);
    }
}