<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    // Relation 
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
