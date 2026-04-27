<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->actif) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => "Votre compte est désactivé.",
                ]);
            }

            $request->session()->regenerate();

            if ($user->isEditeur() || $user->isAdministrateur()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('accueil'));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
