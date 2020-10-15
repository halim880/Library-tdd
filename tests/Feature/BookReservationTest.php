<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title'=> 'Algorithm & Datastructure',
            'author'=> 'Abdul halim'
        ]);
        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function book_can_be_updated(){
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title'=> 'Algorithm & Datastructure',
            'author'=> 'Abdul halim'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'.$book->id, [
            'title'=> 'New title',
            'author'=> 'New author'
        ]);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals('New author', Book::first()->author);
    }

    /** @test */
    public function book_can_be_deleted(){
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title'=> 'Algorithm & Datastructure',
            'author'=> 'Abdul halim'
        ]);

        $book = Book::first();

        $response = $this->delete('/books/'.$book->id);

        $this->assertCount(0, Book::all());
    }
}
