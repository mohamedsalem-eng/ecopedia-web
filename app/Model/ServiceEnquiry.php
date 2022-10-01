<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ServiceEnquiry extends Model
{
    protected $table = 'service_enquiries';
    protected $casts = [
        'customer_id' => 'integer',
        /* 'status' => 'integer', */

        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function conversations()
    {
        return $this->hasMany(ServiceEnquiryConv::class);
    }
    public function notSeen()
    {
        return $this->hasMany(ServiceEnquiryConv::class)->where("checked", 0);
    }
}
