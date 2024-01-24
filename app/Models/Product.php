<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'created_at',
        'updated_at',
    ];

    public function productImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }
}
