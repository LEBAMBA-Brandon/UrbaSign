<?php

namespace Database\Seeders;
use App\Models\Arrondissement;
use App\Models\Commune;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArrondissementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrondissement = ['1er', '2e', '3e', '4e'];
        $libreville = Commune::where('nom_commune', 'Libreville')->firstOrFail();

        foreach($arrondissement as $nom){
            Arrondissement::firstOrCreate([
                'nom_arrondissement' => $nom,
                'commune_id' => $libreville->id
            ]);
        }
    }
}
