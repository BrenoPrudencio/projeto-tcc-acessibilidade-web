<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    /**
     * Os campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'tipo_contratacao',
        'status',
    ];

    /**
     * Define a relação de que uma Vaga pode ter muitos Candidatos.
     */
    public function candidatos()
    {
        return $this->belongsToMany(Candidato::class, 'candidaturas');
    }
}
