<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $candidato = $user->candidato;

        $candidaturas = $candidato
            ? $candidato->vagas()->latest('candidaturas.created_at')->take(5)->get()
            : collect();

        $totalCandidaturas = $candidato ? $candidato->vagas()->count() : 0;

        return view('usuario.dashboard', compact('candidato', 'candidaturas', 'totalCandidaturas'));
    }
}
