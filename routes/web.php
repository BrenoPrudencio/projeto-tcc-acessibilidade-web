<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\CandidatoController;
use Illuminate\Support\Facades\Route;

// Rota da página inicial pública
Route::get('/', function () {
    return view('welcome');
});

// Rota do Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// AGRUPAMENTO DE TODAS AS ROTAS PROTEGIDAS DA APLICAÇÃO WEB
Route::middleware('auth')->group(function () {
    // Rotas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ROTAS DO ADMIN ---
    // Rotas de Vagas e Candidatos existentes são parte do Admin agora?
    // De acordo com a DOCS, a gestão de Vagas é Admin. Vamos deixar como está ou com o middleware admin?
    // A DOCS pede um grupo novo "painel" com middleware "role:candidato".
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('/vagas/destroy-mass', [VagaController::class, 'destroyMass'])->name('vagas.destroy.mass');
        Route::post('/vagas/{vaga}/inscrever', [VagaController::class, 'inscrever'])->name('vagas.inscrever');
        Route::delete('/vagas/{vaga}/candidatos/{candidato}', [VagaController::class, 'cancelarInscricao'])->name('vagas.cancelarInscricao');
        Route::resource('vagas', VagaController::class);

        Route::delete('/candidatos/destroy-mass', [CandidatoController::class, 'destroyMass'])->name('candidatos.destroy.mass');
        Route::resource('candidatos', CandidatoController::class);
    });

    // --- ROTAS DO CANDIDATO ---
    Route::middleware(['role:candidato'])->prefix('painel')->name('usuario.')->group(function () {
        Route::get('/dashboard', function () {
            return view('usuario.dashboard');
        })->name('dashboard');
    });
});

require __DIR__ . '/auth.php';