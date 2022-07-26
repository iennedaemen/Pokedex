<?php

namespace App\Models;

use App\Models\Form;
use App\Models\Move;
use App\Models\Stat;
use App\Models\Team;
use App\Models\Type;
use App\Models\Sprite;
use App\Models\Ability;
use App\Models\Version;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pokemon extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'name', 'sprite', 'height', 'weight', 'order'
    ];

    public function moves()
    {
        return $this->belongsToMany(Move::class, 'move_pokemon', 'pokemon_id', 'move_id')->withPivot('learn_level', 'learn_method', 'version_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_type', 'pokemon_id', 'type_id')->withPivot('slot', 'past');
    }

    public function sprites()
    {
        return $this->hasMany(Sprite::class);
    }

    public function versions()
    {
        return $this->belongsToMany(Version::class, 'version_pokemon_id', 'pokemon_id', 'version_id')->withPivot('index');
    }

    public function stat()
    {
        return $this->hasOne(Stat::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'pokemon_ability', 'pokemon_id', 'ability_id')->withPivot('slot');
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}