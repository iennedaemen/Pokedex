<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Pokemon;

class TeamController extends Controller
{
    public function AddTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|unique:teams,name,NULL,id,user_id,' . Auth::id(),
            'pokemon1' => 'required',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'Can not create team with this info'], 400);
        }

        $position = 1;

        if($request->pokemon1)
            $position = $this->AddToTeam($request->pokemon1, $request->name, $position);
        if($request->pokemon2)
            $position = $this->AddToTeam($request->pokemon2, $request->name, $position);
        if($request->pokemon3)
            $position = $this->AddToTeam($request->pokemon3, $request->name, $position);
        if($request->pokemon4)
            $position = $this->AddToTeam($request->pokemon4, $request->name, $position);
        if($request->pokemon5)
            $position = $this->AddToTeam($request->pokemon5, $request->name, $position);
        if($request->pokemon6)
            $position = $this->AddToTeam($request->pokemon6, $request->name, $position);

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

    private function AddToTeam($pokemon, $name, $position)
    {
        $team = new Team();
        $team->user_id = Auth::id();
        $team->name = $name;
        $team->pokemon_id = $pokemon;
        $team->position = $position;
        $team->save();

        return ++$position;
    }
}