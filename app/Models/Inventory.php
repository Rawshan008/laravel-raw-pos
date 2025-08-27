<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'item_id',
        'quantity'
    ];

    // Relation 
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
