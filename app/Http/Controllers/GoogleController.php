<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar si el usuario ya existe por email
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Si existe, hacer login
                Auth::login($existingUser);
                return redirect()->intended('/home')->with('success', '¡Bienvenido de vuelta!');
            } else {
                // Si NO existe, denegar acceso
                return redirect()->route('login')->with('error', 'Tu cuenta no está registrada o está inactiva.');

            }

        } catch (Exception $e) {
            \Log::error('Error en Google callback:', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors([
                'email' => 'Ocurrió un error al autenticar con Google. Intenta de nuevo.'
            ]);
        }
    }
}
