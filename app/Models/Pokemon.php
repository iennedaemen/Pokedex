<?php

namespace App\Models;

use App\Models\Move;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pokemon extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function moves()
    {
        return $this->belongsToMany(Move::class);
    }
}