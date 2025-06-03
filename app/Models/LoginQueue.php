<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginQueue extends Model
{
        /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'login_queue';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'position',
    ];
}
