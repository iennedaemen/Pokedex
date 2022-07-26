<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_hidden',
    ];

    public function pokemon()
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_ability', 'ability_id', 'pokemon_id')->withPivot('slot');
    }
}