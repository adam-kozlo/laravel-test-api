<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsyncAction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'error_description',
    ];

}
