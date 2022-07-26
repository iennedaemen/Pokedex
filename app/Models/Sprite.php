<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sprite extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'pokemon_id',
        'front_default',
        'front_female',
        'front_shiny',
        'front_shiny_female',
        'back_default',
        'back_female',
        'back_shiny',
        'back_shiny_female',
    ];
}