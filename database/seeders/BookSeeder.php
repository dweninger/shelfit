<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;

class BookSeeder extends Seeder
{
    public function run()
    {
        $client = new Client();
        $totalBooksToFetch = 200;
        $booksFetched = 0;
        $page = 1;
        $booksPerPage = 100;

        while ($booksFetched < $totalBooksToFetch) {
            $url = "https://openlibrary.org/search.json?q=popular&limit={$booksPerPage}&page={$page}";

            try {
                $response = $client->get($url);
                $data = json_decode($response->getBody()->getContents(), true);
                $books = $data['docs'];
                $booksToCreate = [];

                foreach ($books as $book) {
                    if ($booksFetched >= $totalBooksToFetch) {
                        break;
                    }

                    $booksToCreate[] = [
                        'title' => $book['title'] ?? 'Unknown Title',
                        'author' => $book['author_name'][0] ?? 'Unknown Author',
                        'genre' => 'Unknown Genre', // Open Library does not provide genres
                        'published_at' => isset($book['first_publish_year']) ? $book['first_publish_year'].'-01-01' : null,
                        'cover_image' => isset($book['cover_i']) ? 'https://covers.openlibrary.org/b/id/' . $book['cover_i'] . '-L.jpg' : null,
                    ];

                    $booksFetched++;
                }

                Book::insert($booksToCreate);
                $page++;

            } catch (\Exception $e) {
                echo "Error fetching books: " . $e->getMessage() . "\n";
                break;
            }
        }

        echo "Successfully seeded {$booksFetched} books.";
    }
}
