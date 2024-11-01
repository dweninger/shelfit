<?php

namespace App\Http\Controllers;

use App\Enums\BookUserStatus;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookUserController extends Controller
{
    public function statuses(): JsonResponse
    {
        $statuses = BookUserStatus::getStatuses();

        return response()->json(['statuses'=>$statuses]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'book_id' => ['required', Rule::exists('books', 'id')],
            'comment' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'started_reading_at' => ['nullable', 'date'],
            'finished_reading_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string', Rule::in(BookUserStatus::getStatuses())],
        ]);

        $bookId = $validated['book_id'];

        $user->books()->syncWithoutDetaching([
            $bookId => [
                'comment' => $validated['comment'] ?? null,
                'rating' => $validated['rating'] ?? null,
                'started_reading_at' => $validated['started_reading_at'] ?? null,
                'finished_reading_at' => $validated['finished_reading_at'] ?? null,
                'status' => $validated['status'] ?? null,
            ]
        ]);

        return response()->json(['message' => 'Book added to user\'s list'], 201);
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'comment' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'started_reading_at' => ['nullable', 'date'],
            'finished_reading_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string', Rule::in(BookUserStatus::getStatuses())],
        ]);

        $updateData = array_filter($validated);

        $user->books()->updateExistingPivot($book->id, $updateData);

        return response()->json(['message' => 'Book updated successfully'], 200);
    }

    public function destroy(Book $book): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->books()->detach($book);

        return response()->json(['message' => 'Book removed successfully'], 200);
    }

    public function updateSortOrder(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        foreach ($request->books as $bookData) {
            $user->books()->updateExistingPivot($bookData['id'], [
                'sort_order' => $bookData['sort_order']
            ]);
        }

        return response()->json(['message' => 'Order updated successfully'], 200);
    }
}
