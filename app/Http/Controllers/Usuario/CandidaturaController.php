<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Http\Request;

class CandidaturaController extends Controller
{
    /**
     * Inscreve o candidato autenticado em uma vaga.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vaga_id' => 'required|exists:vagas,id',
        ]);

        $user = auth()->user();

        if (!$user->candidato_id) {
            return redirect()->back()->with('error', 'Seu perfil de candidato ainda nao esta vinculado. Atualize seu perfil primeiro.');
        }

        $vaga = Vaga::findOrFail($request->vaga_id);

        if ($vaga->status !== 'ativa') {
            return redirect()->back()->with('error', 'Esta vaga nao esta aceitando candidaturas no momento.');
        }

        $jaInscrito = $vaga->candidatos()->where('candidato_id', $user->candidato_id)->exists();

        if ($jaInscrito) {
            return redirect()->back()->with('error', 'Voce ja esta inscrito nesta vaga.');
        }

        $vaga->candidatos()->attach($user->candidato_id);

        return redirect()->route('usuario.vagas.show', $vaga)->with('success', 'Candidatura realizada com sucesso!');
    }

    /**
     * Cancela a candidatura do candidato autenticado.
     */
    public function destroy($candidaturaId)
    {
        $user = auth()->user();

        $vaga = Vaga::whereHas('candidatos', function ($q) use ($user, $candidaturaId) {
            $q->where('candidaturas.id', $candidaturaId)
              ->where('candidato_id', $user->candidato_id);
        })->firstOrFail();

        $vaga->candidatos()->detach($user->candidato_id);

        return redirect()->back()->with('success', 'Candidatura cancelada com sucesso.');
    }
}
