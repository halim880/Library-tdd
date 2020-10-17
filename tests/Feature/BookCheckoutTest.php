<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checked_out_by_signed_in_user(){
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'.$book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    // /** @test */
    public function only_signed_in_user_can_checkout_a_book(){
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->post('/checkout/'.$book->id)->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());

    }

    // /** @test */
    public function only_existing_book_can_be_checkout(){
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/checkout/123');
        $response->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }
    /** @test */
    public function a_book_can_be_checked_in_by_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'.$book->id);
        $this->actingAs($user)->post('/checkin/'.$book->id);


        $this->assertCount(1, $book->reservations()->get());
        $this->assertEquals($user->id, $book->reservations()->first()->user_id);
        $this->assertEquals($book->id, $book->reservations()->first()->book_id);
        $this->assertEquals(now(), $book->reservations()->first()->checked_in_at);
    }
}
