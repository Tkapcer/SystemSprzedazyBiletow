<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $guarded = [];

//    Pomijanie usuniętych sal
    protected static function booted()
    {
        static::addGlobalScope('notDeleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

//    Jeśli mimo wszystko ktoś by chciał wyświetlić usunięte sale
    public static function allIncludingDeleted()
    {
        return Venue::withoutGlobalScope('notDeleted')->get();
    }


    public function event() {
        return $this->hasMany(Event::class);
    }

    public function sectors() {
        return $this->hasMany(Sector::class);
    }

    public function hasActiveEvents() {
        return $this->event()->whereNotIn('status', ['cancelled', 'expired'])->exists();
    }
}
