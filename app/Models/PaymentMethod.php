<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];


    // Relation 
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
