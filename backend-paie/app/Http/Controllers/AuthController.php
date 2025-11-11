<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // Charger les rôles
        $user->load('roles');
        $roleNames = $user->roles->pluck('name')->toArray();
        
        // Si aucun rôle n'est assigné, utiliser 'Collaborateur' par défaut
        if (empty($roleNames)) {
            $roleNames = ['Collaborateur'];
        }
        
        $primaryRole = $roleNames[0];

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'tel' => $user->tel,
                'role' => $primaryRole,
                'roles' => $roleNames,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Récupérer l'utilisateur authentifié
     */
    public function me(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Charger les rôles
        $user->load('roles');
        $roleNames = $user->roles->pluck('name')->toArray();
        
        // Si aucun rôle n'est assigné, utiliser 'Collaborateur' par défaut
        if (empty($roleNames)) {
            $roleNames = ['Collaborateur'];
        }
        
        $primaryRole = $roleNames[0];

        return response()->json([
            'id' => $user->id,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'tel' => $user->tel,
            'role' => $primaryRole,
            'roles' => $roleNames,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
