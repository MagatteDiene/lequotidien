<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index()
    {
        $tokens = ApiToken::with('createur')->latest()->get();
        return view('admin.tokens.index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        ApiToken::generer($request->nom, auth()->id());

        return redirect()->route('admin.tokens.index')->with('success', 'Token généré.');
    }

    public function toggle(ApiToken $apiToken)
    {
        $apiToken->update(['actif' => !$apiToken->actif]);
        return redirect()->route('admin.tokens.index')->with('success', 'Statut du token mis à jour.');
    }

    public function destroy(ApiToken $apiToken)
    {
        $apiToken->delete();
        return redirect()->route('admin.tokens.index')->with('success', 'Token supprimé.');
    }
}
