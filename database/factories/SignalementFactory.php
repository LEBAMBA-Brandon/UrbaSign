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
            // Coordonnées autour de Libreville
            'latitude'    => fake()->randomFloat(7, 0.35, 0.50),
            'longitude'   => fake()->randomFloat(7, 9.40, 9.50),
            'description' => fake()->sentence(),
            'categorie'   => fake()->randomElement(Signalement::CATEGORIES),
            'gravite'     => fake()->randomElement(Signalement::GRAVITES),
            'statut'      => fake()->randomElement(Signalement::STATUTS),
            'photo'       => 'signalements/exemple.jpg',
            'auteur_id'   => User::factory()->state(['role' => 'collecteur']),
            'quartier_id' => fn () => Quartier::inRandomOrder()->value('id'),
        ];
        
    }
}
