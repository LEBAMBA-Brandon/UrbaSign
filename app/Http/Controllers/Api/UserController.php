<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //affiché les users
    public function index()
    {
        return response()->json(User::orderBy('nom')->get());
    }

    //ajouté un user
   public function store(Request $request)
    {
        $data = $request->validate([
            'nom'      => ['required', 'string', 'max:100'],
            'prenom'   => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['admin', 'collecteur'])],
        ]);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    //affiché les infos d’un user
    public function show(User $user)
    {
        return response()->json($user);
    }

    //modifié un user
    public function update(Request $request, User $user)
    {
        // "sometimes" = on ne valide que quand le champ est envoyé
        $data = $request->validate([
            'nom'      => ['sometimes', 'required', 'string', 'max:100'],
            'prenom'   => ['sometimes', 'required', 'string', 'max:100'],
            'email'    => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($user)],
            'password' => ['sometimes', 'required', 'string', 'min:6'],
            'role'     => ['sometimes', 'required', Rule::in(['admin', 'collecteur'])],
        ]);

        $user->update($data);

        return response()->json($user);
    }

    //supprimé un user
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'Tu ne peux pas supprimer ton propre compte.'], 422);
        }

        if ($user->signalements()->exists()) {
            return response()->json(['message' => 'Cet utilisateur a des signalements, suppression impossible.'], 409);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}
