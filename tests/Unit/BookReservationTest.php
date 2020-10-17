<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checked_out()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_checked_in()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, $book->reservations()->get());
        $this->assertEquals($user->id, $book->reservations()->first()->user_id);
        $this->assertEquals($book->id, $book->reservations()->first()->book_id);
        $this->assertEquals(now(), $book->reservations()->first()->checked_in_at);
    }
    // /** @test */
    public function if_not_checked_out_exception_is_thrown()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkin($user);
    }

    //  /** @test */
     public function a_book_can_be_checked_out_twice()
     {
         $book = Book::factory()->create();
         $user = User::factory()->create();
         $book->checkout($user);
         $book->checkin($user);
         $book->checkout($user);

         $this->assertCount(2, Reservation::all());
         $this->assertEquals($user->id, $book->reservations()->find(2)->user_id);
         $this->assertEquals($book->id, $book->reservations()->find(2)->book_id);
         $this->assertEquals(now(), Reservation::find(2)->checked_out_at);

         $book->checkin($user);
         $this->assertCount(2, Reservation::all());
         $this->assertEquals($user->id, $book->reservations()->find(2)->user_id);
         $this->assertEquals($book->id, $book->reservations()->find(2)->book_id);
         $this->assertNotNull(Reservation::find(2)->checked_in_at);


     }
}
