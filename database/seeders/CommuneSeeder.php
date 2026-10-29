<?php

namespace Database\Seeders;
use App\Models\Commune;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commune = ['Libreville', 'Akanda', 'Owendo', 'Ntoum'];
        
        foreach($commune as $nom){
            Commune::firstOrCreate([
                'nom_commune' => $nom
            ]);
        }    
    }
}
