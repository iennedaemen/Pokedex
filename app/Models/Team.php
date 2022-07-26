<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function pokemon()
    {
        return $this->hasMany(Pokemon::class);
    }
}