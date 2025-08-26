<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'status',
        'image'
    ];

    // Relationship 
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function salesItem(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }
}
