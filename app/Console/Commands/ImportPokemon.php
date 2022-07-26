<?php

namespace App\Console\Commands;

use App\Models\Form;
use App\Models\Move;
use App\Models\Stat;
use App\Models\Type;
use App\Models\Sprite;
use App\Models\Ability;
use App\Models\Pokemon;
use App\Models\Version;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportPokemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:pokemon {id_name : id or name of pokemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a Pokemon from https://pokeapi.co/';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = $this->argument('id_name');

        $data = Http::get('https://pokeapi.co/api/v2/pokemon/' . $input . '/')->json();

        if(Pokemon::where('name', $data['name'])->first())
        {
            return 0;
        }

        $pokemonId = Pokemon::count() + 1;

        Sprite::create([
            "pokemon_id" => $pokemonId,
            "back_default" => $data['sprites']['back_default'],
            "back_female" => $data['sprites']['back_female'],
            "back_shiny" => $data['sprites']['back_shiny'],
            "back_shiny_female" => $data['sprites']['back_shiny_female'],
            "front_default" => $data['sprites']['front_default'],
            "front_female" => $data['sprites']['front_female'],
            "front_shiny" => $data['sprites']['front_shiny'],
            "front_shiny_female" => $data['sprites']['front_shiny_female'],
        ]);

        Pokemon::create([
            "name" => $data['name'],
            "sprite" => $data['sprites']['front_default'],
            "height" => $data['height'],
            "weight" => $data['weight'],
            "order" => $data['order'],
        ]);

        foreach ($data['types'] as $type) 
        {
            $r = Type::where('name', $type['type']['name'])->first();

            if(!$r)
            {
                Type::create([
                    "name" => $type['type']['name'],
                ]);
                
                $r = Type::where('name', $type['type']['name'])->first();
            }

            DB::table('pokemon_type')->insert([
                'pokemon_id' => $pokemonId,
                'type_id' => $r->id,
                'slot' => $type['slot'],
                'past' => 0,
            ]);
        }

        foreach ($data['past_types'] as $past_type) 
        {
            foreach ( $past_type['types'] as $type)
            {
                $r = Type::where('name', $type['type']['name'])->first();

                if(!$r)
                {
                    Type::create([
                        "name" => $type['type']['name'],
                    ]);

                    $r = Type::where('name', $type['type']['name'])->first();
                }

                DB::table('pokemon_type')->insert([
                    'pokemon_id' => $pokemonId,
                    'type_id' => $r->id,
                    'slot' => $type['slot'],
                    'past' => 1,
                ]);
            }
        }

        // ABILITIES
        foreach ($data['abilities'] as $ability) 
        {
            $r = Ability::where('name', $ability['ability']['name'])->first();
            if(!$r)
            {
                Ability::create([
                    "name" => $ability['ability']['name'],
                    "is_hidden" => $ability['is_hidden'],
                ]);

                $r = Ability::where('name', $ability['ability']['name'])->first();
            }

            DB::table('pokemon_ability')->insert([
                'pokemon_id' => $pokemonId,
                'ability_id' => $r->id,
                'slot' => $ability['slot']
            ]);
        }

        // FORMS
        if(count($data['forms']) > 1)
        {
            foreach ($data['forms'] as $form)
            {
                Form::create([
                    "pokemon_id" => $pokemonId,
                    "name" => $form['name'],
                    "sprite" => $form['url'],
                ]);
            }
        }

        //STATS
        Stat::create([
            "pokemon_id" => $pokemonId,
            "base_hp" => $data['stats'][0]['base_stat'],
            "effort_hp" => $data['stats'][0]['effort'],
            "base_attack" => $data['stats'][1]['base_stat'],
            "effort_attack" => $data['stats'][1]['effort'],
            "base_defense" => $data['stats'][2]['base_stat'],
            "effort_defense" => $data['stats'][2]['effort'],
            "base_special_attack" => $data['stats'][3]['base_stat'],
            "effort_special_attack" => $data['stats'][3]['effort'],
            "base_special_defense" => $data['stats'][4]['base_stat'],
            "effort_special_defense" => $data['stats'][4]['effort'],
            "base_speed" => $data['stats'][5]['base_stat'],
            "effort_speed" => $data['stats'][5]['effort'],
        ]);

        // INDEX PER VERSION
        foreach ($data['game_indices'] as $indices) 
        {
            $r = Version::where('name', $indices['version']['name'])->first();
            if(!$r)
            {
                Version::create([
                    "name" => $indices['version']['name'],
                ]);

                $r = Version::where('name', $indices['version']['name'])->first();
            }

            DB::table('version_pokemon_id')->insert([
                'version_id' => $r->id,
                'pokemon_id' => $pokemonId,
                'index' => $indices['game_index']
            ]);
        }

            // MOVES
            foreach ($data['moves'] as $moves) 
            {
                $r = Move::where('name', $moves['move']['name'])->first();
                if(!$r)
                {
                    Move::create([
                        "name" => $moves['move']['name'],
                    ]);

                    $r = Move::where('name', $moves['move']['name'])->first();
                }

                foreach ($moves['version_group_details'] as $version) 
                {
                    $s = Version::where('name', $version['version_group']['name'])->first();
                    if(!$s)
                    {
                        Version::create([
                            "name" => $version['version_group']['name'],
                        ]);
        
                        $s = Version::where('name', $version['version_group']['name'])->first();
                    }

                    DB::table('move_pokemon')->insert([
                        'move_id' => $r->id,
                        'pokemon_id' => $pokemonId,
                        'learn_level' => $version['level_learned_at'],
                        'learn_method' => $version['move_learn_method']['name'],
                        'version_id' => $s->id,
                    ]);
                }
                
            }
        

        return 0;
    }
}