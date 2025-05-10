<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralChat extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'type',
    ];
}
