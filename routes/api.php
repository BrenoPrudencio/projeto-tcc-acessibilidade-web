<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VagaController; // Importa o controller da API
use App\Http\Controllers\Api\CandidatoController;

/*
| API Routes
*/

// Define a rota de recurso para o CRUD de Vagas na API
Route::apiResource('vagas', VagaController::class);

// Define a rota de recurso para o CRUD de Candidatos na API
Route::apiResource('candidatos', CandidatoController::class);