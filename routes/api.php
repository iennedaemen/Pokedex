<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
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



Route::get('/v1/pokemons', [PokemonController::class, 'GetAll']);
Route::get('/v1/pokemons/{id}', [PokemonController::class, 'GetById']);
Route::get('/v2/pokemons', [PokemonController::class, 'GetAllPaginated']);

Route::get('/v1/teams', [TeamController::class, 'getAll'])->middleware('auth:sanctum');
Route::post('/v1/teams', [TeamController::class, 'AddTeam'])->middleware('auth:sanctum');
Route::get('/v1/teams/{id}', [TeamController::class, 'GetById'])->middleware('auth:sanctum');
Route::post('/v1/teams/{id}', [TeamController::class, 'SetTeam'])->middleware('auth:sanctum');

//Route::get('/v1/search', [PokemonController::class, 'search']);

//Route::get('/v2/pokemons', [PokemonController::class, 'get']);

Route::post('/user/add', [UserController::class, 'AddUser']);
Route::post('/user/edit', [UserController::class, 'EditUser'])->middleware('auth:sanctum');
Route::get('/user/delete', [UserController::class, 'DeleteUser'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/logout', [AuthController::class, 'Logout'])->middleware('auth:sanctum');