<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Sector;
use App\Models\Ticket;
use App\Models\Organizer;
use App\Models\LoginQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

//Test sprawdza czy kupowanie biletu przez klienta zachodzi poprawnie
//Przy okazji sprawdzane jest prawidlowe tworzenie:
// - klienta
// - organizatora
// - venue
// - wydarzenia + dodadnie ceny biletow dla sektoru
// - tworzenie biletu


class TicketBuyTest extends TestCase
{

   use RefreshDatabase;
    /**
     * A basic feature test example.
     */
 public function test_user_can_purchase_ticket()
    {
        //utworzenie klienta
        $user = User::factory()->create([
            'name' => 'Cebula Polska',
            'email' => 'shrek@shrek.pl',
            'balance' => '100',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Cebula Polska',
            'email' => 'shrek@shrek.pl',
            'balance' => '100',
        ]);

        //zeby system byl aktywny to pozycja w kolejce klienta wynosi 1
        $loginQueue = LoginQueue::factory()->create(['user_id'=>$user->id,]);
        //utworzenie miejsca wydarzenia
        $venue = Venue::factory()->create([
            'name' => 'Teatr Muzyczny'
        ]);
        $this->assertDatabaseHas('venues', [
            'name' => 'Teatr Muzyczny',
        ]);
        //utworzenie organizatora dla eventu
        $organizer = Organizer::factory()->create([
            'companyName' => 'Teatr Rozrywki',
            'email' => 'teatrrozrywki@org.pl',
            'password' => 'bajojajo', ]);
        //utowrzenie sektoru
        $sector = Sector::factory()->create(['venue_id' => $venue-> id,]);
        $this->assertDatabaseHas('sectors', [
                'venue_id' => ($venue-> id),
        ]);
        //utworzenie eventu
        $event = Event::factory()->create(['venue_id' => $venue->id, 'organizer_id' => $organizer->id,]);
        $this->assertDatabaseHas('events', ['venue_id' => ($venue-> id),'organizer_id' => $organizer->id,]);
        //cena biletu w sektorze wynos 50.00 pln
        $event->sectors()->attach($sector->id, ['price' => 50.00]);

        //ręcznie zbudowanie Seat (zamiast sesji)
        $seat = new \App\Models\Seat($event->id, $sector->id, 1, 1, 50.00);
        $this->actingAs($user);
        //symulowanie sesji
        $this->withSession([
            'selectedSeats' => [$seat],
        ]);

        //wyslanie POST z `status=purchased`
        $response = $this->post(route('ticket.store'), [
            'status' => 'purchased',
        ]);

        //sprawdzenie przekierowania
        $response->assertRedirect(route('home'));

        // sprawdzeie czy ticket powstał
        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'row' => 1,
            'column' => 1,
            'status' => 'purchased',
        ]);

        //sprawdzenie czy odjęto balans
        $user->refresh();
        $this->assertEquals(50.00, $user->balance);
        }
}
