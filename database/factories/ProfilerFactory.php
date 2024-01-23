<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfilerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $users = User::all();
        // $ile = User::count();


        return [
            // 'user_id' => rand(1,$ile),
            // 'slowka' =>   fake() -> words(rand(1,1),true),
        ];

    }
}
