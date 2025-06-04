<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//==Testy maja na celiu sprawdzenie walidacji tylko dla klienta
class ClientRegistrationTest extends TestCase
{
    use RefreshDatabase;

    //lol czemu imie nie jest wymagane
    public function test_client_registration_requires_valid_data()
    {
        $response = $this->post('/register', []); // pusty formularz

        $response->assertSessionHasErrors(['email', 'password']);
    }

    // poprawna walidacja przy blednym mailu
    public function test_client_registration_rejects_invalid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Testowy Klient',
            'email' => 'not-an-email',
            'password' => 'bajojaoajaoajoaj',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // poprawne danw przechodząś
    public function test_client_registration_accepts_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test Klient',
            'email' => 'test@example.com',
            'password' => 'wiecejniz8',
            'password_confirmation' => 'wiecejniz8',
        ]);

        $response->assertRedirect('/home'); 
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }
}
