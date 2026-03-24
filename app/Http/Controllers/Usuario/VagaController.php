<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use App\Models\Vaga;
use Illuminate\Http\Request;

class VagaController extends Controller
{
    /**
     * Listagem pública de vagas ativas com filtros.
     */
    public function index(Request $request)
    {
        $query = Vaga::where('status', 'ativa');

        $query->when($request->search, function ($q) use ($request) {
            return $q->where('titulo', 'like', '%' . $request->search . '%');
        });

        $query->when($request->tipo, function ($q) use ($request) {
            return $q->where('tipo_contratacao', $request->tipo);
        });

        $vagas = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('usuario.vagas.index', ['vagas' => $vagas]);
    }

    /**
     * Exibe os detalhes de uma vaga ativa.
     */
    public function show(Vaga $vaga)
    {
        if ($vaga->status !== 'ativa') {
            abort(404);
        }

        $jaInscrito = false;

        if (auth()->check() && auth()->user()->isCandidato() && auth()->user()->candidato_id) {
            $jaInscrito = $vaga->candidatos()
                ->where('candidato_id', auth()->user()->candidato_id)
                ->exists();
        }

        $candidato = null;
        if (auth()->check() && auth()->user()->candidato_id) {
            $candidato = Candidato::find(auth()->user()->candidato_id);
        }

        return view('usuario.vagas.show', [
            'vaga' => $vaga,
            'jaInscrito' => $jaInscrito,
            'candidato' => $candidato,
        ]);
    }
}
