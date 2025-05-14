<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $guarded = [];

    public function event() {
        return $this->hasMany(Event::class);
    }

    public function sectors() {
        return $this->hasMany(Sector::class);
    }
}
