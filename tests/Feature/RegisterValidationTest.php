<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Organizer;

//===Testy mają na celu sprawdzenie poprawnego działania walidacji dla formularza rejestracji
class RegisterValidationTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_client_registration_requires()
    {
        $response = $this->post('/register', [
            'organizerForm' => false, // klient
        ]);

        $response->assertSessionHasErrors([
            'name',
            'surname',
            'email',
            'password',
        ]);
    }

        public function test_organizer_registration_requires_companyName_email_password()
    {
        $response = $this->post('/register', [
            'organizerForm' => true, // organizator
        ]);

        $response->assertSessionHasErrors([
            'companyName',
            'email',
            'password',
        ]);
    }

    // sprawdzenie czy nie powieli sie email
    public function test_registration_fails_when_email_already_exists_in_user_or_organizer()
    {
        User::factory()->create(['email' => 'test@test.com']);
        $response1 = $this->post('/register', [
            'organizerForm' => 'false',
            'name' => 'Jan',
            'surname' => 'Kowalski',
            'email' => 'test@test.com',
            'password' => 'haslopoprawne',
            'password_confirmation' => 'haslopoprawne',
        ]);
        $response1->assertSessionHasErrors('email');

        Organizer::factory()->create(['email' => 'org@mail.com']);
        $response2 = $this->post('/register', [
            'organizerForm' => 'true',
            'companyName' => 'Firma Firmowa',
            'email' => 'org@mail.com',
            'password' => 'haslopoprawne',
            'password_confirmation' => 'haslopoprawne',
        ]);
        $response2->assertSessionHasErrors('email');
    }

    /**/
    //zwykle tworzenie klienta
    public function test_valid_client_registration_passes()
    {
        $response = $this->post('/register', [
            'name' => 'Anna',
            'surname' => 'Nowak',
            'email' => 'anna@test.com',
            'password' => 'haslopoprawne',
            'password_confirmation' => 'haslopoprawne',
        ]);

    $response->assertRedirect('/home');
    $this->assertDatabaseHas('users', ['email' => 'anna@test.com']);
    }
    

    //tworzzenie organizatora
        public function test_valid_organizer_registration_passes()
    {
        $response = $this->post('/register', [
            'organizerForm' => 'true',
            'companyName' => 'Firma Test',
            'email' => 'firma@mail.com',
            'password' => 'haslopoprawne',
            'password_confirmation' => 'haslopoprawne',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('organizers', ['email' => 'firma@mail.com']);
    }
}
