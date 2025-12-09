<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreBalanceHistory extends Model
{
    protected $fillable = [
        'store_balance_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    // Relationship: belongs to store balance
    public function storeBalance()
    {
        return $this->belongsTo(StoreBalance::class);
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo();
    }
}
