<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignalementRequest;
use App\Models\Signalement;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SignalementController extends Controller
{
    //relation qu’on renvoie avec chaque signalement
    private const RELATIONS = ['auteur:id,nom,prenom', 'quartier:id,nom_quartier'];

    // liste filtrable des signalements
    public function index(Request $request)
    {
        $filtres = $request->validate([
            'categorie'   => ['nullable', Rule::in(Signalement::CATEGORIES)],
            'statut'      => ['nullable', Rule::in(Signalement::STATUTS)],
            'gravite'     => ['nullable', Rule::in(Signalement::GRAVITES)],
            'quartier_id' => ['nullable', 'integer', 'exists:quartiers,id'],
            'date_debut'  => ['nullable', 'date'],
            'date_fin'    => ['nullable', 'date', 'after_or_equal:date_debut'],
        ]);

        $signalements = Signalement::query()
            ->with(self::RELATIONS)
            ->when($filtres['categorie'] ?? null,   fn ($q, $v) => $q->where('categorie', $v))
            ->when($filtres['statut'] ?? null,      fn ($q, $v) => $q->where('statut', $v))
            ->when($filtres['gravite'] ?? null,     fn ($q, $v) => $q->where('gravite', $v))
            ->when($filtres['quartier_id'] ?? null, fn ($q, $v) => $q->where('quartier_id', $v))
            ->when($filtres['date_debut'] ?? null,  fn ($q, $v) => $q->whereDate('date_creation', '>=', $v))
            ->when($filtres['date_fin'] ?? null,    fn ($q, $v) => $q->whereDate('date_creation', '<=', $v))
            ->latest('date_creation')
            ->get();

        return response()->json($signalements);
    }

    //ajouté un signalement
    public function store(StoreSignalementRequest $request)
    {
        $path = $request->file('photo')->store('signalements', 'public');
        $data = Arr::except($request->validated(), ['photo']);

        $signalement = Signalement::create([
            ...$data,
            'photo' => $path,
            'auteur_id' => $request->user()->id,
        ]);

        return response()->json($signalement->refresh()->load(self::RELATIONS), 201);
    }

    //signalements des collecteurs
    public function mesSignalements(Request $request)
    {
        $signalements = $request->user()
            ->signalements()
            ->with('quartier:id,nom_quartier')
            ->latest('date_creation')
            ->get();

        return response()->json($signalements);
    }

    //affiché les infos d’un signalement
    public function show(Signalement $signalement)
    {
        return response()->json($signalement->load(self::RELATIONS));
    }

    //modifié le statut
    public function updateStatut(Request $request, Signalement $signalement)
    {
        $data = $request->validate([
            'statut' => ['required', Rule::in(Signalement::STATUTS)]
        ]);

        $signalement->update(['statut' => $data['statut']]);

        return response()->json($signalement->load(self::RELATIONS));
    }

    //supprimé un signalement
    public function destroy(Signalement $signalement)
    {
        Storage::disk('public')->delete($signalement->photo);
        $signalement->delete();

        return response()->json(null, 204);
    }
}
