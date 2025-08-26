<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesItem extends Model
{
    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'price'
    ];

    // Relation 
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
