<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $query = Candidato::query();

        $query->when($request->search, function ($q) use ($request) {
            $q->where(function ($subQuery) use ($request) {
                $subQuery->where('nome', 'like', '%' . $request->search . '%')
                         ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        });

        if ($request->filled('pcd')) {
            $query->where('pcd', (bool)$request->pcd);
        }

        $candidatos = $query->orderBy('id', 'asc')
                            ->paginate($perPage)
                            ->withQueryString();

        return view('candidatos.index', ['candidatos' => $candidatos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('candidatos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Candidato::create($data);

        return redirect()
            ->route('candidatos.index')
            ->with('success', 'Candidato criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidato $candidato)
    {
        return view('candidatos.show', ['candidato' => $candidato]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidato $candidato)
    {
        return view('candidatos.edit', ['candidato' => $candidato]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidato $candidato)
    {
        $data = $this->validateData($request, $candidato->id);

        $candidato->update($data);

        return redirect()
            ->route('candidatos.index') 
            ->with('success', 'Candidato atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidato $candidato)
    {
        $candidato->delete();

        return redirect()
            ->route('candidatos.index')
            ->with('success', 'Candidato excluído com sucesso!');
    }

    /**
     * Deleção em massa.
     */
    public function destroyMass(Request $request)
    {
        $request->validate([
            'ids' => ['required','array'],
            'ids.*' => ['exists:candidatos,id'],
        ]);

        Candidato::destroy($request->ids);

        return redirect()
            ->route('candidatos.index')
            ->with('success', 'Candidatos selecionados excluídos com sucesso!');
    }

    /**
     * Validação e normalização comum (store/update).
     */
    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'nome' => ['required','string','max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('candidatos','email')->ignore($ignoreId),
            ],
            'telefone' => ['required','string','max:20'],
            'curriculo' => ['nullable','string','max:255'],
            'pcd' => ['nullable','boolean'],
            'tipo_deficiencia' => ['nullable','string','max:100'],
            'acessibilidade' => ['nullable','string','max:500'],
        ];

        $validated = $request->validate($rules);

        // Normaliza telefone (apenas dígitos)
        $validated['telefone'] = preg_replace('/\D+/', '', $validated['telefone']);

        // Normaliza checkbox PCD
        $validated['pcd'] = $request->boolean('pcd');

        // Se não é PCD, limpa campos relacionados
        if (! $validated['pcd']) {
            $validated['tipo_deficiencia'] = null;
            $validated['acessibilidade'] = null;
        }

        // Garante que apenas campos esperados vão para o create/update
        return [
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'curriculo' => $validated['curriculo'] ?? null,
            'pcd' => $validated['pcd'],
            'tipo_deficiencia' => $validated['tipo_deficiencia'],
            'acessibilidade' => $validated['acessibilidade'],
        ];
    }
}