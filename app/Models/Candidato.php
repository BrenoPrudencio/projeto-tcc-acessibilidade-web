<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'curriculo',
        'pcd',
        'tipo_deficiencia',
        'acessibilidade',
    ];
    protected $casts = [
        'pcd' => 'boolean',
    ];

    /**
     * Retorna o telefone formatado.
     *
     * @return string
     */
    public function getTelefoneFormatadoAttribute()
    {
        $telefone = $this->telefone;
        // Remove qualquer caracter que não seja número
        $limpo = preg_replace('/[^0-9]/', '', $telefone);
    
        if (strlen($limpo) == 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $limpo);
    }
    // Retorna o número como está se não tiver o tamanho esperado
        return $telefone;
    }

    /**
     * Define a relação de que um Candidato pode ter muitas Vagas.
     */
    public function vagas()
    {
        return $this->belongsToMany(Vaga::class, 'candidaturas');
    }
}