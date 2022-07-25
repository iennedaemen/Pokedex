<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use File;
use App\Models\Form;
use App\Models\Move;
use App\Models\Stat;
use App\Models\Type;
use App\Models\Sprite;
use App\Models\Ability;
use App\Models\Pokemon;
use App\Models\Version;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/pokemons.json");
        $data = json_decode($json);

        $insertedAbilities = [];
        $insertedVersions = [];
        $insertedMoves = [];
        $insertedTypes = [];
        $pokemonId = 0;

        // POKEMON
        foreach ($data as $d)
        {
            $pokemonId++;
            $pokemon = [];
            
            $pokemon['baseExp'] = $d->base_experience;
            $pokemon['height'] = $d->height;
            $pokemon['weight'] = $d->weight;
            $pokemon['name'] = $d->name;
            $pokemon['sprite'] = $d->sprites->front_default;
            $pokemon['order'] = $d->order;

            // TYPES
            foreach ($d->types as $type) 
            {
                if(!in_array($type->type->name, $insertedTypes))
                {
                    Type::create([
                        "name" => $type->type->name,
                    ]);

                    array_push($insertedTypes, $type->type->name);
                }

                DB::table('pokemon_type')->insert([
                    'pokemon_id' => $pokemonId,
                    'type_id' => array_search($type->type->name, $insertedTypes) + 1,
                    'slot' => $type->slot,
                    'past' => 0,
                ]);
            }

            foreach ($d->past_types as $past_type) 
            {
                foreach ( $past_type->types as $type)
                {
                    if(!in_array($type->type->name, $insertedTypes))
                    {
                        Type::create([
                            "name" => $type->type->name,
                        ]);
    
                        array_push($insertedTypes, $type->type->name);
                    }
    
                    DB::table('pokemon_type')->insert([
                        'pokemon_id' => $pokemonId,
                        'type_id' => array_search($type->type->name, $insertedTypes) + 1,
                        'slot' => $type->slot,
                        'past' => 1,
                    ]);
                }
            }

            //POKEMON
            Pokemon::create([
                "name" => $pokemon['name'],
                "sprite" => $pokemon['sprite'],
                "height" => $pokemon['height'],
                "weight" => $pokemon['weight'],
                "order" => $pokemon['order'],
            ]);
        
            // SPRITES
            Sprite::create([
                "pokemon_id" => $pokemonId,
                "back_default" => $d->sprites->back_default,
                "back_female" => $d->sprites->back_female,
                "back_shiny" => $d->sprites->back_shiny,
                "back_shiny_female" => $d->sprites->back_shiny_female,
                "front_default" => $d->sprites->front_default,
                "front_female" => $d->sprites->front_female,
                "front_shiny" => $d->sprites->front_shiny,
                "front_shiny_female" => $d->sprites->front_shiny_female,
            ]);

            // FORMS
            if(count($d->forms) > 1)
            {
                foreach ($d->forms as $form)
                {
                    Form::create([
                        "pokemon_id" => $pokemonId,
                        "name" => $form->name,
                        "sprite" => $form->sprite,
                    ]);
                }
            }

            // ABILITIES
            foreach ($d->abilities as $ability) 
            {
                if(!in_array($ability->ability->name, $insertedAbilities))
                {
                    Ability::create([
                        "name" => $ability->ability->name,
                        "is_hidden" => $ability->is_hidden,
                    ]);

                    array_push($insertedAbilities, $ability->ability->name);
                }

                DB::table('pokemon_ability')->insert([
                    'pokemon_id' => $pokemonId,
                    'ability_id' => array_search($ability->ability->name, $insertedAbilities) + 1,
                    'slot' => $ability->slot
                ]);
            }


            //STATS
            Stat::create([
                "pokemon_id" => $pokemonId,
                "base_hp" => $d->stats[0]->base_stat,
                "effort_hp" => $d->stats[0]->effort,
                "base_attack" => $d->stats[1]->base_stat,
                "effort_attack" => $d->stats[1]->effort,
                "base_defense" => $d->stats[2]->base_stat,
                "effort_defense" => $d->stats[2]->effort,
                "base_special_attack" => $d->stats[3]->base_stat,
                "effort_special_attack" => $d->stats[3]->effort,
                "base_special_defense" => $d->stats[4]->base_stat,
                "effort_special_defense" => $d->stats[4]->effort,
                "base_speed" => $d->stats[5]->base_stat,
                "effort_speed" => $d->stats[5]->effort,
            ]);

            // INDEX PER VERSION
            foreach ($d->game_indices as $indices) 
            {
                if(!in_array($indices->version->name, $insertedVersions))
                {
                    Version::create([
                        "name" => $indices->version->name,
                    ]);

                    array_push($insertedVersions, $indices->version->name);
                }

                DB::table('version_pokemon_id')->insert([
                    'version_id' => array_search($indices->version->name, $insertedVersions) + 1,
                    'pokemon_id' => $pokemonId,
                    'index' => $indices->game_index
                ]);
            }

            // MOVES
            foreach ($d->moves as $moves) 
            {
                if(!in_array($moves->move->name, $insertedMoves))
                {
                    Move::create([
                        "name" => $moves->move->name,
                    ]);

                    array_push($insertedMoves, $moves->move->name);
                }

                foreach ($moves->version_group_details as $version) 
                {
                    if(!in_array($version->version_group->name, $insertedVersions))
                    {
                        Version::create([
                            "name" => $version->version_group->name,
                        ]);

                        array_push($insertedVersions, $version->version_group->name);
                    }

                    DB::table('move_pokemon')->insert([
                        'move_id' => array_search($moves->move->name, $insertedMoves) + 1,
                        'pokemon_id' => $pokemonId,
                        'learn_level' => $version->level_learned_at,
                        'learn_method' => $version->move_learn_method->name,
                        'version_id' => array_search($version->version_group->name, $insertedVersions) + 1
                    ]);
                }
                
            }
        }
    }
}