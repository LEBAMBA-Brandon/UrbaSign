<?php

namespace Database\Factories;

use App\Models\Signalement;
use App\Models\User;
use App\Models\Quartier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Signalement>
 */
class SignalementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $user = User::factory()->create(),
            $quartier = Quartier::first(),
            $signalement = Signalement::factory()->create([
                'auteur_id' => $user->id,
                'quartier_id' => $quartier->id,
            ]),
        ];
    }
}
