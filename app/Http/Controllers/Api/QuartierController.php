<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quartier;

class QuartierController extends Controller
{
    //affiché les Quartier
    public function index(Request $request)
    {
        $quartiers = Quartier::query()
            ->with('arrondissement:id,nom_arrondissement')
            ->when($request->integer('arrondissement_id'), fn ($q, $v) => $q->where('arrondissement_id', $v))
            ->orderBy('nom_quartier')
            ->get();

        return response()->json($quartiers);
    }

    //ajouté un Quartier
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom_quartier'      => ['required', 'string', 'max:100'],
            'arrondissement_id' => ['required', 'integer', 'exists:arrondissements,id'],
        ]);

        return response()->json(Quartier::create($data), 201);
    }

    //affiché les infos d’un quartier
    public function show(Quartier $quartier)
    {
        return response()->json($quartier->load('arrondissement.commune'));
    }

    //modifié un quartier
    public function update(Request $request, Quartier $quartier)
    {
        $data = $request->validate([
            'nom_quartier'      => ['sometimes', 'required', 'string', 'max:100'],
            'arrondissement_id' => ['sometimes', 'required', 'integer', 'exists:arrondissements,id'],
        ]);

        $quartier->update($data);

        return response()->json($quartier);
    }

    //supprimé un quartier
    public function destroy(Quartier $quartier)
    {
        if ($quartier->signalements()->exists()) {
            return response()->json(['message' => 'Ce quartier a des signalements, suppression impossible.'], 409);
        }

        $quartier->delete();

        return response()->json(null, 204);
    }
}
