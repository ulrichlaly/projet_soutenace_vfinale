<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelEmployeCredentials;

class CollaboratorController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            // Charger les rôles de l'utilisateur
            $user->load('roles');
            $userRoles = $user->roles->pluck('name')->toArray();

            // Vérifier si c'est un admin
            $isAdmin = in_array('Admin', $userRoles) ||
                in_array('Administrateur', $userRoles) ||
                in_array('Admin Général', $userRoles);

            // Si l'utilisateur est admin, retourner tous les collaborateurs
            if ($isAdmin) {
                $collaborators = Collaborator::with('user')->get()->map(function ($collaborator) {
                    return [
                        'id' => $collaborator->id,
                        'user_id' => $collaborator->user_id,
                        'nom_complet' => $collaborator->user->fullname ?? 'N/A',
                        'email' => $collaborator->email,
                        'poste' => $collaborator->poste,
                        'telephone' => $collaborator->user->tel ?? 'N/A',
                        'salaire_base' => number_format($collaborator->salaire_base, 0, ',', ' ') . ' FCFA',
                        'statut' => $collaborator->statut,
                        'matricule' => $collaborator->matricule,
                        'date_embauche' => $collaborator->date_embauche,
                    ];
                });

                return response()->json($collaborators);
            }

            // Si c'est un collaborateur, retourner uniquement ses propres infos
            $collaborator = Collaborator::with('user')->where('user_id', $user->id)->first();

            if (!$collaborator) {
                return response()->json([
                    'message' => 'Profil collaborateur non trouvé',
                    'user_id' => $user->id,
                    'user_roles' => $userRoles
                ], 404);
            }

            return response()->json([
                [
                    'id' => $collaborator->id,
                    'user_id' => $collaborator->user_id,
                    'nom_complet' => $collaborator->user->fullname ?? 'N/A',
                    'email' => $collaborator->email,
                    'poste' => $collaborator->poste,
                    'telephone' => $collaborator->user->tel ?? 'N/A',
                    'salaire_base' => number_format($collaborator->salaire_base, 0, ',', ' ') . ' FCFA',
                    'statut' => $collaborator->statut,
                    'matricule' => $collaborator->matricule,
                    'date_embauche' => $collaborator->date_embauche,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur CollaboratorController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Erreur lors de la récupération des collaborateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'poste' => 'required|string|max:255',
            'salaire_base' => 'required|numeric|min:0',
            'telephone' => 'nullable|string|max:20',
            'date_embauche' => 'required|date',
            'date_naissance' => 'nullable|date',
            'genre' => 'nullable|in:M,F',
            'adresse' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $defaultPassword = 'Password123!';

            // Créer l'utilisateur
            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'tel' => $request->telephone,
            ]);

            // Assigner le rôle Collaborateur (ID 4)
            $user->roles()->attach(4);

            // Générer un matricule unique
            $matricule = 'COL-' . str_pad(Collaborator::count() + 1, 4, '0', STR_PAD_LEFT);

            // Séparer le nom et prénom
            $nameParts = explode(' ', $request->fullname);
            $prenom = $nameParts[0];
            $nom = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : $nameParts[0];

            // Créer le collaborateur
            $collaborator = Collaborator::create([
                'user_id' => $user->id,
                'matricule' => $matricule,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $request->email,
                'tel' => $request->telephone,
                'date_naissance' => $request->date_naissance,
                'genre' => $request->genre,
                'adresse' => $request->adresse,
                'poste' => $request->poste,
                'departement' => 'Administration',
                'salaire_base' => $request->salaire_base,
                'date_embauche' => $request->date_embauche,
                'type_contrat' => 'CDI',
                'statut' => 'actif',
            ]);

            try {
                Mail::to($user->email)->send(new NouvelEmployeCredentials(
                    $user,
                    $collaborator,
                    $defaultPassword
                ));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'message' => 'Employé créé avec succès. Un email a été envoyé avec les identifiants de connexion.',
                'collaborator' => $collaborator->load('user'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création collaborateur: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la création de l\'employé',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();
            $collaborator = Collaborator::with('user')->findOrFail($id);

            // Charger les rôles
            $user->load('roles');
            $userRoles = $user->roles->pluck('name')->toArray();
            $isAdmin = in_array('Admin', $userRoles) ||
                in_array('Administrateur', $userRoles) ||
                in_array('Admin Général', $userRoles);

            // Vérifier les permissions
            if (!$isAdmin && $collaborator->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Vous ne pouvez consulter que vos propres informations'
                ], 403);
            }

            return response()->json($collaborator);
        } catch (\Exception $e) {
            Log::error('Erreur CollaboratorController@show: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la récupération du collaborateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $collaborator = Collaborator::findOrFail($id);

        $request->validate([
            'fullname' => 'sometimes|required|string|max:255',
            'poste' => 'sometimes|required|string|max:255',
            'salaire_base' => 'sometimes|required|numeric|min:0',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'sometimes|in:actif,en_conge,inactif',
            'date_naissance' => 'nullable|date',
            'genre' => 'nullable|in:M,F',
            'adresse' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $collaborator->update([
                'poste' => $request->poste ?? $collaborator->poste,
                'salaire_base' => $request->salaire_base ?? $collaborator->salaire_base,
                'statut' => $request->statut ?? $collaborator->statut,
                'date_naissance' => $request->date_naissance ?? $collaborator->date_naissance,
                'genre' => $request->genre ?? $collaborator->genre,
                'adresse' => $request->adresse ?? $collaborator->adresse,
                'tel' => $request->telephone ?? $collaborator->tel,
            ]);

            if ($request->has('fullname') || $request->has('telephone')) {
                $collaborator->user->update([
                    'fullname' => $request->fullname ?? $collaborator->user->fullname,
                    'tel' => $request->telephone ?? $collaborator->user->tel,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Employé mis à jour avec succès',
                'collaborator' => $collaborator->load('user'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $collaborator = Collaborator::findOrFail($id);
        $user = $collaborator->user;

        DB::beginTransaction();

        try {
            $collaborator->delete();
            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'Employé supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Méthode pour permettre aux collaborateurs de mettre à jour leur propre profil
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            // Trouver le collaborateur
            $collaborator = Collaborator::where('user_id', $user->id)->firstOrFail();

            $request->validate([
                'fullname' => 'sometimes|string|max:255',
                'telephone' => 'sometimes|string|max:20',
                'date_naissance' => 'sometimes|date',
                'genre' => 'sometimes|in:M,F',
                'adresse' => 'sometimes|string|max:255',
            ]);

            DB::beginTransaction();

            // Mettre à jour l'utilisateur
            if ($request->has('fullname') || $request->has('telephone')) {
                $user->update([
                    'fullname' => $request->fullname ?? $user->fullname,
                    'tel' => $request->telephone ?? $user->tel,
                ]);
            }

            // Mettre à jour le collaborateur
            $collaborator->update([
                'date_naissance' => $request->date_naissance ?? $collaborator->date_naissance,
                'genre' => $request->genre ?? $collaborator->genre,
                'adresse' => $request->adresse ?? $collaborator->adresse,
                'tel' => $request->telephone ?? $collaborator->tel,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Profil mis à jour avec succès',
                'collaborator' => $collaborator->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur updateProfile: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
