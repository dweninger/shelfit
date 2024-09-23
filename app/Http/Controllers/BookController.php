<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $books = $user->books;

        return response()->json($books);
    }
}
