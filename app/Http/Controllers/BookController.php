<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
        sleep(1);
        $query = $request->input('title');
        $books = Book::where('title', 'like', '%' . $query . '%')->get();

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
