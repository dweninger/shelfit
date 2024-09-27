<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookUserController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'book_id' => ['required', Rule::exists('books', 'id')],
            'comment' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'started_reading_at' => ['nullable', 'date'],
            'finished_reading_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string', Rule::in(['Completed', 'Reading', "Did Not Finish", "Want to Read"])],
        ]);

        $bookId = $validated['book_id'];

        $user->books()->attach($bookId, [
            'comment' => $validated['comment'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'started_reading_at' => $validated['started_reading_at'] ?? null,
            'finished_reading_at' => $validated['finished_reading_at'] ?? null,
            'status' => $validated['status'] ?? null,
        ]);

        return response()->json(['message' => 'Book added to user\'s list'], 201);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
