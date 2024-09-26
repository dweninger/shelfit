<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'book_id'   => 'required|exists:books,id',
            'comment'  => 'nullable|string',
            'rating'    => 'nullable|integer|min:1|max:5',
            'read_from' => 'nullable|date',
            'read_to'   => 'nullable|date',
            'status'    => 'nullable|string',
        ]);

        $bookId = $validated['book_id'];

        $user->books()->attach($bookId, [
            'comment'  => $validated['comment'] ?? null,
            'rating'    => $validated['rating'] ?? null,
            'read_from' => $validated['read_from'] ?? null,
            'read_to'   => $validated['read_to'] ?? null,
            'status'    => $validated['status'] ?? null,
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
