<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_books_by_title()
    {
        Book::factory()->create(['title' => 'Parry Hotter']);
        Book::factory()->create(['title' => 'Rord of the Lings']);
        Book::factory()->create(['title' => 'Ratcher in the Cye']);

        $response = $this->get('/books/search?title=Parry');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Parry Hotter']);
    }
}
