<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arrondissement;

class ArrondissementController extends Controller
{
    /**
     * Afficher les arrondissements
     * 
     * Affichage de l’ensemble des arrondissements
     * @unauthenticated
    */
    public function index(Request $request)
    {
        $arrondissements = Arrondissement::query()
            ->with('commune:id,nom_commune')
            ->when($request->integer('commune_id'), fn ($q, $v) => $q->where('commune_id', $v))
            ->orderBy('nom_arrondissement')
            ->get();

        return response()->json($arrondissements);
    }

    /**
     * Ajouté un arrondissement
     * 
     * Ajout d’un arrondissement
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_arrondissement' => ['required', 'string', 'max:100'],
            'commune_id'         => ['required', 'integer', 'exists:communes,id'],
        ]);

        return response()->json(Arrondissement::create($data), 201);
    }

    /**
     * Afficher un arrondissement
     * 
     * Détail d’un arrondissement
     * @unauthenticated
    */
    public function show(Arrondissement $arrondissement)
    {
        return response()->json($arrondissement->load(['commune', 'quartiers']));
    }

    /**
     * Modifier un arrondissement
     * 
     * Modification des informations d’un arrondissement
    */
    public function update(Request $request, Arrondissement $arrondissement)
    {
        $data = $request->validate([
            'nom_arrondissement' => ['sometimes', 'required', 'string', 'max:100'],
            'commune_id'         => ['sometimes', 'required', 'integer', 'exists:communes,id'],
        ]);

        $arrondissement->update($data);

        return response()->json($arrondissement);
    }

    /**
     * Supprimer un arrondissement
     * 
     * Suppression d’un arrondissement
    */
    public function destroy(Arrondissement $arrondissement)
    {
        if ($arrondissement->quartiers()->exists()) {
            return response()->json(['message' => 'Cet arrondissement contient des quartiers, suppression impossible.'], 409);
        }

        $arrondissement->delete();

        return response()->json(null, 204);
    }
}
