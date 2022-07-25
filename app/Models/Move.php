<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Move extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class);
    }
}