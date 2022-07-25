<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/



//Route::get('/v1/pokemons/{sort?}', [PokemonController::class, 'GetAll']);
Route::get('/v1/pokemons/{id}', [PokemonController::class, 'GetById']);

//Route::get('/v1/teams', [TeamController::class, 'getAll']);
//Route::post('/v1/teams', [TeamController::class, 'create']);
//Route::get('/v1/teams/{id}', [TeamController::class, 'getById']);
//Route::post('/v1/teams/{id}', [TeamController::class, 'set']);

//Route::get('/v1/search', [PokemonController::class, 'search']);

//Route::get('/v2/pokemons', [PokemonController::class, 'get']);