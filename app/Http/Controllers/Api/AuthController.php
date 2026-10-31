<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // connexion, renvoie un token
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        // Utilisateur introuvable OU mauvais mot de passe = même message
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect.'], 401);
        }

        $token = $user->createToken('urbasign')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    // connexion
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // supprime le token utilisé
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté.']);
    }
}