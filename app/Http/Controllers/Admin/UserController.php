<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('articles')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:editeur,administrateur',
            'actif'    => 'boolean',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'actif'    => $request->has('actif'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:editeur,administrateur',
            'actif'    => 'boolean',
        ]);

        // Empêcher de rétrograder le dernier administrateur
        if ($user->role === 'administrateur' && $request->role !== 'administrateur') {
            $adminCount = User::where('role', 'administrateur')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Impossible de rétrograder le seul administrateur du système.');
            }
        }

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Ne pas modifier son propre rôle ni statut actif
        if ($user->id !== auth()->id()) {
            $data['role']  = $request->role;
            $data['actif'] = $request->has('actif');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Protéger le dernier administrateur
        if ($user->role === 'administrateur') {
            $adminCount = User::where('role', 'administrateur')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Impossible de supprimer le seul administrateur du système.');
            }
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé. Ses articles ont été conservés.');
    }
}
