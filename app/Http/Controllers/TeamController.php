<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Pokemon;
use App\Models\PokemonTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function AddTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|unique:teams,name,NULL,id,user_id,' . Auth::id(),
            'pokemons' => 'array|max:6',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'Can not create team with this info'], 400);
        }

        $team = new Team();
        $team->user_id = Auth::id();
        $team->name = $request->name;
        $team->save();

        $position = 1;

        if($request->pokemons)
        {
            foreach($request->pokemons as $pokemon)
            {
                $position = $this->AddToTeam($pokemon, $team->id, $position, new PokemonTeam());
            }
        }

        return response()->json(['description' => 'Successful operation'], 201);
    }

    public function GetAll()
    {
        $teams = Team::where('user_id', Auth::id())->get();
        $arr = [];
        
        foreach($teams as $team)
        {
            $arr[$team->name] = '';
        }

        foreach($teams as $team)
        {
            $arr[$team->name] .= $team->pokemon . ', ';
        }

        return response()->json(['description' => 'Successful operation', 'teams' => $arr], 200);
    }

    public function GetById($id)
    {
        $t = Team::where('id', $id)->first();

        if($t == null)
            return response()->json(['description' => 'Team not found'], 404);    

        $team = [$t->name, $t->pokemon];

        return response()->json(['description' => 'Successful operation', 'team' => $team], 200);
    }


    public function SetTeam(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pokemons' => 'array|max:6',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'Can not create team with this info'], 400);
        }

        if (Team::where('id', $id) == null) 
        {    
            return response()->json(['description' => 'Team not found'], 400);
        }

        $position = 1;

        if($request->pokemons)
        {
            foreach($request->pokemons as $pokemon)
            {
                $position = $this->AddToTeam($pokemon, $id, $position, PokemonTeam::where('team_id', $id)->where('position', $position)->first());
            }

            for($i = count($request->pokemons) + 1; $i <= 6; ++$i)
            {
                $position = $this->AddToTeam(null, $id, $position, PokemonTeam::where('team_id', $id)->where('position', $position)->first());
            }
        }
        else
        {
            for($i = 1; $i <= 6; ++$i)
            {
                $position = $this->AddToTeam(null, $id, $position, PokemonTeam::where('team_id', $id)->where('position', $position)->first());
            }
        }

        return response()->json(['description' => 'Successful operation'], 201);
    }

    public function DeleteTeam($id)
    {
        Team::where('id', $id)->delete();
        PokemonTeam::where('team_id', $id)->delete();
        return response()->json(['description' => 'Successful operation'], 200);
    }

    private function AddToTeam($pokemon_id, $team_id, $position, $pt)
    {
        if($pokemon_id)
        {
            if($pt == null)
                $pt = new PokemonTeam();
                
            $pt->team_id = $team_id;
            $pt->pokemon_id = $pokemon_id;
            $pt->position = $position;
            $pt->save();
        }
        else
        {
             PokemonTeam::where('team_id' ,$team_id)->where('position', $position)->delete();
        }

        return ++$position;
    }
}