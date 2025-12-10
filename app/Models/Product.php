<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

   protected $fillable = [
    'store_id',
    'product_category_id',
    'name',
    'slug',
    'description',
    'condition',
    'price',
    'weight',
    'stock',
    'material',
    'sizes',
    'is_on_sale', // TAMBAHKAN INI
];

protected $casts = [
    'price' => 'decimal:2',
    'sizes' => 'array',
    'is_on_sale' => 'boolean', // TAMBAHKAN INI
];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
