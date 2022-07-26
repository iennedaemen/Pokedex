<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'pokemon_id',
        'base_hp',
        'effort_hp',
        'base_attack',
        'effort_attack',
        'base_defense',
        'effort_defense',
        'base_special_attack',
        'effort_special_attack',
        'base_special_defense',
        'effort_special_defense',
        'base_speed',
        'effort_speed',
    ];

    public function pokemon()
    {
        return $this->hasOne(Pokemon::class);
    }
}