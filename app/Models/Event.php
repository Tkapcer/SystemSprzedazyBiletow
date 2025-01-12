<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = [];

    public function sectors() {
        return $this->hasMany(Sector::class);
    }

    public function organizers() {
        return $this->belongsTo(Organizer::class);
    }
}
