<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $casts = [
        'creator_id' => 'integer',
        'cost'       => 'float',
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'has_area' => 'boolean',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class,'creator_id');
    }
}
