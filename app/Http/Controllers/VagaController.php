<?php

namespace App\Http\Controllers;

use App\Models\Vaga;
use App\Models\Candidato;
use Illuminate\Http\Request;

class VagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    
    $perPage = $request->input('per_page', 20);

    $query = Vaga::query();

    // FILTRO 1: Pelo campo de busca de texto (título)
    $query->when($request->search, function ($q) use ($request) {
        return $q->where('titulo', 'like', '%' . $request->search . '%');
    });

    // FILTRO 2: Pelo tipo de contratação
    $query->when($request->tipo, function ($q) use ($request) {
        return $q->where('tipo_contratacao', $request->tipo);
    });

    // FILTRO 3: Pelo status
    $query->when($request->status, function ($q) use ($request) {
        return $q->where('status', $request->status);
    });

    $vagas = $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();

    return view('vagas.index', ['vagas' => $vagas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vagas.create');
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_contratacao' => 'required|in:CLT,PJ,Freelancer',
        ]);

        Vaga::create($request->all());

        return redirect()->route('vagas.index')
                         ->with('success', 'Vaga criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vaga $vaga)
    {
        $vaga->load('candidatos'); 

        $candidatos = Candidato::orderBy('nome')->get();
        
        return view('vagas.show', [
            'vaga' => $vaga,
            'candidatos' => $candidatos
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaga $vaga)
    {
        return view('vagas.edit', ['vaga' => $vaga]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaga $vaga)
    {
        $request->validate([    
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_contratacao' => 'required|in:CLT,PJ,Freelancer',
            'status' => 'required|in:ativa,pausada',
        ]);

        $vaga->update($request->all());

        return redirect()->route('vagas.index')
                         ->with('success', 'Vaga atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaga $vaga)
    {
        $vaga->delete();

        return redirect()->route('vagas.index')
                         ->with('success', 'Vaga excluída com sucesso!');
    }

    /**
     * Inscreve um candidato em uma vaga específica.
     */
    public function inscrever(Request $request, Vaga $vaga)
    {
        $request->validate(['candidato_id' => 'required|exists:candidatos,id']);

        if ($vaga->status == 'pausada') {
            return redirect()->back()->with('error', 'Esta vaga está pausada e não aceita inscrições.');
        }

        $jaInscrito = $vaga->candidatos()->where('candidato_id', $request->candidato_id)->exists();

        if ($jaInscrito) {
            return redirect()->back()->with('error', 'Este candidato já está inscrito nesta vaga.');
        }

        $vaga->candidatos()->attach($request->candidato_id);

        return redirect()->back()->with('success', 'Candidato inscrito com sucesso!');
    }
    /**
    * Cancela a inscrição de um candidato de uma vaga.
    */
    public function cancelarInscricao(Vaga $vaga, Candidato $candidato)
    {
        $vaga->candidatos()->detach($candidato->id);

        return redirect()->back()->with('success', 'Inscrição do candidato cancelada com sucesso!');
    }
    public function destroyMass(Request $request)
{
        // Valida se 'ids' foi enviado e se é um array
        $request->validate([
            'ids' => 'required|array'
    ]);

        // Deleta todas as vagas cujos IDs estão no array
        Vaga::destroy($request->ids);

        return redirect()->route('vagas.index')
                     ->with('success', 'Vagas selecionadas excluídas com sucesso!');
}
}