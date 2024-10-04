<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = "animals";
    protected $fillable = [
        'user_id',
        'name',
        'birthday',
        'breed',
        'animal_type_id',
        'image'
    ];
}
