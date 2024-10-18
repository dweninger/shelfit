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
        $genres = ['fiction', 'nonfiction', 'fantasy', 'history', 'science'];
        $totalBooksToFetchPerGenre = 40;
        $maxResults = 40;
        $apiKey = env('GOOGLE_BOOKS_API_KEY');
        $booksFetched = 0;

        foreach ($genres as $genre) {
            $startIndex = 0;

            while ($startIndex < $totalBooksToFetchPerGenre) {
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:{$genre}&startIndex={$startIndex}&maxResults={$maxResults}&key={$apiKey}";

                try {
                    $response = $client->get($url);
                    $data = json_decode($response->getBody()->getContents(), true);
                    $books = $data['items'] ?? [];

                    if (empty($books)) {
                        echo "No more books available for genre: {$genre}.\n";
                        break;
                    }

                    $booksToCreate = [];

                    foreach ($books as $book) {
                        $volumeInfo = $book['volumeInfo'] ?? [];

                        $booksToCreate[] = [
                            'title' => $volumeInfo['title'] ?? 'Unknown Title',
                            'author' => isset($volumeInfo['authors']) ? $volumeInfo['authors'][0] : 'Unknown Author',
                            'genre' => ucfirst($genre),
                            'published_at' => isset($volumeInfo['publishedDate']) ? $volumeInfo['publishedDate'] . '-01-01' : null,
                            'cover_image' => isset($volumeInfo['imageLinks']['thumbnail']) ? $volumeInfo['imageLinks']['thumbnail'] : null,
                        ];

                        $booksFetched++;
                    }

                    Book::insert($booksToCreate);
                    $startIndex += $maxResults;
                    sleep(1);

                } catch (\Exception $e) {
                    echo "Error fetching books for genre {$genre}: " . $e->getMessage() . "\n";
                    break;
                }
            }
        }

        echo "Successfully seeded {$booksFetched} books across all genres.\n";
    }
}
