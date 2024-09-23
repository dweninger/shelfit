<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_track_books()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(3)->create();

        $user->books()->attach($books->pluck('id')->toArray());

        foreach ($books as $book) {
            $this->assertDatabaseHas('book_user', [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
        }
    }
}
