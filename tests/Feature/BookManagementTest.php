<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', $this->data());
        $this->assertCount(1, Book::all());
        $response->assertRedirect('/books/'.Book::first()->id);
    }

    /** @test */
    public function title_is_required(){
        $response = $this->post('/books', $this->data());
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function author_is_required(){
        $response = $this->post('/books', $this->data());
        $this->assertCount(1, Book::all());
    }

    // /** @test */
    public function book_can_be_updated(){
        $this->withoutExceptionHandling();

        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path, ['title'=> 'New title', 'author_id'=>'New author']);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals(8, Book::first()->author_id);
        $response->assertRedirect($book->path);
    }

    /** @test */
    public function book_can_be_deleted(){
        $this->withoutExceptionHandling();

        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->delete('/books/'.$book->id);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }


    /** @test */
    public function an_author_is_recorded()
    {
        Author::firstOrCreate([
            'name'=> 'Author',
        ]);

        $this->assertCount(1, Author::all());
    }

    /** @test */
    public function a_new_author_is_automatically_created(){
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    public function data(){
        return [
            'title'=> 'Algorithm And Data structure',
            'author_id'=> 'Akash',
        ];
    }
}
