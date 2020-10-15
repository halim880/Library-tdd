<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
class BookManagementTest extends TestCase
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
        $this->assertCount(1, Book::all());
        $response->assertRedirect('/books/'.Book::first()->id);
    }

    /** @test */
    public function title_is_required(){
        $response = $this->post('/books', [
            'title'=> 'Algorithm & datastructure',
            'author'=> 'Abdul halim'
        ]);
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function author_is_required(){
        $response = $this->post('/books', [
            'title'=> 'Algorithm & datastructure',
            'author'=> 'Abdul Halim'
        ]);
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
        $response->assertRedirect('/books/'.$book->id);
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
        $response->assertRedirect('/books');
    }
}
