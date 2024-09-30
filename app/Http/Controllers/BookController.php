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
        $books = $user->books;

        return response()->json($books);
    }

    public function search(Request $request)
    {
        $query = $request->input('title');
        $books = Book::where('title', 'like', '%' . $query . '%')->get();

        return response()->json($books);
    }
}
