<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PokemonController extends Controller
{
    public function GetAll(Request $request)
    {
        $allPokemon = null;

        if($request->sort)
        {
            $splitCharIndex = strpos($request->sort, '-');
            $sortKey = substr($request->sort, 0, $splitCharIndex);
            $sortValue = substr($request->sort, $splitCharIndex + 1);
            $allPokemon = Pokemon::orderBy($sortKey, $sortValue)->get();
        }
        else $allPokemon = Pokemon::all();

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

    public function GetAllPaginated(Request $request)
    {
        $allPokemon = null;
 
        // sort, limit, offset
        $sortKey = null;
        $sortValue = null;

        if($request->sort)
        {
            $splitCharIndex = strpos($request->sort, '-');
            $sortKey = substr($request->sort, 0, $splitCharIndex);
            $sortValue = substr($request->sort, $splitCharIndex + 1);
        }

        $count = Pokemon::count();

        if($request->limit && $request->offset)
        {
            if($sortKey && $sortValue)
                $allPokemon = Pokemon::orderBy($sortKey, $sortValue)->skip($request->offset)->take($request->limit)->get();
            else
                $allPokemon = Pokemon::skip($request->offset)->take($request->limit)->get();
        }

        else if($request->limit && !$request->offset)
        {
            if($sortKey && $sortValue)
                $allPokemon = Pokemon::orderBy($sortKey, $sortValue)->take($request->limit)->get();
            else
                $allPokemon = Pokemon::take($request->limit)->get();
        }

        else if(!$request->limit && $request->offset)
        {
            if($sortKey && $sortValue)
                $allPokemon = Pokemon::orderBy($sortKey, $sortValue)->skip($request->offset)->take($count - $request->offset)->get();
            else
                $allPokemon = Pokemon::skip($request->offset)->take($count - $request->offset)->get();
        }

        else
        {
            if($sortKey && $sortValue)
                $allPokemon = Pokemon::orderBy($sortKey, $sortValue)->get();
            else
                $allPokemon = Pokemon::all();
        }

        $nrPages = 0;

        if( $request->limit)
        {
            while($count > 0)
            {
                $nrPages++;
                $count -= $request->limit;
            }
        }
        else $nrPages = 1;

        $currentPage = 1;

        if($request->offset && $request->limit && $request->offset >= $request->limit)
        {
            $currentPage = ceil($request->offset / $request->limit) + 1;
        }

        $route = url()->current();

        $routeNext = null;
        if($request->limit && $request->offset + $request->limit < Pokemon::count())
            $routeNext = $route . '?sort=' . $request->sort  . '&offset=' . $request->offset + $request->limit . '&limit=' . $request->limit;

        $routePrevious = null;
        if($request->limit && $request->offset - $request->limit >= 0)
            $routePrevious = $route . '?sort=' . $request->sort  . '&offset=' . $request->offset - $request->limit . '&limit=' . $request->limit;

        $data = [];
        $data['pages'] = $nrPages;
        $data['page'] = $currentPage;
        $data['total'] = Pokemon::count();
        $data['next'] = $routeNext;
        $data['previous'] = $routePrevious;

        return response()->json(['description' => 'Successful operation', 'allPokemon' => $allPokemon, 'data' => $data], 200);
    }

    
    public function Search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'The query is empty'], 400);
        }

        $pokemon = null;
        $types = null;

        $limit = $request->limit;

        if($limit)
        {
            $types = Type::where('name', 'LIKE', '%' . $request->query('query') . '%')->take($limit)->get();
            $limit -= $types->count();

            if($limit > 0)
                $pokemon = Pokemon::where('name', 'LIKE', '%'.$request->query('query').'%')->take($limit)->get();
        }
        else
        {
            $types = Type::where('name', 'LIKE', '%'.$request->query('query').'%')->get();
            $pokemon = Pokemon::where('name', 'LIKE', '%'.$request->query('query').'%')->get();
        }

        $result = [];
        $result['types'] = $types;
        $result['pokemon'] = $pokemon;

        return response()->json(['description' => 'Successful operation', 'result' => $result], 200);
    }

}