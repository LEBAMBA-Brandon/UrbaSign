<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CommuneSeeder::class,
            ArrondissementSeeder::class,
            QuartierSeeder::class,
        ]);
        
        // User::factory(10)->create();

        User::factory()->create([
            'nom' => 'Admin',
            'prenom' => 'User',
            'email' => 'admin@user.com',
            'password' => '987654',
            'role' => 'admin',
        ]);
    }
}
