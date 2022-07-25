<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function GetById($id)
    {
        $pokemon = Pokemon::where('id', $id)->first();

        //dd($pokemon->moves[0]->pivot->learn_method);
        //dd($pokemon->types[1]->pivot->slot);
        //dd($pokemon->sprites);
        //dd($pokemon->versions[0]->pivot->index);
        //dd($pokemon->stat->base_speed);
        dd($pokemon->abilities);

        return $pokemon;
    }
}