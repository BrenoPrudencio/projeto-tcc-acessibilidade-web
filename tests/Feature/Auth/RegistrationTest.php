<?php

namespace Tests\Feature\Auth;

use App\Models\Candidato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'telefone'              => '(11) 99999-9999',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('usuario.dashboard', absolute: false));
    }

    public function test_registration_requires_telefone(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            // telefone ausente propositalmente
        ]);

        $response->assertSessionHasErrors('telefone');
        $this->assertGuest();
    }

    public function test_pcd_requires_tipo_deficiencia(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test PCD',
            'email'                 => 'pcd@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'telefone'              => '(11) 99999-9999',
            'pcd'                   => '1',
            // tipo_deficiencia ausente propositalmente
        ]);

        $response->assertSessionHasErrors('tipo_deficiencia');
        $this->assertGuest();
    }

    public function test_candidato_is_created_on_register(): void
    {
        $this->post('/register', [
            'name'                  => 'Maria Silva',
            'email'                 => 'maria@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'telefone'              => '(21) 98765-4321',
            'pcd'                   => '1',
            'tipo_deficiencia'      => 'Visual',
            'acessibilidade'        => 'Necessito de leitor de tela.',
        ]);

        $this->assertDatabaseHas('candidatos', [
            'email'            => 'maria@example.com',
            'telefone'         => '(21) 98765-4321',
            'pcd'              => true,
            'tipo_deficiencia' => 'Visual',
            'acessibilidade'   => 'Necessito de leitor de tela.',
        ]);

        // Verifica que o usuário foi vinculado ao candidato
        $user = User::where('email', 'maria@example.com')->firstOrFail();
        $candidato = Candidato::where('email', 'maria@example.com')->firstOrFail();
        $this->assertEquals($candidato->id, $user->candidato_id);
        $this->assertEquals('candidato', $user->role);
    }

    public function test_pcd_false_does_not_require_tipo_deficiencia(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Carlos Neto',
            'email'                 => 'carlos@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'telefone'              => '(31) 91234-5678',
            // pcd não marcado
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('usuario.dashboard', absolute: false));
    }
}
