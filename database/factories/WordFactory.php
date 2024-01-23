<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Word>
 */
class WordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'nrzestawu' => rand(1,3),
            'slowo' =>  fake() -> words(rand(1,2),true),
            'znaczenie' => fake() -> words(rand(1,3),true),
            'przyklad' => fake() -> sentence($nbWords = 6, $variableNbWords = true),
        ];

        // $users = User::pluck('id');
        // foreach($users as $user){
        //     $tabela = 'usertab2_'.$user->id;
        //     DB::table($tabela)->update([
        //         'slowo2' => 'nrzestawu',
        //         'znaczenie2' => 'znaczenie',
        //         'przyklad2' => 'przyklad',
        //     ]);
        // }
    }
}
