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
        'slot',
    ];
}