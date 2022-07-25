<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Move extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function pokemon()
    {
        return $this->belongsToMany(Pokemon::class, 'move_pokemon', 'move_id', 'pokemon_id')->withPivot('learn_level', 'learn_method', 'version_id');
    }
}