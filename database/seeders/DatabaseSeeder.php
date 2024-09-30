<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $users = User::factory()->count(10)->create();
        $this->call(BookSeeder::class);
        $books = Book::all();

        $users->each(function ($user) use ($books) {
            $user->books()->attach(
                $books->random(rand(3, 8))->pluck('id')->toArray()
            );
        });
    }
}
