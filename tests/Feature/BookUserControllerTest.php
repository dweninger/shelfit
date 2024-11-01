<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookUserControllerTest extends TestCase
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
            'started_reading_at' => now()->toDateString(),
            'finished_reading_at'   => now()->toDateString(),
            'status'    => 'Completed',
        ])->assertSuccessful();

        $this->assertDatabaseHas('book_user', [
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'started_reading_at' => now()->toDateString(),
            'finished_reading_at'   => now()->toDateString(),
            'status'    => 'Completed',
        ]);
    }

    public function test_guest_cannot_add_book_to_list()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->postJson(route('book-user.store'), [
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'started_reading_at' => now()->toDateString(),
            'finished_reading_at'   => now()->toDateString(),
            'status'    => 'Completed',
        ])->assertUnauthorized();

        $this->assertDatabaseMissing('book_user', [
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'started_reading_at' => now()->toDateString(),
            'finished_reading_at'   => now()->toDateString(),
            'status'    => 'Completed',
        ]);
    }

    public function test_user_cannot_add_the_same_book_to_list_twice()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('book-user.store'), [
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => 5,
            'started_reading_at' => now()->toDateString(),
            'finished_reading_at'   => now()->toDateString(),
            'status'    => 'Completed',
        ])->assertSuccessful();

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'comment' => 'Loved it!',
            'rating' => 5,
            'status' => 'Completed',
        ]);

        $this->actingAs($user)->postJson(route('book-user.store'), [
            'book_id'   => $book->id,
            'comment'  => 'Updated comment',
            'rating'    => 3,
            'status'    => 'Want to Read',
        ])->assertSuccessful();

        $this->assertDatabaseHas('book_user', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'comment' => 'Updated comment',
            'rating' => 3,
            'status' => 'Want to Read',
        ]);
    }

    /**
     * @dataProvider providesInvalidBookUserData
     */
    public function test_user_cannot_add_book_to_list_with_invalid_data(int $rating, string $readFrom, string $readTo, string $status)
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('book-user.store', $user->id), [
            'book_id'   => $book->id,
            'comment'  => 'Loved it!',
            'rating'    => $rating,
            'started_reading_at' => $readFrom,
            'finished_reading_at'   => $readTo,
            'status'    => $status,
        ])->assertUnprocessable();
    }

    public function providesInvalidBookUserData()
    {

        return [
            'rating too high' => [
                10,
                now()->toDateString(),
                now()->toDateString(),
                'Completed',
            ],
            'rating is too low' => [
                -1,
                now()->toDateString(),
                now()->toDateString(),
                'Completed',
            ],
            'read_from is not a date' => [
                5,
                'taco',
                now()->toDateString(),
                'Completed',
            ],
            'read_to is not a date' => [
                5,
                now()->toDateString(),
                'hotdog',
                'Completed',
            ],
            'invalid status' => [
                5,
                now()->toDateString(),
                now()->toDateString(),
                'pizza',
            ],
        ];
    }

    public function test_user_can_update_a_book_on_their_list()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $user->books()->attach($book->id);

        $updatedData = [
            'status' => 'Completed',
            'rating' => 5,
            'comment' => 'Loved it!',
        ];

        $this->actingAs($user)->put(route('book-user.update', $book->id), $updatedData)
        ->assertSuccessful();

        $this->assertDatabaseHas('book_user', [
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'status'    => 'Completed',
            'rating'    => 5,
            'comment'   => 'Loved it!',
        ]);
    }

    public function test_user_can_remove_a_book_on_their_list()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $user->books()->attach($book->id);

        $this->actingAs($user)->delete(route('book-user.destroy', $book->id))
            ->assertSuccessful();

        $this->assertDatabaseMissing('book_user', [
            'user_id'   => $user->id,
            'book_id'   => $book->id,
        ]);
    }
}
