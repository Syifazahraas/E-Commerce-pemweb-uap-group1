<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'product_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor to get the User who made the review
    public function getUserAttribute()
    {
        // Prioritize direct User relationship
        if ($this->relationLoaded('user')) {
            return $this->getRelation('user');
        }
        
        // If user_id is set but relation not loaded
        if ($this->user_id) {
            return \App\Models\User::find($this->user_id);
        }

        // Fallback to transaction -> buyer -> user
        return $this->transaction->buyer->user ?? null;
    }
}
