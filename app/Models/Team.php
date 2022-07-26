<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function pokemon()
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_team', 'team_id', 'pokemon_id')->withPivot('position');
    }
}