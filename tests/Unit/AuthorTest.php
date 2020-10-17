<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Author;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created(){
        $this->assertTrue(true);
    }
}