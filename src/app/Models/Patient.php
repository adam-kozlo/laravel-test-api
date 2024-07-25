<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
    ];

    public function documents() : HasMany {
        return $this->hasMany('App\Models\Document', 'patient_id', 'id');
    }

}
