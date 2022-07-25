<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Version extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function pokemon()
    {
        return $this->belongsToMany(Pokemon::class, 'version_pokemon_id', 'version_id', 'pokemon_id' )->withPivot('index');
    }

}