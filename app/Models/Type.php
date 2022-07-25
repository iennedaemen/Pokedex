<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function pokemon()
    {
        return $this->belongsToMany(Type::class, 'pokemon_type', 'type_id', 'pokemon_id')->withPivot('slot', 'past');
    }
}