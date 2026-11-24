<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_an_admin_with_a_hashed_password(): void
    {
        $this->artisan('app:create-admin')
            ->expectsQuestion('Nom', 'Admin')
            ->expectsQuestion('Prénom', 'Test')
            ->expectsQuestion('Adresse e-mail', 'ADMIN@example.com')
            ->expectsQuestion('Mot de passe (12 caractères minimum)', 'un-mot-de-passe-long')
            ->expectsQuestion('Confirmer le mot de passe', 'un-mot-de-passe-long')
            ->expectsConfirmation('Créer le compte administrateur pour admin@example.com ?', 'yes')
            ->assertSuccessful();

        $user = User::where('email', 'admin@example.com')->firstOrFail();

        $this->assertSame('admin', $user->role);
        $this->assertTrue(password_verify('un-mot-de-passe-long', $user->password));
    }

    public function test_it_refuses_an_existing_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->artisan('app:create-admin')
            ->expectsQuestion('Nom', 'Admin')
            ->expectsQuestion('Prénom', 'Test')
            ->expectsQuestion('Adresse e-mail', 'taken@example.com')
            ->assertFailed();
    }
}
