<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'total',
        'paid_amount',
        'discount',
    ];

    // Relation 
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function salesItem(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }
}
