<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function GetAll(Request $request)
    {
        $allPokemon = null;

        switch($request->sort)
        {
            case 'name-asc':
                $allPokemon = Pokemon::orderBy('name', 'asc')->get();
                break;
            case 'name-desc':
                $allPokemon = Pokemon::orderBy('name', 'desc')->get();
                break;
            case 'id-asc':
                $allPokemon = Pokemon::orderBy('id', 'asc')->get();
                break;
            case 'id-desc':
                $allPokemon = Pokemon::orderBy('id', 'desc')->get();
                break;
            default:
                $allPokemon = Pokemon::all();
                break;
        }

        return response()->json(['description' => 'Successful operation', 'allPokemon' => $allPokemon], 200);
    }


    public function GetById($id)
    {
        $pokemon = Pokemon::where('id', $id)->first();

        if($pokemon == null)
            return response()->json(['description' => 'Pokemon not found'], 404);

        $pokemon['types'] = $pokemon->types->where('past', 0);
        $pokemon['past_types'] = $pokemon->types->where('past', 1);
        $pokemon['sprites'] = $pokemon->sprites;
        $pokemon['stats'] = $pokemon->stat;
        $pokemon['abilities'] = $pokemon->abilities;
        $pokemon['version_indices'] = $pokemon->versions;
        $pokemon['moves'] = $pokemon->moves;

        // TEST RELATIONS
       // dd(json_encode($pokemon->moves));
        //dd($pokemon->moves[0]->pivot->learn_method);
        //dd($pokemon->types[1]->pivot->slot);
        //dd($pokemon->sprites);
        //dd($pokemon->versions[0]->pivot->index);
        //dd($pokemon->stat->base_speed);
        //dd($pokemon->abilities);

        return response()->json(['description' => 'Successful operation', 'pokemon' => $pokemon], 200);
    }


}