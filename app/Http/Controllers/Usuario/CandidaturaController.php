<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Vaga;
use Illuminate\Http\Request;

class CandidaturaController extends Controller
{
    /**
     * Inscreve o candidato autenticado em uma vaga,
     * atualizando o perfil com telefone e dados PCD.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vaga_id' => 'required|exists:vagas,id',
            'telefone' => 'required|string|max:20',
            'pcd' => 'nullable|boolean',
            'tipo_deficiencia' => 'nullable|string|max:100',
            'acessibilidade' => 'nullable|string|max:500',
        ], [
            'vaga_id.required' => 'A vaga e obrigatoria.',
            'telefone.required' => 'Informe seu telefone para contato.',
            'telefone.max' => 'O telefone deve ter no maximo 20 caracteres.',
        ]);

        $user = auth()->user();

        // Cria o perfil de candidato automaticamente se ainda não existir
        if (!$user->candidato_id) {
            $candidato = Candidato::create([
                'nome' => $user->name,
                'email' => $user->email,
            ]);
            $user->candidato_id = $candidato->id;
            $user->save();
        }

        $vaga = Vaga::findOrFail($request->vaga_id);

        if ($vaga->status !== 'ativa') {
            return redirect()->back()->with('error', 'Esta vaga nao esta aceitando candidaturas no momento.');
        }

        $jaInscrito = $vaga->candidatos()->where('candidato_id', $user->candidato_id)->exists();

        if ($jaInscrito) {
            return redirect()->back()->with('error', 'Voce ja esta inscrito nesta vaga.');
        }

        // Atualiza o perfil do candidato com os dados do formulário
        $candidato = Candidato::findOrFail($user->candidato_id);
        $candidato->update([
            'telefone' => $request->telefone,
            'pcd' => $request->boolean('pcd'),
            'tipo_deficiencia' => $request->boolean('pcd') ? $request->tipo_deficiencia : null,
            'acessibilidade' => $request->boolean('pcd') ? $request->acessibilidade : null,
        ]);

        // Vincula o candidato à vaga
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
