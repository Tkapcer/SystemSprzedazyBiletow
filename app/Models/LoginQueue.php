<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginQueue extends Model
{
    protected $connection = 'mysql';
    protected $table = 'login_queue';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'position',
    ];
}
