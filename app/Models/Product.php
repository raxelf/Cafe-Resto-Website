<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }

    public function order(){
        return $this->hasMany(OrderDetail::class);
    }

    public function cart(){
        return $this->hasMany(CartItem::class);
    }
}
