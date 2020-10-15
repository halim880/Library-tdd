<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;
use Carbon\Carbon;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_author_can_be_created()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/authors', [
            'name' => 'Author',
            'dob' => '01/03/2020'
        ]);
        $response->assertOk();
        $author = Author::all();
        $this->assertCount(1, $author);
    }
}
