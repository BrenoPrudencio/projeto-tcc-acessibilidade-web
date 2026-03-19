<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Http\Request;

class VagaController extends Controller
{
    /**
     * Retorna uma lista paginada de vagas.
     */
    public function index()
    {
        return Vaga::paginate(10);
    }

    /**
     * Cria uma nova vaga.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_contratacao' => 'required|in:CLT,PJ,Freelancer',
            'status' => 'sometimes|in:ativa,pausada', // 'sometimes' torna o campo opcional
        ]);

        $vaga = Vaga::create($request->all());

        return response()->json($vaga, 201); // Retorna a vaga criada com status 201 Created
    }

    /**
     * Retorna os detalhes de uma vaga específica.
     */
    public function show(Vaga $vaga)
    {
        return $vaga;
    }

    /**
     * Atualiza uma vaga existente.
     */
    public function update(Request $request, Vaga $vaga)
    {
        $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string',
            'tipo_contratacao' => 'sometimes|required|in:CLT,PJ,Freelancer',
            'status' => 'sometimes|in:ativa,pausada',
        ]);

        $vaga->update($request->all());

        return response()->json($vaga);
    }

    /**
     * Exclui uma vaga.
     */
    public function destroy(Vaga $vaga)
    {
        $vaga->delete();

        return response()->json(null, 204);
    }
}