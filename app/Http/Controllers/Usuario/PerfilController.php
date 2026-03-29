<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $candidato = $user->candidato;

        return view('usuario.perfil.edit', compact('user', 'candidato'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $candidato = $user->candidato;

        $request->validate([
            'nome'             => 'required|string|max:255',
            'telefone'         => 'nullable|string|max:20',
            'pcd'              => 'nullable|boolean',
            'tipo_deficiencia' => 'nullable|string|max:100',
            'acessibilidade'   => 'nullable|string|max:500',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.max'      => 'O nome deve ter no máximo 255 caracteres.',
        ]);

        $pcd = $request->boolean('pcd');

        $candidato->update([
            'nome'             => $request->nome,
            'telefone'         => $request->telefone ? preg_replace('/[^0-9]/', '', $request->telefone) : null,
            'pcd'              => $pcd,
            'tipo_deficiencia' => $pcd ? $request->tipo_deficiencia : null,
            'acessibilidade'   => $pcd ? $request->acessibilidade : null,
        ]);

        // Sincroniza o nome no User também
        $user->name = $request->nome;
        $user->save();

        return redirect()->route('usuario.perfil.edit')->with('success', 'Perfil atualizado com sucesso!');
    }
}
