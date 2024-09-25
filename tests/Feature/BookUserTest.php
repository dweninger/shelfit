<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_book_to_list()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->actingAs($user)->post(route('book-user.store'), [
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'read_from' => now()->toDateString(),
            'read_to'   => now()->toDateString(),
            'status'    => 'Completed',
        ])->assertSuccessful();

        $this->assertDatabaseHas('book_user', [
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'read_from' => now()->toDateString(),
            'read_to'   => now()->toDateString(),
            'status'    => 'Completed',
        ]);
    }
}
