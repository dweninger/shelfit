<?php

namespace App\Http\Controllers;

use App\Models\Book;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $books = $user->books()->orderBy('sort_order')->get();

        return response()->json($books);
    }

    public function search(Request $request)
    {
        $query = $request->input('title');
        $books = Book::where('title', 'like', '%' . $query . '%')->get();

        if (count($books) < 10) {
            // https://axios-http.com/docs/cancellation
            // Javascript url object <- use this
            // encodeURIComponent x

            $client = new Client();
            $googleApiUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($query);

            try {
                $response = $client->get($googleApiUrl);
                $googleBooksData = json_decode($response->getBody(), true);
                $googleBooks = $googleBooksData['items'] ?? [];

                foreach ($googleBooks as $book) {
                    $publishedDateStr = $book['volumeInfo']['publishedDate'] ?? null;
                    $publishedAt = null;

                    // Parse the date formats
                    if ($publishedDateStr) {
                        if (preg_match('/^\d{4}$/', $publishedDateStr)) {
                            $publishedAt = (new DateTime("$publishedDateStr-01-01"))->format('Y-m-d');
                        } elseif (preg_match('/^\d{4}-\d{2}$/', $publishedDateStr)) {
                            $publishedAt = (new DateTime("$publishedDateStr-01"))->format('Y-m-d');
                        } else {
                            $publishedAt = (new DateTime($publishedDateStr))->format('Y-m-d');
                        }
                    }

                    $formattedBooks[] = [
                        'title' => $book['volumeInfo']['title'] ?? 'Unknown Title',
                        'author' => $book['volumeInfo']['authors'][0] ?? 'Unknown Author',
                        'cover_image' => $book['volumeInfo']['imageLinks']['thumbnail'] ?? null,
                        'genre' => $book['volumeInfo']['categories'][0] ?? 'Unknown Genre',
                        'published_at' => $publishedAt,
                    ];
                }

                $uniqueBooks = [];
                $seenTitles = [];
                foreach ($formattedBooks as $book) {
                    if (!in_array($book['title'], $seenTitles)) {
                        $uniqueBooks[] = $book;
                        $seenTitles[] = $book['title'];
                    } else {
                        foreach ($uniqueBooks as &$existingBook) {
                            if ($existingBook['title'] === $book['title']) {
                                $existingDate = new DateTime($existingBook['published_at']);
                                $newDate = new DateTime($book['published_at']);
                                if ($newDate < $existingDate) {
                                    $existingBook = $book;
                                }
                            }
                        }
                    }
                }
                $books = $books->concat(collect($uniqueBooks));
            } catch (Exception $e) {
                return response()->json(['error' => 'Failed to fetch book data from external API'], 500);
            }
        }

        return response()->json($books);
    }

    public function checkBook(Request $request)
    {
        $exists = Book::where('title', $request->title)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|string|max:255',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }
}
