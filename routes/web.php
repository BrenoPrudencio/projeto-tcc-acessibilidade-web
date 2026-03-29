<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\Usuario\VagaController as UsuarioVagaController;
use Illuminate\Support\Facades\Route;

// Rota da página inicial pública
Route::get('/', function () {
    return view('welcome');
});

// --- ROTAS PÚBLICAS (sem autenticação) ---
Route::get('/vagas', [UsuarioVagaController::class, 'index'])->name('usuario.vagas.index');
Route::get('/vagas/{vaga}', [UsuarioVagaController::class, 'show'])->name('usuario.vagas.show');

// Rota do Dashboard — redireciona candidato para seu painel
Route::get('/dashboard', function () {
    if (auth()->user()->isCandidato()) {
        return redirect()->route('usuario.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// AGRUPAMENTO DE TODAS AS ROTAS PROTEGIDAS DA APLICAÇÃO WEB
Route::middleware('auth')->group(function () {
    // Rotas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ROTAS DO ADMIN (prefixo /admin) ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::delete('/vagas/destroy-mass', [VagaController::class, 'destroyMass'])->name('vagas.destroy.mass');
        Route::post('/vagas/{vaga}/inscrever', [VagaController::class, 'inscrever'])->name('vagas.inscrever');
        Route::delete('/vagas/{vaga}/candidatos/{candidato}', [VagaController::class, 'cancelarInscricao'])->name('vagas.cancelarInscricao');
        Route::resource('vagas', VagaController::class);

        Route::delete('/candidatos/destroy-mass', [CandidatoController::class, 'destroyMass'])->name('candidatos.destroy.mass');
        Route::resource('candidatos', CandidatoController::class);
    });

    // --- ROTAS DO CANDIDATO ---
    Route::middleware(['role:candidato'])->prefix('painel')->name('usuario.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Usuario\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/candidaturas', [App\Http\Controllers\Usuario\CandidaturaController::class, 'index'])->name('candidaturas.index');
        Route::post('/candidaturas', [App\Http\Controllers\Usuario\CandidaturaController::class, 'store'])->name('candidaturas.store');
        Route::delete('/candidaturas/{candidatura}', [App\Http\Controllers\Usuario\CandidaturaController::class, 'destroy'])->name('candidaturas.destroy');

        Route::get('/perfil', [App\Http\Controllers\Usuario\PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('/perfil', [App\Http\Controllers\Usuario\PerfilController::class, 'update'])->name('perfil.update');

        Route::get('/acessibilidade', function () {
            return view('usuario.acessibilidade.index');
        })->name('acessibilidade');
    });
});

require __DIR__ . '/auth.php';