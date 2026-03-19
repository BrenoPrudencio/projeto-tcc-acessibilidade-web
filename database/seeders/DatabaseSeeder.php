<?php

namespace Database\Seeders;

use App\Models\Candidato;
use App\Models\Vaga;      
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Apaga os dados das tabelas para evitar duplicatas ao rodar o seeder múltiplas vezes
        // Candidate-se a esta abordagem com cuidado em produção
        // Vaga::truncate();
        // Candidato::truncate();
        // DB::table('candidaturas')->truncate();
        
        // Cria 50 Vagas usando a Factory
        $vagas = Vaga::factory(50)->create();

        // Cria 100 Candidatos e, para cada um, inscreve em vagas aleatórias
        Candidato::factory(100)->create()->each(function ($candidato) use ($vagas) {
            // Para cada candidato, anexa entre 1 e 5 vagas aleatórias da lista de vagas criadas
            $candidato->vagas()->attach(
                $vagas->random(rand(1, 5))->pluck('id')->toArray()
            );
        });
    }
}