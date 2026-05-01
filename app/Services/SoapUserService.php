<?php

namespace App\Services;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SoapUserService
{
    private function verifierToken(string $token): bool
    {
        $apiToken = ApiToken::where('token', $token)->first();
        return $apiToken && $apiToken->estValide();
    }

    public function listerUtilisateurs(string $token): object
    {
        if (!$this->verifierToken($token)) {
            return (object) ['succes' => false, 'message' => 'Token invalide ou expiré.', 'data' => ''];
        }

        $users = User::all()->map(fn($u) => [
            'id'    => $u->id,
            'nom'   => $u->name,
            'email' => $u->email,
            'role'  => $u->role,
            'actif' => $u->actif,
        ])->values()->toArray();

        return (object) [
            'succes'  => true,
            'message' => count($users) . ' utilisateur(s) trouvé(s).',
            'data'    => json_encode($users),
        ];
    }

    public function ajouterUtilisateur(string $token, string $nom, string $email, string $password, string $role): object
    {
        if (!$this->verifierToken($token)) {
            return (object) ['succes' => false, 'message' => 'Token invalide ou expiré.'];
        }

        if (!in_array($role, ['editeur', 'administrateur'])) {
            return (object) ['succes' => false, 'message' => 'Rôle invalide. Valeurs acceptées : editeur, administrateur.'];
        }

        if (User::where('email', $email)->exists()) {
            return (object) ['succes' => false, 'message' => 'Cet email est déjà utilisé.'];
        }

        $user = User::create([
            'name'     => $nom,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $role,
            'actif'    => true,
        ]);

        return (object) ['succes' => true, 'message' => "Utilisateur créé avec l'ID {$user->id}."];
    }

    public function modifierUtilisateur(string $token, int $id, string $nom, string $email, string $role): object
    {
        if (!$this->verifierToken($token)) {
            return (object) ['succes' => false, 'message' => 'Token invalide ou expiré.'];
        }

        $user = User::find($id);
        if (!$user) {
            return (object) ['succes' => false, 'message' => 'Utilisateur introuvable.'];
        }

        if (!in_array($role, ['editeur', 'administrateur'])) {
            return (object) ['succes' => false, 'message' => 'Rôle invalide. Valeurs acceptées : editeur, administrateur.'];
        }

        if (User::where('email', $email)->where('id', '!=', $id)->exists()) {
            return (object) ['succes' => false, 'message' => 'Cet email est déjà utilisé par un autre compte.'];
        }

        if ($user->role === 'administrateur' && $role !== 'administrateur') {
            $adminCount = User::where('role', 'administrateur')->count();
            if ($adminCount <= 1) {
                return (object) ['succes' => false, 'message' => 'Impossible de rétrograder le seul administrateur.'];
            }
        }

        $user->update(['name' => $nom, 'email' => $email, 'role' => $role]);

        return (object) ['succes' => true, 'message' => 'Utilisateur modifié avec succès.'];
    }

    public function supprimerUtilisateur(string $token, int $id): object
    {
        if (!$this->verifierToken($token)) {
            return (object) ['succes' => false, 'message' => 'Token invalide ou expiré.'];
        }

        $user = User::find($id);
        if (!$user) {
            return (object) ['succes' => false, 'message' => 'Utilisateur introuvable.'];
        }

        if ($user->role === 'administrateur') {
            $adminCount = User::where('role', 'administrateur')->count();
            if ($adminCount <= 1) {
                return (object) ['succes' => false, 'message' => 'Impossible de supprimer le seul administrateur.'];
            }
        }

        $user->delete();

        return (object) ['succes' => true, 'message' => 'Utilisateur supprimé. Ses articles sont conservés.'];
    }

    public function authentifierUtilisateur(string $email, string $password): object
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return (object) ['succes' => false, 'message' => 'Identifiants incorrects.', 'data' => ''];
        }

        if (!$user->actif) {
            return (object) ['succes' => false, 'message' => 'Compte désactivé. Contactez un administrateur.', 'data' => ''];
        }

        return (object) [
            'succes'  => true,
            'message' => 'Authentification réussie.',
            'data'    => json_encode([
                'id'    => $user->id,
                'nom'   => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]),
        ];
    }
}
