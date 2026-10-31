<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommuneController extends Controller
{
    /**
     * Afficher les communes
     * 
     * Affichage de l’ensemble des communes
     * @unauthenticated
    */
    public function index()
    {
        return response()->json(Commune::orderBy('nom_commune')->get());
    }

    /**
     * Ajouter une commune
     * 
     * Ajout d’une commune
    */
    public function store(Request $request)
    {
        $commune = $request->validate([
            'nom_commune' => ['required', 'string', 'max:100', 'unique:communes,nom_commune']
        ]);

        return response()->json(Commune::create($commune), 201);
    }

    /**
     * Afficher une commune
     * 
     * Affichage d’une commune
     * @unauthenticated
    */
    public function show(Commune $commune)
    {
        return response()->json($commune->load('arrondissements'));
    }

    /**
     * Modifier une commune
     * 
     * Modification des informations d’une commune
    */
    public function update(Request $request, Commune $commune)
    {
        $data = $request->validate([
            'nom_commune' => ['required', 'string', 'max:100', Rule::unique('communes', 'nom_commune')->ignore($commune)]
        ]);
        $commune->update($data);

        return response()->json($commune);
    }

    /**
     * Supprimer une commune
     * 
     * Suppression d’une commune
    */
    public function destroy(Commune $commune)
    {
        if ($commune->arrondissements()->exists()) {
            return response()->json(['message' => 'Cette commune contient des arrondissements, suppression impossible.'], 409);
        }

        $commune->delete();

        return response()->json(null, 204);
    }
}
