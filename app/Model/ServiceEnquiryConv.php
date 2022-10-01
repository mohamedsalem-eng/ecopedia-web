<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceEnquiryConv extends Model
{

    protected $table = 'service_enquiry_convs';
    protected $casts = [
        'service_enquiry_id' => 'integer',
        'admin_id'          => 'integer',
        'position'          => 'integer',

        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
