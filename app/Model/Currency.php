<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Currency extends Model
{
    protected $casts = [
        'exchange_rate' => 'float',
        'status'        => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    protected $table = 'currencies';



    public function translations()
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', Helpers::default_lang());
                }
            }]);
        });
    }
}
