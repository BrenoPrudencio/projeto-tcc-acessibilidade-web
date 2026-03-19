<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidatoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int)($request->query('per_page', 15));
        $query = Candidato::query();

        if ($s = $request->query('search')) {
            $query->where(function ($q) use ($s) {
                $q->where('nome', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('pcd')) {
            $query->where('pcd', (bool)$request->query('pcd'));
        }

        $candidatos = $query->orderBy('id')->paginate($perPage)->appends($request->query());

        return response()->json($candidatos);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $candidato = Candidato::create($data);

        return response()->json($candidato, 201);
    }

    public function show(Candidato $candidato)
    {
        return response()->json($candidato);
    }

    public function update(Request $request, Candidato $candidato)
    {
        $data = $this->validateData($request, $candidato->id, partial: true);

        $candidato->update($data);

        return response()->json($candidato);
    }

    public function destroy(Candidato $candidato)
    {
        $candidato->delete();
        return response()->json(null, 204);
    }

    protected function validateData(Request $request, ?int $ignoreId = null, bool $partial = false): array
    {
        $base = [
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

        if ($partial) {
            // Torna os campos opcionais no PATCH-like
            foreach ($base as $k => &$rules) {
                $rules[0] = str_starts_with($rules[0], 'required') ? 'sometimes' : 'sometimes';
            }
        }

        $validated = $request->validate($base);

        if (array_key_exists('telefone', $validated)) {
            $validated['telefone'] = preg_replace('/\D+/', '', $validated['telefone']);
        }

        $validated['pcd'] = $request->boolean('pcd');

        if (! $validated['pcd']) {
            $validated['tipo_deficiencia'] = null;
            $validated['acessibilidade'] = null;
        }

        // Limita apenas aos atributos fillable
        return [
            'nome' => $validated['nome'] ?? $request->candidato?->nome,
            'email' => $validated['email'] ?? $request->candidato?->email,
            'telefone' => $validated['telefone'] ?? $request->candidato?->telefone,
            'curriculo' => $validated['curriculo'] ?? ($request->candidato->curriculo ?? null),
            'pcd' => $validated['pcd'],
            'tipo_deficiencia' => $validated['tipo_deficiencia'],
            'acessibilidade' => $validated['acessibilidade'],
        ];
    }
}