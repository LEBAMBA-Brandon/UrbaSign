<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommuneController extends Controller
{
    //affiché les Commune
    public function index()
    {
        return response()->json(Commune::orderBy('com_commune')->get());
    }

    //ajouté une commune
    public function store(Request $request)
    {
        $commune = $request->validate([
            'nom_commune' => ['required', 'string', 'max:100', 'unique:communes,nom_commune']
        ]);

        return response()->json(Commune::create($commune), 201);
    }

    //affiché les infos d’une commune
    public function show(Commune $commune)
    {
        return response()->json($commune->load('arrondissements'));
    }

    //modifié une commune
    public function update(Request $request, Commune $commune)
    {
        $data = $request->validate([
            'nom_commune' => ['required', 'string', 'max:100', Rule::unique('commune', 'com_commune')->ignore($commune)]
        ]);
        $commune->update($data);

        return response()->json($commune);
    }

    //supprimé une commune
    public function destroy(Commune $commune)
    {
        if ($commune->arrondissements()->exists()) {
            return response()->json(['message' => 'Cette commune contient des arrondissements, suppression impossible.'], 409);
        }

        $commune->delete();

        return response()->json(null, 204);
    }
}
