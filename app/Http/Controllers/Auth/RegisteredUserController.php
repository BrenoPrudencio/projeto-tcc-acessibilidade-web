<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'telefone'         => ['required', 'string', 'max:20'],
            'pcd'              => ['nullable', 'boolean'],
            'tipo_deficiencia' => ['nullable', 'required_if:pcd,1', 'string', 'max:100'],
            'acessibilidade'   => ['nullable', 'string', 'max:1000'],
        ]);

        // Cria o registro de Candidato vinculado
        $candidato = Candidato::create([
            'nome'             => $request->name,
            'email'            => $request->email,
            'telefone'         => $request->telefone,
            'pcd'              => $request->boolean('pcd'),
            'tipo_deficiencia' => $request->pcd ? $request->tipo_deficiencia : null,
            'acessibilidade'   => $request->acessibilidade,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidato',
            'candidato_id' => $candidato->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('usuario.dashboard', absolute: false));
    }
}
